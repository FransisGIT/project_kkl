<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispensasi;
use App\Models\Role;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        // Keuangan (4) validates pending submissions
        if ($user->id_role == 4) {
            $data = Dispensasi::where('status', 'menunggu')->orderBy('created_at', 'desc')->get();
            return view('dispensasi.index', compact('data', 'roles'));
        }

        // Wakil Rektor 2 (5) sees submissions that require warek approval
        if ($user->id_role == 5) {
            $data = Dispensasi::where('status', 'menunggu_warek')->orderBy('created_at', 'desc')->get();
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
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $file = null;
        $paymentProof = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat')->store('surat_dispensasi', 'public');
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
            'file_surat' => $file,
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
        // handle payment proof upload (keuangan)
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

        // Keuangan (role 4) approves initial request
        if ($user->id_role == 4 && $disp->status == 'menunggu') {
            $amount = intval($disp->jumlah_pengajuan ?? 0);
            // if amount >= 5,000,000 -> escalate to Wakil Rektor 2
            if ($amount >= 5000000) {
                $disp->status = 'menunggu_warek';
            } else {
                $disp->status = 'diterima_keuangan';
            }
        }

        // Wakil Rektor 2 (role 5) final approval for escalated requests
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

    /**
     * Return the PDF file inline for preview.
     */
    public function preview($id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);

        // authorization: students can only preview their own file; approvers/admin can preview all
        if ($user->id_role == 3 && $disp->id_user != $user->id_user) {
            abort(403);
        }

        $file = $disp->file_surat ?? $disp->file_pdf ?? null;
        if (!$file) abort(404);

        // try storage/app/public
        $storagePath = storage_path('app/public/' . $file);
        if (file_exists($storagePath)) {
            return response()->file($storagePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
            ]);
        }

        // try public path
        $publicPath = public_path($file);
        if (file_exists($publicPath)) {
            return response()->file($publicPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
            ]);
        }

        abort(404);
    }

    /**
     * Full-page viewer that embeds the preview route.
     */
    public function view($id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);

        // students only view their own
        if ($user->id_role == 3 && $disp->id_user != $user->id_user) {
            abort(403);
        }

        $src = route('dispensasi.preview', $disp->id);
        return view('dispensasi.view', compact('disp', 'src'));
    }

    /**
     * Serve payment proof image inline from storage without requiring storage:link.
     */
    public function paymentProof($id)
    {
        $user = Auth::user();
        $disp = Dispensasi::findOrFail($id);

        // students only view their own
        if ($user->id_role == 3 && $disp->id_user != $user->id_user) {
            abort(403);
        }

        $file = $disp->payment_proof ?? null;
        if (!$file) abort(404);

        $storagePath = storage_path('app/public/' . $file);
        if (file_exists($storagePath)) {
            $mimetype = @mime_content_type($storagePath) ?: 'image/jpeg';
            return response()->file($storagePath, [
                'Content-Type' => $mimetype,
                'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
            ]);
        }

        $publicPath = public_path($file);
        if (file_exists($publicPath)) {
            $mimetype = @mime_content_type($publicPath) ?: 'image/jpeg';
            return response()->file($publicPath, [
                'Content-Type' => $mimetype,
                'Content-Disposition' => 'inline; filename="' . basename($file) . '"'
            ]);
        }

        abort(404);
    }
}
