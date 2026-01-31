@extends('layouts.siswa')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Jatuh Tempo & Denda</h1>
            <p class="text-gray-600 mt-2">Pantau status peminjaman dan kelola pembayaran denda</p>

            <!-- Info Umum -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>📅 Tanggal Hari Ini:</strong> {{ now()->format('l, d F Y') }}
                    </p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-600">
                        <strong>💰 Denda per Hari:</strong> Rp{{ number_format($aturan ? $aturan->denda_per_hari : 1000, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabel Buku yang Dipinjam -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Status Peminjaman Buku</h2>

            @if(($peminjamanTepatWaktu->count() + $peminjamanTerlambat->count()) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Jatuh Tempo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterlambatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Denda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($peminjamanTepatWaktu as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ optional($item['peminjaman']->buku)->judul ?? 'Judul tidak tersedia' }} @if(!$item['peminjaman']->buku)<span class="ml-2 inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">Buku dihapus</span>@endif</div>
                                <div class="text-sm text-gray-500">{{ optional($item['peminjaman']->buku)->penulis ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['peminjaman']->tanggal_pinjam ? $item['peminjaman']->tanggal_pinjam->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['peminjaman']->tanggal_jatuh_tempo ? $item['peminjaman']->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Tepat Waktu
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                        </tr>
                        @endforeach

                        @foreach($peminjamanTerlambat as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ optional($item['peminjaman']->buku)->judul ?? 'Judul tidak tersedia' }} @if(!$item['peminjaman']->buku)<span class="ml-2 inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">Buku dihapus</span>@endif</div>
                                <div class="text-sm text-gray-500">{{ optional($item['peminjaman']->buku)->penulis ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['peminjaman']->tanggal_pinjam ? $item['peminjaman']->tanggal_pinjam->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['peminjaman']->tanggal_jatuh_tempo ? $item['peminjaman']->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Terlambat
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                {{ $item['hari_terlambat'] }} hari
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                Rp{{ number_format($item['total_denda'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button type="button"
                                        data-peminjaman-id="{{ $item['peminjaman']->id }}"
                                        data-book-title="{{ optional($item['peminjaman']->buku)->judul ?? 'Judul tidak tersedia' }}"
                                        data-denda-amount="{{ $item['total_denda'] }}"
                                        onclick="openPaymentModal(this)"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Bayar Denda
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <!-- Tidak ada buku yang dipinjam -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">🎉 Tidak ada buku yang dipinjam</h3>
                <p class="mt-2 text-sm text-gray-500">Anda belum meminjam buku apapun.</p>
                <div class="mt-6">
                    <a href="{{ route('siswa.peminjaman.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Pinjam Buku
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

    </div>
</div>

<!-- Modal Pembayaran Denda -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Bayar Denda</h3>

            <form id="paymentForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="peminjaman_id" id="payment_peminjaman_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                    <p class="text-sm text-gray-900 font-medium" id="payment_book_title"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Denda</label>
                    <p class="text-lg font-bold text-red-600" id="payment_denda_amount"></p>
                    <p class="text-xs text-gray-500 mt-1">Nominal denda tidak dapat diubah</p>
                </div>

                <div class="mb-4">
                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                           accept="image/*" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Upload foto bukti transfer (JPG, PNG, max 2MB)</p>
                </div>

                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                    <p class="text-sm text-blue-800">
                        <strong>📋 Instruksi Pembayaran:</strong><br>
                        • Transfer ke rekening: BCA 1234567890 a.n. Perpustakaan<br>
                        • Nominal sesuai jumlah denda<br>
                        • Simpan bukti transfer untuk upload
                    </p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaymentModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPeminjamanId = null;

function openPaymentModal(button) {
    const peminjamanId = button.getAttribute('data-peminjaman-id');
    const bookTitle = button.getAttribute('data-book-title');
    const dendaAmount = button.getAttribute('data-denda-amount');

    document.getElementById('payment_peminjaman_id').value = peminjamanId;
    document.getElementById('payment_book_title').textContent = bookTitle;
    document.getElementById('payment_denda_amount').textContent = 'Rp' + parseInt(dendaAmount).toLocaleString('id-ID');

    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentForm').reset();
}

// Handle form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("siswa.jatuh-tempo.bayar-denda") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closePaymentModal();
            location.reload(); // Reload to update status
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim pembayaran.');
    });
});

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentModal();
    }
});
</script>
@endsection