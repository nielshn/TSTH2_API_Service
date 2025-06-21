@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        @include('components.flash-message')
        <div class="d-flex justify-content-between mb-3">
            <h4>Data Gudang</h4>
            @can('create_gudang')
                <button type="button" class="btn btn-primary btn-labeled btn-labeled-start mb-2" data-bs-toggle="modal"
                    data-bs-target="#createGudangModal">
                    <span class="btn-labeled-icon bg-black bg-opacity-20">
                        <i class="icon-database-add"></i>
                    </span> Tambah Gudang
                </button>
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Table Gudang</h5>
            </div>
            <table class="table datatable-button-html5-basic">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gudangs as $index => $gudang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $gudang['name'] }}</td>
                            <td>{{ $gudang['description'] }}</td>
                            <td>
                                <div class="d-inline-flex">
                                    @can('view_gudang')
                                        <a href="#" class="text-info me-2" data-bs-toggle="modal"
                                            data-bs-target="#detailGudangModal{{ $gudang['id'] }}">
                                            <i class="ph-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update_gudang')
                                        <a href="#" class="text-warning me-2" data-bs-toggle="modal"
                                            data-bs-target="#editGudangModal{{ $gudang['id'] }}">
                                            <i class="ph-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete_gudang')
                                        <a href="#" class="text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteGudangModal{{ $gudang['id'] }}">
                                            <i class="ph-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @can('create_gudang')
        @include('frontend.gudangs.create-modal')
    @endcan
    @foreach ($gudangs as $gudang)
        @include('frontend.gudangs.detail-modal', ['gudang' => $gudang])
        @include('frontend.gudangs.edit-modal', ['gudang' => $gudang])
        @include('frontend.gudangs.delete-modal', ['gudang' => $gudang])
    @endforeach
@endsection
