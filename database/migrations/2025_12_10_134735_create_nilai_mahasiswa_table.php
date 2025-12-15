<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id_nilai');
            $table->uuid('id_user');
            $table->uuid('id_matakuliah');
            $table->string('nilai', 2); // A, B+, B, C+, C, D, E
            $table->decimal('nilai_angka', 3, 2); // 4.00, 3.50, dst
            $table->enum('status', ['lulus', 'tidak_lulus'])->default('lulus');
            $table->string('semester_ambil', 10); // misal: 2024/2025-1
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_matakuliah')->references('id_matakuliah')->on('mata_kuliah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_mahasiswa');
    }
}
