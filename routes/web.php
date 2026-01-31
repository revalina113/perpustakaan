<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\AturanPeminjamanController;
use App\Http\Controllers\Siswa\PeminjamanController;
use App\Http\Controllers\Siswa\PengembalianController;
use App\Http\Controllers\Siswa\JatuhTempoController;
use App\Http\Controllers\Admin\JatuhTempoController as AdminJatuhTempoController;
use App\Http\Controllers\Admin\PembayaranDendaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('home');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD ROUTE (redirect sesuai role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('siswa.dashboard');
})->middleware(['auth','must.change.password'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','must.change.password', 'isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        // CRUD Buku (Admin)
        Route::resource('buku', BukuController::class);

        // HAPUS route profil siswa dari sini!
        // Route::get('profil', function() {
        //     return view('siswa.profil');
        // })->name('profil');
        Route::resource('transaksi', TransaksiController::class);
        Route::post('transaksi/{id}/kembalikan', [TransaksiController::class, 'markAsReturned'])
            ->name('transaksi.kembalikan');
        Route::get('transaksi-search', [TransaksiController::class, 'search'])
            ->name('transaksi.search');

        // CRUD Anggota (Admin)
        Route::resource('anggota', AnggotaController::class);
        Route::post('anggota/{id}/toggle-status', [AnggotaController::class, 'toggleStatus'])
            ->name('anggota.toggle-status');

        // Aturan Peminjaman
        Route::get('aturan-peminjaman', [AturanPeminjamanController::class, 'index'])
            ->name('aturan-peminjaman.index');
        Route::put('aturan-peminjaman', [AturanPeminjamanController::class, 'update'])
            ->name('aturan-peminjaman.update');

        // Pembayaran Denda
        Route::get('pembayaran-denda', [PembayaranDendaController::class, 'index'])
            ->name('pembayaran-denda.index');
        Route::get('pembayaran-denda/{id}', [PembayaranDendaController::class, 'show'])
            ->name('pembayaran-denda.show');
        Route::put('pembayaran-denda/{id}/status', [PembayaranDendaController::class, 'updateStatus'])
            ->name('pembayaran-denda.update-status');
        Route::post('pembayaran-denda/{id}/verifikasi', [PembayaranDendaController::class, 'verifikasi'])
            ->name('pembayaran-denda.verifikasi');
        Route::post('pembayaran-denda/bulk-verifikasi', [PembayaranDendaController::class, 'bulkVerifikasi'])
            ->name('pembayaran-denda.bulk-verifikasi');
        Route::post('pembayaran-denda/{id}/tolak', [PembayaranDendaController::class, 'tolak'])
            ->name('pembayaran-denda.tolak');

        // Jatuh Tempo & Denda
        Route::get('jatuh-tempo', [AdminJatuhTempoController::class, 'index'])
            ->name('jatuh-tempo.index');
        Route::get('jatuh-tempo/{id}', [AdminJatuhTempoController::class, 'show'])
            ->name('jatuh-tempo.show');
        Route::get('jatuh-tempo/bukti/{id}', [AdminJatuhTempoController::class, 'viewBukti'])
            ->name('jatuh-tempo.bukti');
    });

/*
|--------------------------------------------------------------------------
| SISWA ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','must.change.password', 'isSiswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        Route::get('/dashboard', [SiswaDashboard::class, 'index'])
            ->name('dashboard');

        // Detail Buku
        Route::get('/buku/{buku}', [PeminjamanController::class, 'show'])
            ->name('buku.show');

        // Reviews (siswa) — hanya siswa yang pernah meminjam bisa memberi
        Route::post('/buku/{buku}/review', [\App\Http\Controllers\ReviewController::class, 'store'])
            ->name('buku.review.store');

        // Peminjaman Buku
        Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'store']);

        // Riwayat Peminjaman & Resi PDF
        Route::get('peminjaman/riwayat', [PeminjamanController::class, 'history'])
            ->name('peminjaman.history');
        Route::get('peminjaman/{peminjaman}/resi', [PeminjamanController::class, 'resi'])
            ->name('peminjaman.resi');
        // Direct download of resi PDF
        Route::get('peminjaman/{peminjaman}/resi/download', [PeminjamanController::class, 'resiDownload'])
            ->name('peminjaman.resi.download');

        // Pengembalian Buku
        Route::resource('pengembalian', PengembalianController::class)->only(['index', 'store']);

        // Jatuh Tempo & Denda
        Route::get('jatuh-tempo', [JatuhTempoController::class, 'index'])
            ->name('jatuh-tempo.index');
        Route::post('jatuh-tempo/bayar-denda', [JatuhTempoController::class, 'bayarDenda'])->name('jatuh-tempo.bayar-denda');

        // Profil Siswa
        Route::get('profil', function() {
            return view('siswa.profil');
        })->name('profil');
        // Edit Profil Siswa
        Route::get('profil/edit', function() {
            return view('siswa.profil-edit');
        })->name('profil.edit');
        Route::post('profil/edit', [\App\Http\Controllers\Siswa\ProfilController::class, 'update'])->name('profil.update');
    });

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES (temporary)
|--------------------------------------------------------------------------
|
| Routes below are temporary helpers to check that views render correctly
| without admin middleware or layout interference. They will be removed
| after verification.
|
*/

// Public signed route for resi verification (used by QR code in resi PDF)
Route::get('peminjaman/{peminjaman}/verify-resi', [\App\Http\Controllers\Siswa\PeminjamanController::class, 'verifyResi'])
    ->name('peminjaman.verify-resi')
    ->middleware('signed');
Route::get('/debug/admin/buku', function() {
    $buku = App\Models\Buku::latest()->paginate(15);
    return view('admin.buku.index', compact('buku'));
});

Route::get('/debug/admin/aturan', function() {
    $aturan = App\Models\AturanPeminjaman::aktif();
    return view('admin.aturan-peminjaman.index', compact('aturan'));
});

Route::get('/debug/admin/jatuh-tempo', function() {
    $peminjamanTerlambat = App\Models\Peminjaman::where('status', 'dipinjam')
        ->whereNotNull('tanggal_jatuh_tempo')
        ->whereDate('tanggal_jatuh_tempo', '<', now())
        ->with(['anggota', 'buku'])
        ->paginate(15);
    $aturan = App\Models\AturanPeminjaman::aktif();
    return view('admin.jatuh-tempo.index', compact('peminjamanTerlambat', 'aturan'));
});

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','must.change.password'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Change password (forced if must_change_password is true)
    Route::get('/change-password', [\App\Http\Controllers\Auth\ChangePasswordController::class, 'edit'])
        ->name('password.change');
    Route::post('/change-password', [\App\Http\Controllers\Auth\ChangePasswordController::class, 'update'])
        ->name('password.update');
});

// Graceful handler for GET /logout (some browsers/users might request /logout via GET)
// We keep logout action POST-only for security. This GET route redirects back with a helpful message.
Route::get('/logout', function () {
    return redirect()->back()->with('error', 'Gunakan tombol Logout (POST) untuk keluar dari aplikasi.');
})->name('logout.get');
