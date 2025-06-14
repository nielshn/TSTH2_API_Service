@if (count($daftarBarang) > 0)

    <div class="table-responsive mb-3">
        <table class="table table-bordered table-hover align-middle shadow-sm">
            <thead class="table-primary text-center">
                <tr>
                    <th>Nama Barang</th>
                    <th>Kode</th>
                    <th>Gambar</th>
                    <th>Kategory</th>
                    <th>Stok Tersedia</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($daftarBarang as $barang)
                    <tr id="item-{{ $barang['kode'] }}" data-barang-kode="{{ $barang['kode'] }}">
                        <td class="text-center">{{ $barang['nama'] }}</td>
                        <td class="text-center">{{ $barang['kode'] }}</td>
                        <td class="text-center">
                            <a href="{{ asset($barang['gambar']) }}" data-lightbox="gambar-{{ $barang['kode'] }}">
                                <img src="{{ asset($barang['gambar']) }}" alt="{{ $barang['gambar'] }}"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            </a>
                        </td>
                        <td class="text-center">{{ $barang['kategoribarang'] }}</td>
                        <td class="text-center">{{ $barang['stok_tersedia'] }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-danger px-2"
                                    onclick="updateQuantity('{{ $barang['kode'] }}', -1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <!-- Pastikan span jumlah memiliki kelas "quantity" -->
                                <span class="fw-bold quantity"
                                    id="jumlah-{{ $barang['kode'] }}">{{ $barang['jumlah'] }}</span>
                                <button type="button" class="btn btn-sm btn-outline-success px-2"
                                    onclick="updateQuantity('{{ $barang['kode'] }}', 1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="removeItem('{{ $barang['kode'] }}')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <!-- Tombol submit transaksi diletakkan di sini -->
        <button type="submit" class="btn btn-primary mt-4" id="submit-transaction">
            <i class="bi bi-check2-circle me-2"></i>Simpan Transaksi
        </button>
    </div>
@endif
