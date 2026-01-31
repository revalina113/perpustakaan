<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    public function run()
    {
        $books = [
            ['judul' => 'Buku Laravel', 'penulis' => 'Tono', 'penerbit' => 'Erlangga', 'tahun' => 2020, 'stok' => 10],
            ['judul' => 'Buku PHP', 'penulis' => 'Budi', 'penerbit' => 'Gramedia', 'tahun' => 2019, 'stok' => 8],
            ['judul' => 'Buku MySQL', 'penulis' => 'Susi', 'penerbit' => 'Informatika', 'tahun' => 2018, 'stok' => 12],
            ['judul' => 'Buku JavaScript', 'penulis' => 'Andi', 'penerbit' => 'Mizan', 'tahun' => 2021, 'stok' => 15],
            ['judul' => 'Buku CSS', 'penulis' => 'Rina', 'penerbit' => 'Erlangga', 'tahun' => 2022, 'stok' => 7],
            ['judul' => 'Buku HTML', 'penulis' => 'Dina', 'penerbit' => 'Gramedia', 'tahun' => 2020, 'stok' => 9],
            ['judul' => 'Buku Algoritma', 'penulis' => 'Joko', 'penerbit' => 'Informatika', 'tahun' => 2017, 'stok' => 5],
            ['judul' => 'Buku Struktur Data', 'penulis' => 'Tika', 'penerbit' => 'Mizan', 'tahun' => 2016, 'stok' => 6],
            ['judul' => 'Buku Pemrograman', 'penulis' => 'Rudi', 'penerbit' => 'Erlangga', 'tahun' => 2023, 'stok' => 11],
            ['judul' => 'Buku Basis Data', 'penulis' => 'Lina', 'penerbit' => 'Gramedia', 'tahun' => 2015, 'stok' => 4],
        ];

        foreach ($books as $b) {
            Buku::create($b);
        }
    }
}
