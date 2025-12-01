<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>LSP Polines - @yield('title', 'Halaman Form')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body,
        input,
        textarea,
        select,
        button {
            font-family: 'Poppins', sans-serif;
        }
    </style>

</head>

<body x-data x-cloak class="bg-gray-50 min-h-screen flex">

    <x-sidebar.sidebar />

    {{-- KONTEN UTAMA --}}
    {{-- Perubahan: Tambahkan :class logic untuk mengatur margin kiri --}}
    <main
        class="flex-1 py-6 transition-all duration-300 ease-in-out min-w-0"
        :class="$store.sidebar.open ? 'lg:ml-80' : 'lg:ml-0'">

        {{-- 1. Tombol Buka (Mobile) - Tetap --}}
        <button
            class="lg:hidden fixed top-4 left-4 bg-blue-600 text-white p-3 rounded-full shadow-lg z-40"
            @click="$store.sidebar.setOpen(true)">
            ☰
        </button>

        {{-- 2. Tombol Buka (Desktop) - BARU --}}
        {{-- Tombol ini muncul HANYA di Desktop saat sidebar TERTUTUP --}}
        <button
            x-show="!$store.sidebar.open"
            class="hidden lg:flex fixed top-4 left-6 z-40 bg-white text-blue-600 border border-blue-100 p-2 rounded-lg shadow-md hover:bg-blue-50 transition items-center gap-2"
            @click="$store.sidebar.setOpen(true)"
            x-transition.opacity>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-sm font-semibold">Menu</span>
        </button>

        {{-- Area Konten --}}
        <div class="px-4 lg:px-8">
            @yield('content')
        </div>
    </main>

</body>

</html>