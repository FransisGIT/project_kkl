<!-- Modal: Ajukan Dispensasi (Mahasiswa) -->
<div class="modal fade" id="modalAjukan" tabindex="-1" aria-labelledby="modalAjukanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAjukanLabel">Ajukan Dispensasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dispensasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
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
            <label class="form-label">Surat Dispensasi (PDF)</label>
            <input type="file" name="surat_dispensasi" accept="application/pdf" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Bukti Pembayaran <span class="text-danger">(wajib)</span></label>
            <input type="file" name="payment_proof" accept="image/*" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        </div>
      </form>
    </div>
  </div>
</div>
