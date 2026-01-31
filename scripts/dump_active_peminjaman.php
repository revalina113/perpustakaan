<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;


$rows = DB::table('peminjaman')
    ->where('peminjaman.status', 'dipinjam')
    ->leftJoin('anggota', 'peminjaman.anggota_id', '=', 'anggota.id')
    ->leftJoin('buku', 'peminjaman.buku_id', '=', 'buku.id')
    ->select('peminjaman.id as pid', 'peminjaman.anggota_id', 'anggota.nis', 'anggota.nama as anggota_nama', 'peminjaman.buku_id', 'buku.judul', 'peminjaman.tanggal_pinjam', 'peminjaman.tanggal_jatuh_tempo')
    ->get();

echo "Active loans (status = dipinjam):\n";
if ($rows->isEmpty()) {
    echo "(none)\n";
} else {
    foreach ($rows as $r) {
        echo "peminjaman id={$r->pid} anggota_id={$r->anggota_id} (nis={$r->nis} nama={$r->anggota_nama}) buku_id={$r->buku_id} judul='{$r->judul}' pinjam={$r->tanggal_pinjam} jatuh_tempo={$r->tanggal_jatuh_tempo}\n";
    }
}
