<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $buku = Buku::latest()->limit(6)->get();

        // Cari anggota berdasarkan NIS (username)
        $anggota = Anggota::where('nis', Auth::user()->username)->first();

        // Hitung peminjaman aktif berdasarkan anggota_id dan status 'dipinjam'
        $peminjamanAktif = 0;
        if ($anggota) {
            $peminjamanAktif = Peminjaman::where('anggota_id', $anggota->id)
                ->where('status', 'dipinjam')
                ->count();
        }

        return view('siswa.dashboard', compact('buku', 'peminjamanAktif'));
    }
}
