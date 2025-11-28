<?php

namespace App\Http\Controllers;

use App\Models\absensi_mahasiswa;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Auth;

class absensiMahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $mataKuliah = MataKuliah::all();
        $mkDiambil = $user->mataKuliah()->pluck('mata_kuliah.id_matakuliah')->toArray();
        $jumlahSKS = MataKuliah::whereIn('id_matakuliah', $mkDiambil)->sum('sks');
        $jumlahSKSTempuh = MataKuliah::whereIn('id_matakuliah', $mkDiambil)->where('semester', '<', date('n'))->sum('sks');

        return view('dashboard.index', [
            'mataKuliah' => $mataKuliah,
            'mkDiambil' => $mkDiambil,
            'jumlahSKS' => $jumlahSKS,
            'jumlahSKSTempuh' => $jumlahSKSTempuh
        ]);
    }
}
