<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\AturanPeminjaman;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request as HttpRequest;

class PeminjamanController extends Controller
{
    public function index(HttpRequest $request)
    {
        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);

        Log::info('Peminjaman index: user=' . Auth::user()->username . ', anggota=' . ($anggota ? $anggota->id : 'null'));

        // Always get categories for filter
        $kategori = Buku::distinct()->pluck('penerbit');

        // Always build book query (books should always be displayed)
        $query = Buku::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('penulis', 'like', '%' . $search . '%');
            });
        }

        // Filter by category (using penerbit as category)
        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->where('penerbit', $request->kategori);
        }

        // Always paginate books and include review aggregates
        $buku = $query->withCount('reviews')->withAvg('reviews', 'rating')->paginate(10);

        // Handle active loans based on member status
        $peminjamanAktif = collect();
        $warningMessage = null;

        if ($anggota && $anggota->status === 'aktif') {
            // Member found and active, get their active loans
            $peminjamanAktif = Peminjaman::where('anggota_id', $anggota->id)
                ->where('status', 'dipinjam')
                ->with('buku')
                ->get();
        } elseif ($anggota && $anggota->status !== 'aktif') {
            // Member found but not active
            $warningMessage = 'Akun anggota Anda tidak aktif. Silakan hubungi admin.';
        } else {
            // Member not found
            $warningMessage = 'Anda belum terdaftar sebagai anggota perpustakaan. Silakan hubungi admin untuk registrasi.';
        }

        return view('siswa.peminjaman.index', compact('buku', 'peminjamanAktif', 'kategori', 'anggota'))
            ->with('warning', $warningMessage);
    }

    public function show(Buku $buku)
    {
        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);

        Log::info('Peminjaman show: user=' . Auth::user()->username . ', anggota=' . ($anggota ? $anggota->id : 'null'));

        // Check if student can borrow this book
        $canBorrow = true;
        $borrowMessage = '';

        if (!$anggota) {
            $canBorrow = false;
            $borrowMessage = 'Data anggota tidak ditemukan.';
        } elseif ($anggota->status !== 'aktif') {
            $canBorrow = false;
            $borrowMessage = 'Akun anggota Anda tidak aktif.';
        } elseif ($buku->stok <= 0) {
            $canBorrow = false;
            $borrowMessage = 'Buku tidak tersedia.';
        } else {
            // Check if student already has this book borrowed
            $existing = Peminjaman::where('anggota_id', $anggota->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($existing) {
                $canBorrow = false;
                $borrowMessage = 'Anda sudah meminjam buku ini.';
            } else {
                // Check maximum loan limit (3 books)
                $currentLoans = Peminjaman::where('anggota_id', $anggota->id)
                    ->where('status', 'dipinjam')
                    ->count();

                if ($currentLoans >= 3) {
                    $canBorrow = false;
                    $borrowMessage = 'Anda sudah mencapai batas maksimal peminjaman (3 buku).';
                }
            }
        }

        // Load reviews for the book
        $reviews = \App\Models\Review::where('buku_id', $buku->id)->with('anggota')->orderBy('created_at', 'desc')->get();

        // Determine if the current siswa ever borrowed this book (for review permission)
        $anggota = Auth::user()->anggota ?? Anggota::where('username', Auth::user()->username)->first();
        $hasBorrowed = false;
        $myReview = null;
        if ($anggota) {
            $hasBorrowed = Peminjaman::where('anggota_id', $anggota->id)->where('buku_id', $buku->id)->exists();
            $myReview = \App\Models\Review::where('buku_id', $buku->id)->where('anggota_id', $anggota->id)->first();
        }

        return view('siswa.buku.show', compact('buku', 'canBorrow', 'borrowMessage', 'reviews', 'hasBorrowed', 'myReview'));

    }

    // Riwayat peminjaman siswa (semua status)
    public function history(HttpRequest $request)
    {
        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);
        if (!$anggota) {
            return back()->with('error', 'Data anggota tidak ditemukan.');
        }

        $riwayat = Peminjaman::where('anggota_id', $anggota->id)
            ->with(['buku', 'pembayaranDenda'])
            ->orderBy('tanggal_pinjam', 'desc')
            ->paginate(15);

        return view('siswa.peminjaman.history', compact('riwayat'));
    }

    // Cetak resi PDF untuk satu peminjaman
    public function resi(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);
        if (!$anggota || $peminjaman->anggota_id !== $anggota->id) {
            return abort(403);
        }

        // Siapkan data yang diperlukan di view resi
        $peminjaman->load(['buku', 'anggota', 'pembayaranDenda']);

        // Path logo (jika ada di public/images/logo.svg)
        $logoPath = file_exists(public_path('images/logo.svg')) ? public_path('images/logo.svg') : null;

        // Signed temporary URL for QR-based verification (valid for 30 days)
        $verifyUrl = URL::temporarySignedRoute('peminjaman.verify-resi', now()->addDays(30), ['peminjaman' => $peminjaman->id]);

        // Pastikan paket dompdf terpasang: composer require barryvdh/laravel-dompdf
        $pdf = Pdf::loadView('siswa.peminjaman.resi_pdf', compact('peminjaman', 'logoPath', 'verifyUrl'));

        $filename = 'resi-peminjaman-' . $peminjaman->id . '.pdf';

        return $pdf->stream($filename);
    }

    // Verify resi using signed URL
    public function verifyResi(HttpRequest $request, Peminjaman $peminjaman)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $peminjaman->load(['buku', 'anggota', 'pembayaranDenda']);
        return view('siswa.peminjaman.verify_resi', compact('peminjaman'));
    }

    // Direct download endpoint for resi
    public function resiDownload(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);
        if (!$anggota || $peminjaman->anggota_id !== $anggota->id) {
            return abort(403);
        }

        $peminjaman->load(['buku', 'anggota', 'pembayaranDenda']);
        $logoPath = file_exists(public_path('images/logo.svg')) ? public_path('images/logo.svg') : null;
        $verifyUrl = URL::temporarySignedRoute('peminjaman.verify-resi', now()->addDays(30), ['peminjaman' => $peminjaman->id]);
        $pdf = Pdf::loadView('siswa.peminjaman.resi_pdf', compact('peminjaman', 'anggota', 'logoPath', 'verifyUrl'));
        $filename = 'resi-peminjaman-' . $peminjaman->id . '.pdf';

        return $pdf->download($filename);
    }

    public function store(HttpRequest $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
        ]);

        $user = Auth::user();
        $anggota = $user->anggota ?? Anggota::find($user->anggota_id);
        if (!$anggota) {
            return back()->with('error', 'Data anggota tidak ditemukan.');
        }

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Buku tidak tersedia.');
        }

        // Check if student already has this book borrowed
        $existing = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $request->buku_id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda sudah meminjam buku ini.');
        }

        // Check maximum loan limit (3 books)
        $currentLoans = Peminjaman::where('anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->count();

        if ($currentLoans >= 3) {
            return back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (3 buku).');
        }

        // Get active loan rules
        $aturan = AturanPeminjaman::aktif();
        $lamaPeminjaman = $aturan ? $aturan->lama_peminjaman : 7; // fallback ke 7 hari

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now(),
            'tanggal_jatuh_tempo' => now()->addDays($lamaPeminjaman),
            'tanggal_kembali' => null, // NULL sampai dikembalikan
            'status' => 'dipinjam',
        ]);

        $buku->decrement('stok');

        return back()->with('success', 'Buku berhasil dipinjam.');
    }
}