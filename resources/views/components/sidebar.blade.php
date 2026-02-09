<aside class="w-56 bg-slate-800 min-h-screen shadow-xl overflow-hidden">
    <nav class="py-4">
        <!-- Dashboard - SEMUA ROLE -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
            <i class="fas fa-chart-bar w-5"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        @auth
            <!-- ADMIN ONLY MENU -->
            @if(auth()->user() && auth()->user()->level === 'admin')
                <!-- Users -->
                <a href="{{ route('users.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('users.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Users</span>
                </a>

                <!-- Alat -->
                <a href="{{ route('alat.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('alat.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-wrench w-5"></i>
                    <span class="ml-3">Alat</span>
                </a>

                <!-- Kategori -->
                <a href="{{ route('kategori.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('kategori.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-folder w-5"></i>
                    <span class="ml-3">Kategori</span>
                </a>

                <!-- Peminjaman -->
                <a href="{{ route('peminjaman.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('peminjaman.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span class="ml-3">Peminjaman</span>
                </a>

                <!-- Pengembalian -->
                <a href="{{ route('pengembalian.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('pengembalian.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-undo w-5"></i>
                    <span class="ml-3">Pengembalian</span>
                </a>

                <!-- Persetujuan -->
                <a href="{{ route('persetujuan.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('persetujuan.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-check-circle w-5"></i>
                    <span class="ml-3">Persetujuan</span>
                </a>

                <!-- Laporan -->
                <a href="{{ route('laporan.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('laporan.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3">Laporan</span>
                </a>

                <!-- Log Aktivitas -->
                <a href="{{ route('log.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('log.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-history w-5"></i>
                    <span class="ml-3">Log Aktivitas</span>
                </a>
            @endif

            <!-- PEMINJAM & PETUGAS MENU -->
            @if(auth()->user() && (auth()->user()->level === 'peminjam' || auth()->user()->level === 'petugas'))
                <!-- Daftar Alat -->
                <a href="{{ route('daftar-alat.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('daftar-alat.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-list w-5"></i>
                    <span class="ml-3">Daftar Alat</span>
                </a>

                <!-- Riwayat Peminjaman -->
                <a href="{{ route('riwayat-peminjaman.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('riwayat-peminjaman.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-history w-5"></i>
                    <span class="ml-3">Riwayat Peminjaman</span>
                </a>
            @endif

            <!-- Divider -->
            <div class="my-4 border-t border-slate-700"></div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="px-5">
                @csrf
                <button type="submit" class="w-full flex items-center text-slate-300 hover:text-white hover:bg-slate-700 px-3 py-3 rounded transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        @endauth
    </nav>
</aside>