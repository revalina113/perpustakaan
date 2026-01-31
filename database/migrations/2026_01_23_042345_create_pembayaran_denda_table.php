<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_denda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->integer('jumlah_denda');
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['menunggu_verifikasi', 'lunas', 'ditolak'])->default('menunggu_verifikasi');
            $table->date('tanggal_bayar');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index(['anggota_id', 'status_pembayaran']);
            $table->index('peminjaman_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_denda');
    }
};
