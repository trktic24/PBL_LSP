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

    {{-- FOOTER --}}
    <footer class="bg-blue-900 text-white mt-20">
        <div class="max-w-6xl mx-auto text-center py-12 px-4">
            <h2 class="text-sm uppercase tracking-widest opacity-80 mb-2">Sertifikasi Profesi untuk Karier Anda</h2>
            <h1 class="text-2xl md:text-3xl font-semibold mb-3">Tingkatkan Kompetensi Profesional Anda</h1>
            <p class="max-w-2xl mx-auto text-sm md:text-base opacity-90">
                LSP Polines berkomitmen menghasilkan tenaga kerja kompeten yang siap bersaing dan diakui secara nasional maupun internasional.
            </p>

            <a href="#" class="inline-block bg-white text-blue-700 font-semibold mt-6 px-6 py-3 rounded-full shadow hover:bg-blue-50 transition">
                Hubungi Kami
            </a>
        </div>

        <div class="border-t border-white/20 py-8">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center px-6 gap-6">
                <div class="text-center md:text-left">
                    <p>Jl. Prof. Soedarto, SH, Tembalang, Semarang, Jawa Tengah.</p>
                    <p class="mt-1">(024) 7473417 ext.256 | lsp@polines.ac.id</p>
                    <div class="flex items-center justify-center md:justify-start mt-4">
                        <img src="{{ asset('logo_lsp_polines.png') }}" alt="Logo" class="h-8 mr-2 filter invert brightness-0">
                        <span class="font-bold">LSP POLINES</span>
                    </div>
                </div>
                <div class="text-center md:text-right text-sm opacity-80">
                    <p>© 2025 LSP POLINES. All rights reserved.</p>
                    <div class="mt-2 flex justify-center md:justify-end space-x-3">
                        <a href="#" class="hover:text-blue-300">in</a>
                        <a href="#" class="hover:text-blue-300">f</a>
                        <a href="#" class="hover:text-blue-300">o</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
