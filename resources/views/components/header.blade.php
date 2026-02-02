<header class="bg-white shadow border-b-2 border-slate-800">
    <div class="max-w-full px-5 py-3 flex justify-between items-center">
        <!-- Logo Section -->
        <div class="flex items-center space-x-2">
            <div class="bg-slate-800 p-2 rounded">
                <i class="fas fa-wrench text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight">Sistem Peminjaman Alat</h1>
                <p class="text-slate-500 text-xs font-medium">Management System</p>
            </div>
        </div>

        <!-- User Section -->
        <div class="flex items-center space-x-3">
            <div class="bg-slate-100 px-3 py-1.5 rounded border border-slate-200">
                <span class="text-slate-700 text-xs font-bold uppercase tracking-wider">Admin</span>
            </div>
            <div class="h-6 w-px bg-slate-300"></div>
            <span class="text-slate-700 text-sm font-medium">{{ $username ?? 'Administrator' }}</span>
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-1.5 rounded text-xs font-semibold transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-1.5"></i>Logout
                </button>
            </form>
        </div>
    </div>
</header>