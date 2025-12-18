<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id_user' => Str::uuid(),
            'name' => 'admin',
            'password' => Hash::make('admin'),
            'id_role' => 1,
            'remember_token' => Str::random(10)
        ]);

        User::create([
            'id_user' => Str::uuid(),
            'name' => 'dosen',
            'password' => Hash::make('dosen'),
            'id_role' => 2,
            'remember_token' => Str::random(10)
        ]);

        // Mahasiswa 1
        User::create([
            'id_user' => Str::uuid(),
            'name' => 'mahasiswa1',
            'password' => Hash::make('mahasiswa1'),
            'id_role' => 3,
            'remember_token' => Str::random(10)
        ]);

        // Mahasiswa 2
        User::create([
            'id_user' => Str::uuid(),
            'name' => 'mahasiswa2',
            'password' => Hash::make('mahasiswa2'),
            'id_role' => 3,
            'remember_token' => Str::random(10)
        ]);

        // Mahasiswa 3
        User::create([
            'id_user' => Str::uuid(),
            'name' => 'mahasiswa3',
            'password' => Hash::make('mahasiswa3'),
            'id_role' => 3,
            'remember_token' => Str::random(10)
        ]);

        // Mahasiswa 4
        User::create([
            'id_user' => Str::uuid(),
            'name' => 'mahasiswa4',
            'password' => Hash::make('mahasiswa4'),
            'id_role' => 3,
            'remember_token' => Str::random(10)
        ]);

        User::create([
            'id_user' => Str::uuid(),
            'name' => 'keuangan',
            'password' => Hash::make('keuangan'),
            'id_role' => 4,
            'remember_token' => Str::random(10)
        ]);

        User::create([
            'id_user' => Str::uuid(),
            'name' => 'warek2',
            'password' => Hash::make('warek2'),
            'id_role' => 5,
            'remember_token' => Str::random(10)
        ]);
    }
}
