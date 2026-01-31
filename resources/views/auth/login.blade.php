@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-800 py-12 px-4 sm:px-6 lg:px-8">

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-md">

            <!-- Card Body -->
            <div class="p-8 space-y-4">

                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo-smk8jembero.png') }}"
                         alt="Logo SMK 8 Jember"
                         class="w-16 h-16 mx-auto mb-3 object-contain">
                    <h1 class="text-xl font-bold text-slate-800">PERPUSTAKAAN</h1>
                    <p class="text-sm text-slate-500">Sistem Perpustakaan Digital</p>
                </div>

                <!-- Error -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg p-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-slate-600">Username</label>
                        <input type="text" name="username" required
                               class="w-full mt-1 rounded-lg border border-slate-300 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan username">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Password</label>
                        <input type="password" name="password" required
                               class="w-full mt-1 rounded-lg border border-slate-300 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan password">
                    </div>

                

                    <!-- Button Login -->
                    <div class="mt-4">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition-colors">
                            Masuk
                        </button>
                    </div>
                </form>

                
            </div>
        </div>
    </div>
</div>
@endsection
