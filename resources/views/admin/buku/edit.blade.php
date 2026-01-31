@extends('layouts.admin')

@section('content')
<!-- Success Message -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Header -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <div class="flex items-center">
        <a href="{{ route('admin.buku.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Buku</h2>
            <p class="text-gray-600 mt-1">Ubah informasi buku "{{ $buku->judul }}"</p>
        </div>
    </div>
</div>

<!-- Form -->
<div class="bg-white rounded-xl shadow-md p-6">
    <form method="POST" action="{{ route('admin.buku.update', $buku) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Judul -->
            <div class="md:col-span-2">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $buku->judul) }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('judul') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Masukkan judul buku">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penulis -->
            <div>
                <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $buku->penulis) }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('penulis') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Masukkan nama penulis">
                @error('penulis')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penerbit -->
            <div>
                <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('penerbit') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Masukkan nama penerbit">
                @error('penerbit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" value="{{ old('tahun', $buku->tahun) }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('tahun') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="2024" min="1900" max="2030">
                @error('tahun')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stok -->
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', $buku->stok) }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('stok') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="1" min="0">
                @error('stok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Gambar Cover -->
        <div class="mt-6">
            <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar Cover Buku</label>

            <!-- Current Image Preview -->
            @if($buku->gambar)
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                <div class="inline-block">
                    <img src="{{ asset('storage/' . $buku->gambar) }}" alt="Cover Buku" class="w-32 h-40 object-cover rounded-lg border border-gray-300">
                </div>
            </div>
            @endif

            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Ubah gambar</span>
                            <input id="gambar" name="gambar" type="file" accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                </div>
            </div>
            @error('gambar')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.buku.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Update Buku
            </button>
        </div>
    </form>
</div>
@endsection