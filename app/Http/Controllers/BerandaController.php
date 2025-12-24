<?php

namespace App\Http\Controllers;

use App\Models\absensi_mahasiswa;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = Role::all();

        $mataKuliah = MataKuliah::all();


        $rencanaAktif = $user->rencanaStudiAktif;
        $mkDiambil = [];

        if ($rencanaAktif && $rencanaAktif->status === 'disetujui' && $rencanaAktif->id_mata_kuliah) {
            $mkDiambil = $rencanaAktif->id_mata_kuliah;
        }

        $jumlahSKS = MataKuliah::whereIn('id_matakuliah', $mkDiambil)->sum('sks');
        $jumlahSKSTempuh = MataKuliah::whereIn('id_matakuliah', $mkDiambil)->where('semester', '<', date('n'))->sum('sks');

        return view('beranda.index', [
            'mataKuliah' => $mataKuliah,
            'mkDiambil' => $mkDiambil,
            'jumlahSKS' => $jumlahSKS,
            'jumlahSKSTempuh' => $jumlahSKSTempuh,
            'rencanaAktif' => $rencanaAktif,
            'roles' => $roles,
        ]);
    }
}
