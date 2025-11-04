<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Jadwal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Script sederhana untuk toggle dropdown (opsional, tapi agar sesuai fungsi)
        document.addEventListener('DOMContentLoaded', function () {
            const userMenuButton = document.getElementById('userMenuButton');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownToggle = document.getElementById('dropdownToggle');

            if (userMenuButton) {
                userMenuButton.addEventListener('click', function (event) {
                    // Menghentikan event agar tidak langsung menutup
                    event.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });
            }
            
            // Menutup dropdown jika klik di luar
            document.addEventListener('click', function (event) {
                if (dropdownMenu && !dropdownMenu.classList.contains('hidden') && !userMenuButton.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
    </script>
</head>
<body class="bg-gray-100 font-sans">

    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            
            <div class="flex items-center space-x-6">
                <div class="w-10 h-10 flex items-center justify-center">
                    <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" width="100">
                </div>

                <nav class="absolute left-1/2 transform -translate-x-1/2 flex space-x-6 text-gray-700 font-medium">
                    <a href="{{ route('home') }}" class="text-blue-600 font-semibold underline">Home</a>
                    <a href="{{ route('jadwal') }}" class="hover:text-blue-600">Jadwal Asesmen</a>
                    <a href="{{ route('laporan') }}" class="hover:text-blue-600">Laporan</a>
                    <a href="{{ route('profil') }}" class="hover:text-blue-600">Profil</a>
                </nav>
=======
@extends('layouts.app-profil')
@section('content')

    <section class="relative h-[1000px] rounded-t-4xl overflow-hidden">
        <img src="{{ asset('images/Gedung Polines.jpg') }}"
            alt="Gedung Polines"
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#96C9F4]/95 via-[#96C9F4]/60 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full h-64 bg-gradient-to-t from-white/95 via-white/50 to-transparent"></div>
        <div class="absolute top-1/3 left-16 text-black drop-shadow-lg max-w-xl">
            <h1 class="text-6xl font-bold mb-4">LSP POLINES</h1>
            <p class="text-xl mb-6 leading-relaxed">Lorem ipsum dolor sit amet, you're the best person I've ever met!</p>
        </div>
    </section>

    <style>
        #scrollContainer::-webkit-scrollbar { display: none; }
        #scrollContainer { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Filter Kategori --}}
    <section class="py-10 text-center">
        <div id="scrollContainer" class="overflow-x-auto whitespace-nowrap px-6 cursor-grab active:cursor-grabbing select-none">
            <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
            <div class="inline-flex gap-4">
                <button class="btn btn-sm font-bold bg-yellow-400 text-black border-none rounded-full px-6">Semua</button>
                <button class="btn btn-sm font-bold bg-yellow-100 text-gray-700 border-none rounded-full px-6 hover:bg-yellow-200">Software</button>
                <button class="btn btn-sm font-bold bg-yellow-100 text-gray-700 border-none rounded-full px-6 hover:bg-yellow-200">IoT</button>
>>>>>>> 3ef7adc3e335d9d6c4534613859955b9a89479bc
            </div>

            <!-- Profil User -->
            <div class="relative">
                <div class="flex items-center space-x-3 cursor-pointer" id="userMenuButton">
                    <span class="text-gray-800 font-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                    <a href="{{ route('profil') }}">
                        <img src="{{ Auth::user()->photo_url ?? asset('images/default-profile.png') }}" 
                             alt="Foto Profil" 
                             class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
                    </a>
                    <!-- Tombol Dropdown -->
                    <button id="dropdownToggle" class="focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border border-gray-200">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN TABEL -->
    <main class="max-w-7xl mx-auto mt-8 px-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Halaman Belum Tersedia</h1>
    </main>
</body>
</html>