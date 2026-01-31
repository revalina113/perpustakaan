<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - PERPUSTAKAAN')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-60 bg-gray-800 text-white flex flex-col fixed h-screen">
            <div class="h-14 bg-gray-900 flex items-center justify-center flex-shrink-0">
                <h1 class="text-xl font-bold">PERPUSTAKAAN</h1>
            </div>

            <!-- Menu -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.buku.index') }}" class="flex items-center px-4 py-2 rounded text-sm font-semibold {{ request()->routeIs('admin.buku.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Kelola Data Buku
                </a>
                <a href="{{ route('admin.transaksi.index') }}" class="flex items-center px-4 py-2 rounded text-sm font-semibold {{ request()->routeIs('admin.transaksi.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Transaksi
                </a>
                <a href="{{ route('admin.jatuh-tempo.index') }}" class="flex items-center px-4 py-2 rounded text-sm font-semibold {{ request()->routeIs('admin.jatuh-tempo.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Jatuh Tempo & Denda
                </a>
                <a href="{{ route('admin.anggota.index') }}" class="flex items-center px-4 py-2 rounded text-sm font-semibold {{ request()->routeIs('admin.anggota.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M9 20H4v-2a3 3 0 015.856-1.487M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Kelola Anggota
                </a>
                <a href="{{ route('admin.aturan-peminjaman.index') }}" class="flex items-center px-4 py-2 rounded text-sm font-semibold {{ request()->routeIs('admin.aturan-peminjaman.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Aturan Peminjaman
                </a>
            </nav>

            <!-- Logout -->
            <div class="px-4 py-4 border-t border-gray-700">
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-semibold text-gray-300 hover:bg-gray-700 rounded">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>

        <!-- Main Content -->
        <div class="ml-60 flex-1 overflow-auto">
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>