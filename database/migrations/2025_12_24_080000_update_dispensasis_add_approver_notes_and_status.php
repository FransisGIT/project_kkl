<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add approver_notes JSON column and allow menunggu_warek status
        DB::statement("ALTER TABLE `dispensasis` MODIFY `status` ENUM('menunggu','menunggu_warek','diterima_dosen','diterima_warek','diterima_keuangan','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
        DB::statement("ALTER TABLE `dispensasis` ADD COLUMN `approver_notes` JSON NULL AFTER `status`");
    }

    public function down()
    {
        // Revert enum (dangerous if rows exist with menunggu_warek)
        DB::statement("ALTER TABLE `dispensasis` MODIFY `status` ENUM('menunggu','diterima_dosen','diterima_warek','diterima_keuangan','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
        \Illuminate\Support\Facades\Schema::table('dispensasis', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('approver_notes');
        });
    }
};
