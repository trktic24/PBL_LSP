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
        </div>
    </section>

<<<<<<< HEAD
            <div class="relative">
                <div class="flex items-center space-x-3 cursor-pointer" id="userMenuButton">
                    <span class="text-gray-800 font-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                    <a href="{{ route('profil') }}">
                        <img src="{{ Auth::user()->photo_url ?? asset('images/default-profile.png') }}" 
                            alt="Foto Profil" 
                            class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
                    </a>
                    <button id="dropdownToggle" class="focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border border-gray-200 z-10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Log Out
                        </button>
                    </form>
=======
    <script>
        const scrollContainer = document.getElementById("scrollContainer");
        let isDown = false, startX, scrollLeft;
        scrollContainer.addEventListener("mousedown", (e) => {
            isDown = true; scrollContainer.classList.add("active");
            startX = e.pageX - scrollContainer.offsetLeft; scrollLeft = scrollContainer.scrollLeft;
        });
        scrollContainer.addEventListener("mouseleave", () => { isDown = false; });
        scrollContainer.addEventListener("mouseup", () => { isDown = false; });
        scrollContainer.addEventListener("mousemove", (e) => {
            if (!isDown) return; e.preventDefault();
            const x = e.pageX - scrollContainer.offsetLeft; const walk = (x - startX) * 2;
            scrollContainer.scrollLeft = scrollLeft - walk;
        });
    </script>

    {{-- Carousel Grid Skema (Masih Statis) --}}
    <section class="px-10 mb-16">
        @php
            $slide1Images = ['skema1.jpg','skema2.jpg','skema3.jpg','skema4.jpg','skema5.jpg','skema6.jpg',];
            $slide2Images = ['skema7.jpg','skema8.jpg','skema9.jpg','skema10.jpg','skema11.jpg','skema12.jpg',];
        @endphp
        <div id="gridCarousel" class="relative overflow-hidden rounded-3xl">
            <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full shrink-0 p-6">
                    @foreach ($slide1Images as $index => $file)
                        <div class="transition hover:scale-105">
                            <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3"><img src="{{ asset('images/' . $file) }}" alt="Skema {{ $index + 1 }}" class="h-48 w-full object-cover"></div>
                            <div class="px-2"><h2 class="text-lg font-bold text-gray-800">Skema {{ $index + 1 }} A</h2><p class="text-gray-600">Rp. x.xxx.xxx</p></div>
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full shrink-0 p-6">
                    @foreach ($slide2Images as $index => $file)
                        <div class="transition hover:scale-105">
                            <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3"><img src="{{ asset('images/' . $file) }}" alt="Skema {{ $index + 7 }}" class="h-48 w-full object-cover"></div>
                            <div class="px-2"><h2 class="text-lg font-bold text-gray-800">Skema {{ $index + 7 }} B</h2><p class="text-gray-600">Rp. x.xxx.xxx</p></div>
                        </div>
                    @endforeach
>>>>>>> 3ef7adc3e335d9d6c4534613859955b9a89479bc
                </div>
            </div>
        </div>
    </section>

