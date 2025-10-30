<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Master Skema | LSP Polines</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Font: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    ::-webkit-scrollbar-thumb { background-color: transparent; }
    ::-webkit-scrollbar-track { background-color: transparent; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">
    
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
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">
          Dashboard
        </a>

        <!-- Dropdown Master (aktif) -->
        <div x-data="{ open: false }" class="relative h-full flex items-center">
          <button @click="open = !open" class="flex items-center text-blue-600 transition h-full relative">
            <span>Master</span>
            <i :class="open ? 'fas fa-caret-up ml-2.5 text-sm' : 'fas fa-caret-down ml-2.5 text-sm'"></i>
            <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
          </button>

          <div x-show="open" @click.away="open = false"
               class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
               x-transition>
            <a href="{{ route('master_skema') }}" class="block px-4 py-2 text-blue-600 bg-blue-50 hover:text-blue-600">Skema</a>
            <a href="{{ route('master_asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
            <a href="{{ route('master_asesi') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
          </div>
        </div>

        <a href="{{ route('schedule_admin') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>
        <a href="{{ route('tuk_sewaktu') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">TUK</a>
      </div>

      <!-- PROFIL & NOTIF -->
      <div class="flex items-center space-x-6">
        <!-- Notifikasi -->
        <a href="{{ route('notifications') }}" 
           class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)] 
                  hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
          <i class="fas fa-bell text-xl text-gray-600"></i>
          <span class="absolute top-2 right-2">
              <span class="relative flex w-2 h-2">
                  <span class="absolute inline-flex w-full h-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex w-2 h-2 rounded-full bg-red-500"></span>
              </span>
          </span>
        </a>

        <!-- Profil -->
        <a href="{{ route('profile_admin') }}" 
           class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
           hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
          <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
          <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
            <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
          </div>
        </a>
      </div>
    </nav>

    <!-- MAIN CONTENT (FULL WIDTH) -->
    <main class="p-6">
      <!-- HEADER -->
      <div class="mb-6">
        <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
        <h2 class="text-3xl font-bold text-gray-900">Daftar Skema</h2>
      </div>

      <!-- SEARCH & ACTION BAR -->
      <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <!-- Search -->
        <div class="relative w-full sm:w-1/2 md:w-1/3">
          <input type="text" placeholder="Search"
                 class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
        </div>

        <!-- Buttons -->
        <div class="flex space-x-3">
          <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
            <i class="fas fa-filter mr-2"></i> Filter
          </button>
          <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Skema
          </button>
        </div>
      </div>

      <!-- CONTENT TABLE -->
      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 w-full overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-6 py-3 text-left">ID</th>
              <th class="px-6 py-3 text-left">Kode Unit Skema</th>
              <th class="px-6 py-3 text-left">Nama Skema</th>
              <th class="px-6 py-3 text-left">Jenis Sertifikasi</th>
              <th class="px-6 py-3 text-left">Jumlah Unit</th>
              <th class="px-6 py-3 text-left">Status</th>
              <th class="px-6 py-3 text-mid">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            <tr>
              <td class="px-6 py-4">1</td>
              <td class="px-6 py-4">2609051226</td>
              <td class="px-6 py-4 font-medium">Cybersecurity</td>
              <td class="px-6 py-4">KKNI Level 5</td>
              <td class="px-6 py-4">8 Unit</td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Aktif</span>
              </td>
              <td class="px-6 py-4 flex space-x-2">
                <button class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-lg transition">
                  <i class="fas fa-pen"></i> <span>Edit</span>
                </button>
                <button class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                  <i class="fas fa-trash"></i> <span>Delete</span>
                </button>
              </td>
            </tr>

            <tr>
              <td class="px-6 py-4">2</td>
              <td class="px-6 py-4">2609051227</td>
              <td class="px-6 py-4 font-medium">Data Analyst</td>
              <td class="px-6 py-4">KKNI Level 6</td>
              <td class="px-6 py-4">10 Unit</td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
              </td>
              <td class="px-6 py-4 flex space-x-2">
                <button class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-lg transition">
                  <i class="fas fa-pen"></i> <span>Edit</span>
                </button>
                <button class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                  <i class="fas fa-trash"></i> <span>Delete</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
