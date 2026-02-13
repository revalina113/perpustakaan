@extends('layouts.admin')

@section('title', 'Data Pengunjung - PERPUSTAKAAN')

@section('content')
<div class="bg-white rounded-3 shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Pengunjung</h2>
            <p class="text-gray-600 mt-1">Kelola kunjungan anggota ke perpustakaan</p>
        </div>
        <div class="text-4xl">👥</div>
    </div>
</div>

<!-- statistics cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-3 shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">Total Hari Ini</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalHariIni ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-3 shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">Total Bulan Ini</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalBulanIni ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-3 shadow-sm p-6">
        <p class="text-sm font-medium text-gray-500">Total Semua Kunjungan</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalSemua ?? 0 }}</p>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- Form tambah pengunjung -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4">Tambah Pengunjung</h3>
    <form action="{{ route('admin.pengunjung.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Anggota</label>
            <select name="anggota_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- Pilih Anggota --</option>
                @foreach($anggotaList as $anggota)
                    <option value="{{ $anggota->id }}" {{ old('anggota_id')==$anggota->id ? 'selected' : '' }}>{{ $anggota->nama }} ({{ $anggota->nis }})</option>
                @endforeach
            </select>
            @error('anggota_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Kunjungan</label>
            <input type="date" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', now()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('tanggal_kunjungan')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Jam Masuk</label>
            <input type="time" name="jam_masuk" value="{{ old('jam_masuk', now()->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('jam_masuk')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan (opsional)</label>
            <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('keterangan')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>
        <div class="sm:col-span-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>

<!-- Tabel pengunjung -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Kunjungan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pengunjung as $index => $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->anggota->nama ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->tanggal_kunjungan->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->jam_masuk }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->keterangan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.pengunjung.edit', $p->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                        <form action="{{ route('admin.pengunjung.destroy', $p->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">
        {{ $pengunjung->links() }}
    </div>
</div>
@endsection
