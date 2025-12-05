@extends('app', ['title' => 'Persetujuan KRS'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-body shadow-sm">
                <h3 class="mb-4 fw-bold">Persetujuan KRS Mahasiswa</h3>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Jumlah MK</th>
                                <th>Total SKS</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rencanaStudi as $index => $rs)
                                <tr>
                                    <td>{{ $rencanaStudi->firstItem() + $index }}</td>
                                    <td>{{ $rs->user->name }}</td>
                                    <td>{{ $rs->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $rs->jumlahMk }} MK</td>
                                    <td>{{ $rs->totalSks }} SKS</td>
                                    <td>
                                        @if($rs->status === 'menunggu')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($rs->status === 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rs->status === 'menunggu')
                                            <form action="{{ route('persetujuan-krs.approve', $rs->id_rencana_studi) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui KRS ini?')">
                                                    <i class="mdi mdi-check"></i> Setujui
                                                </button>
                                            </form>

                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $rs->id_rencana_studi }}">
                                                <i class="mdi mdi-close"></i> Tolak
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Modal Tolak --}}
                                <div class="modal fade" id="rejectModal{{ $rs->id_rencana_studi }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('persetujuan-krs.reject', $rs->id_rencana_studi) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak KRS</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="catatan" class="form-control" rows="4" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak KRS</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pengajuan KRS.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $rencanaStudi->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
