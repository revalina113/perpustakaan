<nav class="bg-[var(--primary)] shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-white font-bold text-xl">
                    PerpusKU
                </a>
            </div>

            <!-- Menu -->
            <div class="flex items-center space-x-4">
                @auth
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-gray-200">Dashboard Admin</a>
                        <a href="{{ route('admin.buku.index') }}" class="text-white hover:text-gray-200">Buku</a>
                        <a href="{{ route('admin.jatuh-tempo.index') }}" class="text-white hover:text-gray-200">Jatuh Tempo</a>
                        <a href="{{ route('admin.pembayaran-denda.index') }}" class="text-white hover:text-gray-200">Pembayaran Denda</a>
                    @else
                        <a href="{{ route('siswa.dashboard') }}" class="text-white hover:text-gray-200">Dashboard Siswa</a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="text-white hover:text-gray-200">Profile</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:text-gray-200">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-gray-200">Login</a>
                    <a href="{{ route('register') }}" class="text-white hover:text-gray-200">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
