@extends('app', ['title' => 'Krs'])

@section('content')
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card card-body shadow-sm">
                <h3 class="mb-4 fw-bold">Rencana Studi</h3>

                @if (isset($rencanaAktif) && $rencanaAktif)
                    <div
                        class="alert alert-{{ $rencanaAktif->status === 'disetujui' ? 'success' : ($rencanaAktif->status === 'ditolak' ? 'danger' : 'warning') }}">
                        <strong>Status KRS:</strong>
                        @if ($rencanaAktif->status === 'menunggu')
                            Menunggu Persetujuan
                        @elseif($rencanaAktif->status === 'disetujui')
                            Disetujui
                        @else
                            Ditolak
                        @endif
                        @if ($rencanaAktif->catatan)
                            <br><small>Catatan: {{ $rencanaAktif->catatan }}</small>
                        @endif
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="mb-2">
                    <b>KETERANGAN:</b>
                    <ul style="list-style-type:none;padding-left:0">
                        <li><span
                                style="background:#d1f7c4;width:18px;height:18px;display:inline-block;margin-right:6px;border-radius:4px;"></span>
                            Mata Kuliah yang sudah ditempuh</li>
                        <li><span
                                style="background:#d5f0fa;width:18px;height:18px;display:inline-block;margin-right:6px;border-radius:4px;"></span>
                            Mata Kuliah yang ditawarkan</li>
                    </ul>
                </div>

                <form method="GET" class="row mb-3">
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
                </form>

                <form action="{{ route('rencana-studi.store') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama MK</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Prasyarat</th>
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
                                        $isAmbil = in_array($mk->id_matakuliah, $mkDiambil);

                                        $prasyaratTerpenuhi = true;
                                        $prasyaratInfo = '';
                                        $nilaiLulus = \App\Models\NilaiMahasiswa::where('id_user', Auth::id())
                                            ->where('status', 'lulus')
                                            ->pluck('id_matakuliah')
                                            ->toArray();

                                        if (!empty($mk->prasyarat_ids)) {
                                            $prasyaratMk = \App\Models\MataKuliah::whereIn(
                                                'id_matakuliah',
                                                $mk->prasyarat_ids,
                                            )->get();
                                            $prasyaratNama = [];

                                            foreach ($prasyaratMk as $pra) {
                                                $terpenuhi = in_array($pra->id_matakuliah, $nilaiLulus);
                                                if (!$terpenuhi) {
                                                    $prasyaratTerpenuhi = false;
                                                }
                                                $icon = $terpenuhi ? '✓' : '✗';
                                                $prasyaratNama[] = $icon . ' ' . $pra->kode_matakuliah;
                                            }

                                            $prasyaratInfo = implode(', ', $prasyaratNama);
                                        } else {
                                            $prasyaratInfo = '-';
                                        }

                                        $bgClass = $isAmbil
                                            ? 'bg-success-subtle'
                                            : ($prasyaratTerpenuhi
                                                ? 'bg-info-subtle'
                                                : 'bg-danger-subtle');
                                    @endphp
                                    <tr class="{{ $bgClass }}">
                                        <td>{{ $mk->kode_matakuliah }}</td>
                                        <td class="text-start">{{ $mk->nama_matakuliah }}</td>
                                        <td class="sks-cell" data-sks="{{ $mk->sks }}">{{ $mk->sks }}</td>
                                        <td>{{ $mk->semester }}</td>
                                        <td class="text-start" style="font-size: 0.85rem;">
                                            {!! $prasyaratInfo !!}
                                        </td>
                                        <td>{{ $mk->group }}</td>
                                        <td>{{ $mk->hari }}</td>
                                        <td>{{ $mk->jam }}</td>
                                        <td>{{ $mk->kapasitas }}</td>
                                        <td>{{ $mk->peserta }}</td>
                                        <td>
                                            <input type="checkbox" name="matakuliah[]" value="{{ $mk->id_matakuliah }}"
                                                class="krs-checkbox" {{ $isAmbil ? 'checked' : '' }}
                                                {{ !$prasyaratTerpenuhi ? 'disabled title="Prasyarat belum terpenuhi"' : '' }}>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak ada data mata kuliah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                Total SKS Dipilih: <span id="total-sks">0</span> / 24
                            </span>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">Pengajuan KRS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ url('assets/css/dashboard.css') }}" rel="stylesheet" />
@endpush

@push('styles')
    <link href="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{ asset('assets/libs/jquery-confirm/jquery-confirm.min.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>

    <script src="{{ asset('assets/js/pages/admin/categories/page.js') }}"></script>

    <script>
        function hitungTotalSKS() {
            let totalSKS = 0;
            $('.krs-checkbox:checked:not(:disabled)').each(function() {
                let row = $(this).closest('tr');
                let sks = parseInt(row.find('.sks-cell').data('sks')) || 0;
                totalSKS += sks;
            });

            $('#total-sks').text(totalSKS);


            if (totalSKS > 24) {
                $('#total-sks').parent().removeClass('bg-primary').addClass('bg-danger');
            } else {
                $('#total-sks').parent().removeClass('bg-danger').addClass('bg-primary');
            }

            return totalSKS;
        }


        $(document).ready(function() {
            hitungTotalSKS();

            $('.krs-checkbox').change(function() {
                hitungTotalSKS();
            });


            $('form').submit(function(e) {
                let totalSKS = hitungTotalSKS();
                if (totalSKS > 24) {
                    e.preventDefault();
                    alert('Total SKS (' + totalSKS + ') melebihi batas maksimal 24 SKS!');
                    return false;
                }
            });
        });
    </script>
@endpush
