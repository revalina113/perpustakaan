<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Buku $buku)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::where('username', $user->username)->first();
        if (!$anggota) {
            return back()->with('error', 'Data anggota tidak ditemukan.');
        }

        // Pastikan siswa pernah meminjam atau sedang meminjam buku ini
        $hasBorrowed = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->exists();

        if (! $hasBorrowed) {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan jika pernah meminjam buku ini.');
        }

        // Buat atau perbarui review (1 siswa 1 review per buku)
        $review = Review::updateOrCreate(
            ['buku_id' => $buku->id, 'anggota_id' => $anggota->id],
            ['rating' => $request->rating, 'komentar' => $request->komentar]
        );

        return back()->with('success', 'Ulasan berhasil disimpan. Terima kasih!');
    }
}
