<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\MataKuliahSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MataKuliahSeeder::class,
            NilaiMahasiswaSeeder::class,
            DispensasiSeeder::class,
            SetTunggakanMahasiswaSeeder::class,
            DemoKrsSeeder::class,
        ]);
    }
}
