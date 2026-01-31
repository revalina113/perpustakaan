<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$anggotaWithoutUser = Anggota::doesntHave('user')->get();
$count = 0;
foreach ($anggotaWithoutUser as $a) {
    User::create([
        'name' => $a->nama,
        'username' => $a->nis,
        'email' => $a->nis . '@example.local',
        'password' => Hash::make($a->nis),
        'role' => 'siswa',
        'status' => 'aktif',
        'must_change_password' => true,
        'anggota_id' => $a->id,
    ]);
    $count++;
}

echo "$count accounts created\n";
