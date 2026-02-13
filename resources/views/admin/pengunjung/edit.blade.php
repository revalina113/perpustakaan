@extends('layouts.admin')

@section('title', 'Edit Pengunjung - PERPUSTAKAAN')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Edit Data Pengunjung</h2>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.pengunjung.update', $visit->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    @csrf
    @method('PUT')
    <div>
        <label class="block text-sm font-medium text-gray-700">Anggota</label>
        <select name="anggota_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">-- Pilih Anggota --</option>
            @foreach($anggotaList as $anggota)
                <option value="{{ $anggota->id }}" {{ old('anggota_id', $visit->anggota_id)==$anggota->id ? 'selected' : '' }}>{{ $anggota->nama }} ({{ $anggota->nis }})</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Kunjungan</label>
        <input type="date" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', $visit->tanggal_kunjungan->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Jam Masuk</label>
        <input type="time" name="jam_masuk" value="{{ old('jam_masuk', $visit->jam_masuk) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Keterangan (opsional)</label>
        <input type="text" name="keterangan" value="{{ old('keterangan', $visit->keterangan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div class="sm:col-span-2">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('admin.pengunjung.index') }}" class="ml-4 text-gray-600 hover:underline">Batal</a>
    </div>
</form>
@endsection
