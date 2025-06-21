<div class="modal fade" id="deleteUserModal{{ $user['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('users.destroy', $user['id']) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus User</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus <strong>{{ $user['name'] }}</strong>?</p>
                <p class="text-danger"><small>Data yang sudah dihapus tidak bisa dikembalikan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
