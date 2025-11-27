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
            $table->uuid('id_matakuliah');
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
        Schema::dropIfExists('rencana_studi');
     }
 };
