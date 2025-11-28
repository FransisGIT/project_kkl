<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_matakuliah' => (string) Str::uuid(),
                'kode_matakuliah' => '23STNKN1121',
                'nama_matakuliah' => 'Bahasa Indonesia',
                'sks' => 2,
                'semester' => 1,
                'group' => '15A',
                'hari' => 'Senin',
                'jam' => '15:30-18:00',
                'kapasitas' => 40,
                'peserta' => 38,
            ],
            [
                'id_matakuliah' => (string) Str::uuid(),
                'kode_matakuliah' => '23STNKN1124',
                'nama_matakuliah' => 'Pancasila',
                'sks' => 2,
                'semester' => 1,
                'group' => '15A',
                'hari' => 'Selasa',
                'jam' => '07:00-09:30',
                'kapasitas' => 40,
                'peserta' => 38,
            ],
            [
                'id_matakuliah' => (string) Str::uuid(),
                'kode_matakuliah' => '23STNKN1125',
                'nama_matakuliah' => 'Kriptografi',
                'sks' => 3,
                'semester' => 1,
                'group' => '15A',
                'hari' => 'Selasa',
                'jam' => '09:30-12:00',
                'kapasitas' => 40,
                'peserta' => 20,
            ],
            [
                'id_matakuliah' => (string) Str::uuid(),
                'kode_matakuliah' => '23STNKN1126',
                'nama_matakuliah' => 'Web Programming',
                'sks' => 3,
                'semester' => 3,
                'group' => '35A',
                'hari' => 'Kamis',
                'jam' => '09:30-12:00',
                'kapasitas' => 40,
                'peserta' => 38,
            ],
            [
                'id_matakuliah' => (string) Str::uuid(),
                'kode_matakuliah' => '23STNKN1127',
                'nama_matakuliah' => 'Mobile Programming',
                'sks' => 3,
                'semester' => 3,
                'group' => '35A',
                'hari' => 'Kamis',
                'jam' => '07:00-09:30',
                'kapasitas' => 40,
                'peserta' => 36,
            ],
            [
                'id_matakuliah' => (string) Str::uuid(),
                'kode_matakuliah' => '23STNKN1128',
                'nama_matakuliah' => 'Algoritma Pemrograman',
                'sks' => 3,
                'semester' => 3,
                'group' => '35A',
                'hari' => 'Jumat',
                'jam' => '13:30-15:40',
                'kapasitas' => 40,
                'peserta' => 38,
            ],
        ];

        foreach ($data as $mk) {
            DB::table('mata_kuliah')->insert($mk);
        }
    }
}
