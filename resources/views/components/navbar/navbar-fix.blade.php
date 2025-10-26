@props(['active' => 'home'])

<header class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.1)] py-4 px-4 sm:px-10 z-50">
  <div class="flex flex-wrap items-center gap-4 w-full">

    {{-- 🟦 Logo --}}
    <a href="/">
      <img src="images/Logo LSP No BG.png" alt="logo" class="w-20">
    </a>

    {{-- 🟦 Menu utama --}}
    <nav id="collapseMenu"
      class="lg:ml-12 max-lg:hidden lg:!block max-lg:fixed max-lg:bg-white max-lg:w-2/3 max-lg:min-w-[300px]
             max-lg:top-0 max-lg:left-0 max-lg:h-full max-lg:shadow-md max-lg:p-4 max-lg:overflow-auto z-40">

      {{-- Tombol close (mobile) --}}
      <button id="toggleClose"
        class="lg:hidden fixed top-2 right-4 z-[100] rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">
        ✕
      </button>

      <ul class="lg:flex lg:gap-x-4 max-lg:space-y-3">

        {{-- 🟩 Logo versi mobile --}}
        <li class="max-lg:border-b max-lg:border-gray-300 max-lg:pb-4 px-3 lg:hidden">
          <a href="/">
            <img src="images/Logo LSP No BG.png" alt="logo" class="w-20">
          </a>
        </li>

        {{-- 🟦 Menu utama --}}
        @php
          $menus = [
            ['name' => 'Home', 'href' => 'home'],
            ['name' => 'Jadwal Asesmen', 'href' => 'jadwal'],
            ['name' => 'Sertifikasi', 'href' => 'sertifikasi'],
          ];
        @endphp

        @foreach ($menus as $menu)
          <li>
            <a href="#"
              class="block font-medium text-base px-3 py-2 border-b-2 transition-all duration-200
              {{ $active === $menu['href']
                ? 'text-blue-700 border-blue-600'
                : 'text-slate-900 border-transparent hover:text-blue-700 hover:border-blue-600' }}">
              {{ $menu['name'] }}
            </a>
          </li>
        @endforeach

        {{-- 🔽 Dropdown Info --}}
        <li class="relative group px-3 py-2">
          <button class="dropdown-btn flex items-center text-slate-900 font-medium text-base hover:text-blue-700 focus:outline-none">
            Info
            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6">
              <path d="m1 1 4 4 4-4" />
            </svg>
          </button>

          <ul class="dropdown-menu absolute hidden bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50">
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Alur Proses</a></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Daftar Asesor</a></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">TUK</a></li>
          </ul>
        </li>

        {{-- 🔽 Dropdown Profil --}}
        <li class="relative group px-3 py-2">
          <button class="dropdown-btn flex items-center text-slate-900 font-medium text-base hover:text-blue-700 focus:outline-none">
            Profil
            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6">
              <path d="m1 1 4 4 4-4" />
            </svg>
          </button>

          <ul class="dropdown-menu absolute hidden bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50">
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Visi & Misi</a></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Struktur</a></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mitra</a></li>
          </ul>
        </li>
      </ul>
    </nav>

    {{-- 🟦 Tombol kanan --}}
    <div class="flex ml-auto items-center">
      <button class="px-4 py-2 text-[15px] rounded-md font-medium text-white bg-blue-600 hover:bg-blue-700 cursor-pointer">
        Masuk
      </button>

      {{-- Tombol toggle menu (mobile) --}}
      <button id="toggleOpen" class="flex ml-4 lg:hidden">
        <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20">
          <path fill-rule="evenodd"
            d="M3 5h14M3 10h14M3 15h14"
            stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
        </svg>
      </button>
    </div>
  </div>
</header>

{{-- Spacer biar konten gak ketimpa navbar --}}
<div class="h-[80px]"></div>

{{-- 🟦 Script --}}
<script>
  const toggleOpen = document.getElementById("toggleOpen");
  const toggleClose = document.getElementById("toggleClose");
  const collapseMenu = document.getElementById("collapseMenu");

  toggleOpen?.addEventListener("click", () => collapseMenu.classList.remove("max-lg:hidden"));
  toggleClose?.addEventListener("click", () => collapseMenu.classList.add("max-lg:hidden"));

  // Dropdown click handler
  document.querySelectorAll(".dropdown-btn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      btn.nextElementSibling.classList.toggle("hidden");
    });
  });

  // Tutup dropdown saat klik di luar
  document.addEventListener("click", () => {
    document.querySelectorAll(".dropdown-menu").forEach(menu => menu.classList.add("hidden"));
  });
</script>
