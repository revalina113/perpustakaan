<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $siswaData = [
            ['name' => 'Ahmad Rahman', 'username' => '2024001', 'nis' => '2024001'],
            ['name' => 'Siti Aminah', 'username' => '2024002', 'nis' => '2024002'],
            ['name' => 'Budi Santoso', 'username' => '2024003', 'nis' => '2024003'],
            ['name' => 'Maya Sari', 'username' => '2024004', 'nis' => '2024004'],
            ['name' => 'Rizki Pratama', 'username' => '2024005', 'nis' => '2024005'],
        ];

        foreach ($siswaData as $data) {
            // Cari anggota berdasarkan NIS
            $anggota = Anggota::where('nis', $data['nis'])->first();

            User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'email' => $data['username'] . '@perpus.com',
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa',
                    'status' => 'aktif',
                    'anggota_id' => $anggota ? $anggota->id : null,
                ]
            );
        }
    }
}
