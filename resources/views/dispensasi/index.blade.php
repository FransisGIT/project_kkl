@extends('app', ['title' => 'Riwayat Dispensasi'])

@section('content')
    <div class="card p-4 mt-3 shadow-sm">
        <h4>Riwayat Pengajuan Dispensasi</h4>
        <hr>
        <div class="mb-3 d-flex gap-2">
            <a href="{{ asset('assets/media/sample-dispensasi.docx') }}" target="_blank" rel="noopener"
                class="btn btn-primary btn-sm">
                Surat Dispensasi
            </a>
            @php $me = auth()->user(); @endphp
            @if ($me->id_role == 3)
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjukan">Ajukan
                    Dispensasi</button>
            @endif
            @if ($me->id_role == 3)
                <a href="{{ asset('assets/images/va.jpeg') }}" class="btn btn-success btn-sm">Pembayaran VA</a>
            @endif
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Tahun Akademik</th>
                    <th>Jumlah</th>
                    <th>No HP</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Bukti Pembayaran</th>
                    <th>Aksi</th>
                </tr>
                @if (!empty($d->approver_notes) && count($d->approver_notes) > 0)
                    @php $notes = $d->approver_notes; @endphp
                    <tr class="table-active">
                        <td colspan="8">
                            <strong>Riwayat Persetujuan:</strong>
                            <ul class="mb-0">
                                @foreach ($notes as $n)
                                    @php
                                        $roleName =
                                            $roles->firstWhere('id_role', $n['role_id'])->name ??
                                            'Role ' . ($n['role_id'] ?? '?');
                                    @endphp
                                    <li>
                                        <small>
                                            <strong>{{ $n['by'] }}</strong> ({{ $roleName }}) — {{ $n['action'] }}
                                            pada {{ $n['at'] }}
                                            @if (!empty($n['note']))
                                                — "{{ $n['note'] }}"
                                            @endif
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endif
            </thead>

            <tbody>
                @forelse ($data as $d)
                    <tr>
                        <td>{{ $d->tahun_akademik ?? '-' }}</td>
                        <td>{{ $d->jumlah_pengajuan ?? ($d->jumlah ?? '-') }}</td>
                        <td>{{ $d->no_hp ?? '-' }}</td>
                        <td>{{ $d->tanggal_deadline ?? '-' }}</td>
                        <td>
                            @if (in_array($d->status, ['menunggu']))
                            @elseif ($d->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif ($d->status == 'diterima_dosen')
                                <span class="badge bg-success">Diterima Dosen</span>
                            @elseif ($d->status == 'diterima_warek')
                                <span class="badge bg-primary">Diterima Wakil Rektor 2</span>
                            @elseif ($d->status == 'diterima_keuangan')
                                <span class="badge bg-secondary">Diterima Keuangan</span>
                            @elseif ($d->status == 'menunggu_warek')
                                <span class="badge bg-secondary">Menunggu Wakil Rektor 2</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $file = $d->surat_dispensasi ?? ($d->file_surat ?? null);
                            @endphp
                            @if ($file)
                                <a href="{{ route('dispensasi.preview', $d->id) }}" target="_blank" rel="noopener"
                                    class="btn btn-sm btn-outline-primary m-auto">
                                    Lihat
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if (!empty($d->payment_proof))
                                <a href="{{ route('dispensasi.payment_proof', $d->id) }}" target="_blank" rel="noopener"
                                    class="m-auto">
                                    <img src="{{ route('dispensasi.payment_proof', $d->id) }}" alt="bukti"
                                        style="height:68px; object-fit:cover; background-color: rgb(184, 184, 184); padding: 2px; border-radius: 8px" />
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @php $me = auth()->user(); @endphp
                            @if ($me->id_role == 4 || $me->id_role == 5)
                                @if ($d->status == 'menunggu' || $d->status == 'menunggu_warek')
                                    <form action="{{ route('dispensasi.approve', $d->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="note" value="Disetujui oleh {{ $me->name }}">
                                        <button class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('dispensasi.reject', $d->id) }}" method="POST"
                                        class="d-inline ms-1">
                                        @csrf
                                        <input type="hidden" name="note" value="Ditolak oleh {{ $me->name }}">
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada pengajuan dispensasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



@endsection

@include('dispensasi.modal_create')



@push('styles')
    <link href="{{ url('assets/css/dashboard.css') }}" rel="stylesheet" />
@endpush
