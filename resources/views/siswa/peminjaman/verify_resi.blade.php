@extends('layouts.admin')

@section('title', 'Verifikasi Resi')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Verifikasi Resi #{{ $peminjaman->id }}</h2>
    <p>Nomor Resi: <strong>#{{ $peminjaman->id }}</strong></p>
    <p>Nama Peminjam: <strong>{{ $peminjaman->anggota->nama ?? '-' }}</strong></p>
    <p>Judul Buku: <strong>{{ optional($peminjaman->buku)->judul ?? 'Judul tidak tersedia' }}</strong></p>
    <p>Tanggal Pinjam: <strong>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y') : '-' }}</strong></p>
    <p>Status: <strong>{{ ucfirst($peminjaman->status) }}</strong></p>

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Kembali</a>
    </div>
</div>
@endsection