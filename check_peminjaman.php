<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Peminjaman;
use App\Models\PembayaranDenda;

echo "=== PEMINJAMAN TERLAMBAT ===\n\n";

$peminjaman = Peminjaman::where('status', 'dipinjam')
    ->whereNotNull('tanggal_jatuh_tempo')
    ->whereDate('tanggal_jatuh_tempo', '<', now())
    ->with(['anggota', 'buku'])
    ->get();

foreach($peminjaman as $p) {
    echo "Peminjaman ID: {$p->id}\n";
    echo "  - Anggota: {$p->anggota->nama} (ID: {$p->anggota->id})\n";
    echo "  - User ID: {$p->anggota->user?->id}\n";
    echo "  - Username: {$p->anggota->user?->username}\n";
    echo "  - Buku: " . ($p->buku?->judul ?? 'DELETED') . "\n";
    echo "  - Jatuh Tempo: {$p->tanggal_jatuh_tempo}\n";
    echo "  - Denda: Rp" . number_format($p->total_denda) . "\n";
    
    // Check pembayaran denda
    $pembayaran = PembayaranDenda::where('peminjaman_id', $p->id)->first();
    if ($pembayaran) {
        echo "  - Pembayaran Status: {$pembayaran->status_pembayaran}\n";
    } else {
        echo "  - Pembayaran: Belum ada\n";
    }
    echo "\n";
}

echo "\n=== SEMUA PEMBAYARAN DENDA ===\n";
$payments = PembayaranDenda::with(['peminjaman', 'peminjaman.anggota'])->get();
foreach($payments as $p) {
    echo "Payment ID: {$p->id}\n";
    echo "  - Anggota: {$p->peminjaman->anggota->nama}\n";
    echo "  - Status: {$p->status_pembayaran}\n";
    echo "  - Jumlah: Rp" . number_format($p->jumlah_pembayaran) . "\n";
    echo "\n";
}
