<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dispensasi;
use App\Models\User;
use Illuminate\Support\Str;

class DispensasiSeeder extends Seeder
{
    public function run(): void
    {
        $m1 = User::where('name', 'mahasiswa1')->first();
        $m2 = User::where('name', 'mahasiswa2')->first();
        $m3 = User::where('name', 'mahasiswa3')->first();

        if ($m1) {
            Dispensasi::create([
                'id' => (string) Str::uuid(),
                'id_user' => $m1->id_user,
                'tahun_akademik' => '2025/2026',
                'jumlah_pengajuan' => 1,
                'no_hp' => '081234000111',
                'tanggal_deadline' => now()->addWeeks(2)->toDateString(),
                'status' => 'menunggu',
            ]);
        }

        if ($m2) {
            Dispensasi::create([
                'id' => (string) Str::uuid(),
                'id_user' => $m2->id_user,
                'tahun_akademik' => '2025/2026',
                'jumlah_pengajuan' => 1,
                'no_hp' => '081234000222',
                'tanggal_deadline' => now()->addWeeks(3)->toDateString(),
                'status' => 'diterima_dosen',
            ]);
        }

        if ($m3) {
            Dispensasi::create([
                'id' => (string) Str::uuid(),
                'id_user' => $m3->id_user,
                'tahun_akademik' => '2025/2026',
                'jumlah_pengajuan' => 2,
                'no_hp' => '081234000333',
                'tanggal_deadline' => now()->addWeeks(4)->toDateString(),
                'status' => 'diterima_warek',
            ]);
        }
    }
}
