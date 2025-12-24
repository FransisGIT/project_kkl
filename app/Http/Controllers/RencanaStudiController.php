<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MataKuliah;
use App\Models\NilaiMahasiswa;
use App\Models\RencanaStudi;

class RencanaStudiController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $roles = \App\Models\Role::all();


        if ($user->id_role !== 3) {
            abort(403, 'Hanya mahasiswa yang dapat mengakses halaman ini.');
        }


        $semester = $request->get('semester');
        $search = $request->get('search');

        $query = MataKuliah::query();
        if ($semester) $query->where('semester', $semester);
        if ($search) $query->where('nama_matakuliah', 'like', "%$search%");
        $mataKuliah = $query->get();
        $rencanaAktif = $user->rencanaStudiAktif;
        $mkDiambil = $rencanaAktif && $rencanaAktif->id_mata_kuliah ? $rencanaAktif->id_mata_kuliah : [];

        $jumlahSKS = MataKuliah::whereIn('id_matakuliah', $mkDiambil)->sum('sks');
        $jumlahSKSTempuh = MataKuliah::whereIn('id_matakuliah', $mkDiambil)
            ->where('semester', '<', date('n'))->sum('sks');

        // Prepare per-mata-kuliah metadata to keep Blade clean
        $nilaiLulus = \App\Models\NilaiMahasiswa::where('id_user', $user->id_user)
            ->where('status', 'lulus')
            ->pluck('id_matakuliah')
            ->toArray();

        $pendingStatuses = ['menunggu', 'menunggu_keuangan', 'menunggu_warek'];
        $pendingRencana = RencanaStudi::where('id_user', $user->id_user)
            ->whereIn('status', $pendingStatuses)
            ->get()
            ->pluck('id_mata_kuliah')
            ->toArray();

        // flatten pending mata kuliah ids
        $pendingMkIds = [];
        foreach ($pendingRencana as $arr) {
            if (is_array($arr)) {
                $pendingMkIds = array_merge($pendingMkIds, $arr);
            }
        }

        foreach ($mataKuliah as $mk) {
            $isAmbil = in_array($mk->id_matakuliah, $mkDiambil);

            $sudahDitempuh = in_array($mk->id_matakuliah, $nilaiLulus);
            $sudahDiajukan = in_array($mk->id_matakuliah, $pendingMkIds);

            $prasyaratTerpenuhi = true;
            $prasyaratNamaArr = [];
            if (!empty($mk->prasyarat_ids)) {
                $praList = MataKuliah::whereIn('id_matakuliah', $mk->prasyarat_ids)->get();
                foreach ($praList as $pra) {
                    $terpenuhi = in_array($pra->id_matakuliah, $nilaiLulus);
                    if (!$terpenuhi) $prasyaratTerpenuhi = false;
                    $icon = $terpenuhi ? '✓ ' : '';
                    $prasyaratNamaArr[] = $icon . $pra->nama_matakuliah;
                }
            }

            $prasyaratInfo = !empty($prasyaratNamaArr) ? implode(', ', $prasyaratNamaArr) : '-';

            if ($isAmbil || $sudahDitempuh) {
                $bgClass = 'bg-success-subtle';
            } elseif ($sudahDiajukan) {
                $bgClass = 'bg-warning-subtle';
            } elseif ($prasyaratTerpenuhi) {
                $bgClass = 'bg-info-subtle';
            } else {
                $bgClass = 'bg-danger-subtle';
            }

            $disabledAttr = '';
            if ($sudahDitempuh) {
                $disabledAttr = 'disabled title="Sudah ditempuh"';
            } elseif ($sudahDiajukan) {
                $disabledAttr = 'disabled title="Sudah diajukan (menunggu)"';
            } elseif (!$prasyaratTerpenuhi) {
                $disabledAttr = 'disabled title="Prasyarat belum terpenuhi"';
            }

            $mk->setAttribute('meta', [
                'isAmbil' => $isAmbil,
                'prasyaratTerpenuhi' => $prasyaratTerpenuhi,
                'prasyaratInfo' => $prasyaratInfo,
                'sudahDitempuh' => $sudahDitempuh,
                'sudahDiajukan' => $sudahDiajukan,
                'bgClass' => $bgClass,
                'disabledAttr' => $disabledAttr,
            ]);
        }

        return view('krs.index', [
            'mataKuliah' => $mataKuliah,
            'mkDiambil' => $mkDiambil,
            'jumlahSKS' => $jumlahSKS,
            'jumlahSKSTempuh' => $jumlahSKSTempuh,
            'rencanaAktif' => $rencanaAktif,
            'roles' => $roles,
        ]);
    }


    public function store(Request $request)
    {
        $user = Auth::user();


        if ($user->id_role !== 3) {
            abort(403, 'Hanya mahasiswa yang dapat mengajukan KRS.');
        }

        $mkDipilih = $request->input('matakuliah', []);


        if (empty($mkDipilih)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 mata kuliah!');
        }


        $mataKuliahDipilih = MataKuliah::whereIn('id_matakuliah', $mkDipilih)->get();


        $totalSks = $mataKuliahDipilih->sum('sks');
        if ($totalSks > 24) {
            return redirect()->back()->with('error', "Total SKS yang Anda pilih ($totalSks SKS) melebihi batas maksimal 24 SKS");
        }


        $nilaiMahasiswa = NilaiMahasiswa::where('id_user', $user->id_user)
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

        // Backend: prevent selecting mata kuliah yang sudah ditempuh atau sudah diajukan
        $alreadyLulus = $nilaiMahasiswa;
        $rencanaAktif = $user->rencanaStudiAktif;
        $mkDiambilSaatIni = $rencanaAktif && $rencanaAktif->id_mata_kuliah ? $rencanaAktif->id_mata_kuliah : [];
        $pendingStatuses = ['menunggu', 'menunggu_keuangan', 'menunggu_warek'];

        $conflicts = [];
        foreach ($mkDipilih as $mkId) {
            if (in_array($mkId, $alreadyLulus) || in_array($mkId, $mkDiambilSaatIni)) {
                $conflicts[] = $mkId;
                continue;
            }

            $existsPending = RencanaStudi::where('id_user', $user->id_user)
                ->whereIn('status', $pendingStatuses)
                ->whereJsonContains('id_mata_kuliah', $mkId)
                ->exists();

            if ($existsPending) {
                $conflicts[] = $mkId;
            }
        }

        if (!empty($conflicts)) {
            $mkNames = MataKuliah::whereIn('id_matakuliah', $conflicts)->pluck('nama_matakuliah')->toArray();
            return redirect()->back()->with('error', 'Beberapa mata kuliah tidak dapat diajukan: ' . implode(', ', $mkNames));
        }


        $tunggakan = intval($user->tunggakan ?? 0);
        if ($tunggakan > 0) {

            if ($tunggakan > 5000000) {
                RencanaStudi::create([
                    'id_user' => $user->id_user,
                    'id_mata_kuliah' => $mkDipilih,
                    'status' => 'menunggu_warek',
                ]);

                return redirect()->route('krs.index')->with('warning', "Pengajuan KRS disimpan tetapi menunggu persetujuan Warek2 karena tunggakan Anda sebesar Rp " . number_format($tunggakan, 0, ',', '.'));
            }


            RencanaStudi::create([
                'id_user' => $user->id_user,
                'id_mata_kuliah' => $mkDipilih,
                'status' => 'menunggu_keuangan',
            ]);

            return redirect()->route('krs.index')->with('warning', "Pengajuan KRS disimpan dan menunggu persetujuan Keuangan karena tunggakan Anda sebesar Rp " . number_format($tunggakan, 0, ',', '.'));
        }


        RencanaStudi::create([
            'id_user' => $user->id_user,
            'id_mata_kuliah' => $mkDipilih,
            'status' => 'menunggu',
        ]);

        return redirect()->route('krs.index')->with('success', "Pengajuan KRS berhasil ($totalSks SKS)! Status: Menunggu Persetujuan.");
    }
}
