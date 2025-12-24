<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `rencana_studi` MODIFY `status` ENUM('menunggu','menunggu_keuangan','menunggu_warek','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `rencana_studi` MODIFY `status` ENUM('menunggu','menunggu_warek','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};
