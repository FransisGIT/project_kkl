<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MataKuliah;
use App\Models\RencanaStudi;

class RencanaStudiController extends Controller
{
    /**
     * Tampilkan halaman KRS (Rencana Studi)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roles = \App\Models\Role::all();

        // Pastikan hanya mahasiswa yang bisa akses
        if ($user->id_role !== 3) {
            abort(403, 'Hanya mahasiswa yang dapat mengakses halaman ini.');
        }

        // Filter/sort opsi
        $semester = $request->get('semester');
        $search = $request->get('search');

        $query = MataKuliah::query();
        if ($semester) $query->where('semester', $semester);
        if ($search) $query->where('nama_matakuliah', 'like', "%$search%");
        $mataKuliah = $query->get();

        // Ambil id MK dari rencana studi aktif
        $rencanaAktif = $user->rencanaStudiAktif;
        $mkDiambil = $rencanaAktif && $rencanaAktif->id_mata_kuliah ? $rencanaAktif->id_mata_kuliah : [];

        $jumlahSKS = MataKuliah::whereIn('id_matakuliah', $mkDiambil)->sum('sks');
        $jumlahSKSTempuh = MataKuliah::whereIn('id_matakuliah', $mkDiambil)
            ->where('semester', '<', date('n'))->sum('sks');

        return view('krs.index', [
            'mataKuliah' => $mataKuliah,
            'mkDiambil' => $mkDiambil,
            'jumlahSKS' => $jumlahSKS,
            'jumlahSKSTempuh' => $jumlahSKSTempuh,
            'rencanaAktif' => $rencanaAktif,
            'roles' => $roles,
        ]);
    }

    /**
     * Simpan pengajuan KRS
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Hanya mahasiswa yang bisa mengajukan KRS
        if ($user->id_role !== 3) {
            abort(403, 'Hanya mahasiswa yang dapat mengajukan KRS.');
        }

        $mkDipilih = $request->input('matakuliah', []); // array of id_matakuliah

        // Validasi: minimal pilih 1 mata kuliah
        if (empty($mkDipilih)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 mata kuliah!');
        }

        // Simpan sebagai JSON dengan status menunggu persetujuan
        RencanaStudi::create([
            'id_user' => $user->id_user,
            'id_mata_kuliah' => $mkDipilih,
            'status' => 'menunggu',
        ]);

        return redirect()->route('krs.index')->with('success', 'Pengajuan KRS berhasil! Status: Menunggu Persetujuan.');
    }
}
