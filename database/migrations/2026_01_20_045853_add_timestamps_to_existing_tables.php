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
        // Add timestamps to anggota table if they don't exist
        if (!Schema::hasColumn('anggota', 'created_at')) {
            Schema::table('anggota', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // Add timestamps to peminjaman table if they don't exist
        if (!Schema::hasColumn('peminjaman', 'created_at')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // Add timestamps to pengembalian table if they don't exist
        if (!Schema::hasColumn('pengembalian', 'created_at')) {
            Schema::table('pengembalian', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Remove timestamps from tables
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
