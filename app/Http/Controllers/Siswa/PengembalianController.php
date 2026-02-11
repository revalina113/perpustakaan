<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {

        // Cari anggota berdasarkan anggota_id user login (relasi atau find)
        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);
        if (!$anggota) {
            $peminjaman = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            return view('siswa.pengembalian.index', compact('peminjaman'))->with('error', 'Data anggota tidak ditemukan.');
        }

        $peminjaman = Peminjaman::where('anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->with(['buku', 'pembayaranDenda'])
            ->paginate(10);

        // Attach latest payment status flags to each peminjaman for view
        $peminjaman->getCollection()->transform(function($pinjam) {
            $latest = $pinjam->pembayaranDenda()->orderBy('created_at', 'desc')->first();
            $pinjam->pembayaran_status = $latest?->status_pembayaran;
            // Prioritaskan field status_verifikasi pada peminjaman jika ada
            $pinjam->menunggu_verifikasi = ($pinjam->status_verifikasi ?? null) === 'menunggu' || ($latest && $latest->status_pembayaran === 'menunggu_verifikasi');
            return $pinjam;
        });

        return view('siswa.pengembalian.index', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
        ]);

        // Cari anggota berdasarkan anggota_id user login (relasi atau find)
        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);
        if (!$anggota) {
            return back()->with('error', 'Data anggota tidak ditemukan.');
        }

        $peminjaman = Peminjaman::where('id', $request->peminjaman_id)
            ->where('anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->firstOrFail();

        // Jika ada denda yang belum dibayar, batalkan pengembalian dan arahkan siswa untuk membayar terlebih dahulu
        if ($peminjaman->denda > 0 && !$peminjaman->isDendaLunas()) {
            return back()->with('error', 'Tidak dapat mengembalikan buku. Silakan bayar denda terlebih dahulu di halaman Jatuh Tempo & Denda.');
        }

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

            // Redirect to siswa dashboard after successful return
            return redirect()->route('siswa.dashboard')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengembalikan buku.');
        }
    }
}