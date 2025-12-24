@extends('app', ['title' => 'Ajukan Dispensasi'])

@section('content')
    <div class="card p-4 mt-3 shadow-sm">
        <h4>Form Pengajuan Dispensasi</h4>
        <hr>

        <form action="{{ route('dispensasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tahun Akademik</label>
                <input type="text" name="tahun_akademik" class="form-control" value="{{ old('tahun_akademik') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Pengajuan (Rp)</label>
                <input type="number" name="jumlah_pengajuan" class="form-control" value="{{ old('jumlah_pengajuan') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Deadline</label>
                <input type="date" name="tanggal_deadline" class="form-control" value="{{ old('tanggal_deadline') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">File Surat (PDF)</label>
                <input type="file" name="file_surat" accept="application/pdf" class="form-control">
            </div>

            <button class="btn btn-primary">Kirim Pengajuan</button>
            <a href="{{ route('dispensasi.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>

@endsection

@push('styles')
    <link href="{{ url('assets/css/dashboard.css') }}" rel="stylesheet" />
@endpush
