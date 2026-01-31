<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ResiVerifyTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_url_verifies_successfully()
    {
        $user = User::factory()->create(['role' => 'siswa', 'username' => 'siswa3']);
        $anggota = Anggota::create(['nama' => 'Siswa3', 'nis' => 'siswa3', 'username' => 'siswa3', 'kelas' => 'XII RPL 1', 'alamat' => 'Jalan 3', 'status' => 'aktif']);
        $user->anggota_id = $anggota->id;
        $user->save();

        $buku = Buku::create(['judul' => 'Buku QR', 'penulis' => 'P', 'penerbit' => 'Pub', 'tahun' => 2020, 'stok' => 2]);

        $peminjaman = Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(7),
            'status' => 'dipinjam'
        ]);

        $url = URL::temporarySignedRoute('peminjaman.verify-resi', now()->addMinutes(10), ['peminjaman' => $peminjaman->id]);

        $this->get($url)->assertStatus(200)->assertSee('Verifikasi Resi #' . $peminjaman->id);
    }

    public function test_verify_without_signature_is_forbidden()
    {
        $buku = Buku::create(['judul' => 'Buku QR', 'penulis' => 'P', 'penerbit' => 'Pub', 'tahun' => 2020, 'stok' => 2]);
        $anggota = Anggota::create(['nama' => 'Siswa4', 'nis' => 'siswa4', 'username' => 'siswa4', 'kelas' => 'X RPL 2', 'alamat' => 'Jalan 4', 'status' => 'aktif']);
        $peminjaman = Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(7),
            'status' => 'dipinjam'
        ]);

        $url = route('peminjaman.verify-resi', ['peminjaman' => $peminjaman->id]);
        $this->get($url)->assertStatus(403);
    }
}
