<div class="modal fade" id="createGudangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('gudangs.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Gudang</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label for="user_id">Pilih Operator</label>
                    <select name="user_id" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Operator --</option>
                        @foreach ($operators as $operator)
                            <option value="{{ $operator['id'] }}"
                                {{ old('user_id') == $operator['id'] ? 'selected' : '' }}>{{ $operator['name'] }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('user_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('user_id') }}
                        </div>
                    @endif
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-success">Tambah</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
