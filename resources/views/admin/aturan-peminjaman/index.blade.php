@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            <!-- Header -->
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h1 class="ml-2 text-2xl font-medium text-gray-900">
                        Pengaturan Aturan Peminjaman
                    </h1>
                </div>
                <p class="mt-2 text-sm text-gray-600">
                    Atur lama peminjaman dan denda per hari untuk semua siswa
                </p>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-600 text-sm rounded-lg p-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg p-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.aturan-peminjaman.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Lama Peminjaman -->
                    <div>
                        <label for="lama_peminjaman" class="block text-sm font-medium text-gray-700">
                            Lama Peminjaman (hari)
                        </label>
                        <div class="mt-1">
                            <input type="number"
                                   id="lama_peminjaman"
                                   name="lama_peminjaman"
                                   value="{{ old('lama_peminjaman', $aturan->lama_peminjaman) }}"
                                   min="1"
                                   max="365"
                                   required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Jumlah hari maksimal siswa dapat meminjam buku
                        </p>
                    </div>

                    <!-- Denda Per Hari -->
                    <div>
                        <label for="denda_per_hari" class="block text-sm font-medium text-gray-700">
                            Denda Per Hari (Rupiah)
                        </label>
                        <div class="mt-1">
                            <input type="number"
                                   id="denda_per_hari"
                                   name="denda_per_hari"
                                   value="{{ old('denda_per_hari', $aturan->denda_per_hari) }}"
                                   min="0"
                                   max="100000"
                                   required
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Denda yang dikenakan per hari keterlambatan
                        </p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                            Deskripsi (Opsional)
                        </label>
                        <div class="mt-1">
                            <textarea id="deskripsi"
                                      name="deskripsi"
                                      rows="3"
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      placeholder="Deskripsi aturan peminjaman">{{ old('deskripsi', $aturan->deskripsi) }}</textarea>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Catatan tambahan tentang aturan peminjaman
                        </p>
                    </div>

                    <!-- Preview -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Pratinjau Aturan</h3>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Lama Peminjaman:</strong> <span id="preview-lama">{{ $aturan->lama_peminjaman }}</span> hari</p>
                            <p><strong>Denda Per Hari:</strong> Rp<span id="preview-denda">{{ number_format($aturan->denda_per_hari, 0, ',', '.') }}</span></p>
                            <p><strong>Contoh Denda 3 Hari:</strong> Rp<span id="preview-contoh">{{ number_format($aturan->denda_per_hari * 3, 0, ',', '.') }}</span></p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Update preview when inputs change
document.getElementById('lama_peminjaman').addEventListener('input', updatePreview);
document.getElementById('denda_per_hari').addEventListener('input', updatePreview);

function updatePreview() {
    const lama = document.getElementById('lama_peminjaman').value || 0;
    const denda = document.getElementById('denda_per_hari').value || 0;

    document.getElementById('preview-lama').textContent = lama;
    document.getElementById('preview-denda').textContent = new Intl.NumberFormat('id-ID').format(denda);
    document.getElementById('preview-contoh').textContent = new Intl.NumberFormat('id-ID').format(denda * 3);
}
</script>
@endsection