<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->nullable();
            $table->string('nama')->nullable();
            $table->time('jam_absen')->nullable()->useCurrent();
            $table->date('tanggal_absen')->nullable()->useCurrent();
            $table->string('mata_kuliah')->nullable();
            $table->string('ruangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_mahasiswas');
    }
};
