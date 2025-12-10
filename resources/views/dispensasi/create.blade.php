@extends('app', ['title' => 'Dispensasi'])

@section('content')
    <div class="card p-4 shadow-sm">

        <h4 class="fw-bold mb-2">Dispensasi</h4>

        <p class="text-muted">
            Syarat Pengajuan Dispensasi harus bermaterai.
            Diinputkan ke bagian keuangan dan dikumpulkan melalui offline.
            Harap isi dengan lengkap syarat yang dibutuhkan untuk mengajukan dispensasi.
        </p>

        <hr>

        <h5 class="mb-3">Form Pengajuan Dispensasi</h5>

        <form action="{{ route('dispensasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" class="form-control" placeholder="Masukkan tahun akademik">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jumlah Pengajuan Dispensasi</label>
                    <input type="number" name="jumlah_pengajuan" class="form-control" placeholder="Masukkan jumlah">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Masukkan nomor hp">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal paling lambat pembayaran</label>
                    <input type="date" name="tanggal_deadline" class="form-control">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <button type="button" class="btn btn-outline-primary">Download PDF</button>
                <button type="button" class="btn btn-primary">Cetak Surat</button>
            </div>

            <h5 class="mb-2">Upload Surat Dispensasi</h5>

            <div class="border rounded-3 p-4 text-center bg-light">
                <input type="file" name="file_surat" accept="application/pdf" class="form-control mb-3">
                <small class="text-muted">Upload file PDF</small>
            </div>

            <button class="btn btn-success mt-4">Kirim Pengajuan</button>

        </form>

    </div>
@endsection

@push('styles')
    <link href="{{ url('css/dashboard.css') }}" rel="stylesheet" />
@endpush
