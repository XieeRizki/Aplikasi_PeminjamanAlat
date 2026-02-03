{{-- OPSI 4: Executive Style - Fixed Dimensions --}}
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

        <!-- User Section - Executive Style -->
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-3 px-4 py-1.5 bg-slate-50 rounded border border-slate-200">
                <div class="w-8 h-8 bg-slate-800 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-semibold">{{ strtoupper(substr($username ?? 'A', 0, 1)) }}</span>
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-900 leading-tight">{{ $username ?? 'Administrator' }}</div>
                    <div class="text-xs text-slate-500">Administrator</div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-1.5 bg-white border-2 border-slate-800 text-slate-800 hover:bg-slate-800 hover:text-white rounded text-xs font-semibold transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-1.5"></i>Logout
                </button>
            </form>
        </div>
    </div>
</header>