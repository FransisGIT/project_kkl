<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NilaiMahasiswa;
use App\Models\User;
use App\Models\MataKuliah;

class NilaiMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil mahasiswa
        $mahasiswa = User::where('id_role', 3)->get();

        if ($mahasiswa->isEmpty()) {
            $this->command->warn('Tidak ada mahasiswa ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Ambil mata kuliah berdasarkan kode
        $mataKuliah = MataKuliah::all()->keyBy('kode_matakuliah');

        if ($mataKuliah->isEmpty()) {
            $this->command->warn('Tidak ada mata kuliah ditemukan. Jalankan MataKuliahSeeder terlebih dahulu.');
            return;
        }

        // Mahasiswa 1 - Sudah menyelesaikan semester 1 dan 2 dengan baik (bisa ambil semester 3 dan 4)
        if (isset($mahasiswa[0])) {
            $mhs1 = $mahasiswa[0];

            // Semester 1
            if ($mataKuliah->has('TI101')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI101']->id_matakuliah,
                    'nilai' => 'A',
                    'nilai_angka' => 3.75,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            if ($mataKuliah->has('TI102')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI102']->id_matakuliah,
                    'nilai' => 'B',
                    'nilai_angka' => 3.25,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            if ($mataKuliah->has('TI103')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI103']->id_matakuliah,
                    'nilai' => 'A',
                    'nilai_angka' => 3.80,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            // Semester 2
            if ($mataKuliah->has('TI201')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI201']->id_matakuliah,
                    'nilai' => 'B',
                    'nilai_angka' => 3.00,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Genap',
                ]);
            }

            if ($mataKuliah->has('TI202')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI202']->id_matakuliah,
                    'nilai' => 'A',
                    'nilai_angka' => 3.60,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Genap',
                ]);
            }

            if ($mataKuliah->has('TI203')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI203']->id_matakuliah,
                    'nilai' => 'A',
                    'nilai_angka' => 3.70,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Genap',
                ]);
            }

            // Semester 3
            if ($mataKuliah->has('TI301')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI301']->id_matakuliah,
                    'nilai' => 'B',
                    'nilai_angka' => 3.20,
                    'status' => 'lulus',
                    'semester_ambil' => '2025/2026 Ganjil',
                ]);
            }

            if ($mataKuliah->has('TI302')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs1->id_user,
                    'id_matakuliah' => $mataKuliah['TI302']->id_matakuliah,
                    'nilai' => 'A',
                    'nilai_angka' => 3.85,
                    'status' => 'lulus',
                    'semester_ambil' => '2025/2026 Ganjil',
                ]);
            }
        }

        // Mahasiswa 2 - Baru semester 2, sudah lulus semester 1 tapi belum lulus Web Programming
        if (isset($mahasiswa[1])) {
            $mhs2 = $mahasiswa[1];

            // Semester 1
            if ($mataKuliah->has('TI101')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs2->id_user,
                    'id_matakuliah' => $mataKuliah['TI101']->id_matakuliah,
                    'nilai' => 'B',
                    'nilai_angka' => 3.10,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            if ($mataKuliah->has('TI102')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs2->id_user,
                    'id_matakuliah' => $mataKuliah['TI102']->id_matakuliah,
                    'nilai' => 'C',
                    'nilai_angka' => 2.50,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            if ($mataKuliah->has('TI103')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs2->id_user,
                    'id_matakuliah' => $mataKuliah['TI103']->id_matakuliah,
                    'nilai' => 'B',
                    'nilai_angka' => 3.00,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            // Semester 2 - Web Programming TIDAK LULUS
            if ($mataKuliah->has('TI201')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs2->id_user,
                    'id_matakuliah' => $mataKuliah['TI201']->id_matakuliah,
                    'nilai' => 'C',
                    'nilai_angka' => 2.60,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Genap',
                ]);
            }

            if ($mataKuliah->has('TI203')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs2->id_user,
                    'id_matakuliah' => $mataKuliah['TI203']->id_matakuliah,
                    'nilai' => 'E',
                    'nilai_angka' => 0.00,
                    'status' => 'tidak_lulus',
                    'semester_ambil' => '2024/2025 Genap',
                ]);
            }
        }

        // Mahasiswa 3 - Baru semester 1
        if (isset($mahasiswa[2])) {
            $mhs3 = $mahasiswa[2];

            // Semester 1
            if ($mataKuliah->has('TI101')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs3->id_user,
                    'id_matakuliah' => $mataKuliah['TI101']->id_matakuliah,
                    'nilai' => 'A',
                    'nilai_angka' => 3.90,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            if ($mataKuliah->has('TI102')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs3->id_user,
                    'id_matakuliah' => $mataKuliah['TI102']->id_matakuliah,
                    'nilai' => 'A',
                    'nilai_angka' => 3.85,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }

            if ($mataKuliah->has('TI103')) {
                NilaiMahasiswa::create([
                    'id_user' => $mhs3->id_user,
                    'id_matakuliah' => $mataKuliah['TI103']->id_matakuliah,
                    'nilai' => 'B',
                    'nilai_angka' => 3.40,
                    'status' => 'lulus',
                    'semester_ambil' => '2024/2025 Ganjil',
                ]);
            }
        }

        // Mahasiswa 4 - Belum ada nilai (mahasiswa baru)
        // Tidak perlu menambahkan nilai

        $this->command->info('Nilai mahasiswa berhasil ditambahkan!');
    }
}
