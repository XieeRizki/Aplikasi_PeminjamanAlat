@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="max-w-2xl">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('users.index') }}" class="text-slate-600 hover:text-slate-900 inline-flex items-center space-x-2 mb-4">
                <i class="fas fa-chevron-left"></i>
                <span>Kembali</span>
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Tambah User Baru</h1>
            <p class="text-slate-600 text-sm mt-1">Buat akun pengguna baru untuk sistem</p>
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
        <form method="POST" action="{{ route('users.store') }}" class="bg-white rounded-lg shadow-lg overflow-hidden border border-slate-200">
            @csrf

            <div class="p-8 space-y-6">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-900 mb-2">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
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
                        placeholder="Masukkan password (minimal 6 karakter)"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200 @error('password') border-red-500 ring-2 ring-red-500 @enderror"
                        required
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
                        <option value="admin" {{ old('level') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ old('level') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="peminjam" {{ old('level') === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                    </select>
                    @error('level')
                        <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Level Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs font-semibold text-blue-900 mb-3">Penjelasan Level:</p>
                    <ul class="space-y-2 text-xs text-blue-800">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle text-xs mt-1"></i>
                            <span><strong>Admin:</strong> Akses penuh semua fitur dan manajemen user</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle text-xs mt-1"></i>
                            <span><strong>Petugas:</strong> Akses kelola alat dan peminjaman</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-circle text-xs mt-1"></i>
                            <span><strong>Peminjam:</strong> Akses ajukan peminjaman alat</span>
                        </li>
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
                    <i class="fas fa-save"></i>
                    <span>Simpan User</span>
                </button>
            </div>
        </form>
    </div>
@endsection