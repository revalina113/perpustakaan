@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran Denda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.jatuh-tempo.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-4 inline-block">
            ← Kembali ke Jatuh Tempo
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Verifikasi Pembayaran Denda</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Detail Siswa -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Siswa & Peminjaman</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $pembayaran->anggota->nama }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $pembayaran->anggota->nis }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $pembayaran->peminjaman->buku->judul ?? 'DELETED' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $pembayaran->peminjaman->buku->penulis ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                        <p class="text-gray-900">{{ $pembayaran->peminjaman->tanggal_pinjam->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo</label>
                        <p class="text-gray-900">{{ $pembayaran->peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Denda -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Denda</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg border border-red-200">
                        <span class="text-gray-700 font-medium">Hari Terlambat:</span>
                        <span class="text-2xl font-bold text-red-600">{{ $pembayaran->peminjaman->hari_terlambat }} hari</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Denda per Hari:</span>
                        <span class="text-lg font-semibold text-gray-900">Rp{{ number_format($pembayaran->jumlah_pembayaran / max($pembayaran->peminjaman->hari_terlambat, 1), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <span class="text-gray-700 font-medium text-lg">Total Denda:</span>
                        <span class="text-3xl font-bold text-blue-600">Rp{{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Bukti Pembayaran</h2>
                @if($pembayaran->bukti_pembayaran)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                         alt="Bukti Pembayaran" 
                         class="max-w-full h-auto rounded-lg border border-gray-300">
                </div>
                <div class="text-sm text-gray-600">
                    <p><strong>File:</strong> {{ basename($pembayaran->bukti_pembayaran) }}</p>
                    <p><strong>Tanggal Upload:</strong> {{ $pembayaran->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @else
                <p class="text-gray-600 text-center py-8">Tidak ada bukti pembayaran</p>
                @endif
            </div>

            <!-- Catatan Admin -->
            @if($pembayaran->catatan_admin)
            <div class="bg-yellow-50 rounded-lg shadow-md p-6 border border-yellow-200">
                <h3 class="text-lg font-semibold text-yellow-900 mb-2">Catatan Admin</h3>
                <p class="text-yellow-800">{{ $pembayaran->catatan_admin }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status Pembayaran</h3>
                <div class="mb-6">
                    @if($pembayaran->status_pembayaran === 'menunggu_verifikasi')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            ⏳ Menunggu Verifikasi
                        </span>
                    @elseif($pembayaran->status_pembayaran === 'lunas')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            ✓ Lunas
                        </span>
                    @elseif($pembayaran->status_pembayaran === 'ditolak')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            ✗ Ditolak
                        </span>
                    @endif
                </div>

                @if($pembayaran->status_pembayaran === 'menunggu_verifikasi')
                <div class="space-y-3">
                    <button onclick="openAcceptModal()" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Terima Pembayaran
                    </button>
                    <button onclick="openRejectModal()" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tolak Pembayaran
                    </button>
                </div>
                @else
                <div class="text-center">
                    <a href="{{ route('admin.jatuh-tempo.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Kembali ke Daftar
                    </a>
                </div>
                @endif
            </div>

            <!-- Info Card -->
            <div class="bg-blue-50 rounded-lg shadow-md p-6 border border-blue-200">
                <h3 class="text-lg font-bold text-blue-900 mb-4">ℹ️ Informasi</h3>
                <div class="space-y-3 text-sm text-blue-800">
                    <p>Verifikasi bukti pembayaran dengan teliti sebelum menyetujui.</p>
                    <p>Pastikan nominal pembayaran sesuai dengan total denda yang ditentukan.</p>
                    <p>Bukti pembayaran berupa foto/screenshot transfer bank.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div id="acceptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full md:w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Terima Pembayaran</h3>
            <button onclick="closeAcceptModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menerima pembayaran denda ini?</p>
        <form onsubmit="submitAccept(event)" class="space-y-4">
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                    Ya, Terima Pembayaran
                </button>
            </div>
            <div>
                <button type="button" onclick="closeAcceptModal()" class="w-full px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400 font-medium transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full md:w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Tolak Pembayaran</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form onsubmit="submitReject(event)" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                <textarea name="catatan_admin" 
                          rows="4"
                          placeholder="Jelaskan alasan penolakan pembayaran..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          required></textarea>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors">
                    Tolak Pembayaran
                </button>
            </div>
            <div>
                <button type="button" onclick="closeRejectModal()" class="w-full px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400 font-medium transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAcceptModal() {
        document.getElementById('acceptModal').classList.remove('hidden');
    }

    function closeAcceptModal() {
        document.getElementById('acceptModal').classList.add('hidden');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function submitAccept(e) {
        e.preventDefault();
        const pembayaranId = '{{ $pembayaran->id }}';
        const url = '{{ route("admin.pembayaran-denda.verifikasi", ["id" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', pembayaranId);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pembayaran denda telah diverifikasi!');
                window.location.href = '{{ route("admin.jatuh-tempo.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }

    function submitReject(e) {
        e.preventDefault();
        const pembayaranId = '{{ $pembayaran->id }}';
        const catatan = document.querySelector('textarea[name="catatan_admin"]').value;
        const url = '{{ route("admin.pembayaran-denda.tolak", ["id" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', pembayaranId);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                catatan_admin: catatan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pembayaran denda telah ditolak!');
                window.location.href = '{{ route("admin.jatuh-tempo.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
        });
    }

    // Close modal when clicking outside
    document.getElementById('acceptModal').addEventListener('click', function(e) {
        if (e.target === this) closeAcceptModal();
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>

@endsection
