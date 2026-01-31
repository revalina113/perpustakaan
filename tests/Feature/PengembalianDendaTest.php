<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\AturanPeminjaman;
use Carbon\Carbon;

class PengembalianDendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_return_blocked_when_denda_unpaid()
    {
        // setup aturan
        AturanPeminjaman::create(['lama_peminjaman' => 7, 'denda_per_hari' => 1000, 'aktif' => true]);

        $user = User::factory()->create(['role' => 'siswa', 'username' => 'siswa1']);
        $anggota = Anggota::create(['nama' => 'Siswa', 'nis' => 'siswa1', 'username' => 'siswa1', 'kelas' => 'XII RPL 1', 'alamat' => 'Jalan 1', 'status' => 'aktif']);
        $user->anggota_id = $anggota->id;
        $user->save();

        $buku = Buku::create(['judul' => 'Buku Test', 'penulis' => 'Penulis', 'penerbit' => 'Penerbit', 'tahun' => 2020, 'stok' => 5]);

        $peminjaman = Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => Carbon::now()->subDays(10),
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(3),
            'status' => 'dipinjam'
        ]);

        $this->actingAs($user, 'web')
            ->post(route('siswa.pengembalian.store'), ['peminjaman_id' => $peminjaman->id])
            ->assertSessionHas('error');
    }

    public function test_return_allowed_when_denda_paid()
    {
        AturanPeminjaman::create(['lama_peminjaman' => 7, 'denda_per_hari' => 1000, 'aktif' => true]);

        $user = User::factory()->create(['role' => 'siswa', 'username' => 'siswa2']);
        $anggota = Anggota::create(['nama' => 'Siswa2', 'nis' => 'siswa2', 'username' => 'siswa2', 'kelas' => 'XII RPL 1', 'alamat' => 'Jalan 2', 'status' => 'aktif']);
        $user->anggota_id = $anggota->id;
        $user->save();

        $buku = Buku::create(['judul' => 'Buku Test', 'penulis' => 'Penulis', 'penerbit' => 'Penerbit', 'tahun' => 2020, 'stok' => 5]);

        $peminjaman = Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => Carbon::now()->subDays(10),
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(3),
            'status' => 'dipinjam'
        ]);

        // create pembayaran lunas equal to denda
        $denda = $peminjaman->denda;
        $peminjaman->pembayaranDenda()->create([
            'anggota_id' => $anggota->id,
            'jumlah_denda' => $denda,
            'status_pembayaran' => 'lunas',
            'tanggal_bayar' => now(),
        ]);

        $this->actingAs($user, 'web')
            ->post(route('siswa.pengembalian.store'), ['peminjaman_id' => $peminjaman->id])
            ->assertRedirect(route('siswa.jatuh-tempo.index'));
    }
}
