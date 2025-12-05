<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaStudi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PersetujuanKrsController extends Controller
{
    /**
     * Tampilkan daftar KRS yang perlu disetujui (untuk admin/dosen)
     */
    public function index()
    {
        $user = Auth::user();
        $roles = Role::all();

        // Hanya admin (id_role = 1) atau dosen (id_role = 2) yang bisa akses
        if (!in_array($user->id_role, [1, 2])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $rencanaStudi = RencanaStudi::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Process data untuk setiap rencana studi
        $rencanaStudi->getCollection()->transform(function ($rs) {
            $mataKuliah = \App\Models\MataKuliah::whereIn('id_matakuliah', $rs->id_mata_kuliah ?? [])->get();
            $rs->mataKuliahList = $mataKuliah;
            $rs->totalSks = $mataKuliah->sum('sks');
            $rs->jumlahMk = count($rs->id_mata_kuliah ?? []);
            return $rs;
        });

        return view('persetujuan-krs.index', compact('rencanaStudi', 'roles'));
    }

    /**
     * Setujui KRS
     */
    public function approve($id)
    {
        $user = Auth::user();

        if (!in_array($user->id_role, [1, 2])) {
            abort(403);
        }

        $rencana = RencanaStudi::findOrFail($id);
        $rencana->update([
            'status' => 'disetujui',
            'catatan' => 'KRS telah disetujui',
        ]);

        return redirect()->back()->with('success', 'KRS berhasil disetujui!');
    }

    /**
     * Tolak KRS
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        if (!in_array($user->id_role, [1, 2])) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        $rencana = RencanaStudi::findOrFail($id);
        $rencana->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'KRS berhasil ditolak!');
    }
}
