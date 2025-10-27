<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TUK Sewaktu | LSP Polines</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Font Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    ::-webkit-scrollbar { width: 0; }
    ::-webkit-scrollbar-thumb { background-color: transparent; }
    ::-webkit-scrollbar-track { background-color: transparent; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="h-screen overflow-y-auto">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
      <!-- LOGO -->
      <div class="flex items-center space-x-4">
        <a href="{{ route('dashboard') }}">
          <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
        </a>
      </div>

      <!-- MENU TENGAH -->
      <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Dashboard</a>

        <!-- Dropdown Master -->
        <div x-data="{ open: false }" class="relative h-full flex items-center">
          <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition h-full relative">
            <span>Master</span>
            <i class="fas fa-caret-down ml-2.5 text-sm"></i>
          </button>

          <div x-show="open" @click.away="open = false"
               class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
               x-transition:enter="transition ease-out duration-150"
               x-transition:enter-start="opacity-0 translate-y-1"
               x-transition:enter-end="opacity-100 translate-y-0"
               x-transition:leave="transition ease-in duration-100"
               x-transition:leave-start="opacity-100 translate-y-0"
               x-transition:leave-end="opacity-0 translate-y-1">
            <a href="{{ route('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
          </div>
        </div>

        <a href="{{ route('schedule_admin') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>

        <!-- TUK aktif -->
        <a href="{{ route('tuk_sewaktu') }}" class="text-blue-600 h-full flex items-center relative">
          TUK
          <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
        </a>
      </div>

      <!-- PROFIL & NOTIF -->
      <div class="flex items-center space-x-6">
        <!-- Notifikasi -->
        <a href="{{ route('notifications') }}" 
           class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)] 
                  hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),inset-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
          <i class="fas fa-bell text-xl text-gray-600"></i>
          <span class="absolute top-2 right-2">
            <span class="relative flex size-3">
              <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex size-3 rounded-full bg-red-500"></span>
            </span>
          </span>
        </a>

        <!-- Profil -->
        <a href="{{ route('profile_admin') }}" 
           class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
           hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),inset-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
          <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
          <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
            <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
          </div>
        </a>
      </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto mt-8 px-6 lg:px-8">
      <!-- HEADER -->
      <div class="mb-6">
        <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
        <h2 class="text-3xl font-bold text-gray-900">Tempat Uji Kompetensi (TUK)</h2>
      </div>

      <!-- SEARCH BAR + TAB SWITCH + ACTION BUTTONS -->
      <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div class="flex items-center w-full lg:w-auto gap-4 flex-1">
          <!-- Search -->
          <div class="relative w-full max-w-sm">
            <input type="text" placeholder="Search"
                   class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
          </div>

          <!-- Tab Switch (Sewaktu & Tempat Kerja) -->
          <div class="flex bg-gray-100 rounded-full p-1 shadow-inner">
            <a href="#" 
               class="px-5 py-2.5 rounded-full font-semibold text-gray-800 bg-gradient-to-r #b4e1ff, #d7f89c shadow-md hover:opacity-90 transition-all duration-200 ml-1">
              Sewaktu
            </a>
            <a href="#" 
               class="px-5 py-2.5 rounded-full font-semibold text-gray-800 bg-white shadow-md transition-all duration-200">
              Tempat Kerja
            </a>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3 items-center" x-data="{ open: false }">
          <!-- Filter Dropdown -->
          <div class="relative">
            <button @click="open = !open"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
              <i class="fas fa-filter mr-2"></i> Filter
              <i class="fas fa-caret-down ml-2"></i>
            </button>

            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-20"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1">
              <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Kota/Kab</a>
              <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Kapasitas</a>
              <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Ruang Tersedia</a>
            </div>
          </div>

          <!-- Add Button -->
          <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Add TUK
          </button>
        </div>
      </div>

      <!-- CONTENT -->
      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6">
        <p class="text-gray-600 text-sm text-center">Belum ada data TUK Sewaktu yang ditampilkan.</p>
      </div>
    </main>
  </div>
</body>
</html>