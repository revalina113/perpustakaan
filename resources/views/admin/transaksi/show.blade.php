@extends('layouts.admin')

@section('title', 'Detail Transaksi - Admin')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Detail Transaksi #{{ $peminjaman->id }}</h2>
        <a href="{{ route('admin.transaksi.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold mb-2">Anggota</h3>
            <p class="text-sm">Nama: <strong>{{ $peminjaman->anggota->nama ?? '-' }}</strong></p>
            <p class="text-sm">NIS: {{ $peminjaman->anggota->nis ?? '-' }}</p>
            <p class="text-sm">Status: {{ ucfirst($peminjaman->status) }}</p>
        </div>

        <div>
            <h3 class="font-semibold mb-2">Buku</h3>
            <p class="text-sm">Judul: <strong>{{ $peminjaman->buku->judul ?? 'Buku dihapus' }}</strong></p>
            <p class="text-sm">Pengarang: {{ $peminjaman->buku->penulis ?? '-' }}</p>
            <p class="text-sm">Tanggal Pinjam: {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d/m/Y') : '-' }}</p>
            <p class="text-sm">Jatuh Tempo: {{ $peminjaman->tanggal_jatuh_tempo ? $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}</p>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold mb-2">Informasi Tambahan</h3>
        <p class="text-sm">Denda Saat Ini: <strong>Rp {{ number_format($peminjaman->denda ?? 0, 0, ',', '.') }}</strong></p>
        <p class="text-sm">Hari Terlambat: <strong>{{ $peminjaman->hari_terlambat ?? 0 }}</strong></p>
        <p class="text-sm">Pembayaran Denda terkait: {{ $peminjaman->pembayaranDenda ? $peminjaman->pembayaranDenda->count() : 0 }}</p>
    </div>

    <div class="mt-6 flex space-x-2">
        @if($peminjaman->status === 'dipinjam')
            <form method="POST" action="{{ route('admin.transaksi.kembalikan', $peminjaman->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Tandai Dikembalikan</button>
            </form>
        @endif

        <a href="{{ route('admin.transaksi.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Kembali ke daftar</a>
    </div>
</div>
@endsection