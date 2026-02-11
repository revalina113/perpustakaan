@extends('layouts.siswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

        <!-- Mobile Menu Button -->
        <div class="lg:hidden">
            <button onclick="toggleSidebar()" class="fixed top-4 left-4 z-50 bg-gray-800 text-white p-2 rounded-lg shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Pengembalian Buku</h1>
                <p class="text-gray-600 mt-2">Kembalikan buku yang sedang dipinjam</p>
            </div>
            <div>
                <a href="{{ route('siswa.peminjaman.history') }}" class="inline-block bg-sky-600 text-white px-4 py-2 rounded-lg hover:bg-sky-700">Lihat Riwayat & Cetak Resi</a>
            </div>
        </div>

        <!-- Books to Return -->
        <div class="bg-white rounded-xl shadow-md p-6">
            @if($peminjaman->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($peminjaman as $pinjam)
                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <h3 class="font-semibold text-lg text-gray-800">{{ optional($pinjam->buku)->judul ?? 'Judul tidak tersedia' }}</h3>
                    <p class="text-gray-600">Penulis: {{ optional($pinjam->buku)->penulis ?? '-' }}</p>
                    <p class="text-gray-600">Tanggal Pinjam: {{ $pinjam->tanggal_pinjam ? $pinjam->tanggal_pinjam->format('d M Y') : '-' }}</p>
                    <p class="text-gray-600">Jatuh Tempo: {{ $pinjam->tanggal_jatuh_tempo ? $pinjam->tanggal_jatuh_tempo->format('d M Y') : '-' }}</p>

                    <!-- Status Badge -->
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pinjam->badge_color }}">
                            {{ $pinjam->status_terlambat }}
                        </span>
                    </div>

                    <!-- Tanda merah jika ada denda yang belum dibayar (tanpa menampilkan nominal) -->
                    @if($pinjam->denda > 0 && !$pinjam->isDendaLunas())
                        @if($pinjam->menunggu_verifikasi)
                            <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-sm text-yellow-800 font-medium">
                                    Bukti pembayaran telah dikirim dan <span class="font-semibold">menunggu verifikasi admin</span>. Silakan tunggu atau hubungi admin untuk konfirmasi.
                                </p>
                            </div>
                        @else
                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded">
                                <p class="text-sm text-red-600 font-medium">
                                    Denda belum dibayar — silakan bayar di halaman <a href="{{ route('siswa.jatuh-tempo.index') }}" class="underline font-semibold">Jatuh Tempo & Denda</a>
                                </p>
                            </div>
                        @endif
                    @endif

                    <!-- Action Button -->
                    <div class="mt-4">
                        @if($pinjam->denda > 0 && !$pinjam->isDendaLunas())
                            @if($pinjam->menunggu_verifikasi)
                                <button type="button" disabled class="w-full inline-block text-center bg-yellow-400 text-white py-2 px-4 rounded-lg cursor-not-allowed">
                                    ⏳ Menunggu Verifikasi
                                </button>
                            @else
                                <a href="{{ route('siswa.jatuh-tempo.index') }}" class="w-full inline-block text-center bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">
                                    Bayar Dan Kembalikan
                                </a>
                            @endif
                        @else
                        <form action="{{ route('siswa.pengembalian.store') }}" method="POST" class="">
                            @csrf
                            <input type="hidden" name="peminjaman_id" value="{{ $pinjam->id }}">

                            <!-- Modal Trigger -->
                            <button type="button"
                                    data-peminjaman-id="{{ $pinjam->id }}"
                                    data-book-title="{{ optional($pinjam->buku)->judul ?? 'Judul tidak tersedia' }}"
                                    data-denda="{{ $pinjam->denda }}"
                                    data-denda-paid="{{ $pinjam->isDendaLunas() ? 1 : 0 }}"
                                    onclick="openModal(this)"
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors">
                                Kembalikan Buku
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $peminjaman->links() }}
            </div>
            @else
            <p class="text-gray-600 text-center py-8">Tidak ada buku yang sedang dipinjam.</p>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Konfirmasi Pengembalian</h3>
            <div class="mb-4">
                <p class="text-sm text-gray-600" id="modalMessage"></p>
                <div id="dendaInfo" class="mt-2 p-3 bg-red-50 border border-red-200 rounded hidden">
                    <p class="text-sm text-red-600 font-medium" id="dendaAmount"></p>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <a id="gotoPayBtn" href="{{ route('siswa.jatuh-tempo.index') }}" class="hidden px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Bayar Denda
                </a>
                <button id="confirmReturnBtn" type="button" onclick="submitReturn()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Ya, Kembalikan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPeminjamanId = null;

function openModal(button) {
    currentPeminjamanId = button.getAttribute('data-peminjaman-id');
    const bookTitle = button.getAttribute('data-book-title');
    const denda = parseInt(button.getAttribute('data-denda'));
    const dendaPaid = button.getAttribute('data-denda-paid') === '1';

    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const dendaInfo = document.getElementById('dendaInfo');
    const dendaAmount = document.getElementById('dendaAmount');
    const confirmBtn = document.getElementById('confirmReturnBtn');
    const gotoPayBtn = document.getElementById('gotoPayBtn');

    modalTitle.textContent = 'Kembalikan Buku';

    if (denda > 0 && !dendaPaid) {
        modalMessage.textContent = `Buku "${bookTitle}" memiliki denda yang belum dibayar. Silakan bayar terlebih dahulu di halaman Jatuh Tempo & Denda.`;
        dendaAmount.textContent = `Total denda: Rp${denda.toLocaleString('id-ID')}`;
        dendaInfo.classList.remove('hidden');

        // Tampilkan tombol bayar dan sembunyikan tombol konfirmasi
        confirmBtn.classList.add('hidden');
        gotoPayBtn.classList.remove('hidden');
    } else {
        modalMessage.textContent = `Apakah Anda yakin ingin mengembalikan buku "${bookTitle}"?`;
        if (denda > 0) {
            dendaAmount.textContent = `Total denda yang harus dibayar: Rp${denda.toLocaleString('id-ID')}`;
            dendaInfo.classList.remove('hidden');
        } else {
            dendaInfo.classList.add('hidden');
        }

        confirmBtn.classList.remove('hidden');
        gotoPayBtn.classList.add('hidden');
    }

    document.getElementById('confirmationModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('confirmationModal').classList.add('hidden');
    currentPeminjamanId = null;

    // Reset buttons
    const confirmBtn = document.getElementById('confirmReturnBtn');
    const gotoPayBtn = document.getElementById('gotoPayBtn');
    confirmBtn.classList.remove('hidden');
    gotoPayBtn.classList.add('hidden');
}

function submitReturn() {
    if (currentPeminjamanId) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("siswa.pengembalian.store") }}';

        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Peminjaman ID
        const peminjamanId = document.createElement('input');
        peminjamanId.type = 'hidden';
        peminjamanId.name = 'peminjaman_id';
        peminjamanId.value = currentPeminjamanId;
        form.appendChild(peminjamanId);

        document.body.appendChild(form);
        form.submit();
    }
}

// Close modal when clicking outside
document.getElementById('confirmationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection