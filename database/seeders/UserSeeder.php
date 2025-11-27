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

        User::create([
            'id_user' => Str::uuid(),
            'name' => 'mahasiswa',
            'password' => Hash::make('mahasiswa'),
            'id_role' => 3,
            'remember_token' => Str::random(10)
        ]);
    }
}
