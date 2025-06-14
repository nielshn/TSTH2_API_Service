@extends('layouts.main')

@section('content')
    <div class="content d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="text-center">
            {{-- Ilustrasi --}}
            <img src="{{ asset('template/assets/images/error_bg.svg') }}" alt="403 Forbidden" class="img-fluid mb-4"
                style="max-width: 200px;">

            {{-- Kode Error --}}
            <h1 class="display-1 fw-bold text-primary mb-3">403</h1>

            {{-- Judul --}}
            <p class="h4 text-secondary mb-2">Halaman Anda tidak diberi akses</p>

            {{-- Pesan Tambahan --}}
            <p class="text-muted mb-4">
                Anda tidak memiliki izin untuk melihat halaman ini.<br>
                Silakan hubungi administrator jika ini seharusnya dapat diakses.
            </p>

            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-house-door-fill me-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection
