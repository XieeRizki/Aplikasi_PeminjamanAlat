@extends('layouts.app')

@section('title', 'Monitoring Pengembalian')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Monitoring Pengembalian</h1>
            <p class="text-slate-600 text-sm mt-1">Pantau pengembalian alat dari peminjam</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Cards Grid Section -->
    @if($pengembalians->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($pengembalians as $pengembalian)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border-l-4 border-blue-500">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 p-4 border-b border-slate-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Peminjam</p>
                                <p class="text-lg font-bold text-slate-900 mt-1">{{ $pengembalian->peminjaman->user->username ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-blue-100 px-3 py-1 rounded-full">
                                <span class="text-xs font-bold text-blue-800">SELESAI</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-4">
                        <!-- Alat Info -->
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Alat</p>
                            <p class="text-sm font-bold text-blue-900 mt-1 flex items-center space-x-2">
                                <i class="fas fa-tools"></i>
                                <span>{{ $pengembalian->peminjaman->alat->nama_alat ?? 'N/A' }}</span>
                            </p>
                        </div>

                        <!-- Details Row 1 -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Jumlah -->
                            <div class="bg-purple-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Jumlah</p>
                                <p class="text-lg font-bold text-purple-900 mt-1">{{ $pengembalian->peminjaman->jumlah }} Unit</p>
                            </div>

                            <!-- Kondisi Alat -->
                            <div class="rounded-lg p-3 {{ $pengembalian->kondisi_alat === 'baik' ? 'bg-green-50' : 'bg-red-50' }}">
                                <p class="text-xs font-semibold {{ $pengembalian->kondisi_alat === 'baik' ? 'text-green-600' : 'text-red-600' }} uppercase tracking-wider">Kondisi</p>
                                <p class="text-lg font-bold {{ $pengembalian->kondisi_alat === 'baik' ? 'text-green-900' : 'text-red-900' }} mt-1">
                                    {{ ucfirst($pengembalian->kondisi_alat) }}
                                </p>
                            </div>
                        </div>

                        <!-- Details Row 2 -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Tgl Peminjaman -->
                            <div class="bg-orange-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Tgl Pinjam</p>
                                <p class="text-sm font-bold text-orange-900 mt-1">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_peminjaman)->format('d/m/Y') }}</p>
                            </div>

                            <!-- Tgl Pengembalian -->
                            <div class="bg-indigo-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Tgl Kembali</p>
                                <p class="text-sm font-bold text-indigo-900 mt-1">{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual)->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <!-- Rencana vs Aktual -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Rencana -->
                            <div class="bg-cyan-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-cyan-600 uppercase tracking-wider">Rencana Kembali</p>
                                <p class="text-sm font-bold text-cyan-900 mt-1">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_kembali_rencana)->format('d/m/Y') }}</p>
                            </div>

                            <!-- Status Keterlambatan -->
                            @php
                                $tglRencana = \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_kembali_rencana);
                                $tglAktual = \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual);
                                $terlambat = $tglAktual->isAfter($tglRencana);
                                $hariTerlambat = $tglAktual->diffInDays($tglRencana);
                            @endphp
                            <div class="rounded-lg p-3 {{ $terlambat ? 'bg-red-50' : 'bg-green-50' }}">
                                <p class="text-xs font-semibold {{ $terlambat ? 'text-red-600' : 'text-green-600' }} uppercase tracking-wider">Status</p>
                                <p class="text-sm font-bold {{ $terlambat ? 'text-red-900' : 'text-green-900' }} mt-1">
                                    @if($terlambat)
                                        Terlambat {{ $hariTerlambat }} Hari
                                    @else
                                        Tepat Waktu
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        @if($pengembalian->keterangan)
                            <div class="bg-slate-50 rounded-lg p-3">
                                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Keterangan</p>
                                <p class="text-sm text-slate-700 mt-1 line-clamp-2">{{ $pengembalian->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <i class="fas fa-inbox text-6xl text-slate-300 mb-4 block"></i>
                        <p class="text-slate-500 font-medium text-xl">Belum ada data pengembalian</p>
                        <p class="text-slate-400 text-sm mt-2">Pengembalian alat akan muncul di sini</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($pengembalians->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $pengembalians->links() }}
            </div>
        @endif
    @else
        <!-- Empty State - Full Page -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-inbox text-6xl text-slate-300 mb-4 block"></i>
            <p class="text-slate-500 font-medium text-xl">Belum ada data pengembalian</p>
            <p class="text-slate-400 text-sm mt-2">Pengembalian alat akan muncul di sini</p>
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