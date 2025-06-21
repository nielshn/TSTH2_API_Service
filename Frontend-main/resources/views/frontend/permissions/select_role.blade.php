@extends('layouts.main')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-4 text-center text-primary fw-bold">
                        <i class="bi bi-person-gear me-2"></i>Pilih Role
                    </h4>

                    <form action="{{ route('permissions.show') }}" method="GET">
                        <div class="mb-3">
                            <label for="role" class="form-label fw-semibold">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['name'] }}">{{ ucfirst($role['name']) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-eye-fill me-1"></i> Lihat Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
