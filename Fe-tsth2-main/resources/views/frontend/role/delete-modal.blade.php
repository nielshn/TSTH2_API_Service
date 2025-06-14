<div class="modal fade" id="deleteRoleModal{{ $role['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('roles.destroy', $role['id']) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Hapus Jenis Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus <strong>{{ $role['name'] }}</strong>?</p>
                <p class="text-danger"><small>Data yang sudah dihapus tidak bisa dikembalikan.</small></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="submit">Ya, Hapus</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
            </div>
        </form>
    </div>
</div>
