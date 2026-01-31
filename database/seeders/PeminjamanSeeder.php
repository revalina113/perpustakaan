<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Peminjaman::create([
            'anggota_id' => 1,
            'buku_id' => 1,
            'tanggal_pinjam' => '2026-01-15',
            'tanggal_kembali' => '2026-01-22',
            'status' => 'dipinjam'
        ]);

        \App\Models\Peminjaman::create([
            'anggota_id' => 2,
            'buku_id' => 2,
            'tanggal_pinjam' => '2026-01-10',
            'tanggal_kembali' => '2026-01-17',
            'status' => 'dikembalikan'
        ]);

        \App\Models\Peminjaman::create([
            'anggota_id' => 3,
            'buku_id' => 3,
            'tanggal_pinjam' => '2026-01-08',
            'tanggal_kembali' => '2026-01-15',
            'status' => 'terlambat'
        ]);

        \App\Models\Peminjaman::create([
            'anggota_id' => 4,
            'buku_id' => 4,
            'tanggal_pinjam' => '2026-01-12',
            'tanggal_kembali' => '2026-01-19',
            'status' => 'dipinjam'
        ]);

        \App\Models\Peminjaman::create([
            'anggota_id' => 5,
            'buku_id' => 5,
            'tanggal_pinjam' => '2026-01-05',
            'tanggal_kembali' => '2026-01-12',
            'status' => 'dikembalikan'
        ]);
    }
}
