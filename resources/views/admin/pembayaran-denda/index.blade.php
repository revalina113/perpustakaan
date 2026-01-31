@extends('layouts.app')

@section('title', 'Kelola Pembayaran Denda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Kelola Pembayaran Denda</h1>
        </div>

        <!-- Filter dan Search -->
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari nama siswa atau judul buku..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="md:w-48">
                <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                    <option value="lunas">Lunas</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>

        <!-- Tabel Pembayaran Denda -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pembayaranTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan dimuat via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-6">
            <!-- Pagination akan dimuat via AJAX -->
        </div>
    </div>
</div>

<!-- Modal Detail Pembayaran -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Pembayaran Denda</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="detailContent">
                <!-- Detail akan dimuat via AJAX -->
            </div>
            <div class="flex justify-end space-x-3 mt-6" id="actionButtons">
                <!-- Tombol aksi akan dimuat via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Bukti Pembayaran -->
<div id="buktiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Bukti Pembayaran</h3>
                <button onclick="closeBuktiModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="buktiContent" class="text-center">
                <!-- Bukti pembayaran akan dimuat via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentSearch = '';
let currentStatus = '';

document.addEventListener('DOMContentLoaded', function() {
    loadPembayaranData();

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', debounce(function() {
        currentSearch = this.value;
        currentPage = 1;
        loadPembayaranData();
    }, 500));

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        currentStatus = this.value;
        currentPage = 1;
        loadPembayaranData();
    });
});

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function loadPembayaranData() {
    const url = `/admin/pembayaran-denda?page=${currentPage}&search=${encodeURIComponent(currentSearch)}&status=${currentStatus}`;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        renderTable(data.data);
        renderPagination(data);
    })
    .catch(error => {
        console.error('Error loading data:', error);
        showNotification('Gagal memuat data pembayaran denda', 'error');
    });
}

