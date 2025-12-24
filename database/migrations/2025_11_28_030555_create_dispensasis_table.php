<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispensasisTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('dispensasis')) {
            Schema::create('dispensasis', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('id_user')->nullable();
                $table->string('tahun_akademik')->nullable();
                $table->integer('jumlah_pengajuan')->nullable();
                $table->string('no_hp')->nullable();
                $table->date('tanggal_deadline')->nullable();
                $table->string('surat_dispensasi')->nullable();
                $table->enum('status', ['menunggu','diterima_dosen','diterima_warek','diterima_keuangan','disetujui','ditolak'])->default('menunggu');
                $table->timestamps();

                $table->index('id_user');
                $table->index('status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('dispensasis');
    }
}
