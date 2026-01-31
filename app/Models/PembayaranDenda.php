<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDenda extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_denda';

    protected $fillable = [
        'peminjaman_id',
        'anggota_id',
        'jumlah_denda',
        'bukti_pembayaran',
        'status_pembayaran',
        'tanggal_bayar',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_denda' => 'integer',
    ];

    // Relationships
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status_pembayaran) {
            'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
            'lunas' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status_pembayaran) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'ditolak' => 'Ditolak',
            default => 'Unknown',
        };
    }
}
