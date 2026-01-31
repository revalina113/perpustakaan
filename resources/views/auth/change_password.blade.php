@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h2 class="text-lg font-semibold mb-4">Ganti Password</h2>

            @if(session('info'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-3 rounded mb-4">{{ session('info') }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="mb-4">
                    <label class="text-sm font-medium text-slate-600">Password Baru</label>
                    <input type="password" name="password" required class="w-full mt-1 rounded-lg border border-slate-300 p-2" placeholder="Masukkan password baru">
                    @error('password') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium text-slate-600">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full mt-1 rounded-lg border border-slate-300 p-2" placeholder="Ulangi password">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold">Simpan Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
