@extends('layouts.app')

@section('title', 'Monitoring Pengembalian')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Monitoring Pengembalian</h1>
                <p class="text-slate-600 text-sm mt-1">Pantau pengembalian alat dari peminjam</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Kembali Rencana</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Tgl Kembali Aktual</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($pengembalians as $index => $pengembalian)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ ($pengembalians->currentPage() - 1) * $pengembalians->perPage() + $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-slate-900">
                            {{ $pengembalian->peminjaman->user->username ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-900">
                            {{ $pengembalian->peminjaman->alat->nama_alat ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $pengembalian->peminjaman->jumlah }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_kembali_rencana)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali_aktual)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($pengembalian->kondisi_alat === 'baik')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Baik</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Rusak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Selesai</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                            <i class="fas fa-inbox text-3xl mb-2 block"></i>
                            Belum ada data pengembalian
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $pengembalians->links() }}
        </div>
    </div>
@endsection