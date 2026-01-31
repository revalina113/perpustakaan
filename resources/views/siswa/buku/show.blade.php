@extends('layouts.siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

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

    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-600">
        <a href="{{ route('siswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="{{ route('siswa.peminjaman.index') }}" class="hover:text-blue-600">Pinjam Buku</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 font-medium">{{ $buku->judul }}</span>
    </nav>

    <!-- Book Detail Header -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
            <!-- Book Cover -->
            <div class="md:w-1/3 p-6 flex justify-center">
                <div class="w-full max-w-sm">
                    @if($buku->gambar)
                        <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" class="w-full h-80 object-cover rounded-lg shadow-lg" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-80 flex items-center justify-center bg-gray-100 rounded-lg" style="display: none;">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @else
                        <div class="w-full h-80 flex items-center justify-center bg-gray-100 rounded-lg">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Information -->
            <div class="md:w-2/3 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $buku->judul }}</h1>
                        <p class="text-xl text-gray-600 mb-4">oleh <span class="font-semibold">{{ $buku->penulis }}</span></p>
                    </div>

                    <!-- Status Badge -->
                    <div class="ml-4">
                        @if($buku->stok > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tidak Tersedia
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Book Details Grid -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Kategori</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">{{ $buku->penerbit }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Tahun Terbit</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">{{ $buku->tahun_terbit }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Stok Tersedia</span>
                        </div>
                        <p class="text-lg font-semibold {{ $buku->stok > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $buku->stok }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Durasi Pinjam</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">7 Hari</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    @if($canBorrow)
                    <form action="{{ route('siswa.peminjaman.store') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Pinjam Buku Sekarang
                        </button>
                    </form>
                    @else
                    <button disabled class="flex-1 bg-gray-400 text-white py-3 px-6 rounded-lg cursor-not-allowed flex items-center justify-center font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ $borrowMessage }}
                    </button>
                    @endif

                    <a href="{{ route('siswa.peminjaman.index') }}" class="bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Book Statistics -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Tambahan</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">ID Buku</span>
                    <span class="font-semibold text-gray-900">#{{ str_pad($buku->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Total Peminjaman</span>
                    <span class="font-semibold text-gray-900">{{ \App\Models\Peminjaman::where('buku_id', $buku->id)->count() }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Peminjaman Aktif</span>
                    <span class="font-semibold text-gray-900">{{ \App\Models\Peminjaman::where('buku_id', $buku->id)->where('status', 'dipinjam')->count() }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Tanggal Ditambahkan</span>
                    <span class="font-semibold text-gray-900">{{ $buku->created_at ? $buku->created_at->format('d M Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Related Books -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Buku Serupa</h2>
            @php
                $relatedBooks = \App\Models\Buku::where('penerbit', $buku->penerbit)
                    ->where('id', '!=', $buku->id)
                    ->where('stok', '>', 0)
                    ->limit(3)
                    ->get();
            @endphp

            @if($relatedBooks->count() > 0)
            <div class="space-y-3">
                @foreach($relatedBooks as $related)
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                        @if($related->gambar)
                            <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->judul }}" class="w-full h-full object-cover rounded">
                        @else
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-800 text-sm line-clamp-1">{{ $related->judul }}</h4>
                        <p class="text-xs text-gray-600">{{ $related->penulis }}</p>
                    </div>
                    <a href="{{ route('siswa.buku.show', $related) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p class="text-gray-500 text-sm">Tidak ada buku serupa tersedia</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Reviews -->
    <div class="bg-white rounded-xl shadow-md p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ulasan Pembaca</h2>

        @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="border border-gray-100 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-semibold text-gray-700">{{ strtoupper(substr($review->anggota->nama, 0, 1)) }}</div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $review->anggota->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div class="text-sm">
                        {{-- rating stars --}}
                        @for($i=1;$i<=5;$i++)
                            @if($i <= $review->rating)
                                <span class="text-yellow-400">★</span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>
                </div>
                @if($review->komentar)
                <div class="mt-3 text-sm text-gray-700">{{ $review->komentar }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-gray-500">Belum ada ulasan untuk buku ini.</div>
        @endif

        @if(auth()->check() && auth()->user()->role === 'siswa')
            @if($hasBorrowed)
                <div class="mt-6 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Tulis Ulasan Anda</h3>

                    <form action="{{ route('siswa.buku.review.store', $buku) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Rating</label>
                            <select name="rating" required class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @for($r=1;$r<=5;$r++)
                                    <option value="{{ $r }}" {{ $myReview && $myReview->rating == $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Komentar (opsional)</label>
                            <textarea name="komentar" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $myReview->komentar ?? '' }}</textarea>
                        </div>

                        <div class="flex space-x-3 items-center">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kirim Ulasan</button>
                            @if($myReview)
                                <div class="text-sm text-gray-500">Anda sudah menulis ulasan. Ubah data lalu klik "Kirim Ulasan" untuk memperbarui.</div>
                            @endif
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-6 text-sm text-gray-600">Anda hanya dapat menulis ulasan jika pernah meminjam buku ini.</div>
            @endif
        @endif
    </div>
@endsection