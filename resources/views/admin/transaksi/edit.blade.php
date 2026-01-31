@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Edit Transaksi Peminjaman</h1>
                    <p class="text-gray-600 mt-2">Edit transaksi peminjaman buku</p>
                </div>
                <a href="{{ route('admin.transaksi.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('admin.transaksi.update', $peminjaman->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Anggota -->
                <div>
                    <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Anggota <span class="text-red-500">*</span>
                    </label>
                    <select name="anggota_id" id="anggota_id"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        <option value="">Pilih Anggota</option>
                        @foreach($anggota as $item)
                        <option value="{{ $item->id }}" {{ $peminjaman->anggota_id == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }} ({{ $item->nis }})
                        </option>
                        @endforeach
                    </select>
                    @error('anggota_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buku -->
                <div>
                    <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Buku <span class="text-red-500">*</span>
                    </label>
                    <select name="buku_id" id="buku_id"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        <option value="">Pilih Buku</option>
                        @foreach($buku as $item)
                        <option value="{{ $item->id }}" {{ $peminjaman->buku_id == $item->id ? 'selected' : '' }}>
                            {{ $item->judul }} - {{ $item->penulis }} (Stok: {{ $item->stok }})
                        </option>
                        @endforeach
                    </select>
                    @error('buku_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pinjam -->
                <div>
                    <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Pinjam <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                           value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('Y-m-d') : '') }}"
                           max="{{ date('Y-m-d') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                    @error('tanggal_pinjam')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Status Info -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-800 mb-2">Status Transaksi Saat Ini</h4>
                    <p class="text-sm text-gray-700">
                        Status: <strong>{{ ucfirst($peminjaman->status) }}</strong><br>
                        @if($peminjaman->tanggal_jatuh_tempo)
                        Tanggal Jatuh Tempo: <strong>{{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</strong>
                        @endif
                    </p>
                </div>

                <!-- Info Aturan -->
                @if($aturan)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Aturan Peminjaman</h4>
                    <p class="text-sm text-blue-700">
                        Lama peminjaman: <strong>{{ $aturan->lama_peminjaman }} hari</strong><br>
                        Denda per hari: <strong>Rp{{ number_format($aturan->denda_per_hari, 0, ',', '.') }}</strong>
                    </p>
                    <p class="text-xs text-blue-600 mt-2">
                        Tanggal jatuh tempo akan dihitung ulang berdasarkan tanggal pinjam yang baru.
                    </p>
                </div>
                @endif

                <!-- Error Message -->
                @if($errors->has('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="mt-1 text-sm text-red-700">{{ $errors->first('error') }}</p>
                </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.transaksi.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Update Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection