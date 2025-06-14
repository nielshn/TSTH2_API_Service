@extends('frontend.profile.layout.template')
@section('contentprofile')
    <!-- Card Profil -->
    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-4 p-4 ">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-muted form-label mb-0">
                    <i class="bi bi-person-circle me-2 text-muted text-primary form-label"></i> Profil Pengguna
                </h5>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label text-muted">Nama Lengkap</label>
                    <div class="form-control bg-light border-0 shadow-sm">{{ $user['name'] }}</div>
                </div>
                <div class="col-md-12">
                    <label class="form-label text-muted">Email</label>
                    <div class="form-control bg-light border-0 shadow-sm">{{ $user['email'] }}</div>
                    <small class="text-muted mt-1 d-block">Klik tombol di bawah untuk mengganti email.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label text-muted">Nomor Telepon</label>
                    <div class="form-control bg-light border-0 shadow-sm">{{ $user['phone'] ?? '-' }}</div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button class="btn btn-primary rounded-pill px-4 me-2" data-bs-toggle="modal"
                    data-bs-target="#editInfoModal">
                    <i class="bi bi-pencil me-1"></i> Edit Profil
                </button>
                <button class="btn btn-outline-dark rounded-pill px-4" data-bs-toggle="modal"
                    data-bs-target="#changeEmailModal">
                    <i class="bi bi-envelope me-1"></i> Ubah Email
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editInfoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form method="POST" action="{{ route('profile.update-user') }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header text-muted text-primary form-label border-0 form-label">
                        <h5 class="modal-title text-muted "><i class="bi bi-pencil-square me-1 text-primary"></i> Edit
                            Profil</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light border-0 shadow-sm" name="name"
                                    value="{{ $user['name'] }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control bg-light border-0 shadow-sm" name="phone"
                                    value="{{ $user['phone'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4">Simpan</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form method="POST" action="{{ route('profile.update-email') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header text-muted text-primary form-label border-0">
                        <h5 class="modal-title"><i class="bi bi-envelope-at me-1 text-warning"></i> Ubah Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Email Baru</label>
                        <input type="email" class="form-control bg-light border-0 shadow-sm" name="new_email"
                            placeholder="nama@email.com" required>
                        <small class="text-muted mt-1 d-block">Kami akan mengirimkan konfirmasi ke email baru.</small>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
