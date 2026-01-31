<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'email' => 'admin@perpus.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );
    }
}
