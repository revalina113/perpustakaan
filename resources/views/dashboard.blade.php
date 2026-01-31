<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Dashboard Siswa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold text-slate-800">
                    Halo, {{ Auth::user()->username }} 👋
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Selamat datang di sistem perpustakaan
                </p>
            </div>

            <!-- Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-600">
                    <p class="text-sm text-slate-500">Status Akun</p>
                    <h3 class="text-xl font-semibold text-slate-800 mt-2">
                        Aktif
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow p-6 border-l-4 border-slate-400">
                    <p class="text-sm text-slate-500">Role</p>
                    <h3 class="text-xl font-semibold text-slate-800 mt-2">
                        Siswa
                    </h3>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
