<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SetTunggakanMahasiswaSeeder extends Seeder
{
    public function run()
    {
        $mahasiswas = User::where('id_role', 3)->orderBy('created_at')->take(3)->get();

        $amounts = [2500000, 1000000, 6000000];

        foreach ($mahasiswas as $i => $m) {
            if (!isset($amounts[$i])) break;
            $m->tunggakan = $amounts[$i];
            $m->save();
        }
    }
}
