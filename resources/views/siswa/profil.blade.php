@extends('layouts.siswa')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded shadow flex flex-col items-center">
    @if(session('success'))
        <div class="mb-4 w-full text-center">
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded">{{ session('success') }}</span>
        </div>
    @endif
    <div class="flex flex-col items-center mb-6">
        <!-- Foto Profil Bulat -->
        <div class="w-28 h-28 mb-4">
            @if(Auth::user()->anggota->foto ?? false)
                <img src="{{ asset('storage/' . Auth::user()->anggota->foto) }}" alt="Foto Profil" class="w-28 h-28 rounded-full object-cover border-4 border-blue-200">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=128" alt="Avatar" class="w-28 h-28 rounded-full object-cover border-4 border-blue-200">
            @endif
        </div>
        <!-- Nama Besar -->
        <div class="text-2xl font-extrabold text-gray-800 mb-1">{{ Auth::user()->name }}</div>
        <!-- Username -->
        <!-- Status Badge -->
        @php $status = Auth::user()->anggota->status ?? '-'; @endphp
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ ucfirst($status) }}
        </span>
    </div>
    <div class="space-y-4 w-full max-w-xs">
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full"><svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.657-1.343-3-3-3s-3 1.343-3 3 1.343 3 3 3 3-1.343 3-3z"/></svg></span>
            <span class="text-xs text-gray-500">NIS</span>
            <span class="font-bold ml-auto">{{ Auth::user()->anggota->nis ?? '-' }}</span>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full"><svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h0a4 4 0 014 4v2"/></svg></span>
            <span class="text-xs text-gray-500">Kelas</span>
            <span class="font-bold ml-auto">{{ Auth::user()->anggota->kelas ?? '-' }}</span>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10a1 1 0 011-1h16a1 1 0 011 1v8a1 1 0 01-1 1H4a1 1 0 01-1-1v-8z"/></svg></span>
            <span class="text-xs text-gray-500">No HP</span>
            <span class="font-bold ml-auto">{{ Auth::user()->anggota->no_hp ?? '-' }}</span>
        </div>
    </div>
    <div class="flex justify-center space-x-2 mt-8 w-full">
        <a href="{{ route('siswa.profil.edit') }}" class="px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">Edit Profil</a>
        <a href="{{ route('password.change') }}" class="px-4 py-2 rounded bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-colors">Ubah Password</a>
    </div>
</div>
@endsection
