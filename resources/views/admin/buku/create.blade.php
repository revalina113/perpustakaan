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
            <h2 class="text-2xl font-bold text-gray-800">Tambah Buku Baru</h2>
            <p class="text-gray-600 mt-1">Masukkan informasi buku yang akan ditambahkan</p>
        </div>
    </div>
</div>

<!-- Form -->
<div class="bg-white rounded-xl shadow-md p-6">
    <form method="POST" action="{{ route('admin.buku.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Judul -->
            <div class="md:col-span-2">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('judul') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Masukkan judul buku">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penulis -->
            <div>
                <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                <input type="text" name="penulis" id="penulis" value="{{ old('penulis') }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('penulis') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Masukkan nama penulis">
                @error('penulis')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penerbit -->
            <div>
                <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit') }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('penerbit') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="Masukkan nama penerbit">
                @error('penerbit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="kategori" id="kategori" required
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('kategori') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Kategori</option>
                    <option value="Novel" {{ old('kategori') === 'Novel' ? 'selected' : '' }}>Novel</option>
                    <option value="Sejarah" {{ old('kategori') === 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                    <option value="Pelajaran" {{ old('kategori') === 'Pelajaran' ? 'selected' : '' }}>Pelajaran</option>
                    <option value="Komik" {{ old('kategori') === 'Komik' ? 'selected' : '' }}>Komik</option>
                    <option value="Motivasi" {{ old('kategori') === 'Motivasi' ? 'selected' : '' }}>Motivasi</option>
                    <option value="Teknologi" {{ old('kategori') === 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                    <option value="Agama" {{ old('kategori') === 'Agama' ? 'selected' : '' }}>Agama</option>
                    <option value="Biografi" {{ old('kategori') === 'Biografi' ? 'selected' : '' }}>Biografi</option>
                    <option value="Ensiklopedia" {{ old('kategori') === 'Ensiklopedia' ? 'selected' : '' }}>Ensiklopedia</option>
                    <option value="Lainnya" {{ old('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('kategori')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" value="{{ old('tahun') }}"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('tahun') ? 'border-red-500' : 'border-gray-300' }}"
                       placeholder="2024" min="1900" max="2030">
                @error('tahun')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stok -->
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', 1) }}"
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
            
            <!-- Preview Container -->
            <div id="previewContainer" class="mb-4" style="display: none;">
                <div class="flex items-end gap-4">
                    <div class="flex-shrink-0">
                        <img id="previewImage" alt="Preview" class="w-32 h-48 object-cover rounded-lg border border-gray-300 shadow-md">
                    </div>
                    <button type="button" id="deletePreviewBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
            
            <!-- Upload Area -->
            <div id="uploadArea" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Upload gambar</span>
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

        <!-- JavaScript for Image Preview -->
        <script>
            const imageInput = document.getElementById('gambar');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const uploadArea = document.getElementById('uploadArea');
            const deletePreviewBtn = document.getElementById('deletePreviewBtn');

            // Handle file selection
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImage.src = event.target.result;
                        uploadArea.style.display = 'none';
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle delete/reset preview
            deletePreviewBtn.addEventListener('click', function(e) {
                e.preventDefault();
                imageInput.value = '';
                previewContainer.style.display = 'none';
                uploadArea.style.display = 'flex';
            });

            // Handle drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-blue-400', 'bg-blue-50');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    imageInput.files = files;
                    const event = new Event('change', { bubbles: true });
                    imageInput.dispatchEvent(event);
                }
            });
        </script>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.buku.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Simpan Buku
            </button>
        </div>
    </form>
</div>
@endsection