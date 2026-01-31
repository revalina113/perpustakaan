@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo dan Judul -->
        <div class="text-center">
            <img src="{{ asset('images/logo-smk8jembero.png') }}"
                 alt="Logo SMK 8 Jember"
                 class="w-20 h-20 mx-auto mb-4 object-contain">
            <h1 class="text-4xl font-bold text-white mb-2">PERPUSTAKAAN</h1>
            <p class="text-xl text-gray-300 mb-8">Sistem Perpustakaan Digital</p>
        </div>

        <!-- Deskripsi -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Selamat Datang</h2>
            <p class="text-gray-600 text-center leading-relaxed">
                Kelola koleksi buku, pantau peminjaman, dan akses perpustakaan digital
                dengan mudah dan efisien. Sistem modern untuk pengelolaan perpustakaan sekolah.
            </p>
        </div>

        <!-- Tombol Aksi -->
        <div class="space-y-4">
            <a href="{{ route('login') }}"
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Masuk ke Sistem
            </a>

           
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-gray-400 text-sm">
                © 2026 SMK 8 Jember - Sistem Perpustakaan Digital
            </p>
        </div>
    </div>
</div>
@endsection