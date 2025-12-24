<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DemoKrsSeeder extends Seeder
{
    public function run(): void
    {
        // Find a mahasiswa user
        $mahasiswa = DB::table('users')->where('id_role', 3)->first();
        if (! $mahasiswa) return;

        $mata = DB::table('mata_kuliah')->limit(8)->get();
        if ($mata->count() == 0) return;

        // Mark first two as already passed (nilai mahasiswa)
        $passed = $mata->slice(0, 2)->pluck('id_matakuliah')->all();
        foreach ($passed as $idMk) {
            $exists = DB::table('nilai_mahasiswa')
                ->where('id_user', $mahasiswa->id_user)
                ->where('id_matakuliah', $idMk)
                ->exists();
            if (! $exists) {
                DB::table('nilai_mahasiswa')->insert([
                    'id_user' => $mahasiswa->id_user,
                    'id_matakuliah' => $idMk,
                    'nilai' => 'A',
                    'nilai_angka' => 4.00,
                    'status' => 'lulus',
                    'semester_ambil' => date('Y') . '/' . substr((string) (date('Y') + 1), -2) . '-1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create a pending rencana with next two mata kuliah
        $pending = $mata->slice(2, 2)->pluck('id_matakuliah')->all();
        if (! empty($pending)) {
            $exists = DB::table('rencana_studi')
                ->where('id_user', $mahasiswa->id_user)
                ->whereIn('status', ['menunggu','menunggu_keuangan','menunggu_warek'])
                ->exists();
            if (! $exists) {
                DB::table('rencana_studi')->insert([
                    'id_user' => $mahasiswa->id_user,
                    'id_mata_kuliah' => json_encode($pending),
                    'status' => 'menunggu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Optionally create another rencana that is disetujui to show different color
        $approved = $mata->slice(4, 1)->pluck('id_matakuliah')->all();
        if (! empty($approved)) {
            $exists = DB::table('rencana_studi')
                ->where('id_user', $mahasiswa->id_user)
                ->where('status', 'disetujui')
                ->exists();
            if (! $exists) {
                DB::table('rencana_studi')->insert([
                    'id_user' => $mahasiswa->id_user,
                    'id_mata_kuliah' => json_encode($approved),
                    'status' => 'disetujui',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
