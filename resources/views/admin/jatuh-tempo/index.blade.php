@extends('layouts.admin')

@section('title', 'Jatuh Tempo & Denda - Admin')

@section('content')

<!-- Header -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Jatuh Tempo & Denda</h1>
            <p class="text-gray-600 mt-2">Kelola peminjaman yang terlambat dan verifikasi pembayaran denda</p>
        </div>
        @if($aturan)
        <div class="text-right bg-blue-50 p-4 rounded-lg border border-blue-200">
            <p class="text-sm text-blue-800">
                <strong>💰 Denda per Hari:</strong><br>
                Rp{{ number_format($aturan->denda_per_hari, 0, ',', '.') }}
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Filter dan Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" id="searchInput" placeholder="Cari nama siswa atau judul buku..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="md:w-48">
            <select id="paymentStatusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status Pembayaran</option>
                <option value="belum_bayar">Belum Bayar</option>
                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                <option value="lunas">Lunas</option>
            </select>
        </div>
    </div>
</div>

    <!-- Tabel Peminjaman Terlambat -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Siswa</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Buku</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Jatuh Tempo</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Terlambat</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total Denda</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status Pembayaran</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($peminjamanTerlambat as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->anggota->nama }}</div>
                        <div class="text-xs text-gray-500">{{ $item->anggota->nis }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $item->buku?->judul ?? 'DELETED' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->buku?->penulis ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->tanggal_jatuh_tempo?->format('d/m/Y') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                            {{ $item->hari_terlambat }} hari
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                        Rp{{ number_format($item->denda, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $payment = $item->pembayaranDenda()->latest()->first();
                            $status = $payment ? $payment->status_pembayaran : 'belum_bayar';
                        @endphp
                        @if($status === 'menunggu_verifikasi')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                ⏳ Menunggu Verifikasi
                            </span>
                        @elseif($status === 'lunas')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                ✓ Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                ⚠ Belum Bayar
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewDetails('{{ $item->id }}')" 
                                class="text-blue-600 hover:text-blue-800 hover:underline">
                            Detail
                        </button>
                        @if($payment && $payment->status_pembayaran == 'menunggu_verifikasi')
                            <span class="text-gray-300 mx-2">|</span>
                            <a href="{{ route('admin.pembayaran-denda.show', $payment->id) }}" 
                               class="text-purple-600 hover:text-purple-800 hover:underline">
                                Verifikasi
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">Tidak ada peminjaman yang terlambat</p>
                            <p class="text-sm mt-1">Semua siswa telah mengembalikan buku tepat waktu</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

<!-- Pagination -->
@if($peminjamanTerlambat->hasPages())
<div class="mt-6">
    {{ $peminjamanTerlambat->links() }}
</div>
@endif

@endsection

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full md:w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Detail Peminjaman</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div id="detailContent" class="space-y-4">
            <div class="animate-spin flex justify-center">
                <div class="animate-pulse">Loading...</div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPeminjamanId = null;

    function viewDetails(peminjamanId) {
        currentPeminjamanId = peminjamanId;
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');

        // Show modal
        modal.classList.remove('hidden');

        // Fetch details
        fetch(`{{ route('admin.jatuh-tempo.show', '') }}/${peminjamanId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const p = data.data.peminjaman;
                    const pembayaran = data.data.pembayaran;
                    let html = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                                <p class="text-gray-900 font-semibold">${p.anggota.nama}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIS</label>
                                <p class="text-gray-900 font-semibold">${p.anggota.nis}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                                <p class="text-gray-900 font-semibold">${p.buku?.judul || 'DELETED'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penulis</label>
                                <p class="text-gray-900 font-semibold">${p.buku?.penulis || '-'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                                <p class="text-gray-900 font-semibold">${new Date(p.tanggal_pinjam).toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric'})}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo</label>
                                <p class="text-gray-900 font-semibold">${new Date(p.tanggal_jatuh_tempo).toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric'})}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hari Terlambat</label>
                                <p class="text-red-600 font-semibold">${data.data.hari_terlambat} hari</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Denda per Hari</label>
                                <p class="text-gray-900 font-semibold">Rp${data.data.denda_per_hari.toLocaleString('id-ID')}</p>
                            </div>
                            <div class="col-span-2 bg-red-50 p-4 rounded-lg border border-red-200">
                                <label class="block text-sm font-medium text-red-800">Total Denda</label>
                                <p class="text-2xl font-bold text-red-600">Rp${data.data.total_denda.toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    `;

                    if (pembayaran) {
                        html += `
                            <div class="mt-6 border-t pt-6">
                                <h4 class="font-semibold text-gray-900 mb-4">Status Pembayaran</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <p class="text-gray-900 font-semibold">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                ${pembayaran.status_pembayaran === 'menunggu_verifikasi' ? 'bg-yellow-100 text-yellow-800' : ''}
                                                ${pembayaran.status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : ''}
                                                ${pembayaran.status_pembayaran === 'ditolak' ? 'bg-red-100 text-red-800' : ''}
                                            ">
                                                ${pembayaran.status_pembayaran}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Upload</label>
                                        <p class="text-gray-900">${new Date(pembayaran.created_at).toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
                                        <a href="{{ route('admin.jatuh-tempo.bukti', '') }}/${pembayaran.id}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Lihat Bukti
                                        </a>
                                    </div>
                                    ${pembayaran.status_pembayaran === 'menunggu_verifikasi' ? `
                                    <div class="mt-6 pt-4 border-t">
                                        <a href="{{ route('admin.pembayaran-denda.show', '') }}/${pembayaran.id}" 
                                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            ✓ Verifikasi Pembayaran
                                        </a>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="mt-6 border-t pt-6 bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600">Belum ada bukti pembayaran yang dikirimkan</p>
                            </div>
                        `;
                    }

                    content.innerHTML = html;
                } else {
                    content.innerHTML = '<p class="text-red-600">Error loading details</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<p class="text-red-600">Error loading details</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    // Filter functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const search = e.target.value;
        const status = document.getElementById('paymentStatusFilter').value;
        updateTable(search, status);
    });

    document.getElementById('paymentStatusFilter').addEventListener('change', function(e) {
        const search = document.getElementById('searchInput').value;
        const status = e.target.value;
        updateTable(search, status);
    });

    function updateTable(search, status) {
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('payment_status', status);

        window.location.href = `{{ route('admin.jatuh-tempo.index') }}?${params.toString()}`;
    }
</script>
