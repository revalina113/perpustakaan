<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            SiswaSeeder::class,
            BukuSeeder::class,
            AnggotaSeeder::class,
            PeminjamanSeeder::class,
            AturanPeminjamanSeeder::class,
        ]);
    }
}
