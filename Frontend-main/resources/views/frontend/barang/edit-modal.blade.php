<!-- Modal Edit Barang (Refactored) -->
<div class="modal fade" id="modalEditBarang{{ $barang['id'] }}" tabindex="-1" aria-labelledby="modalEditBarangLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEditBarang{{ $barang['id'] }}" method="POST" class="form-edit-barang" data-parsley-validate>
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" value="{{ $barang['id'] }}">

                    <div class="col-md-6">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="barang_nama" class="form-control" required
                            value="{{ $barang['barang_nama'] }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Harga Barang</label>
                        <input type="number" name="barang_harga" class="form-control" required
                            value="{{ $barang['barang_harga'] }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="barangcategory_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori_barangs as $category)
                                <option value="{{ $category['id'] }}"
                                    {{ $category['id'] == $barang['barangcategory_id'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Barang</label>
                        <select name="jenisbarang_id" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($jenis_barangs as $jenis)
                                <option value="{{ $jenis['id'] }}"
                                    {{ $jenis['id'] == $barang['jenisbarang_id'] ? 'selected' : '' }}>
                                    {{ $jenis['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gambar Barang</label>
                        <div id="dropArea{{ $barang['id'] }}" class="border rounded p-3 text-center drop-area"
                            data-id="{{ $barang['id'] }}">
                            <p class="m-0">Seret gambar ke sini atau klik untuk unggah</p>
                            <input type="file" id="inputFile{{ $barang['id'] }}" class="file-input d-none"
                                accept="image/*">
                        </div>
                        <small id="statusGambar{{ $barang['id'] }}" class="text-muted d-block mt-1"></small>
                        <div id="previewContainer{{ $barang['id'] }}"
                            class="mt-2 {{ $barang['barang_gambar'] ? '' : 'd-none' }}">
                            <img id="previewGambar{{ $barang['id'] }}" src="{{ asset($barang['barang_gambar']) }}"
                                class="img-thumbnail" style="max-height:200px;">
                        </div>
                        <input type="hidden" id="base64Input{{ $barang['id'] }}" name="barang_gambar"
                            value="{{ $barang['barang_gambar'] }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Satuan</label>
                        <select name="satuan_id" class="form-select" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach ($satuans as $satuan)
                                <option value="{{ $satuan['id'] }}"
                                    {{ $satuan['id'] == $barang['satuan_id'] ? 'selected' : '' }}>
                                    {{ $satuan['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="progress d-none" id="progressBar{{ $barang['id'] }}" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%">
                                Menyimpan...</div>
                        </div>
                        <div id="alertBox{{ $barang['id'] }}" class="alert alert-danger mt-3 d-none" role="alert">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const id = '{{ $barang['id'] }}';
        const form = document.getElementById(`formEditBarang${id}`);
        const dropArea = document.getElementById(`dropArea${id}`);
        const inputFile = document.getElementById(`inputFile${id}`);
        const inputBase64 = document.getElementById(`base64Input${id}`);
        const gambarStatus = document.getElementById(`statusGambar${id}`);
        const gambarPreview = document.getElementById(`previewGambar${id}`);
        const gambarPreviewContainer = document.getElementById(`previewContainer${id}`);
        const progressBar = document.getElementById(`progressBar${id}`);
        const alertBox = document.getElementById(`alertBox${id}`);

        const showProgress = () => progressBar.classList.remove('d-none');
        const hideProgress = () => progressBar.classList.add('d-none');
        const showAlert = (msg, type = 'danger') => {
            alertBox.className = `alert alert-${type} mt-3`;
            alertBox.textContent = msg;
            alertBox.classList.remove('d-none');
        };

        dropArea.addEventListener('click', () => inputFile.click());
        ['dragenter', 'dragover'].forEach(evt => dropArea.addEventListener(evt, e => {
            e.preventDefault();
            dropArea.classList.add('bg-light');
        }));
        ['dragleave', 'drop'].forEach(evt => dropArea.addEventListener(evt, e => {
            e.preventDefault();
            dropArea.classList.remove('bg-light');
        }));
        dropArea.addEventListener('drop', e => handleImage(e.dataTransfer.files[0]));
        inputFile.addEventListener('change', () => handleImage(inputFile.files[0]));

        let gambarDiubah = false; // Flag ini akan true jika user upload gambar baru

        function handleImage(file) {
            if (!file || file.size > 2 * 1024 * 1024) {
                gambarStatus.textContent = 'Ukuran maksimal 2MB';
                return;
            }
            showProgress();
            gambarStatus.textContent = 'Mengompres gambar...';

            const reader = new FileReader();
            reader.onload = e => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const maxW = 600,
                        scale = maxW / img.width;
                    canvas.width = maxW;
                    canvas.height = img.height * scale;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    const base64 = canvas.toDataURL('image/jpeg', 0.7);
                    inputBase64.value = base64;
                    gambarPreview.src = base64;
                    gambarPreviewContainer.classList.remove('d-none');
                    gambarStatus.textContent = 'Gambar siap disimpan';
                    hideProgress();
                    gambarDiubah = true; // flag jadi true
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        form.addEventListener('submit', e => {
            e.preventDefault();
            if (!$(form).parsley().isValid()) return;
            showProgress();

            const formData = {
                barang_nama: form.barang_nama.value,
                barang_harga: form.barang_harga.value,
                barangcategory_id: form.barangcategory_id.value,
                jenisbarang_id: form.jenisbarang_id.value,
                satuan_id: form.satuan_id.value,
                ...(gambarDiubah ? {
                    barang_gambar: inputBase64.value
                } : {})
            };


            axios.put(`{{ route('barangs.update', $barang['id']) }}`, formData, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }).then(() => {
                hideProgress();
                const modalEl = document.getElementById(`modalEditBarang${id}`);
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                modalEl.addEventListener('hidden.bs.modal', () => location.reload());
                modalInstance.hide();

            }).catch(err => {
                hideProgress();
                if (err.response && err.response.data && typeof err.response.data ===
                    'object') {
                    showAlert(err.response.data.error || 'Terjadi kesalahan.');
                } else {
                    showAlert('Response error: ' + err.message);
                    console.log('Full Error:', err);
                }
            });
        });
    });
</script>
