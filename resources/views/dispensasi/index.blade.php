@extends('app', ['title' => 'Riwayat Dispensasi'])

@section('content')
    <div class="card p-4 mt-3 shadow-sm">
        <h4>Riwayat Pengajuan Dispensasi</h4>
        <hr>
        <div class="mb-3">
            <button type="button" class="btn btn-primary btn-sm btn-preview-pdf"
                data-url="{{ asset('assets/media/sample-dispensasi.pdf') }}"
                data-filename="sample-dispensasi.pdf">
                Surat Dispensasi (Dummy)
            </button>
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
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $d)
                    <tr>
                        <td>{{ $d->tahun_akademik ?? '-' }}</td>
                        <td>{{ $d->jumlah_pengajuan ?? $d->jumlah ?? '-' }}</td>
                        <td>{{ $d->no_hp ?? '-' }}</td>
                        <td>{{ $d->tanggal_deadline ?? '-' }}</td>
                        <td>
                            @if (in_array($d->status, ['menunggu']))
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif ($d->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif ($d->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif ($d->status == 'diterima_dosen')
                                <span class="badge bg-info">Diterima Dosen</span>
                            @elseif ($d->status == 'diterima_warek')
                                <span class="badge bg-primary">Diterima Warek</span>
                            @elseif ($d->status == 'diterima_keuangan')
                                <span class="badge bg-secondary">Diterima Keuangan</span>
                            @else
                                <span class="badge bg-light text-dark">{{ $d->status }}</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $file = $d->file_pdf ?? $d->file_surat ?? null;
                            @endphp
                            @if ($file)
                                <button type="button" class="btn btn-sm btn-outline-primary btn-preview-pdf"
                                    data-url="{{ asset('storage/' . $file) }}"
                                    data-filename="{{ basename($file) }}">
                                    Preview
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @php $me = auth()->user(); @endphp
                            @if(in_array($me->id_role, [5,4]))
                                @php
                                    $canProcess = false;
                                    if ($me->id_role == 5 && $d->status == 'menunggu') $canProcess = true;
                                    if ($me->id_role == 4 && $d->status == 'diterima_warek') $canProcess = true;
                                @endphp

                                @if($canProcess)
                                    <form action="{{ route('dispensasi.approve', $d->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="note" value="Disetujui oleh {{ $me->name }}">
                                        <button class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('dispensasi.reject', $d->id) }}" method="POST" class="d-inline ms-1">
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

    {{-- PDF Preview Modal (placed inside view push scripts) --}}
    <div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Surat Dispensasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height:80vh;">
                    <iframe id="pdf-frame" src="{{ asset("assets/media/sample-dispensasi.pdf") }}" frameborder="0" style="width:100%; height:100%;"></iframe>
                </div>
                <div class="modal-footer">
                    <a id="pdf-download" class="btn btn-primary" href="#" target="_blank">Download</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-preview-pdf').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    var url = btn.getAttribute('data-url');
                    var filename = btn.getAttribute('data-filename') || 'file.pdf';
                    var iframe = document.getElementById('pdf-frame');
                    var download = document.getElementById('pdf-download');
                    iframe.setAttribute('src', url);
                    download.setAttribute('href', url);
                    download.setAttribute('download', filename);
                    var modalEl = document.getElementById('pdfPreviewModal');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                });
            });

            // Clear iframe on modal hide to stop PDF loading
            var modalEl = document.getElementById('pdfPreviewModal');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    var iframe = document.getElementById('pdf-frame');
                    if (iframe) iframe.setAttribute('src', 'about:blank');
                });
            }
        });
    </script>
@endpush
