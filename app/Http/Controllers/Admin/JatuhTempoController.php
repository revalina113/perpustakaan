<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PembayaranDenda;
use App\Models\AturanPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JatuhTempoController extends Controller
{
    public function index(Request $request)
    {
        // Ambil aturan peminjaman aktif
        $aturan = AturanPeminjaman::aktif();

        // Query peminjaman yang terlambat
        $query = Peminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->whereDate('tanggal_jatuh_tempo', '<', now())
            ->with(['anggota', 'buku']);

        // Filter berdasarkan status pembayaran
        if ($request->has('payment_status') && $request->payment_status) {
            if ($request->payment_status === 'menunggu_verifikasi') {
                $query->whereHas('pembayaranDenda', function($q) {
                    $q->where('status_pembayaran', 'menunggu_verifikasi');
                });
            } elseif ($request->payment_status === 'belum_bayar') {
                $query->whereDoesntHave('pembayaranDenda', function($q) {
                    $q->whereIn('status_pembayaran', ['menunggu_verifikasi', 'lunas']);
                });
            } elseif ($request->payment_status === 'lunas') {
                $query->whereHas('pembayaranDenda', function($q) {
                    $q->where('status_pembayaran', 'lunas');
                });
            }
        }

        // Search berdasarkan nama siswa atau judul buku
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('anggota', function($qa) use ($search) {
                    $qa->where('nama', 'like', "%{$search}%");
                })->orWhereHas('buku', function($qb) use ($search) {
                    $qb->where('judul', 'like', "%{$search}%");
                });
            });
        }

        // Paginate hasil
        $peminjamanTerlambat = $query->orderBy('tanggal_jatuh_tempo', 'asc')->paginate(15);

        return view('admin.jatuh-tempo.index', compact('peminjamanTerlambat', 'aturan'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku', 'pembayaranDenda'])->findOrFail($id);
        $aturan = AturanPeminjaman::aktif();

        // Hitung denda
        $hariTerlambat = $peminjaman->hari_terlambat;
        $dendaPerHari = $aturan ? $aturan->denda_per_hari : 1000;
        $totalDenda = $hariTerlambat * $dendaPerHari;

        return response()->json([
            'success' => true,
            'data' => [
                'peminjaman' => $peminjaman,
                'hari_terlambat' => $hariTerlambat,
                'denda_per_hari' => $dendaPerHari,
                'total_denda' => $totalDenda,
                'pembayaran' => $peminjaman->pembayaranDenda()->first()
            ]
        ]);
    }

    public function viewBukti($id)
    {
        $pembayaran = PembayaranDenda::findOrFail($id);

        if (!$pembayaran->bukti_pembayaran) {
            return response()->json(['success' => false, 'message' => 'File bukti tidak ditemukan'], 404);
        }

        $diskPath = storage_path('app/public/' . $pembayaran->bukti_pembayaran);

        if (!file_exists($diskPath)) {
            return response()->json(['success' => false, 'message' => 'File bukti tidak ditemukan di storage'], 404);
        }

        return response()->file($diskPath);
    }
}
