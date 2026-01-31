@extends('layouts.siswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

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

    @if(session('warning'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ session('warning') }}
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Koleksi Buku Lengkap</h1>
                <p class="text-blue-100 mt-2">Jelajahi seluruh koleksi buku perpustakaan</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <form method="GET" action="{{ route('siswa.peminjaman.index') }}" class="flex flex-col md:flex-row gap-4 flex-1">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Buku</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul buku atau nama penulis..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="md:w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results Info -->
        @if(request('search') || request('kategori'))
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800">
                @if(request('search') && request('kategori'))
                    Menampilkan hasil pencarian "<strong>{{ request('search') }}</strong>" dalam kategori "<strong>{{ request('kategori') }}</strong>"
                @elseif(request('search'))
                    Menampilkan hasil pencarian "<strong>{{ request('search') }}</strong>"
                @elseif(request('kategori'))
                    Menampilkan buku kategori "<strong>{{ request('kategori') }}</strong>"
                @endif
                <a href="{{ route('siswa.peminjaman.index') }}" class="text-blue-600 hover:text-blue-800 underline ml-2">Reset</a>
            </p>
        </div>
        @endif
    </div>

    <!-- Books Grid -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Koleksi Buku Lengkap</h2>
            <div class="text-sm text-gray-600">
                Menampilkan {{ $buku->count() }} dari {{ $buku->total() }} buku
            </div>
        </div>

        @if($buku->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($buku as $book)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                <!-- Book Cover -->
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    @if($book->gambar)
                        <img src="{{ asset('storage/' . $book->gambar) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    @endif
                </div>

                <!-- Book Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-800 mb-2 line-clamp-2">{{ $book->judul }}</h3>
                    <p class="text-gray-600 text-sm mb-1"><strong>Penulis:</strong> {{ $book->penulis }}</p>
                    <p class="text-gray-600 text-sm mb-1"><strong>Kategori:</strong> {{ $book->penerbit }}</p>
                    <p class="text-gray-600 text-sm mb-3"><strong>Tahun:</strong> {{ $book->tahun_terbit }}</p>

                    <!-- Stock Info -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Stok: <span class="font-semibold {{ $book->stok > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $book->stok }}</span></span>

                            {{-- Rating & Reviews --}}
                            @if(isset($book->reviews_count) && $book->reviews_count > 0)
                                <div class="flex items-center text-sm text-gray-600">
                                    <div class="mr-2 text-yellow-400 font-semibold">{{ number_format($book->reviews_avg_rating, 1) }} ★</div>
                                    <div class="text-gray-500">({{ $book->reviews_count }} ulasan)</div>
                                </div>
                            @else
                                <div class="text-sm text-gray-500">Belum ada ulasan</div>
                            @endif
                        </div>

                        @if($book->stok > 0)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">Tersedia</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">Habis</span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <a href="{{ route('siswa.buku.show', $book) }}" class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center justify-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Detail
                        </a>

                        <form action="{{ route('siswa.peminjaman.store') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="buku_id" value="{{ $book->id }}">
                            <button type="submit" {{ (!$anggota || $book->stok <= 0) ? 'disabled' : '' }}
                                    class="w-full {{ (!$anggota || $book->stok <= 0) ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} text-white py-2 px-4 rounded-lg transition-colors inline-flex items-center justify-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                @if(!$anggota)
                                    Belum Terdaftar
                                @elseif($book->stok <= 0)
                                    Stok Habis
                                @else
                                    Pinjam Buku
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $buku->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak ada buku tersedia</h3>
            <p class="text-gray-500 mb-4">Coba ubah kriteria pencarian atau kembali nanti.</p>
            <a href="{{ route('siswa.peminjaman.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Reset Pencarian
            </a>
        </div>
        @endif
    </div>

    <!-- Current Loans Summary -->
    @if($peminjamanAktif->count() > 0)
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Buku yang Sedang Dipinjam</h2>
            <a href="{{ route('siswa.pengembalian.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Kelola Pengembalian →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($peminjamanAktif as $pinjam)
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                        @if($pinjam->buku && $pinjam->buku->gambar)
                            <img src="{{ asset('storage/' . $pinjam->buku->gambar) }}" alt="{{ $pinjam->buku->judul }}" class="w-full h-full object-cover rounded">
                        @else
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-800 text-sm line-clamp-2">{{ $pinjam->buku->judul ?? 'Buku tidak ditemukan' }}</h4>
                        <p class="text-xs text-gray-600 mt-1">
                            <span class="font-medium">Pinjam:</span> {{ $pinjam->tanggal_pinjam ? \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') : '-' }}
                        </p>
                        <p class="text-xs text-gray-600">
                            <span class="font-medium">Status:</span> Sedang Dipinjam
                        </p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Dipinjam
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection