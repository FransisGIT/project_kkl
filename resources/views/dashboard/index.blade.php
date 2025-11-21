@extends('app', ['title' => 'Dashboard'])

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">
                Dashboard
            </h4>
        </div>
    </div>

    <div class="row">
            <div class="col-12">
            <div class="card card-body shadow-sm">
                {{-- <div class="row g-2">
                    <div class="col-12 col-md-auto d-flex flex-grow-1 align-items-center">
                        <input type="text" id="search-categories" class="form-control bg-light border-0 me-2"
                            placeholder="Masukkan nama mahasiswa" style="height: 38px;" />

                        <button class="btn btn-info btn-search me-2 d-flex align-items-center justify-content-center"
                            style="height: 38px;">
                            <span class="mdi mdi-magnify me-1"></span>
                            <span class="d-none d-md-inline">Cari</span>
                        </button>

                        <div class="dropdown mx-2">
                            <button class="btn btn-dark dropdown-toggle" type="button" id="filterStatusCategories"
                                data-bs-toggle="dropdown" aria-expanded="false" style="height: 38px;">
                                <span class="mdi mdi-filter-outline"></span>
                                <span class="d-none d-md-inline ms-1">Filter Ruangan</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterStatusCategories">
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="all">
                                        A39
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="aktif">
                                        A38
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="nonaktif">
                                        A37
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle" type="button" id="filterStatusCategories"
                                data-bs-toggle="dropdown" aria-expanded="false" style="height: 38px;">
                                <span class="mdi mdi-filter-outline"></span>
                                <span class="d-none d-md-inline ms-1">Filter Mata Kuliah</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterStatusCategories">
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="all">
                                        Algoritma
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="aktif">
                                        Web Programming
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item filter-status" href="#" data-status="nonaktif">
                                        Embed System
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-auto">
                        <a type="button" class="btn btn-primary w-100" href="/form-tambah-absensi">
                            <span class="mdi mdi-plus me-1"></span>
                            Tambah
                        </a>
                    </div>
                </div> --}}

                {{-- <div class="table-responsive mt-3">
                    <table id="ohmytable" class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">
                                    No
                                </th>
                                <th class="text-center text-uppercase">
                                    Nim
                                </th>
                                <th class="text-left text-uppercase">
                                    Nama Mahasiswa
                                </th>
                                <th class="text-left text-uppercase">
                                    Jam Absen
                                </th>
                                <th class="text-left text-uppercase">
                                    Tanggal Absen
                                </th>
                                <th class="text-center text-uppercase">
                                    Mata Kuliah
                                </th>
                                <th class="text-center text-uppercase">
                                    Ruangan
                                </th>
                                <th class="text-center">
                                    <span class="mdi mdi-collage fs-18"></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataAbsensi as $data)
                                <tr>
                                    <td class="text-center text-uppercase">{{ $loop->iteration }}</td>
                                    <td class="text-center text-uppercase">{{ $data->nim }}</td>
                                    <td class="text-center text-uppercase">{{ $data->nama }}</td>
                                    <td class="text-center text-uppercase">{{ $data->jam_absen }}</td>
                                    <td class="text-center text-uppercase">{{ $data->tanggal_absen }}</td>
                                    <td class="text-center text-uppercase">{{ $data->mata_kuliah }}</td>
                                    <td class="text-center text-uppercase">{{ $data->ruangan }}</td>
                                    <td class="text-center text-uppercase">
                                        <div class="d-flex justify-content-between items-center align-content-center gap-3">
                                            <a href="" class="btn btn-sm btn-danger">Delete</a>
                                            <a href="" class="btn btn-sm btn-primary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>

    <script src="{{ asset('assets/js/pages/admin/categories/page.js') }}"></script>
@endpush
