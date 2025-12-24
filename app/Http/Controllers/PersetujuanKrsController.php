<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaStudi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PersetujuanKrsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = Role::all();

        if (in_array($user->id_role, [1, 2])) {
            $rencanaStudi = RencanaStudi::with('user')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($user->id_role == 5) {
            $rencanaStudi = RencanaStudi::with('user')->where('status', 'menunggu_warek')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($user->id_role == 4) {
            $rencanaStudi = RencanaStudi::with('user')->where('status', 'menunggu_keuangan')->orderBy('created_at', 'desc')->paginate(20);
        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $rencanaStudi->getCollection()->transform(function ($rs) {
            $mataKuliah = \App\Models\MataKuliah::whereIn('id_matakuliah', $rs->id_mata_kuliah ?? [])->get();
            $rs->mataKuliahList = $mataKuliah;
            $rs->totalSks = $mataKuliah->sum('sks');
            $rs->jumlahMk = count($rs->id_mata_kuliah ?? []);
            return $rs;
        });

        return view('persetujuan-krs.index', compact('rencanaStudi', 'roles'));
    }

    public function approve($id)
    {
        $user = Auth::user();

        $rencana = RencanaStudi::findOrFail($id);

        if (in_array($user->id_role, [1, 2])) {
            $rencana->update([
                'status' => 'disetujui',
                'catatan' => 'KRS telah disetujui',
            ]);
            return redirect()->back()->with('success', 'KRS berhasil disetujui!');
        }

        if ($user->id_role == 5) {
            if ($rencana->status !== 'menunggu_warek') {
                return redirect()->back()->with('error', 'Pengajuan tidak memerlukan persetujuan Warek2.');
            }

            $rencana->update([
                'status' => 'menunggu',
                'catatan' => 'Disetujui oleh Warek2',
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Warek2.');
        }

        if ($user->id_role == 4) {
            if ($rencana->status !== 'menunggu_keuangan') {
                return redirect()->back()->with('error', 'Pengajuan tidak memerlukan persetujuan Keuangan.');
            }

            $rencana->update([
                'status' => 'menunggu',
                'catatan' => 'Disetujui oleh Keuangan',
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Keuangan.');
        }

        abort(403);
    }

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
