@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Cetak Laporan</h1>
                <p class="text-slate-600 text-sm mt-1">Data peminjaman untuk laporan</p>
            </div>
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 inline-flex items-center space-x-2 shadow-md hover:shadow-lg">
                <i class="fas fa-print"></i>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ $peminjamans->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-list text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Menunggu</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $peminjamans->where('status', 'menunggu')->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Disetujui</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $peminjamans->where('status', 'disetujui')->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Selesai</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $peminjamans->where('status', 'selesai')->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-check text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Peminjaman</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Kembali Rencana</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tujuan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($peminjamans as $index => $peminjaman)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-slate-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $peminjaman->user->username ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-500">{{ $peminjaman->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-900 font-semibold">{{ $peminjaman->alat->nama_alat ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-bold">{{ $peminjaman->jumlah }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusColors = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                                    'disetujui' => 'bg-green-100 text-green-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'selesai' => 'bg-blue-100 text-blue-800',
                                ];
                                $statusColor = $statusColors[$peminjaman->status] ?? 'bg-slate-100 text-slate-800';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                {{ ucfirst($peminjaman->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ $peminjaman->tujuan_peminjaman ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            <p class="font-medium">Belum ada data peminjaman</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Print CSS -->
    <style>
        @media print {
            body {
                background: white !important;
            }
            button {
                display: none !important;
            }
            .bg-white {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
            }
            .grid {
                display: none !important;
            }
        }
    </style>
@endsection