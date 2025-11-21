<?php

namespace App\Http\Controllers;

use App\Models\absensi_mahasiswa;
use Illuminate\Http\Request;

class absensiMahasiswaController extends Controller
{
    public function index()
    {
        $dataAbsensi = absensi_mahasiswa::all();
        return view('dashboard.index', compact('dataAbsensi'));
    }
}
