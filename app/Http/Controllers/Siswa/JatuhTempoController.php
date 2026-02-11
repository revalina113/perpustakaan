<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\PembayaranDenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class JatuhTempoController extends Controller
{
    public function index()
    {
        // Cari anggota berdasarkan user yang login (berdasarkan anggota_id)
        $user = Auth::user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data anggota tidak ditemukan.');
        }

        // Ambil aturan peminjaman aktif
        $aturan = \App\Models\AturanPeminjaman::aktif();

        // Ambil semua peminjaman siswa yang masih dipinjam
        $peminjaman = Peminjaman::where('anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->with('buku')
            ->get();

        // Hitung status untuk setiap peminjaman
        $peminjamanDenganStatus = $peminjaman->map(function($pinjam) use ($aturan) {
            $hariTerlambat = $pinjam->hari_terlambat;
            $dendaPerHari = $aturan ? $aturan->denda_per_hari : 1000;
            $totalDenda = $hariTerlambat * $dendaPerHari;

            // Cek pembayaran terakhir untuk peminjaman ini
            $latestPayment = $pinjam->pembayaranDenda()->orderBy('created_at', 'desc')->first();

            // Prioritaskan field pada peminjaman (status_verifikasi) jika tersedia, jika tidak, turunkan dari pembayaran terakhir
            $statusVerif = $pinjam->status_verifikasi ?? null;
            if (!$statusVerif) {
                $statusVerif = $latestPayment ? match($latestPayment->status_pembayaran) {
                    'menunggu_verifikasi' => 'menunggu',
                    'lunas' => 'terverifikasi',
                    'ditolak' => 'ditolak',
                    default => 'belum_bayar',
                } : 'belum_bayar';
            }

            $sudahBayar = $latestPayment && $latestPayment->status_pembayaran === 'lunas';
            $menunggu = $statusVerif === 'menunggu';

            return [
                'peminjaman' => $pinjam,
                'status' => $hariTerlambat > 0 ? 'terlambat' : 'tepat_waktu',
                'hari_terlambat' => $hariTerlambat,
                'denda_per_hari' => $dendaPerHari,
                'total_denda' => $totalDenda,
                'sudah_bayar' => $sudahBayar,
                'pembayaran_status' => $latestPayment?->status_pembayaran,
                'pembayaran_id' => $latestPayment?->id,
                'menunggu_verifikasi' => $menunggu,
                'status_verifikasi' => $statusVerif,
            ];
        });

        // Pisahkan yang tepat waktu dan terlambat
        $peminjamanTepatWaktu = $peminjamanDenganStatus->where('status', 'tepat_waktu');
        $peminjamanTerlambat = $peminjamanDenganStatus->where('status', 'terlambat');

        return view('siswa.jatuh-tempo.index', compact(
            'peminjamanTepatWaktu',
            'peminjamanTerlambat',
            'aturan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
        ]);

        $anggota = Anggota::where('nis', Auth::user()->username)->first();
        if (!$anggota) {
            return back()->with('error', 'Data anggota tidak ditemukan.');
        }

        $peminjaman = Peminjaman::where('id', $request->peminjaman_id)
            ->where('anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Hitung denda
            $denda = $peminjaman->denda;

            // Update peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
                'total_denda' => $denda
            ]);

            // Tambah stok buku
            $peminjaman->buku->increment('stok');

            DB::commit();

            $message = 'Buku berhasil dikembalikan.';
            if ($denda > 0) {
                $message .= ' Total denda: Rp' . number_format($denda, 0, ',', '.');
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengembalikan buku.');
        }
    }

    public function bayarDenda(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate(
                [
                    'peminjaman_id' => 'required|exists:peminjaman,id',
                    'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ],
                [
                    'peminjaman_id.required' => 'ID peminjaman tidak ditemukan',
                    'peminjaman_id.exists' => 'Data peminjaman tidak valid',
                    'bukti_pembayaran.required' => 'File bukti pembayaran harus diunggah',
                    'bukti_pembayaran.image' => 'File harus berupa gambar',
                    'bukti_pembayaran.mimes' => 'Format file harus JPG, JPEG, atau PNG',
                    'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
                ]
            );

            // Cari anggota berdasarkan user yang login
            $user = Auth::user();
            $anggota = $user->anggota;

            if (!$anggota) {
                return response()->json(['success' => false, 'message' => 'Data anggota tidak ditemukan.'], 400);
            }

            $peminjaman = Peminjaman::where('id', $request->peminjaman_id)
                ->where('anggota_id', $anggota->id)
                ->where('status', 'dipinjam')
                ->first();

            if (!$peminjaman) {
                return response()->json(['success' => false, 'message' => 'Peminjaman tidak ditemukan.'], 404);
            }

            // Hitung denda
            $jumlahDenda = $peminjaman->denda;

            if ($jumlahDenda <= 0) {
                return response()->json(['success' => false, 'message' => 'Tidak ada denda yang perlu dibayar.'], 400);
            }

            // Check if payment already exists for this peminjaman
            $existingPayment = PembayaranDenda::where('peminjaman_id', $peminjaman->id)
                ->where('status_pembayaran', '!=', 'ditolak')
                ->first();
            
            if ($existingPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengirim bukti pembayaran untuk peminjaman ini. Menunggu verifikasi admin.'
                ], 400);
            }

            DB::beginTransaction();

            // Upload bukti pembayaran
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                
                if (!$file->isValid()) {
                    DB::rollback();
                    return response()->json(['success' => false, 'message' => 'File upload tidak valid'], 400);
                }

                $filename = time() . '_' . $anggota->id . '_' . $peminjaman->id . '.' . $file->getClientOriginalExtension();
                $buktiPath = $file->storeAs('bukti_pembayaran', $filename, 'public');
                
                if (!$buktiPath) {
                    DB::rollback();
                    return response()->json(['success' => false, 'message' => 'Gagal menyimpan file'], 500);
                }
            }

            // Simpan pembayaran denda
            $pembayaran = PembayaranDenda::create([
                'peminjaman_id' => $peminjaman->id,
                'anggota_id' => $anggota->id,
                'jumlah_denda' => $jumlahDenda,
                'bukti_pembayaran' => $buktiPath,
                'status_pembayaran' => 'menunggu_verifikasi',
                'tanggal_bayar' => now()->toDateString(),
            ]);

            // Tandai peminjaman sebagai menunggu verifikasi (jika kolom ada)
            if (Schema::hasColumn('peminjaman', 'status_verifikasi')) {
                $peminjaman->update(['status_verifikasi' => 'menunggu']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $errors = array_map(fn($errors) => $errors[0], $e->errors());
            return response()->json([
                'success' => false,
                'message' => implode(', ', $errors)
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
