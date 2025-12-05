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
        Schema::create('rencana_studi', function (Blueprint $table) {
            $table->bigIncrements('id_rencana_studi');
            $table->uuid('id_user');
            $table->json('id_mata_kuliah');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
        Schema::dropIfExists('rencana_studi');
     }
 };
