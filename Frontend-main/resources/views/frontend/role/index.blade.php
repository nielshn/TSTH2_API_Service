@extends('layouts.main')

@section('content')
    @include('components.flash-message')

    <div class="d-flex justify-content-between mb-3">
        <h4>Data Role</h4>
        <button type="button" class="btn btn-primary btn-labeled btn-labeled-start mb-2" data-bs-toggle="modal"
            data-bs-target="#createRoleModal">
            <span class="btn-labeled-icon bg-black bg-opacity-20">
                <i class="icon-database-add"></i>
            </span> Tambah Role
        </button>
    </div>

    <!-- Table role -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Table Role</h5>
        </div>
        <table class="table datatable-button-html5-basic">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $role['name'] }}</td>
                        <td>
                            <div class="d-inline-flex">
                                <a href="#" class="text-info me-2" data-bs-toggle="modal"
                                    data-bs-target="#detailRoleModal{{ $role['id'] }}">
                                    <i class="ph-eye"></i>
                                </a>
                                <a href="#" class="text-warning me-2" data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal{{ $role['id'] }}">
                                    <i class="ph-pencil"></i>
                                </a>
                                <a href="#" class="text-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteRoleModal{{ $role['id'] }}">
                                    <i class="ph-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('frontend.role.create-modal')
    @foreach ($roles as $role)
        @include('frontend.role.detail-modal', ['role' => $role])
        @include('frontend.role.edit-modal', ['role' => $role])
        @include('frontend.role.delete-modal', ['role' => $role])
    @endforeach
@endsection
