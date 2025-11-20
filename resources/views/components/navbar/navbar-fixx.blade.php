@props(['active' => 'home'])

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

{{--
    LOGIKA PHP
--}}
@php
    $user = auth()->user();
    $role = $user?->role->nama_role;

    // 1. URL LOGO
    $logoUrl = ($role === 'asesor') ? route('home.index') : url('/');

    // 2. MENU UTAMA (TENGAH)
    $menus = [];

    if (!$user) {
        // --- TAMU (GUEST) ---
        $menus = [
            ['name' => 'Home', 'href' => url('/'), 'active_check' => Request::is('/')],
            ['name' => 'Jadwal Asesmen', 'href' => url('/jadwal'), 'active_check' => Request::is('jadwal')],
            // ✅ PERMINTAAN 1: Menu Sertifikasi tetap ada
            ['name' => 'Sertifikasi', 'href' => url('/login'), 'active_check' => false],
        ];
    } elseif ($role === 'asesor') {
        // --- ASESOR ---
        $menus = [
            ['name' => 'Home', 'href' => route('home.index'), 'active_check' => Route::is('home.index')],
            ['name' => 'Jadwal', 'href' => route('jadwal.index'), 'active_check' => Request::is('asesor/jadwal*')],
            ['name' => 'Profil', 'href' => route('profil.show'), 'active_check' => Request::is('asesor/myprofil*')],
        ];
    } elseif ($role === 'asesi') {
        // --- ASESI ---
        $menus = [
            ['name' => 'Dashboard', 'href' => url('/dashboard'), 'active_check' => Request::is('dashboard')],
            ['name' => 'Sertifikasi', 'href' => url('/riwayat-sertifikasi'), 'active_check' => Request::is('riwayat-sertifikasi')],
        ];
    }
@endphp

<header
  x-data="{ openMenu: false, openInfo: false, openProfil: false, openUser: false }"
  class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.1)] py-4 px-6 sm:px-12 z-50 font-[Poppins]"
