<aside class="w-56 bg-slate-800 min-h-screen shadow-xl overflow-hidden">
    <nav class="py-4">
        <!-- Dashboard - SEMUA ROLE -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
            <i class="fas fa-chart-bar w-5"></i>
            <span class="ml-3">Dashboard</span>
        </a>

        @auth
            <!-- ======================== ADMIN MENU ======================== -->
            @if(auth()->user() && auth()->user()->level === 'admin')
                <div class="px-5 py-2 mt-2 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Admin Menu</p>
                </div>

                <!-- Users Management -->
                <a href="{{ route('users.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('users.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Users</span>
                </a>

                <!-- Kategori Management -->
                <a href="{{ route('kategori.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('kategori.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-folder w-5"></i>
                    <span class="ml-3">Kategori</span>
                </a>

                <!-- Log Aktivitas -->
                <a href="{{ route('log.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('log.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-history w-5"></i>
                    <span class="ml-3">Log Aktivitas</span>
                </a>
            @endif

            <!-- ======================== ADMIN & PETUGAS MENU ======================== -->
            @if(auth()->user() && (auth()->user()->level === 'admin' || auth()->user()->level === 'petugas'))
                @if(auth()->user()->level === 'petugas')
                    <div class="px-5 py-2 mt-2 mb-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Petugas Menu</p>
                    </div>
                @else
                    <div class="px-5 py-2 mt-2 mb-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Management</p>
                    </div>
                @endif

                <!-- Kelola Alat -->
                <a href="{{ route('alat.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('alat.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-tools w-5"></i>
                    <span class="ml-3">Kelola Alat</span>
                </a>

                <!-- Peminjaman Management -->
                <a href="{{ route('peminjaman.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('peminjaman.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span class="ml-3">Persetujuan Peminjaman</span>
                </a>

                <!-- Pengembalian Management -->
                <a href="{{ route('pengembalian.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('pengembalian.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-undo w-5"></i>
                    <span class="ml-3">Monitoring Pengembalian</span>
                </a>

                <!-- Laporan -->
                <a href="{{ route('laporan.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('laporan.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3">Laporan</span>
                </a>
            @endif

            <!-- ======================== PEMINJAM MENU ======================== -->
            @if(auth()->user() && auth()->user()->level === 'peminjam')
                <div class="px-5 py-2 mt-2 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Peminjam Menu</p>
                </div>

                <!-- Daftar Alat -->
                <a href="{{ route('alat.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('alat.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-list-check w-5"></i>
                    <span class="ml-3">Daftar Alat</span>
                </a>

                <!-- Mengajukan Peminjaman -->
                <a href="{{ route('peminjaman.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('peminjaman.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-hand-paper w-5"></i>
                    <span class="ml-3">Ajukan Peminjaman</span>
                </a>

                <!-- Mengembalikan Alat -->
                <a href="{{ route('riwayat-peminjaman.index') }}" class="flex items-center px-5 py-3 {{ request()->routeIs('riwayat-peminjaman.*') ? 'bg-slate-700 text-white border-l-4 border-emerald-500 font-semibold' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition-all duration-200">
                    <i class="fas fa-undo-alt w-5"></i>
                    <span class="ml-3">Pengembalian Alat</span>
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