function renderTable(pembayaranList) {
    const tbody = document.getElementById('pembayaranTableBody');

    if (pembayaranList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada data pembayaran denda ditemukan
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = pembayaranList.map(pembayaran => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${new Date(pembayaran.created_at).toLocaleDateString('id-ID')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${pembayaran.anggota.nama}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${pembayaran.peminjaman.buku.judul}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                Rp ${pembayaran.jumlah_denda.toLocaleString('id-ID')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(pembayaran.status_pembayaran)}">
                    ${getStatusText(pembayaran.status_pembayaran)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button onclick="showDetail(${pembayaran.id})"
                        class="text-blue-600 hover:text-blue-900">
                    Detail
                </button>
                ${pembayaran.status_pembayaran === 'menunggu_verifikasi' ? `
                    <button onclick="verifikasiPembayaran(${pembayaran.id})"
                            class="text-green-600 hover:text-green-900">
                        Setujui
                    </button>
                    <button onclick="tolakPembayaran(${pembayaran.id})"
                            class="text-red-600 hover:text-red-900">
                        Tolak
                    </button>
                ` : ''}
            </td>
        </tr>
    `).join('');
}

function renderPagination(data) {
    const container = document.getElementById('paginationContainer');
    const { current_page, last_page, per_page, total } = data;

    if (last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let paginationHtml = '<div class="flex justify-between items-center">';

    // Info
    paginationHtml += `<div class="text-sm text-gray-700">
        Menampilkan ${((current_page - 1) * per_page) + 1} sampai ${Math.min(current_page * per_page, total)} dari ${total} hasil
    </div>`;

    // Navigation
    paginationHtml += '<div class="flex space-x-1">';

    // Previous
    if (current_page > 1) {
        paginationHtml += `<button onclick="changePage(${current_page - 1})" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50">Sebelumnya</button>`;
    }

    // Page numbers
    for (let i = Math.max(1, current_page - 2); i <= Math.min(last_page, current_page + 2); i++) {
        paginationHtml += `<button onclick="changePage(${i})"
            class="px-3 py-1 text-sm border ${i === current_page ? 'bg-blue-500 text-white border-blue-500' : 'border-gray-300 hover:bg-gray-50'} rounded">
            ${i}
        </button>`;
    }

    // Next
    if (current_page < last_page) {
        paginationHtml += `<button onclick="changePage(${current_page + 1})" class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50">Selanjutnya</button>`;
    }

    paginationHtml += '</div></div>';

    container.innerHTML = paginationHtml;
}

function changePage(page) {
    currentPage = page;
    loadPembayaranData();
}

function getStatusBadgeClass(status) {
    switch (status) {
        case 'menunggu_verifikasi':
            return 'bg-yellow-100 text-yellow-800';
        case 'lunas':
            return 'bg-green-100 text-green-800';
        case 'ditolak':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'menunggu_verifikasi':
            return 'Menunggu Verifikasi';
        case 'lunas':
            return 'Lunas';
        case 'ditolak':
            return 'Ditolak';
        default:
            return status;
    }
}

function showDetail(id) {
    fetch(`/admin/pembayaran-denda/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderDetailModal(data.data);
            document.getElementById('detailModal').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error loading detail:', error);
        showNotification('Gagal memuat detail pembayaran', 'error');
    });
}

function renderDetailModal(pembayaran) {
    const content = document.getElementById('detailContent');
    const actionButtons = document.getElementById('actionButtons');

    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pembayaran</label>
                    <p class="mt-1 text-sm text-gray-900">${new Date(pembayaran.created_at).toLocaleString('id-ID')}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(pembayaran.status_pembayaran)}">
                            ${getStatusText(pembayaran.status_pembayaran)}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Siswa</label>
                    <p class="mt-1 text-sm text-gray-900">${pembayaran.anggota.nama}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <p class="mt-1 text-sm text-gray-900">${pembayaran.anggota.kelas}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Buku</label>
                    <p class="mt-1 text-sm text-gray-900">${pembayaran.peminjaman.buku.judul}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Denda</label>
                    <p class="mt-1 text-sm text-gray-900">Rp ${pembayaran.jumlah_denda.toLocaleString('id-ID')}</p>
                </div>
            </div>
            ${pembayaran.bukti_pembayaran ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
                    <button onclick="showBuktiPembayaran('${pembayaran.bukti_pembayaran}')"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Bukti
                    </button>
                </div>
            ` : ''}
            ${pembayaran.catatan_admin ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                    <p class="mt-1 text-sm text-gray-900">${pembayaran.catatan_admin}</p>
                </div>
            ` : ''}
        </div>
    `;

    if (pembayaran.status_pembayaran === 'menunggu_verifikasi') {
        actionButtons.innerHTML = `
            <button onclick="verifikasiPembayaran(${pembayaran.id})"
                    class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600">
                Setujui Pembayaran
            </button>
            <button onclick="tolakPembayaran(${pembayaran.id})"
                    class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600">
                Tolak Pembayaran
            </button>
            <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600">
                Tutup
            </button>
        `;
    } else {
        actionButtons.innerHTML = `
            <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600">
                Tutup
            </button>
        `;
    }
}

function showBuktiPembayaran(buktiPath) {
    const content = document.getElementById('buktiContent');
    content.innerHTML = `
        <img src="/storage/${buktiPath}" alt="Bukti Pembayaran" class="max-w-full h-auto mx-auto rounded-lg shadow-lg">
    `;
    document.getElementById('buktiModal').classList.remove('hidden');
}

function closeBuktiModal() {
    document.getElementById('buktiModal').classList.add('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function verifikasiPembayaran(id) {
    if (!confirm('Apakah Anda yakin ingin menyetujui pembayaran denda ini?')) {
        return;
    }

    fetch(`/admin/pembayaran-denda/${id}/verifikasi`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeDetailModal();
            loadPembayaranData();
        } else {
            showNotification('Gagal memverifikasi pembayaran', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memverifikasi pembayaran', 'error');
    });
}

function tolakPembayaran(id) {
    const catatan = prompt('Masukkan alasan penolakan:');
    if (!catatan) return;

    fetch(`/admin/pembayaran-denda/${id}/tolak`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ catatan_admin: catatan })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeDetailModal();
            loadPembayaranData();
        } else {
            showNotification('Gagal menolak pembayaran', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menolak pembayaran', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Simple notification - you can replace with a proper notification library
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection