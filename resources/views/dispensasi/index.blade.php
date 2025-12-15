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

@push('styles')
    <link href="{{ url('assets/css/dashboard.css') }}" rel="stylesheet" />
@endpush
