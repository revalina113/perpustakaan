<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AturanPeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AturanPeminjaman::create([
            'lama_peminjaman' => 7,
            'denda_per_hari' => 1000,
            'deskripsi' => 'Aturan peminjaman default: 7 hari peminjaman, denda Rp1.000 per hari',
            'aktif' => true
        ]);
    }
}
