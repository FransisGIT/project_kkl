<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Extend enum to include menunggu_warek
        \DB::statement("ALTER TABLE `rencana_studi` MODIFY `status` ENUM('menunggu','menunggu_warek','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }

    public function down()
    {
        // Revert to original enum (dangerous if data exists with menunggu_warek)
        \DB::statement("ALTER TABLE `rencana_studi` MODIFY `status` ENUM('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};
