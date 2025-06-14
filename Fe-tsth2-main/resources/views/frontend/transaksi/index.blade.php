@extends('layouts.main')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Transaksi</h4>
        <div>
            @can('create_transaction')
                <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-labeled btn-labeled-start mb-2">
                    <span class="btn-labeled-icon bg-black bg-opacity-20">
                        <i class="icon-plus-circle2"></i>
                    </span> Tambah Transaksi
                </a>
            @endcan
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Table Transaksi</h5>
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
                        <th>Aksi</th>
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
                                <div class="d-inline-flex">
                                    <a href="#" class="text-info me-2" data-bs-toggle="modal"
                                        data-bs-target="#detailTransaction{{ $trx['id'] }}" title="Detail">
                                        <i class="ph-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Data transaksi belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($transactions as $trx)
        @include('frontend.transaksi.detil', ['transaction' => $trx])
    @endforeach
@endsection
