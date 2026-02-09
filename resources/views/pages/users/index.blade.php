@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <!-- Header Section -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Manajemen User</h1>
            <p class="text-slate-600 text-sm mt-1">Kelola pengguna sistem peminjaman alat</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-200 shadow-lg">
            <i class="fas fa-plus text-sm"></i>
            <span>Tambah User</span>
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center shadow-sm">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <!-- Table Header -->
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center">
                                        <i class="fas fa-user text-slate-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm font-medium text-slate-900">{{ $user->username }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $levelBadge = match($user->level) {
                                        'admin' => 'bg-red-100 text-red-800',
                                        'petugas' => 'bg-blue-100 text-blue-800',
                                        'peminjam' => 'bg-green-100 text-green-800',
                                        default => 'bg-slate-100 text-slate-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $levelBadge }}">
                                    {{ ucfirst($user->level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition-all duration-200 inline-flex items-center space-x-1">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold transition-all duration-200 inline-flex items-center space-x-1" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                            <i class="fas fa-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="fas fa-inbox text-slate-300 text-5xl"></i>
                                    <p class="text-slate-500 font-medium">Belum ada user</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection