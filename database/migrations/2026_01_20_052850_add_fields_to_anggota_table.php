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
        Schema::table('anggota', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->after('kelas');
            $table->string('no_hp', 20)->nullable()->after('jenis_kelamin');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'no_hp', 'status']);
        });
    }
};
