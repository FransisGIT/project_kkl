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

        // // Optional: pastikan hanya mahasiswa yang bisa akses
        // if ($user->role->name !== 'Mahasiswa') {
        //     abort(403, 'Hanya mahasiswa yang dapat mengakses halaman ini.');
        // }

        // Filter/sort opsi
        $semester = $request->get('semester');
        $search = $request->get('search');

        $query = MataKuliah::query();
        if ($semester) $query->where('semester', $semester);
        if ($search) $query->where('nama_matakuliah', 'like', "%$search%");
        $mataKuliah = $query->get();

        // Ambil id MK yg sudah diambil
        $mkDiambil = $user->mataKuliah()->pluck('mata_kuliah.id_matakuliah')->toArray();

        $jumlahSKS = MataKuliah::whereIn('id_matakuliah', $mkDiambil)->sum('sks');
        // Untuk contoh, SKS sudah ditempuh = SMTR < semester skrg (bisa custom)
        $jumlahSKSTempuh = MataKuliah::whereIn('id_matakuliah', $mkDiambil)
            ->where('semester', '<', date('n'))->sum('sks');


        return view('krs.index', [
            'mataKuliah' => $mataKuliah,
            'mkDiambil' => $mkDiambil,
            'jumlahSKS' => $jumlahSKS,
            'jumlahSKSTempuh' => $jumlahSKSTempuh,
        ]);
    }

    /**
     * Simpan pengajuan KRS
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Optional: hanya mahasiswa
        // if ($user->role->name !== 'Mahasiswa') {
        //     abort(403, 'Hanya mahasiswa yang dapat akses.');
        // }

        $mkDipilih = $request->input('matakuliah', []); // array of id_matakuliah

        // Hapus KRS lama & tambahkan baru (logika reset)
        $user->rencanaStudi()->delete();

        foreach ($mkDipilih as $id_mk) {
            RencanaStudi::create([
                'id_user' => $user->id_user,
                'id_matakuliah' => $id_mk,
            ]);
        }

        return redirect()->route('dashboard.index')->with('success', 'Rencana Studi berhasil diperbarui!');
    }
}
