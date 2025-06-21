<div class="modal fade" id="detailTransaction{{ $transaction['id'] }}" tabindex="-1"
    aria-labelledby="detailTransactionLabel{{ $transaction['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title" id="detailTransactionLabel{{ $transaction['id'] }}">
                    <i class="fas fa-file-invoice-dollar"></i> <strong>Detail Transaksi</strong> -
                    {{ $transaction['transaction_code'] }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong class="text-muted">Kode Transaksi:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="text-dark">{{ $transaction['transaction_code'] }}</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong class="text-muted">Tanggal:</strong>
                    </div>
                    <div class="col-md-8">
                        <span
                            class="text-dark">{{ \Carbon\Carbon::parse($transaction['transaction_date'])->format('d-m-Y') }}</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong class="text-muted">Jenis Transaksi:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="text-dark">{{ $transaction['transaction_type']['name'] }}</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong class="text-muted">Keterangan:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="text-dark">
                            {{ $transaction['description'] ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong class="text-muted">Operator:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="text-dark">{{ $transaction['user']['name'] }}</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong class="text-muted">Jumlah Barang:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="text-dark">{{ count($transaction['items']) }}</span>
                    </div>
                </div>

                <!-- Tabel Daftar Barang -->
                <div class="row mb-4">
                    <div class="col-12">
                        <strong class="text-muted">Daftar Barang:</strong>
                        <table class="table table-striped table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction['items'] as $item)
                                    <tr>
                                        <td>{{ $item['barang']['nama'] }}</td>
                                        <td>{{ $item['quantity'] }} {{ $item['barang']['satuan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
