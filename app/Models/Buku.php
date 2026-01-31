<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'stok',
        'gambar'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'buku_id');
    }
}
