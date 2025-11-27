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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->uuid('id_matakuliah')->primary()->autoIncrement();
            $table->string('kode_matakuliah')->unique();
            $table->string('nama_matakuliah');
            $table->bigInteger('sks')->default(2);
            $table->bigInteger('semester')->nullable();
            $table->string('group')->nullable();
            $table->string('hari')->nullable();
            $table->string('jam')->nullable();
            $table->bigInteger('kapasitas')->default(0);
            $table->bigInteger('peserta')->default(0);
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
        Schema::dropIfExists('mata_kuliah');
    }
};
