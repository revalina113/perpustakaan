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
        Schema::create('aturan_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->integer('lama_peminjaman')->default(7)->comment('Lama peminjaman dalam hari');
            $table->integer('denda_per_hari')->default(1000)->comment('Denda per hari keterlambatan dalam rupiah');
            $table->text('deskripsi')->nullable()->comment('Deskripsi aturan peminjaman');
            $table->boolean('aktif')->default(true)->comment('Status aturan aktif atau tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aturan_peminjaman');
    }
};
