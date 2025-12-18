<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispensasi;
use App\Models\Role;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

class DispensasiController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        $user = auth()->user();

        // Dosen tidak melihat/terlibat dalam alur dispensasi
        if ($user->id_role == 2) {
            $data = collect([]); // kosong
            return view('dispensasi.index', compact('data', 'roles'));
        }

        // Mahasiswa lihat miliknya
        if ($user->id_role == 3) {
            $data = Dispensasi::where('id_user', $user->id_user)->orderBy('created_at', 'desc')->get();
            return view('dispensasi.index', compact('data', 'roles'));
        }

        // Approver: Wakil Rektor 2 (5) sees pending; Keuangan (4) sees warek-approved
        if ($user->id_role == 5) {
            $data = Dispensasi::where('status', 'menunggu')->orderBy('created_at', 'desc')->get();
            return view('dispensasi.index', compact('data', 'roles'));
        }

        if ($user->id_role == 4) {
            $data = Dispensasi::where('status', 'diterima_warek')->orderBy('created_at', 'desc')->get();
            return view('dispensasi.index', compact('data', 'roles'));
        }

        // Default (admin/others): show all
        $data = Dispensasi::orderBy('created_at', 'desc')->get();
        return view('dispensasi.index', compact('data', 'roles'));
    }

    public function create()
    {
        return view('dispensasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'nullable|string',
            'jumlah_pengajuan' => 'nullable|integer',
            'no_hp' => 'nullable|string',
            'tanggal_deadline' => 'nullable|date',
            'file_surat' => 'nullable|mimes:pdf|max:4096',
        ]);

        $file = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat')->store('surat_dispensasi', 'public');
        }

        Dispensasi::create([
            'id' => (string) Str::uuid(),
            'id_user' => Auth::user()->id_user,
            'tahun_akademik' => $request->input('tahun_akademik'),
            'jumlah_pengajuan' => $request->input('jumlah_pengajuan'),
            'no_hp' => $request->input('no_hp'),
            'tanggal_deadline' => $request->input('tanggal_deadline'),
            'file_surat' => $file,
            'status' => 'menunggu',
        ]);

        return redirect()->route('dispensasi.index')->with('success_message', 'Pengajuan dispensasi berhasil dikirim.');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);

        $note = $request->input('note');
        $notes = $disp->approver_notes ?? [];
        $notes[] = [
            'by' => $user->name,
            'role_id' => $user->id_role,
            'note' => $note,
            'action' => 'approve',
            'at' => now()->toDateTimeString(),
        ];

        // Workflow without dosen: menunggu -> diterima_warek -> disetujui
        if ($user->id_role == 5 && $disp->status == 'menunggu') {
            $disp->status = 'diterima_warek';
        } elseif ($user->id_role == 4 && $disp->status == 'diterima_warek') {
            $disp->status = 'disetujui';
        }

        $disp->approver_notes = $notes;
        $disp->save();

        return redirect()->back()->with('success_message', 'Pengajuan telah disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);

        $note = $request->input('note');
        $notes = $disp->approver_notes ?? [];
        $notes[] = [
            'by' => $user->name,
            'role_id' => $user->id_role,
            'note' => $note,
            'action' => 'reject',
            'at' => now()->toDateTimeString(),
        ];

        $disp->status = 'ditolak';
        $disp->approver_notes = $notes;
        $disp->save();

        return redirect()->back()->with('success_message', 'Pengajuan telah ditolak.');
    }
}
