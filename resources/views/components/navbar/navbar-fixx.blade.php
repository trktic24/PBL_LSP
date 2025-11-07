@props(['active' => 'home'])

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<header
  x-data="{ openMenu: false, openDropdown: false }"
  class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.1)] py-4 px-6 sm:px-12 z-50 font-[Poppins]"
>
  <div class="flex items-center justify-between w-full max-w-7xl mx-auto">

    {{-- 🟦 Logo --}}
    <a href="{{ url('/') }}">
      <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" alt="logo" class="w-20">
    </a>

    {{-- 🟦 Menu utama --}}
    <nav
      :class="{ 'max-lg:hidden': !openMenu }"
      class="lg:flex lg:flex-1 lg:justify-center max-lg:fixed max-lg:flex max-lg:flex-col max-lg:justify-between
             max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:h-full
             max-lg:shadow-md max-lg:p-4 max-lg:overflow-auto z-40 mx-auto transition-all"
    >
      {{-- Tombol close (mobile) --}}
      <button
        @click="openMenu = false"
        class="lg:hidden fixed top-2 right-4 z-[100] rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer"
      >
        ✕
      </button>

      {{-- 🟩 Menu utama --}}
      <ul class="lg:flex lg:items-center lg:justify-center lg:gap-x-8 max-lg:space-y-4">
        {{-- 🟩 Logo versi mobile --}}
        <li class="max-lg:border-b max-lg:border-gray-300 max-lg:pb-4 px-3 lg:hidden">
          <a href="{{ url('/') }}">
            <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" alt="logo" class="w-20">
          </a>
        </li>

        {{-- 🟦 Menu --}}
        @php
          $menus = [
            ['name' => 'Home', 'href' => route('home')],
            ['name' => 'Jadwal', 'href' => route('jadwal.index')],
            ['name' => 'Profil', 'href' => route('profil')],
          ];
        @endphp

        @foreach ($menus as $menu)
          <li>
            <a href="{{ $menu['href'] }}"
              class="block font-medium text-[15px] px-2 py-2 border-b-2 transition-all duration-200
              {{ request()->is(ltrim(parse_url($menu['href'], PHP_URL_PATH), '/'))
                ? 'text-blue-700 border-blue-600'
                : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600' }}">
              {{ $menu['name'] }}
            </a>
          </li>
        @endforeach
      </ul>

      {{-- 🟨 Profil User (mobile: bawah sidebar) --}}
      <div class="lg:hidden mt-auto border-t border-gray-200 pt-4 relative" x-data="{ openDropdownMobile: false }">
        <div class="flex items-center justify-between px-2">
          {{-- klik foto/nama -> pindah ke profil --}}
          <a
            href="{{ route('profil') }}"
            class="flex items-center space-x-3 cursor-pointer select-none"
          >
            <img
              src="{{ Auth::user()->photo_url ?? asset('images/profil_asesor.jpeg') }}"
              alt="Foto Profil"
              class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover"
            >
            <span class="text-gray-800 font-semibold">
              {{ Auth::user()->name ?? 'User' }}
            </span>
          </a>

          {{-- tombol panah untuk toggle dropdown (logout) --}}
          <button @click="openDropdownMobile = !openDropdownMobile" class="p-2 rounded-md focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
        </div>

        {{-- dropdown mobile (muncul di atas area profil) --}}
        <div
          x-show="openDropdownMobile"
          @click.away="openDropdownMobile = false"
          x-transition.opacity.scale.95
          class="absolute right-4 bottom-14 w-40 bg-white rounded-md shadow-lg border border-gray-200 z-50"
        >
          <form action="{{ route('logout') }}" method="POST" class="px-2 py-2">
            @csrf
            <button
              type="submit"
              class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md"
            >
              Log Out
            </button>
          </form>
        </div>
      </div>
    </nav>

    {{-- 🟦 Profil di pojok kanan (desktop) --}}
    <div
      x-data="{ openDropdown: false }"
      class="hidden lg:flex items-center space-x-3 cursor-pointer select-none relative"
    >
      {{-- Klik ke profil --}}
      <a href="{{ route('profil') }}" class="flex items-center space-x-3">
        <span class="text-gray-800 font-semibold">
          {{ Auth::user()->name ?? 'User' }}
        </span>
        <img
          src="{{ Auth::user()->photo_url ?? asset('images/profil_asesor.jpeg') }}"
          alt="Foto Profil"
          class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover"
        >
      </a>

      {{-- Dropdown Toggle --}}
      <button @click="openDropdown = !openDropdown" class="focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      {{-- Dropdown desktop --}}
      <div
        x-show="openDropdown"
        @click.away="openDropdown = false"
        x-transition.opacity.scale.80
        class="absolute right-0 top-[60px] w-40 bg-white rounded-md shadow-lg border border-gray-200 z-50"
      >
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button
            type="submit"
            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
          >
            Log Out
          </button>
        </form>
      </div>
    </div>

    {{-- 🟦 Tombol toggle menu (mobile) --}}
    <button @click="openMenu = true" class="flex ml-2 lg:hidden">
      <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M3 5h14M3 10h14M3 15h14"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
        ></path>
      </svg>
    </button>

  </div>
</header>

<div class="h-[80px]"></div>
