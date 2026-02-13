<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    use HasFactory;

    protected $table = 'pengunjung';

    protected $fillable = [
        'anggota_id',
        'tanggal_kunjungan',
        'jam_masuk',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        // 'jam_masuk' cast removed because 'time' is not a valid Eloquent cast
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
