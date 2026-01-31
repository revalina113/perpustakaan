<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING FIXED CONTROLLER LOGIC ===\n";

// Simulate different users
$testUsers = ['siswa1', 'siswa2', '2024001', '2024002'];

foreach($testUsers as $username) {
    $user = App\Models\User::where('username', $username)->first();
    if($user) {
        echo "\nTesting user: {$user->name} (username: {$user->username})\n";

        // New logic: find by name
        $anggota = App\Models\Anggota::where('nama', $user->name)->first();
        if($anggota) {
            echo "✅ Found anggota: {$anggota->nama} (NIS: {$anggota->nis})\n";

            // Test book query
            $buku = App\Models\Buku::where('stok', '>', 0)->paginate(10);
            echo "📚 Books available: {$buku->total()} books\n";

            // Test active loans
            $peminjamanAktif = App\Models\Peminjaman::where('anggota_id', $anggota->id)
                ->where('status', 'dipinjam')
                ->with('buku')
                ->get();
            echo "📖 Active loans: {$peminjamanAktif->count()} books\n";

        } else {
            echo "❌ No anggota found\n";
        }
    } else {
        echo "❌ User {$username} not found\n";
    }
}