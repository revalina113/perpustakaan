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
        if (!Schema::hasTable('pengembalian')) {
            Schema::create('pengembalian', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('peminjaman_id');
                $table->date('tanggal_pengembalian');
                $table->decimal('denda', 10, 2)->default(0);
                $table->enum('status', ['dikembalikan', 'terlambat'])->default('dikembalikan');
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
        Schema::dropIfExists('pengembalian');
    }
};
