<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Anggota::updateOrCreate(
            ['nis' => '2024001'],
            [
                'nama' => 'Ahmad Rahman',
                'username' => '2024001',
                'kelas' => 'XII RPL 1',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567890',
                'status' => 'aktif'
            ]
        );

        \App\Models\Anggota::updateOrCreate(
            ['nis' => '2024002'],
            [
                'nama' => 'Siti Aminah',
                'username' => '2024002',
                'kelas' => 'XII RPL 2',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567891',
                'status' => 'aktif'
            ]
        );

        \App\Models\Anggota::updateOrCreate(
            ['nis' => '2024003'],
            [
                'nama' => 'Budi Santoso',
                'username' => '2024003',
                'kelas' => 'XI RPL 1',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567892',
                'status' => 'aktif'
            ]
        );

        \App\Models\Anggota::updateOrCreate(
            ['nis' => '2024004'],
            [
                'nama' => 'Maya Sari',
                'username' => '2024004',
                'kelas' => 'XI RPL 2',
                'jenis_kelamin' => 'P',
                'no_hp' => '081234567893',
                'status' => 'nonaktif'
            ]
        );

        \App\Models\Anggota::updateOrCreate(
            ['nis' => '2024005'],
            [
                'nama' => 'Rizki Pratama',
                'username' => '2024005',
                'kelas' => 'X RPL 1',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567894',
                'status' => 'aktif'
            ]
        );
    }
}
