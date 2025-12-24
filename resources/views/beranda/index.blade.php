@extends('app', ['title' => 'Beranda'])


@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card card-body shadow-sm">
                <h3 class="mb-4 fw-bold">Beranda</h3>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row mb-4 dashboard-stats">
                    <div class="col-md-4">
                        <div class="card text-center stat-card mb-3">
                            <div class="card-body">
                                <h6 class="mb-1">Jumlah SKS yang diambil</h6>
                                <h2 class="mb-0">{{ $jumlahSKS ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="card text-center stat-card alt mb-3">
                            <div class="card-body">
                                <h6 class="mb-1">Jumlah SKS sudah ditempuh</h6>
                                <h2 class="mb-0">{{ $jumlahSKSTempuh ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="mb-2">
                    <b>KETERANGAN:</b>
                    <ul style="list-style-type:none;padding-left:0">
                        <li><span
                                style="background:#d1f7c4;width:18px;height:18px;display:inline-block;margin-right:6px;border-radius:4px;"></span>
                            Mata Kuliah yang sudah ditempuh</li>
                        <li><span
                                style="background:#d5f0fa;width:18px;height:18px;display:inline-block;margin-right:6px;border-radius:4px;"></span>
                            Mata Kuliah yang ditawarkan</li>
                    </ul>
                </div> --}}

                {{-- Form Filter/Search --}}
                {{-- <form method="GET" class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama mata kuliah ...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="semester" class="form-control">
                            <option value="">Filter Semester</option>
                            @foreach ($mataKuliah->pluck('semester')->unique()->sort() as $sem)
                                <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                                    {{ $sem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-secondary" type="submit">Filter</button>
                    </div>
                </form> --}}

                {{-- Form Table MK --}}
                {{-- <form action="{{ route('rencana-studi.store') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama MK</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Group</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Kap</th>
                                    <th>Pst</th>
                                    <th>Ambil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mataKuliah as $mk)
                                    @php
                                        $rowStyle = in_array($mk->id_matakuliah, $mkDiambil)
                                            ? 'background:#d1f7c4;'
                                            : 'background:#d5f0fa;';
                                    @endphp
                                    <tr style="{{ $rowStyle }}">
                                        <td>{{ $mk->kode_matakuliah }}</td>
                                        <td class="text-start">{{ $mk->nama_matakuliah }}</td>
                                        <td>{{ $mk->sks }}</td>
                                        <td>{{ $mk->semester }}</td>
                                        <td>{{ $mk->group }}</td>
                                        <td>{{ $mk->hari }}</td>
                                        <td>{{ $mk->jam }}</td>
                                        <td>{{ $mk->kapasitas }}</td>
                                        <td>{{ $mk->peserta }}</td>
                                        <td>
                                            <input type="checkbox" name="matakuliah[]" value="{{ $mk->id_matakuliah }}"
                                                {{ in_array($mk->id_matakuliah, $mkDiambil) ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data mata kuliah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">Pengajuan KRS</button>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('assets/css/dashboard.css') }}">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>

    <script src="{{ asset('assets/js/pages/admin/categories/page.js') }}"></script>
@endpush
