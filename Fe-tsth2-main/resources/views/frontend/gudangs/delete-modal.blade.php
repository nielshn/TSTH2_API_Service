<div class="modal fade" id="deleteGudangModal{{ $gudang['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('gudangs.destroy', $gudang['id']) }}" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus gudang <strong>{{ $gudang['name'] }}</strong>?</p>
                <p class="text-danger"><small>Data yang sudah dihapus tidak bisa dikembalikan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
