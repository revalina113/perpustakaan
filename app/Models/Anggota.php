<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $fillable = [
        'nama',
        'nis',
        'username',
        'kelas',
        'jenis_kelamin',
        'no_hp',
        'status'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'anggota_id');
    }
}
