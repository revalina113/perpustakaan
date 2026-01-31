<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'total_denda',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pembayaranDenda()
    {
        return $this->hasMany(PembayaranDenda::class);
    }

    // Method untuk menghitung hari keterlambatan
    public function getHariTerlambatAttribute()
    {
        if ($this->status === 'dikembalikan' || !$this->tanggal_jatuh_tempo) {
            return 0;
        }

        $hariIni = now()->startOfDay();
        $jatuhTempo = $this->tanggal_jatuh_tempo->startOfDay();

        if ($hariIni->lte($jatuhTempo)) {
            return 0;
        }

        return $hariIni->diffInDays($jatuhTempo);
    }

    // Method untuk menghitung denda
    public function getDendaAttribute()
    {
        $aturan = AturanPeminjaman::aktif();
        $dendaPerHari = $aturan ? $aturan->denda_per_hari : 1000; // fallback ke 1000 jika tidak ada aturan

        return $this->hari_terlambat * $dendaPerHari;
    }

    // Method untuk mendapatkan status keterlambatan
    public function getStatusTerlambatAttribute()
    {
        if ($this->status === 'dikembalikan') {
            return 'Sudah Dikembalikan';
        }

        if ($this->hari_terlambat > 0) {
            return 'Terlambat ' . $this->hari_terlambat . ' hari';
        }

        return 'Tepat Waktu';
    }

    // Method untuk mendapatkan badge color
    public function getBadgeColorAttribute()
    {
        if ($this->status === 'dikembalikan') {
            return 'bg-blue-100 text-blue-800';
        }

        if ($this->hari_terlambat > 0) {
            return 'bg-red-100 text-red-800';
        }

        return 'bg-green-100 text-green-800';
    }

    // Cek apakah denda sudah dibayar (lunas)
    public function isDendaLunas()
    {
        // Jika tidak ada denda, anggap lunas
        if ($this->denda <= 0) {
            return true;
        }

        // Jumlah yang sudah dibayar dengan status 'lunas'
        $paid = $this->pembayaranDenda()->where('status_pembayaran', 'lunas')->sum('jumlah_denda');

        return $paid >= $this->denda;
    }
}
