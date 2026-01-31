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
        if (!Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('anggota_id');
                $table->unsignedBigInteger('buku_id');
                $table->date('tanggal_pinjam');
                $table->date('tanggal_kembali');
                $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
};
