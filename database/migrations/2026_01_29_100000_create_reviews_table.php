<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buku_id');
            $table->unsignedBigInteger('anggota_id');
            $table->tinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->unique(['buku_id', 'anggota_id']);

            $table->foreign('buku_id')->references('id')->on('buku')->onDelete('cascade');
            $table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};