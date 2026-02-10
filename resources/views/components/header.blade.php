{{-- Header Dynamic based on Auth User --}}
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

        <!-- User Section - Dynamic -->
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-3 px-4 py-1.5 bg-slate-50 rounded border border-slate-200">
                <!-- Avatar with Initial -->
                <div class="w-8 h-8 bg-slate-800 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </span>
                </div>
                
                <!-- User Info -->
                <div>
                    <div class="text-sm font-semibold text-slate-900 leading-tight">
                        {{ auth()->user()->username }}
                    </div>
                    <div class="text-xs text-slate-500">
                        @if(auth()->user()->isAdmin())
                            <i class="fas fa-user-shield mr-1"></i>Administrator
                        @elseif(auth()->user()->isPetugas())
                            <i class="fas fa-user-tie mr-1"></i>Petugas
                        @elseif(auth()->user()->isPeminjam())
                            <i class="fas fa-user mr-1"></i>Peminjam
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-1.5 bg-white border-2 border-slate-800 text-slate-800 hover:bg-slate-800 hover:text-white rounded text-xs font-semibold transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-1.5"></i>Logout
                </button>
            </form>
        </div>
    </div>
</header>