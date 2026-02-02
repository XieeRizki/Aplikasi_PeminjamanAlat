<aside class="w-56 bg-slate-800 min-h-screen shadow border-r-2 border-slate-900 p-4 overflow-hidden">
    <nav class="space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-chart-bar text-base"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Alat -->
        <a href="{{ route('alat.index') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('alat.*') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-wrench text-base"></i>
            <span class="font-medium">Alat</span>
        </a>

        <!-- Peminjaman -->
        <a href="{{ route('peminjaman.index') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('peminjaman.*') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-clipboard-list text-base"></i>
            <span class="font-medium">Peminjaman</span>
        </a>

        <!-- Pengembalian -->
        <a href="{{ route('pengembalian.index') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('pengembalian.*') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-undo text-base"></i>
            <span class="font-medium">Pengembalian</span>
        </a>

        <!-- Users -->
        <a href="{{ route('users.index') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('users.*') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-users text-base"></i>
            <span class="font-medium">Users</span>
        </a>

        <!-- Kategori -->
        <a href="{{ route('kategori.index') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('kategori.*') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-folder text-base"></i>
            <span class="font-medium">Kategori</span>
        </a>

        <!-- Laporan -->
        <a href="{{ route('laporan.index') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('laporan.*') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-file-alt text-base"></i>
            <span class="font-medium">Laporan</span>
        </a>

        <!-- Log Aktivitas -->
        <a href="{{ route('log.index') }}" class="flex items-center space-x-2.5 px-3 py-2.5 {{ request()->routeIs('log.*') ? 'bg-slate-700 text-white rounded shadow-sm' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} rounded transition-all duration-200 text-sm">
            <i class="fas fa-history text-base"></i>
            <span class="font-medium">Log Aktivitas</span>
        </a>

        <!-- Divider -->
        <div class="my-2 border-t border-slate-700"></div>

        <!-- Settings -->
        <button onclick="alert('Settings coming soon')" class="w-full flex items-center space-x-2.5 px-3 py-2.5 text-slate-300 hover:bg-slate-700 hover:text-white rounded transition-all duration-200 text-sm">
            <i class="fas fa-cog text-base"></i>
            <span class="font-medium">Settings</span>
        </button>
    </nav>
</aside>