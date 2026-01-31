@extends('layouts.siswa')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Edit Profil</h2>
    <form action="{{ route('siswa.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Foto Profil</label>
            <input type="file" name="foto" class="block w-full border rounded p-2">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-1">No HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->anggota->no_hp ?? '') }}" class="block w-full border rounded p-2">
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('siswa.profil') }}" class="px-4 py-2 rounded bg-gray-200 text-gray-700">Batal</a>
            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
        </div>
    </form>
</div>
@endsection
