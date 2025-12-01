<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
  body {
    font-family: 'Poppins', sans-serif;
  }


  .menu-link {
    color: #1e293b;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
  }

  .menu-link:hover {
    color: #1d4ed8;
    border-bottom: 2px solid #2563eb;
  }

  .menu-link.active {
    color: #1d4ed8;
    border-bottom: 2px solid #2563eb;
  }
</style>

<!-- 🟦 Navbar -->
<header class="fixed top-0 left-0 w-full flex [box-shadow:rgba(0,0,0,0.1)_-4px_9px_25px_-6px]
  py-4 px-4 sm:px-10 bg-white min-h-[75px] tracking-wide z-50">
  <div class="flex flex-wrap items-center gap-4 w-full">

    <a href="#">
      <img src="images\Logo LSP No BG.png" alt="logo" class="w-36" />
    </a>

    <div id="collapseMenu"
      class="lg:ml-12 max-lg:hidden lg:!block max-lg:before:fixed max-lg:before:bg-black max-lg:before:opacity-40 max-lg:before:inset-0 max-lg:before:z-50">

      <!-- Tombol Tutup Menu (Mobile) -->
      <button id="toggleClose"
        class="lg:hidden fixed top-2 right-4 z-[100] rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 fill-black" viewBox="0 0 320.591 320.591">
          <path
            d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"/>
          <path
            d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"/>
        </svg>
      </button>

      <!-- 🟩 Daftar Menu -->
      <ul class="lg:flex lg:gap-x-4 max-lg:space-y-3 max-lg:fixed max-lg:bg-white
        max-lg:w-2/3 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:p-4
        max-lg:h-full max-lg:shadow-md max-lg:overflow-auto z-50">

        <li class="max-lg:border-b max-lg:border-gray-300 max-lg:pb-4 px-3 lg:hidden">
          <a href="#">
            <img src="images\Logo LSP No BG.png" alt="logo" class="w-36" />
          </a>
        </li>

        <li><a href="#" class="menu-link active block font-medium text-base px-3 py-2">Home</a></li>
        <li><a href="#" class="menu-link block font-medium text-base px-3 py-2">Jadwal Asesmen</a></li>
        <li><a href="#" class="menu-link block font-medium text-base px-3 py-2">Sertifikasi</a></li>

        <li class="relative group px-3 py-2">
          <button class="flex items-center text-slate-900 font-medium text-base hover:text-blue-700 focus:outline-none">
            Info
            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6">
              <path d="m1 1 4 4 4-4" />
            </svg>
          </button>

          <ul class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50">
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Alur Proses</a></li>
            <li><hr class="my-0 border-gray-200"></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Daftar Asesor</a></li>
            <li><hr class="my-0 border-gray-200"></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">TUK</a></li>
          </ul>
        </li>

        <li class="relative group px-3 py-2">
          <button class="flex items-center text-slate-900 font-medium text-base hover:text-blue-700 focus:outline-none">
            Profil
            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 6">
              <path d="m1 1 4 4 4-4" />
            </svg>
          </button>

          <ul class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-2 w-44 border border-gray-100 z-50">
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Visi & Misi</a></li>
            <li><hr class="my-0 border-gray-200"></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Struktur</a></li>
            <li><hr class="my-0 border-gray-200"></li>
            <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mitra</a></li>
          </ul>
        </li>
      </ul>
    </div>

    <div class="flex ml-auto items-center">
      <button class="px-4 py-2 text-[15px] rounded-md font-medium text-white bg-blue-600 hover:bg-blue-700 cursor-pointer">
        Masuk
      </button>

      <div id="toggleOpen" class="flex ml-4 lg:hidden">
        <button class="cursor-pointer">
          <svg class="w-7 h-7" fill="#000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</header>


<div class="h-[80px]"></div>


<script>
  const toggleOpen = document.getElementById("toggleOpen");
  const toggleClose = document.getElementById("toggleClose");
  const collapseMenu = document.getElementById("collapseMenu");


  toggleOpen.addEventListener("click", () => {
    collapseMenu.classList.remove("max-lg:hidden");
  });
  toggleClose.addEventListener("click", () => {
    collapseMenu.classList.add("max-lg:hidden");
  });

  const menuLinks = document.querySelectorAll(".menu-link");
  menuLinks.forEach(link => {
    link.addEventListener("click", () => {
      menuLinks.forEach(l => l.classList.remove("active"));
      link.classList.add("active");
    });
  });
</script>
