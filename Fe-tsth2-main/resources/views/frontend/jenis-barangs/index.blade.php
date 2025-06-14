@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        @include('components.flash-message')
        <div class="d-flex justify-content-between mb-3">
            <h4>Data Jenis Barang</h4>
            @can('create_jenis_barang')
                <button type="button" class="btn btn-primary btn-labeled btn-labeled-start mb-2" data-bs-toggle="modal"
                    data-bs-target="#createJenisBarangModal">
                    <span class="btn-labeled-icon bg-black bg-opacity-20">
                        <i class="icon-database-add"></i>
                    </span> Tambah Jenis Barang
                </button>
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Table Jenis Barang</h5>
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
                    @forelse ($jenisBarangs as $index => $jenisBarang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jenisBarang['name'] }}</td>
                            <td>{{ $jenisBarang['description'] ?? '-' }}</td>
                            <td>
                                <div class="d-inline-flex">
                                    @can('view_jenis_barang')
                                        <a href="#" class="text-info me-2" data-bs-toggle="modal"
                                            data-bs-target="#detailJenisBarangModal{{ $jenisBarang['id'] }}">
                                            <i class="ph-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update_jenis_barang')
                                        <a href="#" class="text-warning me-2" data-bs-toggle="modal"
                                            data-bs-target="#editJenisBarangModal{{ $jenisBarang['id'] }}">
                                            <i class="ph-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('delete_jenis_barang')
                                        <a href="#" class="text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteJenisBarangModal{{ $jenisBarang['id'] }}">
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

    @include('frontend.jenis-barangs.create-modal')

    @foreach ($jenisBarangs as $jenisBarang)
        @include('frontend.jenis-barangs.detail-modal', ['jenisBarang' => $jenisBarang])
        @include('frontend.jenis-barangs.edit-modal', ['jenisBarang' => $jenisBarang])
        @include('frontend.jenis-barangs.delete-modal', ['jenisBarang' => $jenisBarang])
    @endforeach
@endsection
