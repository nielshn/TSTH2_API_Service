<div class="modal fade" id="editTransactionTypeModal{{ $transactionType['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('transaction-types.update', $transactionType['id']) }}" method="POST"
            class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Jenis Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ $transactionType['name'] }}"
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
