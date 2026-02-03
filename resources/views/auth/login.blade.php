<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card Login -->
        <div class="bg-white rounded-lg shadow-2xl p-8 border-t-4 border-slate-800">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-slate-800 p-3 rounded-lg">
                        <i class="fas fa-wrench text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 mb-2">Sistem Peminjaman Alat</h1>
                <p class="text-slate-600 text-sm">Management System</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <!-- Username -->
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Username</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-3 text-slate-400 text-sm"></i>
                        <input type="text" name="username" required 
                            class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-3 text-slate-400 text-sm"></i>
                        <input type="password" name="password" id="password" required 
                            class="w-full pl-9 pr-10 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-transparent transition-all duration-200 text-sm"
                            placeholder="Masukkan password">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-eye text-sm" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                @if($errors->has('login'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg text-sm flex items-center space-x-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span>{{ $errors->first('login') }}</span>
                    </div>
                @endif

                <!-- Login Button -->
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl mt-6">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 pt-6 border-t border-slate-200">
                <p class="text-center text-slate-600 text-xs">
                    Demo Username: <span class="font-semibold text-slate-900">admin</span> | 
                    Password: <span class="font-semibold text-slate-900">admin123</span>
                </p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4 border border-white border-opacity-20">
            <p class="text-white text-xs text-center leading-relaxed">
                <i class="fas fa-shield-alt mr-1.5 text-slate-300"></i>
                Sistem ini hanya dapat diakses oleh pengguna yang terdaftar
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>