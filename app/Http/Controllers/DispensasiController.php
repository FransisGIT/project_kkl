<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispensasi;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Log;

class DispensasiController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        $user = auth()->user();

        if ($user->id_role == 3) {
            $data = Dispensasi::where('id_user', $user->id_user)->orderBy('created_at', 'desc')->get();
            return view('dispensasi.index', compact('data', 'roles'));
        }

        if ($user->id_role == 4) {
            $data = Dispensasi::where('status', 'menunggu')->orderBy('created_at', 'desc')->get();
            return view('dispensasi.index', compact('data', 'roles'));
        }


        if ($user->id_role == 5) {
            $data = Dispensasi::where('status', 'menunggu_warek')->orderBy('created_at', 'desc')->get();
            return view('dispensasi.index', compact('data', 'roles'));
        }


        $data = Dispensasi::orderBy('created_at', 'desc')->get();
        return view('dispensasi.index', compact('data', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'nullable|string',
            'jumlah_pengajuan' => 'nullable|integer',
            'no_hp' => 'nullable|string',
            'tanggal_deadline' => 'nullable|date',
            'surat_dispensasi' => 'nullable|mimes:pdf|max:4096',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $suratDispensasi = null;
        $paymentProof = null;
        if ($request->hasFile('surat_dispensasi')) {
            $suratDispensasi = $request->file('surat_dispensasi')->store('surat_dispensasi', 'public');
        }
        if ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('bukti_pembayaran', 'public');
        }

        Dispensasi::create([
            'id' => (string) Str::uuid(),
            'id_user' => Auth::user()->id_user,
            'tahun_akademik' => $request->input('tahun_akademik'),
            'jumlah_pengajuan' => $request->input('jumlah_pengajuan'),
            'no_hp' => $request->input('no_hp'),
            'tanggal_deadline' => $request->input('tanggal_deadline'),
            'surat_dispensasi' => $suratDispensasi,
            'payment_proof' => $paymentProof,
            'status' => 'menunggu',
        ]);

        return redirect()->route('dispensasi.index')->with('success_message', 'Pengajuan dispensasi berhasil dikirim.');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);
        $request->validate([
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $note = $request->input('note');

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('bukti_pembayaran', 'public');
            $disp->payment_proof = $path;
        }
        $notes = $disp->approver_notes ?? [];
        $notes[] = [
            'by' => $user->name,
            'role_id' => $user->id_role,
            'note' => $note,
            'action' => 'approve',
            'at' => now()->toDateTimeString(),
        ];


        if ($user->id_role == 4 && $disp->status == 'menunggu') {
            $amount = intval($disp->jumlah_pengajuan ?? 0);

            if ($amount >= 5000000) {
                $disp->status = 'menunggu_warek';
            } else {
                $disp->status = 'diterima_keuangan';
            }
        }


        if ($user->id_role == 5 && $disp->status == 'menunggu_warek') {
            $disp->status = 'diterima_warek';
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


    public function preview($id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);


        if ($user->id_role == 3 && $disp->id_user != $user->id_user) {
            abort(403);
        }

        $file = $disp->surat_dispensasi ?? $disp->file_surat ?? null;
        if (!$file) {
            Log::error('File not found for preview', ['id' => $id]);
            abort(404);
        }

        $storagePath = storage_path('app/public/' . $file);
        Log::info('Serving file for preview', ['file' => $storagePath]);

        if (file_exists($storagePath)) {
            return response()->make(file_get_contents($storagePath), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
            ]);
        }

        Log::error('File does not exist on disk', ['file' => $storagePath]);
        abort(404);
    }


    public function view($id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);


        if ($user->id_role == 3 && $disp->id_user != $user->id_user) {
            abort(403);
        }

        $src = route('dispensasi.preview', $disp->id);
        return view('dispensasi.view', compact('disp', 'src'));
    }


    public function sample()
    {
        return view('dispensasi.sample');
    }


    public function paymentProof($id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);

        if ($user->id_role == 3 && $disp->id_user != $user->id_user) {
            abort(403);
        }

        $file = $disp->payment_proof ?? null;
        if (!$file) {
            Log::error('Payment proof file not found', ['id' => $id]);
            abort(404);
        }

        $storagePath = storage_path('app/public/' . $file);
        Log::info('Serving payment proof', ['file' => $storagePath]);

        if (file_exists($storagePath)) {
            $mimetype = @mime_content_type($storagePath) ?: 'image/jpeg';
            return response()->make(file_get_contents($storagePath), 200, [
                'Content-Type' => $mimetype,
                'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
            ]);
        }

        Log::error('Payment proof file does not exist on disk', ['file' => $storagePath]);
        abort(404);
    }
}
