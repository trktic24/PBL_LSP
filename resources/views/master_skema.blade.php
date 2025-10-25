<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Skema</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
    <script src="//unpkg.com/alpinejs" defer></script>
  <style>
    /* Garis biru di bawah menu aktif */
    /* Catatan: Gaya ini dipertahankan, meskipun navbar baru menggunakan inline Tailwind CSS */
    .nav-active {
      position: relative;
      color: #2563eb; /* biru-600 */
      font-weight: 600;
    }
    .nav-active::after {
      content: "";
      position: absolute;
      bottom: -6px;
      left: 0;
      width: 100%;
      height: 3px;
      background-color: #2563eb;
      border-radius: 9999px;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
        <div class="flex items-center space-x-4">
                <a href="/">
          <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
        </a>
    </div>

        <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
                        <a href="/dashboard" class="relative text-gray-600 hover:text-blue-600 h-full flex items-center transition">
            Dashboard
        </a>

                <div x-data="{ open: false }" class="relative h-full flex items-center">
            <button @click="open = !open" class="flex items-center text-blue-600 transition h-full">
                <span>Master</span>
                <i class="fas fa-caret-down ml-2.5 text-sm"></i>                                 <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
            </button>
            <div x-show="open" @click.away="open = false"
                class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                style="display: none;"
                >
                                <a href="/master/skema" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
                <a href="/master/asesor" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
                <a href="/master/asesi" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
            </div>
        </div>

        <a href="/schedule" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>
        <a href="/tuk" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">TUK</a>
    </div>

        <div class="flex items-center space-x-6">
                        <a href="#"
            class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)]
            hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
            <i class="fas fa-bell text-xl text-gray-600"></i>

                    <span class="absolute top-2 right-2">
                <span class="relative flex size-3">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex size-3 rounded-full bg-red-500"></span>
                </span>
            </span>
        </a>

                        <a href="#"
        class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)]
        hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
            <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
            <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
                <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
            </div>
        </a>
    </div>
  </nav>

    <header class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <p class="text-sm text-gray-500 mb-1">Hi, Roihan’s.</p>
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Daftar Skema</h2>

        <div class="flex items-center justify-between">
      <div class="relative w-1/3">
        <input
          type="text"
          placeholder="Search"
          class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
      </div>

      <div class="flex space-x-3">
        <button
          class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300"
        >
          <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <button
          class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium"
        >
          <i class="fas fa-plus mr-2"></i> Add Skema
        </button>
      </div>
    </div>
  </header>
</body>
</html>