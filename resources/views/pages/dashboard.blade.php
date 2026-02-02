@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Section -->
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-slate-900 mb-1">Dashboard</h1>
        <p class="text-slate-600 text-sm">Selamat datang kembali, {{ session('username') ?? 'Administrator' }}</p>
    </div>

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <!-- Total Alat -->
        <div class="bg-white rounded shadow p-4 border-l-2 border-slate-800 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-xs font-semibold uppercase tracking-widest">Total Alat</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">5</p>
                    <p class="text-slate-500 text-xs font-medium mt-1">Alat tersedia</p>
                </div>
                <div class="bg-slate-100 p-2 rounded">
                    <i class="fas fa-wrench text-slate-800 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Peminjaman -->
        <div class="bg-white rounded shadow p-4 border-l-2 border-slate-700 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-xs font-semibold uppercase tracking-widest">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">2</p>
                    <p class="text-slate-500 text-xs font-medium mt-1">Peminjaman aktif</p>
                </div>
                <div class="bg-slate-100 p-2 rounded">
                    <i class="fas fa-clipboard-list text-slate-700 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Pengembalian -->
        <div class="bg-white rounded shadow p-4 border-l-2 border-slate-700 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-xs font-semibold uppercase tracking-widest">Total Pengembalian</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">1</p>
                    <p class="text-slate-500 text-xs font-medium mt-1">Sudah dikembalikan</p>
                </div>
                <div class="bg-slate-100 p-2 rounded">
                    <i class="fas fa-check-circle text-slate-700 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Denda -->
        <div class="bg-white rounded shadow p-4 border-l-2 border-slate-700 hover:shadow-md transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-xs font-semibold uppercase tracking-widest">Total Denda</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">Rp 0</p>
                    <p class="text-slate-500 text-xs font-medium mt-1">Tidak ada denda</p>
                </div>
                <div class="bg-slate-100 p-2 rounded">
                    <i class="fas fa-money-bill-wave text-slate-700 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="bg-white rounded shadow p-5 border-l-2 border-slate-800 mb-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-2 flex items-center">
                    Selamat Datang! 
                    <i class="fas fa-hand-sparkles text-slate-600 ml-2 text-sm"></i>
                </h3>
                <p class="text-slate-700 leading-relaxed text-sm">
                    Sistem Peminjaman Alat ini membantu Anda mengelola peminjaman alat dengan efisien dan terorganisir. 
                    Gunakan menu di sidebar untuk mengakses berbagai fitur manajemen.
                </p>
                <div class="mt-3 flex space-x-2">
                    <a href="{{ route('peminjaman.index') }}" class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center transition-all duration-200">
                        <i class="fas fa-plus mr-1.5"></i>Ajukan Peminjaman
                    </a>
                    <a href="{{ route('laporan.index') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1.5 rounded text-xs font-semibold inline-flex items-center transition-all duration-200">
                        <i class="fas fa-chart-line mr-1.5"></i>Lihat Laporan
                    </a>
                </div>
            </div>
            <div class="bg-slate-100 p-2 rounded flex-shrink-0">
                <i class="fas fa-info-circle text-slate-700 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Recent Activity -->
        <div class="bg-white rounded shadow p-4 border-t border-slate-800">
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center">
                <i class="fas fa-history text-slate-800 mr-2 text-xs"></i>
                Aktivitas Terbaru
            </h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <div>
                        <p class="text-slate-900 font-medium text-sm">Peminjaman Alat</p>
                        <p class="text-slate-500 text-xs">Hari ini</p>
                    </div>
                    <span class="bg-slate-200 text-slate-800 px-2 py-0.5 rounded text-xs font-semibold">Baru</span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <div>
                        <p class="text-slate-900 font-medium text-sm">Pengembalian Alat</p>
                        <p class="text-slate-500 text-xs">Kemarin</p>
                    </div>
                    <span class="bg-slate-200 text-slate-800 px-2 py-0.5 rounded text-xs font-semibold">Selesai</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-900 font-medium text-sm">User Baru</p>
                        <p class="text-slate-500 text-xs">2 hari lalu</p>
                    </div>
                    <span class="bg-slate-200 text-slate-800 px-2 py-0.5 rounded text-xs font-semibold">User</span>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white rounded shadow p-4 border-t border-slate-700">
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center">
                <i class="fas fa-server text-slate-700 mr-2 text-xs"></i>
                Informasi Sistem
            </h3>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                    <span class="text-slate-700 font-medium text-sm">Status Sistem</span>
                    <span class="bg-slate-200 text-slate-800 px-2 py-0.5 rounded text-xs font-semibold flex items-center">
                        <i class="fas fa-circle text-slate-600 mr-1.5 text-xs"></i>
                        Aktif
                    </span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                    <span class="text-slate-700 font-medium text-sm">Total Users</span>
                    <span class="text-slate-900 font-bold text-sm">5</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-700 font-medium text-sm">Update Terakhir</span>
                    <span class="text-slate-800 font-semibold text-sm">{{ date('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection