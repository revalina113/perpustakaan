@extends('layouts.admin')

@section('title', 'Dashboard Admin - PERPUSTAKAAN')

@section('content')
<!-- Welcome Card -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }} 👋</h2>
            <p class="text-gray-600 mt-1">Kamu login sebagai Admin</p>
        </div>
        <div class="text-4xl">📚</div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Buku -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Buku</p>
                <p class="text-3xl font-bold text-gray-900">{{ $total_buku ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Total Siswa -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1M9 9a4 4 0 100-8 4 4 0 000 8zM21 9a4 4 0 100-8 4 4 0 000 8z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                <p class="text-3xl font-bold text-gray-900">{{ $total_siswa ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Total Peminjaman -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Peminjaman</p>
                <p class="text-3xl font-bold text-gray-900">{{ $total_peminjaman ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Siswa Terlambat -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Siswa Terlambat</p>
                <p class="text-3xl font-bold text-red-600">{{ $total_siswa_terlambat ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Siswa Terlambat -->
@if($total_siswa_terlambat > 0)
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
    <div class="bg-red-50 px-6 py-4 border-b border-red-200">
        <h3 class="text-lg font-semibold text-red-800">⚠️ Daftar Siswa dengan Peminjaman Terlambat</h3>
        <p class="text-sm text-red-600 mt-1">{{ $total_siswa_terlambat }} siswa memiliki peminjaman yang melewati tanggal jatuh tempo</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Terlambat</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($peminjamanTerlambat as $index => $pinjam)
                <tr class="hover:bg-red-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pinjam->anggota->nama ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $pinjam->buku->judul ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pinjam->tanggal_jatuh_tempo ? $pinjam->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Terlambat {{ now()->diffInDays($pinjam->tanggal_jatuh_tempo) }} hari
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white rounded-xl shadow-md p-6 text-center mb-8">
    <div class="flex justify-center mb-4">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
    <h3 class="text-lg font-semibold text-gray-800">✅ Tidak Ada Peminjaman Terlambat</h3>
    <p class="text-gray-600 mt-2">Semua siswa sudah mengembalikan buku tepat waktu atau belum melewati tanggal jatuh tempo.</p>
</div>
@endif

<!-- Menu Admin -->
<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Admin</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.buku.index') }}" class="flex items-center justify-center bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Kelola Buku
        </a>
        <a href="{{ route('admin.anggota.index') }}" class="flex items-center justify-center bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1M9 9a4 4 0 100-8 4 4 0 000 8zM21 9a4 4 0 100-8 4 4 0 000 8z"></path>
            </svg>
            Kelola Siswa
        </a>
        <a href="{{ route('admin.transaksi.index') }}" class="flex items-center justify-center bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-colors font-semibold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Data Peminjaman
        </a>
    </div>
</div>

<!-- Action Queue -->
<div class="bg-white rounded-xl shadow-md p-6 mt-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Action Queue</h3>
        <div class="text-sm text-gray-500">Menunggu verifikasi: <span class="font-semibold text-indigo-600">{{ $pending_payments_count ?? 0 }}</span></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Payments -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-semibold">Pembayaran Denda (Menunggu Verifikasi)</h4>
                <div>
                    <button id="bulkVerifyBtn" class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-sm mr-2">Verifikasi Terpilih</button>
                </div>
            </div>
            @if(!empty($pending_payments) && $pending_payments->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"><input type="checkbox" id="selectAllPending" /></th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pending_payments as $index => $pay)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900"><input type="checkbox" class="pending-checkbox" value="{{ $pay->id }}" /></td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $pay->anggota->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $pay->peminjaman->buku->judul ?? 'Buku dihapus' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($pay->jumlah, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $pay->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm text-right space-x-2">
                                <button data-id="{{ $pay->id }}" class="js-verifikasi px-3 py-1 bg-green-500 text-white rounded-lg text-sm">Verifikasi</button>
                                <button data-id="{{ $pay->id }}" class="js-tolak px-3 py-1 bg-red-500 text-white rounded-lg text-sm">Tolak</button>
                                <a href="{{ route('admin.pembayaran-denda.show', $pay->id) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-lg text-sm">Lihat</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-sm text-gray-500">Tidak ada pembayaran menunggu verifikasi.</p>
            @endif
        </div>

        <!-- Overdue Returns -->
        <div>
            <h4 class="font-semibold mb-2">Tindakan Pengembalian (Terlambat)</h4>
            @if(!empty($overdue_peminjaman) && $overdue_peminjaman->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status Terlambat</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($overdue_peminjaman as $index => $pinjam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $pinjam->anggota->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $pinjam->buku->judul ?? 'Buku dihapus' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Terlambat {{ now()->diffInDays($pinjam->tanggal_jatuh_tempo) }} hari
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-right space-x-2">
                                <button data-id="{{ $pinjam->id }}" class="js-mark-return px-3 py-1 bg-indigo-600 text-white rounded-lg text-sm">Tandai Dikembalikan</button>
                                <a href="{{ route('admin.transaksi.show', $pinjam->id) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-lg text-sm">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-sm text-gray-500">Tidak ada peminjaman terlambat untuk tindakan saat ini.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showNotification(message, type = 'info') {
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

function verifikasiPembayaran(id) {
    if (!confirm('Apakah Anda yakin ingin menyetujui pembayaran denda ini?')) return;

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
            setTimeout(() => location.reload(), 800);
        } else {
            showNotification('Gagal memverifikasi pembayaran', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memverifikasi pembayaran', 'error');
    });
}

// Bulk verification
function bulkVerifikasi(ids) {
    if (!ids || !ids.length) {
        showNotification('Pilih minimal satu pembayaran.', 'error');
        return;
    }
    if (!confirm('Verifikasi semua pembayaran yang dipilih?')) return;

    fetch('/admin/pembayaran-denda/bulk-verifikasi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showNotification('Gagal memverifikasi beberapa pembayaran', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memverifikasi pembayaran', 'error');
    });
}

// Select all handler
document.getElementById('selectAllPending')?.addEventListener('change', function(e){
    document.querySelectorAll('.pending-checkbox').forEach(cb => cb.checked = e.target.checked);
});

// Bulk verify button
document.getElementById('bulkVerifyBtn')?.addEventListener('click', function(){
    const ids = Array.from(document.querySelectorAll('.pending-checkbox:checked')).map(i => i.value);
    bulkVerifikasi(ids);
});

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
            setTimeout(() => location.reload(), 800);
        } else {
            showNotification('Gagal menolak pembayaran', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menolak pembayaran', 'error');
    });
}

function markAsReturned(id) {
    if (!confirm('Tandai transaksi ini sebagai dikembalikan?')) return;

    fetch(`/admin/transaksi/${id}/kembalikan`, {
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
            setTimeout(() => location.reload(), 800);
        } else {
            showNotification(data.message || 'Gagal menandai sebagai dikembalikan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat melakukan tindakan', 'error');
    });
}

// Delegated click handlers for buttons using data-id to avoid inline onclick and editor parse issues
document.addEventListener('click', function (e) {
    const verBtn = e.target.closest('.js-verifikasi');
    if (verBtn) {
        const id = verBtn.getAttribute('data-id');
        verifikasiPembayaran(id);
        return;
    }

    const tolakBtn = e.target.closest('.js-tolak');
    if (tolakBtn) {
        const id = tolakBtn.getAttribute('data-id');
        tolakPembayaran(id);
        return;
    }

    const markBtn = e.target.closest('.js-mark-return');
    if (markBtn) {
        const id = markBtn.getAttribute('data-id');
        markAsReturned(id);
        return;
    }
});
</script>
@endpush
