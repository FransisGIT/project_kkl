@extends('app', ['title' => 'Jadwal Kuliah'])

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card card-body shadow-sm">
                <h3 class="mb-4 fw-bold">Jadwal Kuliah Saya</h3>

                @if($statusKrs === 'disetujui')
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle me-2"></i>
                        <strong>KRS Anda telah disetujui!</strong>
                        @if($rencanaAktif && $rencanaAktif->catatan)
                            <br><small>Catatan: {{ $rencanaAktif->catatan }}</small>
                        @endif
                    </div>

                    @if($mataKuliahList->count() > 0)
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h6 class="text-white mb-1">Total Mata Kuliah</h6>
                                        <h2 class="text-white mb-0">{{ $mataKuliahList->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6 class="text-white mb-1">Total SKS</h6>
                                        <h2 class="text-white mb-0">{{ $totalSks }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($mataKuliahList as $index => $mk)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-start border-primary border-4 shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge bg-primary">{{ $mk->kode_matakuliah }}</span>
                                                <span class="badge bg-success">{{ $mk->sks }} SKS</span>
                                            </div>

                                            <h5 class="card-title fw-bold mb-3">{{ $mk->nama_matakuliah }}</h5>

                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="mdi mdi-book-outline me-1"></i>Semester
                                                    </small>
                                                    <span class="fw-semibold">{{ $mk->semester }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="mdi mdi-account-group-outline me-1"></i>Group
                                                    </small>
                                                    <span class="fw-semibold">{{ $mk->group ?? '-' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="mdi mdi-calendar-outline me-1"></i>Hari
                                                    </small>
                                                    <span class="fw-semibold">{{ $mk->hari ?? '-' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">
                                                        <i class="mdi mdi-clock-outline me-1"></i>Jam
                                                    </small>
                                                    <span class="fw-semibold">{{ $mk->jam ?? '-' }}</span>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="mdi mdi-account-multiple me-1"></i>
                                                    {{ $mk->peserta }}/{{ $mk->kapasitas }} Mahasiswa
                                                </small>
                                                @php
                                                    $percentage = $mk->kapasitas > 0 ? ($mk->peserta / $mk->kapasitas) * 100 : 0;
                                                @endphp
                                                <small class="text-{{ $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success') }}">
                                                    {{ number_format($percentage, 0) }}%
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert me-2"></i>
                            KRS Anda disetujui tetapi tidak ada mata kuliah yang terdaftar.
                        </div>
                    @endif

                @elseif($statusKrs === 'menunggu')
                    <div class="alert alert-warning">
                        <i class="mdi mdi-clock-outline me-2"></i>
                        <strong>KRS Anda masih menunggu persetujuan.</strong>
                        <br><small>Silakan tunggu hingga admin/dosen menyetujui pengajuan KRS Anda.</small>
                    </div>
                    <div class="text-center py-5">
                        <i class="mdi mdi-clock-alert-outline" style="font-size: 100px; color: #ffc107;"></i>
                        <p class="mt-3 text-muted">Pengajuan KRS Anda sedang dalam proses review</p>
                    </div>

                @elseif($statusKrs === 'ditolak')
                    <div class="alert alert-danger">
                        <i class="mdi mdi-close-circle me-2"></i>
                        <strong>KRS Anda ditolak.</strong>
                        @if($rencanaAktif && $rencanaAktif->catatan)
                            <br><small>Alasan: {{ $rencanaAktif->catatan }}</small>
                        @endif
                        <br><small>Silakan ajukan ulang KRS Anda di menu KRS.</small>
                    </div>
                    <div class="text-center py-5">
                        <i class="mdi mdi-close-circle-outline" style="font-size: 100px; color: #dc3545;"></i>
                        <p class="mt-3 text-muted">Silakan perbaiki dan ajukan kembali KRS Anda</p>
                        <a href="{{ route('krs.index') }}" class="btn btn-primary mt-3">
                            <i class="mdi mdi-file-document-outline me-1"></i>
                            Ke Halaman KRS
                        </a>
                    </div>

                @else
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong>Anda belum mengajukan KRS.</strong>
                        <br><small>Silakan ajukan KRS terlebih dahulu di menu KRS.</small>
                    </div>
                    <div class="text-center py-5">
                        <i class="mdi mdi-file-document-outline" style="font-size: 100px; color: #0dcaf0;"></i>
                        <p class="mt-3 text-muted">Mulai dengan mengisi KRS Anda</p>
                        <a href="{{ route('krs.index') }}" class="btn btn-primary mt-3">
                            <i class="mdi mdi-file-document-outline me-1"></i>
                            Isi KRS Sekarang
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
