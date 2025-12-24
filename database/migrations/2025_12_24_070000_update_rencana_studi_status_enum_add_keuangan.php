<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // Add 'menunggu_keuangan' to enum
        \DB::statement("ALTER TABLE `rencana_studi` MODIFY `status` ENUM('menunggu','menunggu_keuangan','menunggu_warek','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }

    public function down()
    {
        // Revert by removing 'menunggu_keuangan' (dangerous if data exists)
        \DB::statement("ALTER TABLE `rencana_studi` MODIFY `status` ENUM('menunggu','menunggu_warek','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};
