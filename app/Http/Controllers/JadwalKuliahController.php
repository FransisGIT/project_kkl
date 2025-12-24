<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MataKuliah;
use App\Models\RencanaStudi;
use App\Models\Role;

class JadwalKuliahController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $roles = Role::all();


        if ($user->id_role !== 3) {
            abort(403, 'Hanya mahasiswa yang dapat mengakses halaman ini.');
        }


        $rencanaAktif = RencanaStudi::where('id_user', $user->id_user)
            ->where('status', 'disetujui')
            ->latest()
            ->first();

        $mataKuliahList = collect([]);
        $statusKrs = null;

        if ($rencanaAktif) {
            $statusKrs = 'disetujui';
            $mataKuliahIds = $rencanaAktif->id_mata_kuliah ?? [];

            if (!empty($mataKuliahIds)) {
                $mataKuliahList = MataKuliah::whereIn('id_matakuliah', $mataKuliahIds)
                    ->orderBy('hari')
                    ->orderBy('jam')
                    ->get();
            }
        } else {

            $rencanaLain = RencanaStudi::where('id_user', $user->id_user)
                ->latest()
                ->first();
            if ($rencanaLain) {
                $statusKrs = $rencanaLain->status;
            }
        }

        $totalSks = $mataKuliahList->sum('sks');

        return view('jadwal-kuliah.index', [
            'mataKuliahList' => $mataKuliahList,
            'totalSks' => $totalSks,
            'statusKrs' => $statusKrs,
            'rencanaAktif' => $rencanaAktif,
            'roles' => $roles,
        ]);
    }
}
