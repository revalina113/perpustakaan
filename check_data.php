<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DATABASE CHECK ===\n";
echo "Books count: " . App\Models\Buku::count() . "\n";
echo "Anggota count: " . App\Models\Anggota::count() . "\n";
echo "Users count: " . App\Models\User::count() . "\n\n";

echo "Books with stock > 0:\n";
$books = App\Models\Buku::where('stok', '>', 0)->get();
foreach($books as $book) {
    echo "- {$book->judul} (stok: {$book->stok})\n";
}

echo "\nSample Anggota:\n";
$anggota = App\Models\Anggota::take(3)->get();
foreach($anggota as $a) {
    echo "- {$a->nama} (NIS: {$a->nis})\n";
}

echo "\nSample Users:\n";
$users = App\Models\User::take(3)->get();
foreach($users as $u) {
    echo "- {$u->name} (username: {$u->username}, role: {$u->role})\n";
}