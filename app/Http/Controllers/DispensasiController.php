<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispensasi;
use App\Models\Role;

class DispensasiController extends Controller
{
    public function index()
    {
        $data = Dispensasi::where('id', auth()->id())->get();
        $roles = Role::all();

        return view('dispensasi.index', compact('data', 'roles'));
    }

    public function create()
    {
        return view('dispensasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string',
            'jumlah_pengajuan' => 'required|integer',
            'no_hp' => 'required|string',
            'tanggal_deadline' => 'required|date',
            'file_surat' => 'nullable|mimes:pdf|max:2048',
        ]);

        $file = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat')->store('surat_dispensasi', 'public');
        }

        Dispensasi::create([
            'user_id' => auth()->id(),
            'tahun_akademik' => $request->tahun_akademik,
            'jumlah_pengajuan' => $request->jumlah_pengajuan,
            'no_hp' => $request->no_hp,
            'tanggal_deadline' => $request->tanggal_deadline,
            'file_surat' => $file,
            'status' => 'menunggu',
        ]);

        return redirect()->route('dispensasi.index')
            ->with('success_message', 'Pengajuan dispensasi berhasil dikirim.');
    }
}
