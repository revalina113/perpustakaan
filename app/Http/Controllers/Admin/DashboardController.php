<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\PembayaranDenda;
use App\Models\Pengunjung;

class DashboardController extends Controller
{
    public function index()
    {
        // Recent overdue peminjaman (status dipinjam and jatuh tempo passed)
        $peminjamanTerlambat = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->whereDate('tanggal_jatuh_tempo', '<', now())
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        $totalSiswaTerlambat = $peminjamanTerlambat->count();

        // Pending pembayaran denda (menunggu_verifikasi)
        $pendingPayments = PembayaranDenda::with(['anggota', 'peminjaman.buku'])
            ->where('status_pembayaran', 'menunggu_verifikasi')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $pendingPaymentsCount = PembayaranDenda::where('status_pembayaran', 'menunggu_verifikasi')->count();

        $overduePeminjaman = $peminjamanTerlambat->take(10);

        $data = [
            'total_buku' => Buku::count(),
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_peminjaman' => Peminjaman::count(),
            'total_pengunjung_hari_ini' => \App\Models\Pengunjung::whereDate('tanggal_kunjungan', now())->count(),
            'total_siswa_terlambat' => $totalSiswaTerlambat,
            'peminjamanTerlambat' => $peminjamanTerlambat,
            'pending_payments_count' => $pendingPaymentsCount,
            'pending_payments' => $pendingPayments,
            'overdue_peminjaman' => $overduePeminjaman,
        ];

        return view('admin.dashboard', $data);
    }
}
