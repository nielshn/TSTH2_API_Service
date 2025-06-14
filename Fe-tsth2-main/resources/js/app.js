
import './bootstrap';
import { createApp } from "vue";

import App from "./components/App.vue";

createApp(App).mount("#app");

import Pusher from 'pusher-js';
import axios from 'axios';

// Aktifkan logging Pusher untuk debugging
Pusher.logToConsole = true;

// Inisialisasi Pusher dengan konfigurasi dari .env
const pusher = new Pusher(import.meta.env.VITE_REVERB_APP_KEY, {
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: import.meta.env.VITE_REVERB_SCHEME === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

// Subscribe ke channel barang-categories
const channel = pusher.subscribe('barang-categories');

// Tangani event barang-category.created
channel.bind('barang-category.created', function (data) {
    console.log('Received barang-category.created event:', data);
    addBarangCategoryToTable(data);
});

// Tangani event barang-category.updated
channel.bind('barang-category.updated', function (data) {
    console.log('Received barang-category.updated event:', data);
    updateBarangCategoryInTable(data);
});

// Tangani event barang-category.deleted
channel.bind('barang-category.deleted', function (data) {
    console.log('Received barang-category.deleted event:', data);
    removeBarangCategoryFromTable(data.id);
});

// Fungsi untuk menambah baris ke tabel
function addBarangCategoryToTable(category) {
    const table = document.querySelector('.datatable-button-html5-basic tbody');
    if (!table) return;

    const index = table.querySelectorAll('tr').length + 1;
    const row = document.createElement('tr');
    row.setAttribute('data-id', category.id);
    row.innerHTML = `
        <td>${index}</td>
        <td>${category.name}</td>
        <td>
            <div class="d-inline-flex">
                <a href="#" class="text-info me-2" data-bs-toggle="modal" data-bs-target="#detailBarangCategoryModal${category.id}">
                    <i class="ph-eye"></i>
                </a>
                <a href="#" class="text-warning me-2" data-bs-toggle="modal" data-bs-target="#editBarangCategoryModal${category.id}">
                    <i class="ph-pencil"></i>
                </a>
                <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteBarangCategoryModal${category.id}">
                    <i class="ph-trash"></i>
                </a>
            </div>
        </td>
    `;
    table.appendChild(row);

    // Tambahkan modal dinamis (opsional)
    addDynamicModals(category);
}

// Fungsi untuk memperbarui baris di tabel
function updateBarangCategoryInTable(category) {
    const row = document.querySelector(`tr[data-id="${category.id}"]`);
    if (row) {
        row.cells[1].textContent = category.name;
        // Perbarui modal edit jika terbuka
        const editInput = document.querySelector(`#editBarangCategoryModal${category.id} input[name="name"]`);
        if (editInput) editInput.value = category.name;
    }
}

// Fungsi untuk menghapus baris dari tabel
function removeBarangCategoryFromTable(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        row.remove();
        console.log(`Removed barang category row with ID ${id}`);
        // Perbarui nomor urut
        updateTableIndices();
    }
}

// Fungsi untuk memperbarui nomor urut di tabel
function updateTableIndices() {
    const rows = document.querySelectorAll('.datatable-button-html5-basic tbody tr');
    rows.forEach((row, index) => {
        row.cells[0].textContent = index + 1;
    });
}

// Fungsi untuk menambah modal dinamis (opsional)
function addDynamicModals(category) {
    // Implementasi jika modal detail, edit, delete perlu ditambahkan secara dinamis
    // Misalnya, append modal ke document.body
}
