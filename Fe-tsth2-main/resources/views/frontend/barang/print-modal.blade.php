<div class="modal fade" id="modalprintBarang{{ $barang['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('barangs.exportPDF', $barang['id']) }}" method="GET" class="modal-content" target="_blank">
            <div class="modal-header">
                <h5 class="modal-title">Print QR Code <strong>{{ $barang['barang_kode'] }}</strong></h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">QR Code</p>
                @php
                    $qrCodeBaseUrl = rtrim(config('api.qr_code'), '/') . '/qr_code/';
                    $qrCodeFormats = ['png', 'jpg', 'jpeg'];
                    $qrCodeUrl = null;

                    foreach ($qrCodeFormats as $format) {
                        $tempUrl = $qrCodeBaseUrl . $barang['barang_kode'] . '.' . $format;
                        $headers = @get_headers($tempUrl);
                        if ($headers && strpos($headers[0], '200')) {
                            $qrCodeUrl = $tempUrl;
                            break;
                        }
                    }
                @endphp
                @if ($qrCodeUrl)
                    <img src="{{ $qrCodeUrl }}" class="img-fluid img-thumbnail"
                        style="max-width: 100px; max-height: 100px;" alt="QR Code">
                @else
                    <p class="text-muted">Tidak tersedia</p>
                @endif
            </div>
            <div class="modal-body">
                <label>Jumlah</label>
                <input type="text" name="jumlah" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Print</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
            </div>
        </form>
    </div>
</div>
