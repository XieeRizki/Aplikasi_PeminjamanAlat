<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Peminjaman</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-800 px-4">
        <div class="w-full max-w-md">
            <!-- Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-slate-900 text-white px-6 py-8 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-tools text-slate-900 text-xl"></i>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold">Aplikasi Peminjaman</h1>
                    <p class="text-slate-300 text-sm mt-1">Sistem Manajemen Alat</p>
                </div>

                <!-- Body -->
                <div class="p-8">
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Username</label>
                            <input
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200 @error('username') border-red-500 @enderror"
                                required
                            >
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Password</label>
                            <input
                                type="password"
                                name="password"
                                placeholder="Masukkan password"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all duration-200"
                                required
                            >
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>