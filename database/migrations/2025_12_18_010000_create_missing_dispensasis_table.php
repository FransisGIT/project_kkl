<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingDispensasisTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('dispensasis')) {
            Schema::create('dispensasis', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('id_user')->nullable();
                $table->string('tahun_akademik')->nullable();
                $table->integer('jumlah_pengajuan')->nullable();
                $table->integer('jumlah')->nullable();
                $table->string('no_hp')->nullable();
                $table->date('tanggal_deadline')->nullable();
                $table->string('file_surat')->nullable();
                $table->string('file_pdf')->nullable();
                $table->enum('status', ['menunggu','diterima_dosen','diterima_warek','diterima_keuangan','disetujui','ditolak'])->default('menunggu');
                $table->json('approver_notes')->nullable();
                $table->json('applied_action')->nullable();
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
