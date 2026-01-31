<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AturanPeminjaman;
use App\Models\Peminjaman;

echo "=== ATURAN PEMINJAMAN ===\n";
$aturan = AturanPeminjaman::aktif();
if ($aturan) {
    echo "Ada aturan aktif:\n";
    echo "  - Durasi: {$aturan->durasi_peminjaman} hari\n";
    echo "  - Denda per hari: Rp" . number_format($aturan->denda_per_hari) . "\n";
} else {
    echo "TIDAK ada aturan aktif!\n";
}

echo "\n=== CEK DENDA MENGGUNAKAN ATTRIBUTE ===\n";
$peminjaman = Peminjaman::where('status', 'dipinjam')
    ->whereNotNull('tanggal_jatuh_tempo')
    ->whereDate('tanggal_jatuh_tempo', '<', now())
    ->first();

if ($peminjaman) {
    echo "Peminjaman ID: {$peminjaman->id}\n";
    echo "  - Hari Terlambat: {$peminjaman->hari_terlambat}\n";
    echo "  - Denda (attribute): Rp" . number_format($peminjaman->denda) . "\n";
    echo "  - Total Denda (field): Rp" . number_format($peminjaman->total_denda) . "\n";
}
