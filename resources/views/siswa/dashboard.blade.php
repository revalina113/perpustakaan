@extends('layouts.siswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Siswa</h1>
        <p class="text-gray-600 mt-2">Selamat datang di sistem perpustakaan. Jelajahi koleksi buku kami.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Buku Tersedia</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Buku::where('stok', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Buku Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $peminjamanAktif }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Koleksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Buku::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pengembalian Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Peminjaman::where('anggota_id', auth()->user()->anggota->id ?? 0)->where('status', 'dikembalikan')->whereDate('tanggal_kembali', today())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('siswa.peminjaman.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Pinjam Buku</h3>
                    <p class="text-sm text-gray-600">Jelajahi dan pinjam buku</p>
                </div>
            </a>

            <a href="{{ route('siswa.pengembalian.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Kembalikan Buku</h3>
                    <p class="text-sm text-gray-600">Kembalikan buku yang dipinjam</p>
                </div>
            </a>

            <a href="{{ route('siswa.profil') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Profil Saya</h3>
                    <p class="text-sm text-gray-600">Kelola informasi pribadi</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Books -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Buku Terbaru</h2>
            <a href="{{ route('siswa.peminjaman.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>

        @if($buku->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($buku as $book)
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

                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada buku tersedia</h3>
            <p class="text-gray-500">Koleksi buku akan segera ditambahkan.</p>
        </div>
        @endif
    </div>

    <!-- Current Loans -->
    @if($peminjamanAktif > 0)
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Buku yang Sedang Dipinjam</h2>
        <div class="space-y-4">
            @php
                $peminjamanList = \App\Models\Peminjaman::where('anggota_id', \App\Models\Anggota::where('nis', Auth::user()->username)->first()?->id)
                    ->where('status', 'dipinjam')
                    ->with('buku')
                    ->latest()
                    ->limit(3)
                    ->get();
            @endphp

            @foreach($peminjamanList as $pinjam)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                        @if($pinjam->buku && $pinjam->buku->gambar)
                            <img src="{{ asset('storage/' . $pinjam->buku->gambar) }}" alt="{{ $pinjam->buku->judul }}" class="w-full h-full object-cover rounded">
                        @else
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $pinjam->buku->judul ?? 'Buku tidak ditemukan' }}</h4>
                        <p class="text-sm text-gray-600">Dipinjam: {{ $pinjam->tanggal_pinjam ? \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') : '-' }}</p>
                        <p class="text-sm text-gray-600">Kembali: {{ $pinjam->tanggal_kembali ? \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') : 'Belum dikembalikan' }}</p>
                    </div>
                </div>
                <a href="{{ route('siswa.pengembalian.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                    Kembalikan
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