<<<<<<< HEAD
    <main class="max-w-7xl mx-auto mt-8 px-6 pb-12">
        
        <div class="flex items-center space-x-5 mb-10">
            <img src="{{ Auth::user()->photo_url ?? asset('images/default-profile.png') }}" 
                alt="Foto Profil" 
                class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 grayscale">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Selamat Datang Ajeng!</h1>
                <p class="text-xl font-semibold text-gray-800 mt-1">Ajeng Febria Hidayati</p>
                <p class="text-base text-gray-600">90973646526352</p>
                <p class="text-base text-gray-600">Pemrograman</p>
            </div>
        </div>

        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">5</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Belum Direview</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">7</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Dalam Proses</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">4</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Telah Direview</span>
                    </div>
                    <div class="text-6xl opacity-70">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l-3 3-1.5-1.5" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">18</span>
                        <span class="text-base font-medium mt-1">Jumlah<br>Asesi</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Jadwal Anda</h2>
            </div>

            <div class="bg-amber-50 rounded-xl border border-gray-300 shadow-sm overflow-hidden">
                <div class="flex px-6 py-3 border-b-2 border-black">
                    <span class="w-1/12 text-sm font-semibold text-gray-700">No</span>
                    <span class="w-5/12 text-sm font-semibold text-gray-700">Skema Sertifikasi</span>
                    <span class="w-4/12 text-sm font-semibold text-gray-700">Tanggal</span>
                    <span class="w-2/12 text-sm font-semibold text-gray-700">Aksi</span>
                </div>
                
                <div class="divide-y divide-gray-200">
                    <div class="flex px-6 py-4 items-center">
                        <span class="w-1/12 text-sm text-gray-800">1</span>
                        <span class="w-5/12 text-sm text-gray-800 font-medium">Junior Web Dev</span>
                        <span class="w-4/12 text-sm text-gray-800">29 September 2025</span>
                        <span class="w-2/12">
                            <a href="#" class="bg-yellow-400 text-black text-xs font-bold py-1 px-3 rounded-md hover:bg-yellow-500">Lihat Detail</a>
                        </span>
                    </div>
                    <div class="flex px-6 py-4 items-center">
                        <span class="w-1/12 text-sm text-gray-800">2</span>
                        <span class="w-5/12 text-sm text-gray-800 font-medium">Data Science</span>
                        <span class="w-4/12 text-sm text-gray-800">24 November 2025</span>
                        <span class="w-2/12">
                            <a href="#" class="bg-yellow-400 text-black text-xs font-bold py-1 px-3 rounded-md hover:bg-yellow-500">Lihat Detail</a>
                        </span>
                    </div>
                    <div class="flex px-6 py-4 items-center">
                        <span class="w-1/12 text-sm text-gray-800">3</span>
                        <span class="w-5/12 text-sm text-gray-800 font-medium">Programming</span>
                        <span class="w-4/12 text-sm text-gray-800">30 November 2025</span>
                        <span class="w-2/12">
                            <a href="#" class="bg-yellow-400 text-black text-xs font-bold py-1 px-3 rounded-md hover:bg-yellow-500">Lihat Detail</a>
                        </span>
                    </div>
                    <div class="flex px-6 py-4 items-center">
                        <span class="w-1/12 text-sm text-gray-800">4</span>
                        <span class="w-5/12 text-sm text-gray-800 font-medium">Game Dev</span>
                        <span class="w-4/12 text-sm text-gray-800">4 Januari 2025</span>
                        <span class="w-2/12">
                            <a href="#" class="bg-yellow-400 text-black text-xs font-bold py-1 px-3 rounded-md hover:bg-yellow-500">Lihat Detail</a>
                        </span>
                    </div>
                    <div class="flex px-6 py-4 items-center">
                        <span class="w-1/12 text-sm text-gray-800">5</span>
                        <span class="w-5/12 text-sm text-gray-800 font-medium">Cyber Security</span>
                        <span class="w-4/12 text-sm text-gray-800">10 Januari 2025</span>
                        <span class="w-2/12">
                            <a href="#" class="bg-yellow-400 text-black text-xs font-bold py-1 px-3 rounded-md hover:bg-yellow-500">Lihat Detail</a>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="text-right mt-4">
                <a href="#" class="text-sm text-blue-600 hover:underline font-medium">Lihat Selengkapnya</a>
            </div>
        </div>

    </main>
</body>
</html>
=======
    <script>
        const gridSlides = document.getElementById('gridSlides'); const slides = document.querySelectorAll('#gridSlides > div');
        const totalSlides = slides.length; let currentIndex = 0;
        function showSlide(index) { gridSlides.style.transform = `translateX(-${index * 100}%)`; }
        setInterval(() => { currentIndex = (currentIndex + 1) % totalSlides; showSlide(currentIndex); }, 5000);
    </script>

    {{-- ====================================================== --}}
    {{-- BAGIAN "Jadwal yang Akan Datang" (SUDAH DIPERBAIKI) --}}
    {{-- ====================================================== --}}
    <section class="bg-gray-50 py-12 px-10 text-center">
        <h2 class="text-3xl font-bold mb-8">Jadwal yang Akan Datang</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-6xl mx-auto">
            
            {{-- Perulangan ini akan berjalan JIKA data dummy Anda ada --}}
            @foreach($jadwals as $jadwal)
                <div class="card bg-white shadow-lg rounded-2xl">
                    <div class="card-body flex flex-col p-8">
                        <p class="text-base mb-1 font-bold text-left">Sertifikasi:</p>
                        <p class="text-base mb-3 text-left">{{ $jadwal->nama_skema }}</p>
                        
                        <p class="text-base mb-1 font-bold text-left">TUK:</p>
                        <p class="text-base mb-3 text-left">{{ $jadwal->tuk }}</p>
                        
                        <p class="text-base mb-1 font-bold text-left">Tanggal:</p>
                        <p class="text-base mb-6 text-left">{{ $jadwal->tanggal->format('d F Y') }}</p>
                        
                        {{-- Ini adalah link yang benar --}}
                        <a href="{{ route('jadwal.detail', ['id' => $jadwal->id]) }}" 
                           class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300 px-8 py-3 rounded-full text-base">
                           Detail
                        </a>
                    </div>
                </div>
            @endforeach

            @if($jadwals->isEmpty())
                <p class="text-gray-600 col-span-1 md:col-span-2">Belum ada jadwal yang akan datang saat ini. (Silakan jalankan `php artisan migrate:fresh --seed`)</p>
            @endif

        </div>
    </section>
@endsection
>>>>>>> 3ef7adc3e335d9d6c4534613859955b9a89479bc
