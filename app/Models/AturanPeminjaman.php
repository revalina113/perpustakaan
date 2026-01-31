<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AturanPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'aturan_peminjaman';

    protected $fillable = [
        'lama_peminjaman',
        'denda_per_hari',
        'deskripsi',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'lama_peminjaman' => 'integer',
        'denda_per_hari' => 'integer'
    ];

    /**
     * Get the active loan rule
     */
    public static function aktif()
    {
        return static::where('aktif', true)->first();
    }

    /**
     * Calculate due date based on loan date
     */
    public function hitungTanggalJatuhTempo($tanggalPinjam)
    {
        return \Carbon\Carbon::parse($tanggalPinjam)->addDays($this->lama_peminjaman);
    }

    /**
     * Calculate fine based on overdue days
     */
    public function hitungDenda($hariTerlambat)
    {
        return $hariTerlambat * $this->denda_per_hari;
    }
}
