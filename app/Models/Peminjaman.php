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
        'status_verifikasi', // baru: 'belum_bayar', 'menunggu', 'terverifikasi', 'ditolak'
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

    // Method untuk mendapatkan status verifikasi pembayaran (badge)
    public function getVerifikasiBadgeAttribute()
    {
        $st = $this->status_verifikasi ?? 'belum_bayar';

        return match($st) {
            'menunggu' => 'bg-yellow-100 text-yellow-800',
            'terverifikasi' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getVerifikasiTextAttribute()
    {
        $st = $this->status_verifikasi ?? 'belum_bayar';

        return match($st) {
            'menunggu' => 'Menunggu Verifikasi Admin',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Belum Bayar',
        };
    }

    public function isVerifikasiTerverifikasi()
    {
        return ($this->status_verifikasi ?? 'belum_bayar') === 'terverifikasi';
    }

    public function isVerifikasiMenunggu()
    {
        return ($this->status_verifikasi ?? 'belum_bayar') === 'menunggu';
    }

    public function isVerifikasiDitolak()
    {
        return ($this->status_verifikasi ?? 'belum_bayar') === 'ditolak';
    }

    public function isVerifikasiBelumBayar()
    {
        return ($this->status_verifikasi ?? 'belum_bayar') === 'belum_bayar';
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
