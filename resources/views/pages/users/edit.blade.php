@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-2xl">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('users.index') }}" class="text-slate-600 hover:text-slate-900 inline-flex items-center space-x-2 mb-4">
                <i class="fas fa-chevron-left"></i>
                <span>Kembali</span>
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Edit User</h1>
            <p class="text-slate-600 text-sm mt-1">Ubah informasi pengguna</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <p class="font-semibold text-sm mb-3">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white rounded-lg shadow-lg overflow-hidden border border-slate-200">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-6">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-900 mb-2">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        placeholder="Masukkan username"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200 @error('username') border-red-500 ring-2 ring-red-500 @enderror"
                        required
                    >
                    @error('username')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200 @error('password') border-red-500 ring-2 ring-red-500 @enderror"
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Level -->
                <div>
                    <label for="level" class="block text-sm font-semibold text-slate-900 mb-2">Level</label>
                    <select
                        id="level"
                        name="level"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200 @error('level') border-red-500 ring-2 ring-red-500 @enderror"
                        required
                    >
                        <option value="">-- Pilih Level --</option>
                        <option value="admin" {{ old('level', $user->level) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ old('level', $user->level) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="peminjam" {{ old('level', $user->level) === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                    </select>
                    @error('level')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Info -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <p class="text-xs font-semibold text-slate-900 mb-2">Informasi User:</p>
                    <ul class="space-y-1 text-xs text-slate-600">
                        <li><strong>Username:</strong> {{ $user->username }}</li>
                        <li><strong>Level:</strong> {{ ucfirst($user->level) }}</li>
                        <li><strong>Dibuat:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                        <li><strong>Terakhir Update:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</li>
                    </ul>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-50 px-8 py-4 flex items-center justify-between border-t border-slate-200">
                <a href="{{ route('users.index') }}" class="text-slate-600 hover:text-slate-900 font-medium text-sm">
                    Batal
                </a>
                <button
                    type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 px-6 rounded-lg transition-all duration-200 flex items-center space-x-2"
                >
                    <i class="fas fa-sync"></i>
                    <span>Update User</span>
                </button>
            </div>
        </form>
    </div>
@endsection