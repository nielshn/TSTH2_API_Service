<div class="modal fade" id="detailUserModal{{ $user['id'] }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <p><strong>Nama:</strong> {{ $user['name'] }}</p>
                <p><strong>Email:</strong> {{ $user['email'] }}</p>
                <p><strong>Role:</strong> {{ $user['roles'][0] ?? '-' }}</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
