<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('template/assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('template/assets/demo/demo_configurator.js') }}"></script>
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">

    <style>
        html,
        body {
            height: auto;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .page-content {
            padding: 2rem 1rem;
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .profile-card {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 1rem;
        }

        .profile-img-container {
            width: 140px;
            height: 140px;
            margin: 0 auto;
            position: relative;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .edit-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #ffffff;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25);
            z-index: 2;
        }

        .btn-flat {
            border-radius: 0.5rem;
            box-shadow: none;
            transition: all 0.2s ease-in-out;
        }

        .btn-flat:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .btn-section .btn {
            margin-bottom: 0.75rem;
            font-weight: 500;
        }

        @media (max-width: 767.98px) {
            .profile-img-container {
                width: 110px;
                height: 110px;
            }

            .page-content {
                padding: 1.5rem 0.5rem;
            }

            .profile-card {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>

<body>
    @include('components.demo_config')

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg px-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('dashboard') }}" class="btn btn-link text-dark">
                <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Profile -->
                <div class="col-12 col-md-5 col-lg-4 mb-4">
                    <div class="profile-card card text-center">
                        <div class="profile-img-container mb-3">
                            <img id="currentProfileImg" src="{{ Avatar::create(session('user')['name'])->toBase64() }}"
                                class="profile-img" alt="Profile Picture">
                            <label for="uploadFotoProfil" class="edit-icon" data-bs-toggle="modal"
                                data-bs-target="#editProfileModal">
                                <i class="bi bi-pencil-fill text-primary fs-6"></i>
                            </label>
                        </div>
                        <h5 class="text-muted mb-1">{{ $user['phone'] ?? '' }}</h5>
                        <p class="text-muted small mb-4">{{ $user['email'] ?? '' }}</p>
                        <div class="btn-section">
                            <a href="{{ route('profile.user_profile') }}"
                                class="btn btn-outline-primary w-100 btn-flat">
                                <i class="bi bi-person me-2"></i>Profil Pengguna
                            </a>
                            <a href="{{ route('profile.ganti-password') }}"
                                class="btn btn-outline-secondary w-100 btn-flat">
                                <i class="bi bi-key me-2"></i>Ganti Password
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Content -->
                <div class="col-12 col-md-7 col-lg-8">
                    @yield('contentprofile')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="modalFotoForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold text-primary">Ganti Foto Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-3">
                            <img id="previewFoto" src="" class="rounded-circle shadow-sm border"
                                style="width: 130px; height: 130px; object-fit: cover;">
                        </div>
                        <p class="small text-muted">Pastikan foto berformat JPG, PNG atau JPEG.</p>
                        <input type="file" class="form-control d-none" id="uploadFotoProfilModal" name="foto"
                            accept="image/*">
                        <button type="button" class="btn btn-outline-primary" id="changeImageBtn">Pilih Gambar
                            Baru</button>
                        <input type="hidden" name="foto_base64" id="fotoBase64">
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-light btn-flat" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-flat px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    {{-- @include('layouts.footer') --}}

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const uploadInputModal = document.getElementById('uploadFotoProfilModal');
        const previewImgModal = document.getElementById('previewFoto');
        const fotoBase64Input = document.getElementById('fotoBase64');
        const changeImageBtn = document.getElementById('changeImageBtn');

        changeImageBtn.addEventListener('click', function() {
            uploadInputModal.click();
        });

        uploadInputModal.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImgModal.src = event.target.result;
                    fotoBase64Input.value = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>


</body>

</html>
