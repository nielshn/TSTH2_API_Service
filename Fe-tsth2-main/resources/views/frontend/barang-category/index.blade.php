@extends('layouts.main')

@section('content')
    @include('components.flash-message')
    <div class="d-flex justify-content-between mb-3">
        <h4>Data Kategori Barang</h4>
        @can('create_category_barang')
            <button type="button" class="btn btn-primary btn-labeled btn-labeled-start mb-2"
                data-bs-toggle="modal"data-bs-target="#createBarangCategoryModal">
                <span class="btn-labeled-icon bg-black bg-opacity-20">
                    <i class="icon-database-add"></i>
                </span> Tambah Kategori Barang
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Table Kategori Barang</h5>
        </div>
        <table class="table datatable-button-html5-basic">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangCategories as $index => $barangCategory)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barangCategory['name'] }}</td>
                        <td>
                            <div class="d-inline-flex">
                                @can('view_category_barang')
                                    <a href="#" class="text-info me-2" data-bs-toggle="modal"
                                        data-bs-target="#detailBarangCategoryModal{{ $barangCategory['id'] }}">
                                        <i class="ph-eye"></i>
                                    </a>
                                @endcan
                                @can('update_category_barang')
                                    <a href="#" class="text-warning me-2" data-bs-toggle="modal"
                                        data-bs-target="#editBarangCategoryModal{{ $barangCategory['id'] }}">
                                        <i class="ph-pencil"></i>
                                    </a>
                                @endcan
                                @can('delete_category_barang')
                                    <a href="#" class="text-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteBarangCategoryModal{{ $barangCategory['id'] }}">
                                        <i class="ph-trash"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('frontend.barang-category.create-modal')

    {{-- Modal Detail, Edit, Delete --}}
    @foreach ($barangCategories as $barangCategory)
        {{-- Detail --}}
        @include('frontend.barang-category.detail-modal', ['barangCategory' => $barangCategory])
        {{-- Edit --}}
        @include('frontend.barang-category.edit-modal', ['barangCategory' => $barangCategory])
        {{-- Delete --}}
        @include('frontend.barang-category.delete-modal', ['barangCategory' => $barangCategory])
    @endforeach
@endsection
