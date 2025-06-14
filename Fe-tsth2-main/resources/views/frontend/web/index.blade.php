@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        @include('components.flash-message')
        <div class="container py-5">
            <div class="row g-4">
                <!-- Kartu Profil -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{ $web['web_logo'] }}" class="mb-3" alt="Logo" style="width: 100px;">
                            <h6 class="text-muted">Judul Website</h6>
                            <h5>{{ $web['web_nama'] }}</h5>
                            <hr>
                            <h6 class="text-muted">Deskripsi</h6>
                            <p>{{ $web['web_deskripsi'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Pengaturan -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Ubah Pengaturan</h5>
                            <div class="alert alert-primary p-2">
                                <strong>Extensi Gambar</strong><br>
                                .jpg .jpeg .png
                            </div>
                            <!-- Menambahkan enctype untuk upload file -->
                            <form id="upload-form" action="{{ route('webs.update', 1) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control" id="logo" name="web_logo">
                                </div>
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul Website</label>
                                    <input type="text" class="form-control" id="judul" name="web_nama"
                                        value="{{ $web['web_nama'] }}">
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Website</label>
                                    <textarea class="form-control" id="deskripsi" name="web_deskripsi" rows="3">{{ $web['web_deskripsi'] }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.getElementById('logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                const base64Image = reader.result;
                // Pastikan base64 memiliki format yang benar
                if (base64Image.startsWith('data:image/png;base64,') || base64Image.startsWith(
                        'data:image/jpeg;base64,')) {
                    console.log('Base64 Gambar Valid:', base64Image);
                    // Tempelkan base64Image ke dalam form sebelum submit
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'web_logo';
                    input.value = base64Image;
                    document.getElementById('upload-form').appendChild(input);
                } else {
                    console.log('Data base64 tidak valid:', base64Image);
                    alert('Gambar tidak valid! Pastikan format gambar benar.');
                }
            };

            reader.readAsDataURL(file);
        });
    </script>
@endpush
