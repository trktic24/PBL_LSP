<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP Polines - @yield('title', 'Beranda')</title>

    {{-- Tambahkan Tailwind & JS dari Laravel Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- HEADER --}}
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img src="{{ asset('logo_lsp_polines.png') }}" alt="LSP Polines Logo" class="h-10 mr-3">
                <span class="font-bold text-blue-800 text-lg">LSP POLINES</span>
            </div>

            <nav class="flex space-x-4">
                <a href="#" class="nav-link text-gray-700 hover:text-blue-700 font-medium transition">Home</a>
                <a href="#" class="nav-link text-gray-700 hover:text-blue-700 font-medium transition">Jadwal Asesmen</a>
                <a href="#" class="nav-link text-gray-700 hover:text-blue-700 font-medium transition">Sertifikasi</a>
                
                <div class="relative group">
                    <button class="nav-link text-gray-700 hover:text-blue-700 font-medium transition flex items-center">
                        Info <span class="text-xs ml-1">▼</span>
                    </button>
                    {{-- Dropdown --}}
                    <div class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-2 z-10">
                        <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Alur Sertifikasi</a>
                        <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Daftar TUK</a>
                    </div>
                </div>

                <div class="relative group">
                    <button class="nav-link text-gray-700 hover:text-blue-700 font-medium transition flex items-center">
                        Profil <span class="text-xs ml-1">▼</span>
                    </button>
                    {{-- Dropdown --}}
                    <div class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-2 z-10">
                        <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Tentang Kami</a>
                        <a href="#" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Kontak</a>
                    </div>
                </div>

                <a href="#" class="nav-link text-gray-700 hover:text-blue-700 font-medium transition">Masuk</a>
            </nav>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    

</body>
</html>
