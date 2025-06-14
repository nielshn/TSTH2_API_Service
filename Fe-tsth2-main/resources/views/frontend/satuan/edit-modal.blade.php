<div class="modal fade" id="editSatuanModal{{ $satuan['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('satuans.update', $satuan['id']) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Satuan Barang</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ $satuan['name'] }}" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $satuan['description'] }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
            </div>
        </form>
    </div>
</div>
