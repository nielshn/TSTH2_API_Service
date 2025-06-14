<div class="modal fade" id="detailTransactionTypeModal{{ $transactionType['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Jenis Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Nama</dt>
                    <dd class="col-sm-8">{{ $transactionType['name'] }}</dd>

                    <dt class="col-sm-4">Slug</dt>
                    <dd class="col-sm-8">{{ $transactionType['slug'] }}</dd>

                    <dt class="col-sm-4">Dibuat Pada</dt>
                    <dd class="col-sm-8">
                        {{ \Carbon\Carbon::parse($transactionType['created_at'])->format('d-m-Y H:i') }}
                    </dd>

                    <dt class="col-sm-4">Diperbarui Pada</dt>
                    <dd class="col-sm-8">
                        {{ \Carbon\Carbon::parse($transactionType['updated_at'])->format('d-m-Y H:i') }}
                    </dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Tutup</button>
            </div>
        </div>
    </div>
</div>
