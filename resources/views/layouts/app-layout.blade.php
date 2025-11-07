<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LSP Polines') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
      body {
        font-family: 'Poppins', sans-serif;
      }
      /* Custom CSS menu link BISA DIHAPUS jika tidak perlu */
      .menu-link {
        color: #1e293b; /* slate-900 */
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
        padding-bottom: 2px; /* Jarak border bawah */
      }
      .menu-link:hover {
        color: #1d4ed8; /* biru */
        border-bottom-color: #2563eb; /* Cukup ganti warna border */
      }
      /* Logic 'active' sebaiknya dihandle server-side/Alpine */
      .menu-link.active {
        color: #1d4ed8; /* biru */
        border-bottom-color: #2563eb;
        font-weight: 600; /* Tebalkan yang aktif */
      }
    </style>

</head>
<body class="font-sans antialiased bg-base-100">
    <div class="min-h-screen flex flex-col">

        {{-- Kita pake Alpine.js buat toggle menu mobile --}}
        <header x-data="{ open: false }" class="sticky top-0 left-0 w-full flex items-center bg-white shadow-md min-h-[75px] py-4 px-4 sm:px-10 z-50">
            <div class="flex flex-wrap items-center gap-4 w-full">
                <a href="/">
                    {{-- Ganti ke logo LSP Polines --}}
                    <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-10 sm:h-12 w-auto" />
                </a>

                <nav class="lg:ml-12 lg:flex lg:items-center lg:gap-x-5 hidden" :class="{'!block max-lg:fixed max-lg:inset-0 max-lg:bg-black/40 max-lg:z-50' : open}">
                     <div class="lg:flex lg:gap-x-5 max-lg:fixed max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:p-4 max-lg:h-full max-lg:shadow-md max-lg:overflow-auto z-[60]">

                        <div class="flex items-center justify-between lg:hidden pb-4 mb-4 border-b">
                            <a href="/">
                                <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="Logo LSP Polines" class="h-10 w-auto" />
                            </a>
                            <button @click="open = false" class="p-2 border rounded-full hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-black" viewBox="0 0 320.591 320.591">
                                    <path d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"/>
                                    <path d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"/>
                                </svg>
                            </button>
                        </div>

                        <ul class="lg:flex lg:gap-x-5 max-lg:space-y-3">
                            {{-- Contoh link aktif pake request()->is('/') --}}
                            <li><a href="/" class="menu-link {{ request()->is('/') ? 'active' : '' }} block font-medium text-base px-3 py-2">Home</a></li>
                            <li><a href="#" class="menu-link block font-medium text-base px-3 py-2">Skema</a></li>
                            <li><a href="#" class="menu-link block font-medium text-base px-3 py-2">Jadwal Asesmen</a></li>
                            <li><a href="#" class="menu-link block font-medium text-base px-3 py-2">Sertifikasi</a></li>
                            <li><a href="#" class="menu-link block font-medium text-base px-3 py-2">Info</a></li>
                            <li><a href="#" class="menu-link block font-medium text-base px-3 py-2">Profil</a></li>
                            {{-- Dropdown bisa dibuat komponen terpisah nanti --}}
                        </ul>
                    </div>
                </nav>

                <div class="flex ml-auto items-center">
                    {{-- Tombol Masuk HANYA SATU --}}
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline btn-primary hidden sm:inline-flex"> {{-- Sembunyikan di layar extra small --}}
                        Masuk
                    </a>

                    <button @click="open = true" class="ml-4 lg:hidden">
                        <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            {{ $slot }} {{-- Konten home.blade.php akan masuk di sini --}}
        </main>

        <footer class="bg-blue-700 text-white py-10 px-4">
           {{-- Kode Footer lo --}}
           <div class="text-center">
                <h3 class="text-xl font-semibold mb-2">Tingkatkan Kompetensi Profesional Anda</h3>
                <p class="max-w-xl mx-auto mb-4 text-sm sm:text-base">
                    LSP Polines berkomitmen meningkatkan tenaga kerja kompeten siap bersaing di dunia industri secara nasional maupun internasional.
                </p>
                <a href="#" class="btn bg-white text-blue-700 font-semibold border-none hover:bg-blue-100 mb-8">Hubungi Kami</a>
                <div class="text-xs sm:text-sm text-gray-200 space-y-1">
                    <p>Jl. Prof. Soedarto, SH, Tembalang, Semarang, Jawa Tengah</p>
                    <p>Email: lsp@polines.ac.id | Telp: (024) 7465407 ext. 125</p>
                    <p>© {{ date('Y') }} LSP POLINES - All rights reserved</p>
                </div>
            </div>
        </footer>

    </div>
</body>
</html>