>
  <div class="flex items-center justify-between w-full max-w-7xl mx-auto">

    {{-- 🟦 LOGO (KIRI) --}}
    <a href="{{ $logoUrl }}" class="flex-shrink-0">
      <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" alt="logo" class="w-20">
    </a>

    {{-- Overlay Mobile --}}
    <div x-show="openMenu" x-transition.opacity @click="openMenu = false" class="lg:hidden fixed inset-0 bg-black/50 z-40" x-cloak></div>

    {{-- 🟦 NAVIGASI UTAMA (TENGAH) --}}
    <nav
      :class="{'max-lg:translate-x-0': openMenu, 'max-lg:-translate-x-full': !openMenu}"
      class="lg:flex lg:flex-1 lg:justify-center lg:items-center
             max-lg:fixed max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px]
             max-lg:top-0 max-lg:left-0 max-lg:h-full max-lg:shadow-md max-lg:p-4 max-lg:overflow-auto
             z-50 mx-auto transition-transform duration-300"
      x-cloak
    >
      {{-- Tombol Close (Mobile) --}}
      <button @click="openMenu = false" class="lg:hidden fixed top-3 right-4 z-60 rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">✕</button>

      <ul class="lg:flex lg:items-center lg:justify-center lg:gap-x-8 max-lg:space-y-4">

        {{-- Logo versi mobile --}}
        <li class="max-lg:border-b max-lg:border-gray-300 max-lg:pb-4 px-3 lg:hidden">
          <a href="{{ $logoUrl }}">
            <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" alt="logo" class="w-20">
          </a>
        </li>

        {{-- 🟩 LOOP MENU UTAMA --}}
        @foreach ($menus as $menu)
          <li>
            <a href="{{ $menu['href'] }}"
              class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
              {{ $menu['active_check'] ? 'text-blue-700 border-blue-600' : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600' }}">
              {{ $menu['name'] }}
            </a>
          </li>
        @endforeach

        {{-- 🟨 DROPDOWN INFO & PROFIL (Hanya di Mobile atau disatukan di tengah untuk Desktop) --}}
        @guest
            {{-- Di Desktop, Info & Profil publik saya letakkan di sini agar rapi di tengah --}}
            <li class="relative px-2 py-2 group">
                <button @click="openInfo = !openInfo; openProfil = false" class="inline-flex items-center gap-1 text-slate-900 font-medium text-[15px] hover:text-blue-700 focus:outline-none">
                    <span>Info</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6"><path d="m1 1 4 4 4-4" /></svg>
                </button>
                {{-- Dropdown Menu --}}
                <ul x-show="openInfo" @click.away="openInfo = false" class="lg:absolute lg:top-full lg:left-0 bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50 max-lg:w-full max-lg:shadow-none max-lg:border-none max-lg:pl-4" x-transition>
                    <li><a href="{{ url('/alur-sertifikasi') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Alur Proses</a></li>
                    <li><a href="{{ url('/daftar-asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Daftar Asesor</a></li>
                    <li><a href="{{ url('/info-tuk') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">TUK</a></li>
                </ul>
            </li>

            <li class="relative px-2 py-2 group">
                <button @click="openProfil = !openProfil; openInfo = false" class="inline-flex items-center gap-1 text-slate-900 font-medium text-[15px] hover:text-blue-700 focus:outline-none">
                    <span>Profil</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6"><path d="m1 1 4 4 4-4" /></svg>
                </button>
                 {{-- Dropdown Menu --}}
                <ul x-show="openProfil" @click.away="openProfil = false" class="lg:absolute lg:top-full lg:left-0 bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50 max-lg:w-full max-lg:shadow-none max-lg:border-none max-lg:pl-4" x-transition>
                    <li><a href="{{ url('/visimisi') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Visi & Misi</a></li>
                    <li><a href="{{ url('/struktur') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Struktur</a></li>
                    <li><a href="{{ url('/mitra') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mitra</a></li>
                </ul>
            </li>
        @endguest

        {{-- 🔻 MENU MOBILE: Login/User --}}
        <li class="lg:hidden border-t border-gray-200 pt-4 mt-4 px-2 space-y-2">
          @auth
            <div class="mb-3 px-2">
              <p class="font-semibold text-gray-900 truncate">{{ Auth::user()->nama_lengkap ?? 'User' }}</p>
            </div>
            <a href="{{ ($role === 'asesor') ? route('home.index') : url('/dashboard') }}" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Dashboard</a>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="block w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-md">Logout</button>
            </form>
          @else
             {{-- Tombol Masuk Mobile --}}
            <a href="{{ url('/login') }}" class="block w-full text-center px-3 py-2 text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md font-medium">Masuk</a>
          @endauth
        </li>
      </ul>
    </nav>

    {{-- 🟦 BAGIAN KANAN (MASUK / PROFIL USER) --}}
    <div class="flex items-center justify-end gap-3">

      @guest
        {{-- ✅ PERMINTAAN 2: Tombol Masuk di Desktop (Pojok Kanan) --}}
        {{-- Posisi ini memastikan tombol ada di sebelah kanan, terpisah dari menu tengah --}}
        <a href="{{ url('/login') }}"
           class="hidden lg:inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-full hover:bg-blue-700 transition shadow-sm">
           Masuk
        </a>
      @endguest

      @auth
        {{-- Dropdown Profil User (Desktop) --}}
        <div class="hidden lg:block relative" x-data="{ openUser: false }">
          <button @click="openUser = !openUser" class="flex items-center gap-2 rounded-full py-1 pl-2 pr-3 hover:bg-gray-100 transition">
            @if($role === 'asesor')
                 <img src="{{ Auth::user()->asesor?->url_foto ?? asset('images/profil_asesor.jpeg') }}" class="w-8 h-8 rounded-full border border-gray-300 object-cover" alt="Avatar">
            @else
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-medium text-xs">
                  {{ substr(Auth::user()->nama_lengkap ?? 'U', 0, 2) }}
                </span>
            @endif
            <span class="font-medium text-[15px] text-slate-900 max-w-[100px] truncate">
              {{ Auth::user()->nama_lengkap ?? 'User' }}
            </span>
            <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6"><path d="m1 1 4 4 4-4" /></svg>
          </button>

          <div x-show="openUser" @click.away="openUser = false" x-transition class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 shadow-lg rounded-md z-50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200">
              <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->nama_lengkap }}</p>
              <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
            <div class="py-1">
              <a href="{{ ($role === 'asesor') ? route('home.index') : url('/dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
              <a href="{{ ($role === 'asesor') ? route('profil.show') : url('/profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
            </div>
            <div class="py-1 border-t border-gray-200">
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-b-md">Logout</button>
              </form>
            </div>
          </div>
        </div>
      @endauth

      {{-- Toggle Menu (Mobile) --}}
      <button @click="openMenu = true" class="flex ml-2 lg:hidden text-slate-900">
        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

  </div>
</header>

<div class="h-[80px]"></div>