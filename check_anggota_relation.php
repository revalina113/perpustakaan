<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Anggota;

echo "=== USER - ANGGOTA RELATIONSHIP CHECK ===\n\n";

// Check all users
$users = User::all();
foreach($users as $user) {
    echo "User: {$user->username} (ID: {$user->id}, role: {$user->role})\n";
    echo "  - anggota_id: " . ($user->anggota_id ?? 'NULL') . "\n";
    
    if ($user->anggota_id) {
        $anggota = Anggota::find($user->anggota_id);
        if ($anggota) {
            echo "  - Anggota: {$anggota->nama} (ID: {$anggota->id})\n";
        } else {
            echo "  - ERROR: Anggota ID {$user->anggota_id} tidak ada!\n";
        }
    }
    echo "\n";
}

echo "\n=== ANGGOTA WITHOUT USER ===\n";
$anggotaWithoutUser = Anggota::whereDoesntHave('user')->get();
foreach($anggotaWithoutUser as $a) {
    echo "- {$a->nama} (ID: {$a->id}, NIS: {$a->nis})\n";
}
