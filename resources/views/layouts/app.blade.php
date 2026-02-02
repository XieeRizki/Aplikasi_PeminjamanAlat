<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-50">
    <!-- Header Component (Fixed) -->
    <div class="fixed top-0 left-0 right-0 z-50">
        <x-header :username="session('username')" />
    </div>

    <div class="flex pt-16">
        <!-- Sidebar Component (Fixed) -->
        <div class="fixed left-0 top-16 bottom-0 w-56">
            <x-sidebar />
        </div>

        <!-- Main Content -->
        <main class="ml-56 flex-1 p-5 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>