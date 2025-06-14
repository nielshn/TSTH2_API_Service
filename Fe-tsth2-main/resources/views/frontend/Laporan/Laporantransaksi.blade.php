@extends('layouts.main')

@section('content')
    @include('components.flash-message')

    <div class="d-flex justify-content-between mb-3">
        <div></div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0 w-100 w-sm-auto">Table Transaksi</h5>
            <div class="d-flex gap-2 flex-wrap w-100 w-sm-auto">
                <!-- Dropdown Filter -->
                <select class="form-select form-select-sm mb-2 mb-sm-0 w-100 w-sm-auto" aria-label="Filter"
                    onchange="window.location.href=this.value;">
                    <option value="{{ route('laporan.transaksi') }}" @if (!request('transaction_type_id')) selected @endif>Pilih
                        Transaksi</option>
                    @foreach ($transactionTypes as $tt)
                        <option value="{{ url('laporan-transaksi?transaction_type_id=' . $tt['id']) }}"
                            @if (request('transaction_type_id') == $tt['id']) selected @endif>
                            {{ $tt['name'] }}
                        </option>
                    @endforeach
                </select>

                <!-- Export PDF per jenis transaksi -->
                @if (request('transaction_type_id'))
                    <a href="{{ route('transactions.exportPdfByType', request('transaction_type_id')) }}"class="btn btn-outline-danger btn-sm w-100 w-sm-auto mb-2 mb-sm-0"
                        target="_blank">
                        <i class="ph-file-pdf"></i> Laporan PDF
                        ({{ $transactionTypes->firstWhere('id', request('transaction_type_id'))['name'] ?? 'Per Jenis' }})
                    </a>
                @else
                    <a href="{{ route('transactions.exportPdf') }}"
                        class="btn btn-outline-danger btn-sm w-100 w-sm-auto mb-2 mb-sm-0" target="_blank">
                        <i class="ph-file-pdf"></i> Laporan PDF (Semua)
                    </a>
                @endif
                <!-- Tombol Laporan Excel -->
                <button class="btn btn-outline-success btn-sm w-100 w-sm-auto" id="btn-excel">
                    <i class="icon-file-excel"></i> Laporan Excel
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table datatable-button-html5-basic">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Operator</th>
                        <th>Jumlah Barang</th>
                        <th>Gudang</th>
                        <th>Total</th>
                        <th>Daftar Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $key => $trx)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $trx['transaction_code'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx['transaction_date'])->format('d-m-Y') }}</td>
                            <td>{{ $trx['transaction_type']['name'] }}</td>
                            <td>{{ $trx['user']['name'] }}</td>
                            <td>{{ count($trx['items']) }}</td>
                            <td>
                                @foreach ($trx['items'] as $item)
                                    <li>{{ $item['gudang']['nama'] }}</li>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($trx['items'] as $item)
                                    <li>{{ $item['barang']['nama'] }}</li>
                                @endforeach
                            </td>
                            <td>{{ $item['quantity'] }} {{ $item['barang']['satuan'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Data transaksi belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
