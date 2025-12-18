@extends('app', ['title' => 'Riwayat Dispensasi'])

@section('content')
    <div class="card p-4 shadow-sm">
        <h4>Riwayat Pengajuan Dispensasi</h4>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Tahun Akademik</th>
                    <th>Jumlah</th>
                    <th>No HP</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>File</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $d)
                    <tr>
                        <td>{{ $d->tahun_akademik }}</td>
                        <td>{{ $d->jumlah_pengajuan }}</td>
                        <td>{{ $d->no_hp }}</td>
                        <td>{{ $d->tanggal_deadline }}</td>
                        <td>
                            @if ($d->status == 'menunggu')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif ($d->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->file_surat)
                                <a href="{{ asset('storage/' . $d->file_surat) }}" target="_blank">Download</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pengajuan dispensasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection

<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Dispensasi Mahasiswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
        }
        button {
            background-color: #27ae60;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #219150;
        }
        footer {
            text-align: center;
            padding: 15px;
            color: #777;
            font-size: 13px;
        }
    </style>
</head>
<body>

<header>
    <h1>SIAKAD - Pengajuan Dispensasi Mahasiswa</h1>
</header>

<div class="container">
    <h2>Form Pengajuan Dispensasi</h2>

    <!-- FORM DISPENSASI -->
    <form action="proses_dispensasi.php" method="post">
        <label>NIM</label>
        <input type="text" name="nim" placeholder="Masukkan NIM" required>

        <label>Nama Mahasiswa</label>
        <input type="text" name="nama" placeholder="Masukkan Nama" required>

        <label>Program Studi</label>
        <input type="text" name="prodi" placeholder="Masukkan Program Studi" required>

        <label>Mata Kuliah</label>
        <input type="text" name="matkul" placeholder="Masukkan Mata Kuliah" required>

        <label>Jenis Dispensasi</label>
        <select name="jenis" required>
            <option value="">-- Pilih --</option>
            <option value="Sakit">Sakit</option>
            <option value="Izin Kegiatan">Izin Kegiatan</option>
            <option value="Keperluan Keluarga">Keperluan Keluarga</option>
        </select>

        <label>Alasan Dispensasi</label>
        <textarea name="alasan" rows="4" placeholder="Tuliskan alasan dispensasi" required></textarea>

        <label>Tanggal</label>
        <input type="date" name="tanggal" required>

        <button type="submit">Kirim Pengajuan</button>
    </form>
</div>

<footer>
    &copy; 2025 Sistem Informasi Akademik
</footer>

</body>
</html>
=======
@push('styles')
    <link href="{{ url('assets/css/dashboard.css') }}" rel="stylesheet" />
@endpush
>>>>>>> c1b1388ccc0efa547c9a4b88a81aa64400aeb5dc
