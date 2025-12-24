<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dispensasis', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('surat_dispensasi');
        });
    }

    public function down()
    {
        Schema::table('dispensasis', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }
};
