@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        @include('components.flash-message')
        <div class="d-flex justify-content-between mb-3">
            <h4>Data Jenis Transaksi</h4>
            @can('create_transaction_type')
                <button class="btn btn-primary btn-labeled btn-labeled-start mb-2" data-bs-toggle="modal" data-bs-target="#createTransactionTypeModal">
                    <span class="btn-labeled-icon bg-black bg-opacity-20">
                        <i class="icon-database-add"></i></span> Tambah Jenis Transaksi
                </button>
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Table Jenis Transaksi</h5>
            </div>
            <table class="table datatable-button-html5-basic">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactionTypes as $index => $transactionType)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transactionType['name'] }}</td>
                            <td>{{ $transactionType['slug'] }}</td>
                            <td>
                                <div class="d-inline-flex">
                                    @can('view_transaction_type')
                                        <a href="#" class="text-info me-2" data-bs-toggle="modal"
                                            data-bs-target="#detailTransactionTypeModal{{ $transactionType['id'] }}">
                                            <i class="ph-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update_transaction_type')
                                        <a href="#" class="text-warning me-2" data-bs-toggle="modal"
                                            data-bs-target="#editTransactionTypeModal{{ $transactionType['id'] }}">
                                            <i class="ph-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete_transaction_type')
                                        <a href="#" class="text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteTransactionTypeModal{{ $transactionType['id'] }}">
                                            <i class="ph-trash"></i>
                                        </a>
                                    @endcan
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('frontend.transaction-types.create-modal')

    @foreach ($transactionTypes as $transactionType)
        @include('frontend.transaction-types.detail-modal', ['transactionType' => $transactionType])
        @include('frontend.transaction-types.edit-modal', ['transactionType' => $transactionType])
        @include('frontend.transaction-types.delete-modal', ['transactionType' => $transactionType])
    @endforeach
@endsection
