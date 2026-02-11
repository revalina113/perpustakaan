<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranDenda;
use App\Models\AturanPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PembayaranDendaController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranDenda::with(['peminjaman.buku', 'anggota']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status_pembayaran', $request->status);
        }

        // Search berdasarkan nama siswa atau judul buku
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('anggota', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('peminjaman.buku', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax()) {
            $pembayaranDenda = $query->orderBy('created_at', 'desc')->paginate(10);
            return response()->json($pembayaranDenda);
        }

        // Jika request biasa, kembalikan view
        $pembayaranDenda = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pembayaran-denda.index', compact('pembayaranDenda'));
    }

    public function show($id)
    {
        $pembayaran = PembayaranDenda::with(['peminjaman.buku', 'anggota'])->findOrFail($id);
        
        // Hitung hari terlambat dan denda dengan logika yang sama seperti di controller siswa
        $peminjaman = $pembayaran->peminjaman;
        
        // Hitung hari terlambat dari tanggal jatuh tempo sampai hari ini
        if ($peminjaman->status === 'dikembalikan' || !$peminjaman->tanggal_jatuh_tempo) {
            $hariTerlambat = 0;
        } else {
            $hariIni = now()->startOfDay();
            $jatuhTempo = $peminjaman->tanggal_jatuh_tempo->startOfDay();

            if ($hariIni->lte($jatuhTempo)) {
                $hariTerlambat = 0;
            } else {
                $hariTerlambat = $hariIni->diffInDays($jatuhTempo);
            }
        }

        // Ambil denda per hari dari aturan peminjaman
        $aturan = AturanPeminjaman::aktif();
        $dendaPerHari = $aturan ? $aturan->denda_per_hari : 1000;

        // Hitung total denda
        $totalDenda = $hariTerlambat * $dendaPerHari;

        return view('admin.pembayaran-denda.show', compact('pembayaran', 'hariTerlambat', 'dendaPerHari', 'totalDenda'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,lunas,ditolak',
            'catatan_admin' => 'nullable|string|max:500'
        ]);

        $pembayaran = PembayaranDenda::findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        $message = match($request->status) {
            'lunas' => 'Pembayaran denda telah disetujui dan ditandai lunas.',
            'ditolak' => 'Pembayaran denda telah ditolak.',
            default => 'Status pembayaran telah diperbarui.'
        };

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function verifikasi($id)
    {
        $pembayaran = PembayaranDenda::with('peminjaman.buku')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Mark payment as paid
            $pembayaran->update([
                'status_pembayaran' => 'lunas',
                'tanggal_bayar' => now()->toDateString(),
            ]);

            // If related peminjaman still exists, update verifikasi/status safely
            $peminjaman = $pembayaran->peminjaman;
            if ($peminjaman) {
                // Set verifikasi status on peminjaman only if column exists
                if (\Illuminate\Support\Facades\Schema::hasColumn('peminjaman', 'status_verifikasi')) {
                    $peminjaman->update([
                        'status_verifikasi' => 'terverifikasi',
                        'total_denda' => $pembayaran->jumlah_denda,
                    ]);
                } else {
                    // Fallback: still set total_denda if possible
                    $peminjaman->update(['total_denda' => $pembayaran->jumlah_denda]);
                }

                if ($peminjaman->status === 'dipinjam') {
                    $peminjaman->update([
                        'status' => 'dikembalikan',
                        'tanggal_kembali' => now(),
                    ]);

                    // Increment stok buku hanya jika ada dan belum dikembalikan sebelumnya
                    if ($peminjaman->buku) {
                        $peminjaman->buku->increment('stok');
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran denda telah diverifikasi dan ditandai lunas.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memverifikasi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkVerifikasi(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:pembayaran_denda,id'
        ]);

        $ids = $request->ids;

        $payments = PembayaranDenda::with('peminjaman.buku')->whereIn('id', $ids)->get();

        DB::beginTransaction();
        try {
            foreach ($payments as $pembayaran) {
                $pembayaran->update([
                    'status_pembayaran' => 'lunas',
                    'tanggal_bayar' => now()->toDateString(),
                ]);

                $peminjaman = $pembayaran->peminjaman;
                if ($peminjaman) {
                        // Update status_verifikasi only if column exists
                        if (\Illuminate\Support\Facades\Schema::hasColumn('peminjaman', 'status_verifikasi')) {
                            $peminjaman->update([
                                'status_verifikasi' => 'terverifikasi',
                                'total_denda' => $pembayaran->jumlah_denda,
                            ]);
                        } else {
                            $peminjaman->update(['total_denda' => $pembayaran->jumlah_denda]);
                        }

                    if ($peminjaman->status === 'dipinjam') {
                        $peminjaman->update([
                            'status' => 'dikembalikan',
                            'tanggal_kembali' => now(),
                        ]);

                        if ($peminjaman->buku) {
                            $peminjaman->buku->increment('stok');
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran yang dipilih berhasil diverifikasi.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memverifikasi beberapa pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500'
        ]);

        $pembayaran = PembayaranDenda::findOrFail($id);

        $pembayaran->update([
            'status_pembayaran' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        // Set peminjaman verifikasi status to 'ditolak' if exists
        $peminjaman = $pembayaran->peminjaman;
        if ($peminjaman) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('peminjaman', 'status_verifikasi')) {
                    $peminjaman->update(['status_verifikasi' => 'ditolak']);
                }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran denda telah ditolak.'
        ]);
    }
}
