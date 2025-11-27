<?php

namespace App\Http\Controllers;

use App\Models\absensi_mahasiswa;
use Illuminate\Http\Request;

class absensiMahasiswaController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
