<div class="modal fade" id="editBarangCategoryModal{{ $barangCategory['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('barang-categories.update', $barangCategory['id']) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategory Barang</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ $barangCategory['name'] }}"
                        required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
            </div>
        </form>
    </div>
</div>
