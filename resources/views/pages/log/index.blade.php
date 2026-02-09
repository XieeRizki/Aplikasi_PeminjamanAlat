@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    <!-- Header Section -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Log Aktivitas Sistem</h1>
            <p class="text-slate-600 text-sm mt-0.5">Pantau semua aktivitas pengguna di sistem</p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded shadow overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-widest">No</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-widest">Waktu</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-widest">User</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-widest">Modul</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-widest">Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-4 py-2 text-xs font-medium text-slate-700">
                                {{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}
                            </td>
                            <td class="px-4 py-2 text-xs text-slate-600 whitespace-nowrap">
                                {{ $log->timestamp->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-2 text-xs">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs">
                                        <i class="fas fa-user text-slate-600"></i>
                                    </div>
                                    <span class="font-semibold text-slate-900">{{ $log->user->username ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-2 text-xs">
                                @php
                                    $modulColor = match($log->modul) {
                                        'Auth' => 'bg-purple-100 text-purple-800',
                                        'User' => 'bg-blue-100 text-blue-800',
                                        'Alat' => 'bg-green-100 text-green-800',
                                        'Kategori' => 'bg-yellow-100 text-yellow-800',
                                        'Peminjaman' => 'bg-orange-100 text-orange-800',
                                        'Pengembalian' => 'bg-cyan-100 text-cyan-800',
                                        'Laporan' => 'bg-indigo-100 text-indigo-800',
                                        default => 'bg-slate-100 text-slate-800'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded font-semibold text-xs {{ $modulColor }}">
                                    {{ $log->modul }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-slate-700">
                                <span class="inline-flex items-center space-x-1.5">
                                    @php
                                        $icon = match($log->modul) {
                                            'Auth' => 'fa-lock',
                                            'User' => 'fa-users',
                                            'Alat' => 'fa-wrench',
                                            'Kategori' => 'fa-folder',
                                            'Peminjaman' => 'fa-clipboard-list',
                                            'Pengembalian' => 'fa-undo',
                                            'Laporan' => 'fa-file-pdf',
                                            default => 'fa-circle'
                                        };
                                    @endphp
                                    <i class="fas {{ $icon }} text-slate-400 text-xs"></i>
                                    <span>{{ $log->aktivitas }}</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-slate-300 text-3xl mb-2"></i>
                                    <p class="text-slate-500 font-medium text-sm">Belum ada log aktivitas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="mt-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    Menampilkan <span class="font-semibold">{{ $logs->firstItem() }}</span> hingga 
                    <span class="font-semibold">{{ $logs->lastItem() }}</span> dari 
                    <span class="font-semibold">{{ $logs->total() }}</span> data
                </div>
                <nav class="flex items-center space-x-1">
                    <!-- Previous Page -->
                    @if ($logs->onFirstPage())
                        <span class="px-3 py-1.5 text-sm font-medium text-slate-400 bg-slate-100 rounded cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                        @if ($page == $logs->currentPage())
                            <span class="px-3 py-1.5 text-sm font-bold text-white bg-slate-800 rounded">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    <!-- Next Page -->
                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-1.5 text-sm font-medium text-slate-400 bg-slate-100 rounded cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    @endif
@endsection