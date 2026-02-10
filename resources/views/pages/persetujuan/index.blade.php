@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Persetujuan Peminjaman</h1>
            <p class="text-slate-600 text-sm mt-1">Kelola permintaan peminjaman dari peminjam</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Cards Grid Section -->
    @if($peminjamans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($peminjamans as $peminjaman)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border-l-4 border-yellow-500">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 p-4 border-b border-slate-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Peminjam</p>
                                <p class="text-lg font-bold text-slate-900 mt-1">{{ $peminjaman->user->username ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-yellow-100 px-3 py-1 rounded-full">
                                <span class="text-xs font-bold text-yellow-800">MENUNGGU</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-4">
                        <!-- Alat Info -->
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Alat yang Dipinjam</p>
                            <p class="text-sm font-bold text-blue-900 mt-1 flex items-center space-x-2">
                                <i class="fas fa-tools"></i>
                                <span>{{ $peminjaman->alat->nama_alat ?? 'N/A' }}</span>
                            </p>
                        </div>

                        <!-- Details Row 1 -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Jumlah -->
                            <div class="bg-purple-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Jumlah</p>
                                <p class="text-lg font-bold text-purple-900 mt-1">{{ $peminjaman->jumlah }} Unit</p>
                            </div>

                            <!-- Kategori -->
                            <div class="bg-green-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Kategori</p>
                                <p class="text-lg font-bold text-green-900 mt-1">{{ $peminjaman->alat->kategori->nama_kategori ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Details Row 2 -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Tgl Peminjaman -->
                            <div class="bg-orange-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Tgl Peminjaman</p>
                                <p class="text-sm font-bold text-orange-900 mt-1">{{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}</p>
                            </div>

                            <!-- Tgl Kembali Rencana -->
                            <div class="bg-indigo-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Kembali Rencana</p>
                                <p class="text-sm font-bold text-indigo-900 mt-1">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <!-- Tujuan Peminjaman -->
                        @if($peminjaman->tujuan_peminjaman)
                            <div class="bg-slate-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Tujuan Peminjaman</p>
                                <p class="text-sm text-slate-700 mt-1 line-clamp-2">{{ $peminjaman->tujuan_peminjaman }}</p>
                            </div>
                        @endif

                        <!-- User Email -->
                        <div class="bg-cyan-50 rounded-lg p-3">
                            <p class="text-xs font-semibold text-cyan-600 uppercase tracking-wider">Email Peminjam</p>
                            <p class="text-sm text-cyan-900 mt-1 truncate">{{ $peminjaman->user->email ?? 'N/A' }}</p>
                        </div>

                        <!-- Duration Info -->
                        <div class="bg-slate-100 rounded-lg p-3">
                            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Durasi Peminjaman</p>
                            <p class="text-sm font-bold text-slate-900 mt-1">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)) }} Hari
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer - Action Buttons -->
                    <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 flex gap-2">
                        <!-- Approve Button -->
                        <form action="{{ route('persetujuan.approve', $peminjaman) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-2 px-3 rounded-lg transition-all duration-200 inline-flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                <i class="fas fa-check-circle"></i>
                                <span>Setujui</span>
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <form action="{{ route('persetujuan.reject', $peminjaman) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menolak peminjaman ini?');">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold py-2 px-3 rounded-lg transition-all duration-200 inline-flex items-center justify-center space-x-2 shadow-md hover:shadow-lg">
                                <i class="fas fa-times-circle"></i>
                                <span>Tolak</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <div class="mb-4">
                            <i class="fas fa-inbox text-6xl text-slate-300 mb-4 block"></i>
                        </div>
                        <p class="text-slate-500 font-medium text-xl">Tidak ada peminjaman yang menunggu persetujuan</p>
                        <p class="text-slate-400 text-sm mt-2">Semua permintaan peminjaman sudah diproses</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($peminjamans->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $peminjamans->links() }}
            </div>
        @endif
    @else
        <!-- Empty State - Full Page -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="mb-4">
                <i class="fas fa-inbox text-6xl text-slate-300 mb-4 block"></i>
            </div>
            <p class="text-slate-500 font-medium text-xl">Tidak ada peminjaman yang menunggu persetujuan</p>
            <p class="text-slate-400 text-sm mt-2">Semua permintaan peminjaman sudah diproses</p>
        </div>
    @endif

    <!-- Custom Pagination Styling -->
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .pagination .active span {
            background-color: #1e293b;
            color: white;
            border-color: #1e293b;
        }

        .pagination .disabled {
            color: #cbd5e1;
            cursor: not-allowed;
        }
    </style>
@endsection