<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING USER-ANGGOTA RELATIONSHIP ===\n";

$users = App\Models\User::where('role', 'siswa')->get();
foreach($users as $user) {
    $anggota = App\Models\Anggota::where('nis', $user->username)->first();
    if($anggota) {
        echo "✅ User {$user->username} -> Anggota {$anggota->nama} (NIS: {$anggota->nis})\n";
    } else {
        echo "❌ User {$user->username} -> NO ANGGOTA FOUND\n";
    }
}

echo "\n=== TESTING CONTROLLER LOGIC ===\n";
// Simulate login as siswa1
$user = App\Models\User::where('username', 'siswa1')->first();
if($user) {
    echo "Logged in as: {$user->name} (username: {$user->username})\n";

    // Current logic
    $anggota = App\Models\Anggota::where('nis', $user->username)->first();
    if($anggota) {
        echo "✅ Found anggota: {$anggota->nama}\n";
    } else {
        echo "❌ No anggota found with current logic\n";

        // Try alternative: search by name
        $anggotaByName = App\Models\Anggota::where('nama', $user->name)->first();
        if($anggotaByName) {
            echo "✅ Found by name: {$anggotaByName->nama} (NIS: {$anggotaByName->nis})\n";
        }
    }
}