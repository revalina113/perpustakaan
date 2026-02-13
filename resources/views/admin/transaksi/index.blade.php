@extends('layouts.admin')

@section('title', 'Data Transaksi - PERPUSTAKAAN')

@section('content')



    <!-- Main Content -->
    <div class="pt-0 px-4 sm:px-6 lg:px-8 mb-0">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button onclick="toggleSidebar()" class="fixed top-4 left-4 z-50 bg-gray-800 text-white p-2 rounded-lg shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Header -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Data Transaksi</h2>
                        <p class="text-gray-600 mt-1">Kelola transaksi peminjaman dan pengembalian buku</p>
                        <p class="text-xs text-green-600 mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Update otomatis setiap 30 detik
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.transaksi.create') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Transaksi
                        </a>
                        <a href="{{ route('admin.transaksi.export') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Export Excel
                        </a>
                        <div class="text-4xl">📋</div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Transaksi -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalTransaksi }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sedang Dipinjam -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $sedangDipinjam }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sudah Dikembalikan -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Sudah Dikembalikan</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $sudahDikembalikan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Terlambat -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Terlambat</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $terlambat }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="search" placeholder="Cari nama siswa atau judul buku..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               onkeyup="filterTransactions()">
                    </div>
                    <div class="sm:w-48">
                        <select id="statusFilter" onchange="filterTransactions()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="dikembalikan">Dikembalikan</option>
                            <option value="terlambat">Terlambat</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Transaksi</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsTable" class="bg-white divide-y divide-gray-200">
                            @foreach($peminjaman as $index => $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $peminjaman->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $p->anggota->nama ?? 'Anggota tidak ditemukan' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->buku->judul ?? 'Buku tidak tersedia' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('Y-m-d') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($p->status === 'dikembalikan')
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Dikembalikan</span>
                                    @elseif($p->status === 'dipinjam')
                                        @if($p->hari_terlambat > 0)
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Terlambat {{ $p->hari_terlambat }} hari</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Dipinjam</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">{{ ucfirst($p->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($p->status === 'dipinjam')
                                        <button class="mark-returned text-green-600 hover:text-green-900" data-id="{{ $p->id }}" title="Tandai Dikembalikan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <a href="/admin/transaksi/{{ $p->id }}/edit" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @endif
                                        <button class="view-detail text-blue-600 hover:text-blue-900" data-id="{{ $p->id }}" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        @if($p->status === 'dikembalikan')
                                        <button class="delete-transaction text-red-600 hover:text-red-900" data-id="{{ $p->id }}" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $peminjaman->firstItem() }}</span> sampai <span class="font-medium">{{ $peminjaman->lastItem() }}</span> dari <span class="font-medium">{{ $peminjaman->total() }}</span> hasil
                        </div>
                        <div class="flex space-x-2">
                            {{ $peminjaman->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
// Transaksi page JS (moved here)
function filterTransactions() {
    const query = document.getElementById('search').value;
    const status = document.getElementById('statusFilter').value;
    fetch(`/admin/transaksi?search=${encodeURIComponent(query)}&status=${encodeURIComponent(status)}`)
        .then(res => res.json())
        .then(data => { if (data.success) updateTransactionsTable(data.data); })
        .catch(e => console.error(e));
}

function updateTransactionsTable(transactions) {
    const tbody = document.getElementById('transactionsTable');
    if (!tbody) return;
    tbody.innerHTML = '';
    (transactions.data || []).forEach((p, idx) => {
        const rowNumber = (transactions.from || 0) + idx;
        const namaAnggota = (p.anggota && p.anggota.nama) || 'Anggota tidak ditemukan';
        const judulBuku = (p.buku && p.buku.judul) || 'Buku tidak tersedia';
        const tanggalKembali = p.tanggal_kembali ? new Date(p.tanggal_kembali).toLocaleDateString('id-ID') : '-';
        let statusBadge = '';
        if (p.status === 'dikembalikan') {
            statusBadge = `<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Dikembalikan</span>`;
        } else if (p.status === 'dipinjam') {
            statusBadge = p.hari_terlambat > 0 ? `<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Terlambat ${p.hari_terlambat} hari</span>` : `<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Dipinjam</span>`;
        } else {
            statusBadge = `<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">${(p.status||'').charAt(0).toUpperCase() + (p.status||'').slice(1)}</span>`;
        }

        let actionButtons = '';
        if (p.status === 'dipinjam') {
            actionButtons += `<button class="mark-returned text-green-600 hover:text-green-900" data-id="${p.id}" title="Tandai Dikembalikan"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg></button>`;
            actionButtons += `<a href="/admin/transaksi/${p.id}/edit" class="text-yellow-600 hover:text-yellow-900" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>`;
        }
        actionButtons += `<button class="view-detail text-blue-600 hover:text-blue-900 ml-2" data-id="${p.id}" title="Lihat Detail"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>`;
        if (p.status === 'dikembalikan') {
            actionButtons += `<button class="delete-transaction text-red-600 hover:text-red-900 ml-2" data-id="${p.id}" title="Hapus"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>`;
        }

        const row = `<tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${rowNumber}</td><td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${namaAnggota}</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${judulBuku}</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(p.tanggal_pinjam).toLocaleDateString('id-ID')}</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${tanggalKembali}</td><td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td><td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><div class="flex space-x-2">${actionButtons}</div></td></tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
    });
    attachEventListeners();
    updatePaginationInfo(transactions);
}

function updatePaginationInfo(transactions) {
    const paginationInfo = document.querySelector('.text-sm.text-gray-700');
    if (paginationInfo) {
        paginationInfo.innerHTML = `Menampilkan <span class="font-medium">${transactions.from || 0}</span> sampai <span class="font-medium">${transactions.to || 0}</span> dari <span class="font-medium">${transactions.total || 0}</span> hasil`;
    }
    const paginationContainer = document.querySelector('.flex.space-x-2');
    if (paginationContainer && transactions.links) paginationContainer.innerHTML = generatePaginationLinks(transactions);
}

function generatePaginationLinks(transactions) {
    let links = '';
    if (transactions.prev_page_url) links += `<a href="#" onclick="loadPage('${transactions.prev_page_url}')" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">Previous</a>`;
    if (transactions.next_page_url) links += `<a href="#" onclick="loadPage('${transactions.next_page_url}')" class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">Next</a>`;
    return links;
}

function loadPage(url) { fetch(url).then(r=>r.json()).then(d=>{ if(d.success) updateTransactionsTable(d.data); }).catch(e=>console.error(e)); }

function attachEventListeners() {
    document.querySelectorAll('.mark-returned').forEach(b=>b.addEventListener('click',function(){ const id=this.getAttribute('data-id'); markAsReturned(id); }));
    document.querySelectorAll('.view-detail').forEach(b=>b.addEventListener('click',function(){ const id=this.getAttribute('data-id'); viewDetail(id); }));
    document.querySelectorAll('.delete-transaction').forEach(b=>b.addEventListener('click',function(){ const id=this.getAttribute('data-id'); deleteTransaction(id); }));
}

let autoRefreshInterval;
function startAutoRefresh(){ autoRefreshInterval=setInterval(()=>{ const searchValue=document.getElementById('search').value; const statusValue=document.getElementById('statusFilter').value; if(!searchValue && !statusValue) filterTransactions(); },30000); }
function stopAutoRefresh(){ if(autoRefreshInterval) clearInterval(autoRefreshInterval); }

document.addEventListener('DOMContentLoaded', function() { attachEventListeners(); startAutoRefresh(); document.getElementById('search').addEventListener('input', stopAutoRefresh); document.getElementById('statusFilter').addEventListener('change', stopAutoRefresh); });
</script>
@endpush