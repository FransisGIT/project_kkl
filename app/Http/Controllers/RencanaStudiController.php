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

        // Ambil data mata kuliah yang dipilih
        $mataKuliahDipilih = MataKuliah::whereIn('id_matakuliah', $mkDipilih)->get();

        // Validasi maksimal 24 SKS
        $totalSks = $mataKuliahDipilih->sum('sks');
        if ($totalSks > 24) {
            return redirect()->back()->with('error', "Total SKS yang Anda pilih ($totalSks SKS) melebihi batas maksimal 24 SKS");
        }

        // Validasi prasyarat - ambil mata kuliah yang sudah lulus
        $nilaiMahasiswa = \App\Models\NilaiMahasiswa::where('id_user', $user->id_user)
            ->where('status', 'lulus')
            ->pluck('id_matakuliah')
            ->toArray();

        foreach ($mataKuliahDipilih as $mataKuliah) {
            if (!empty($mataKuliah->prasyarat_ids)) {
                foreach ($mataKuliah->prasyarat_ids as $prasyaratId) {
                    if (!in_array($prasyaratId, $nilaiMahasiswa)) {
                        $prasyaratMk = MataKuliah::find($prasyaratId);
                        $namaPrasyarat = $prasyaratMk ? $prasyaratMk->nama_matakuliah : 'mata kuliah prasyarat';
                        return redirect()->back()->with('error', "Anda belum menyelesaikan mata kuliah prasyarat ($namaPrasyarat) untuk mengambil {$mataKuliah->nama_matakuliah}");
                    }
                }
            }
        }

        // Cek tunggakan mahasiswa
        $tunggakan = intval($user->tunggakan ?? 0);
        if ($tunggakan > 0) {
            // Jika tunggakan lebih dari 5 juta, simpan pengajuan dan arahkan ke proses persetujuan Warek2
            if ($tunggakan > 5000000) {
                RencanaStudi::create([
                    'id_user' => $user->id_user,
                    'id_mata_kuliah' => $mkDipilih,
                    'status' => 'menunggu_warek',
                ]);

                return redirect()->route('krs.index')->with('warning', "Pengajuan KRS disimpan tetapi menunggu persetujuan Warek2 karena tunggakan Anda sebesar Rp " . number_format($tunggakan,0,',','.'));
            }

            // Jika ada tunggakan (<= 5 juta), simpan pengajuan sebagai menunggu persetujuan Keuangan
            RencanaStudi::create([
                'id_user' => $user->id_user,
                'id_mata_kuliah' => $mkDipilih,
                'status' => 'menunggu_keuangan',
            ]);

            return redirect()->route('krs.index')->with('warning', "Pengajuan KRS disimpan dan menunggu persetujuan Keuangan karena tunggakan Anda sebesar Rp " . number_format($tunggakan,0,',','.'));
        }

        // Simpan sebagai JSON dengan status menunggu persetujuan
        RencanaStudi::create([
            'id_user' => $user->id_user,
            'id_mata_kuliah' => $mkDipilih,
            'status' => 'menunggu',
        ]);

        return redirect()->route('krs.index')->with('success', "Pengajuan KRS berhasil ($totalSks SKS)! Status: Menunggu Persetujuan.");
    }
}
