<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Master Schedule | LSP Polines</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
      <div class="flex items-center space-x-4">
        <a href="{{ route('dashboard') }}">
          <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
        </a>
      </div>

      <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Dashboard</a>

        <div x-data="{ open: false }" class="relative h-full flex items-center">
          <button @click="open = !open" class="flex items-center text-blue-600 transition h-full relative">
            Master
            <i class="fas fa-caret-down ml-2.5 text-sm"></i>
            <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
          </button>
          <div x-show="open" @click.away="open = false"
               class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
               x-transition>
            <a href="{{ route('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
            <a href="{{ route('master_asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
            <a href="{{ route('master_asesi') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
            <a href="{{ route('master_schedule') }}" class="block px-4 py-2 text-blue-600 bg-blue-50 font-semibold">Schedule</a>
          </div>
        </div>

        <a href="{{ route('schedule_admin') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>
        <a href="{{ route('tuk_sewaktu') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">TUK</a>
      </div>

      <div class="flex items-center space-x-6">
        <a href="{{ route('notifications') }}" class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-md hover:shadow-inner transition-all">
          <i class="fas fa-bell text-xl text-gray-600"></i>
          <span class="absolute top-2 right-2">
            <span class="relative flex w-2 h-2">
              <span class="absolute inline-flex w-full h-full animate-ping rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex w-2 h-2 rounded-full bg-red-500"></span>
            </span>
          </span>
        </a>

        <a href="{{ route('profile_admin') }}" class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-md hover:shadow-inner transition-all">
          <span class="text-gray-800 font-semibold text-base mr-2">Rohan Enrico</span>
          <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
            <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
          </div>
        </a>
      </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="p-6">
      <!-- HEADER -->
      <div class="mb-6">
          <a href="{{ route('schedule_admin') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium mb-4">
              <i class="fas fa-arrow-left mr-2"></i> Back
          </a>
          <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
          <h2 class="text-3xl font-bold text-gray-900">Schedule</h2>
      </div>

      <!-- SEARCH + FILTER + BUTTON -->
      <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <div class="relative w-full max-w-xs">
          <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>

        <div class="flex items-center space-x-3">
          <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
            <i class="fas fa-filter mr-2"></i> Filter
          </button>

          <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center shadow-md">
            <i class="fas fa-plus mr-2"></i> Add Schedule
          </button>
        </div>
      </div>

      <!-- TABLE -->
      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-4 py-3 text-left">No</th>
              <th class="px-4 py-3 text-left">Nama Skema</th>
              <th class="px-4 py-3 text-left">Kode Unit</th>
              <th class="px-4 py-3 text-left">Deskripsi</th>
              <th class="px-4 py-3 text-left">Gelombang</th>
              <th class="px-4 py-3 text-left">Jadwal</th>
              <th class="px-4 py-3 text-left">TUK</th>
              <th class="px-4 py-3 text-left">Daftar Asesor</th>
              <th class="px-4 py-3 text-left">Daftar Asesi</th>
              <th class="px-4 py-3 text-left">Daftar Hadir</th>
              <th class="px-4 py-3 text-left">Berita Acara</th>
              <th class="px-4 py-3 text-left">Tandai Selesai</th>
              <th class="px-4 py-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr class="hover:bg-gray-50 transition">
              <td class="px-4 py-3">1</td>
              <td class="px-4 py-3 font-semibold">Cybersecurity</td>
              <td class="px-4 py-3">#090909090</td>
              <td class="px-4 py-3 text-gray-500">Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
              <td class="px-4 py-3">1</td>
              <td class="px-4 py-3">15/12/2025<br>07:00 - 12:00 WIB</td>
              <td class="px-4 py-3">Mandiri</td>
              <td class="px-4 py-3">Dimas Enrico</td>
              <td class="px-4 py-3">Rafa Saputra<br>Zulfikar<br>Terra Pujangga</td>
              <td class="px-4 py-3 text-center">
                <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium text-xs px-3 py-1 rounded-lg shadow-sm transition">
                  <i class="fas fa-eye mr-1"></i> Lihat
                </button>
              </td>
              <td class="px-4 py-3 text-center">
                <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium text-xs px-3 py-1 rounded-lg shadow-sm transition">
                  <i class="fas fa-file-alt mr-1"></i> Lihat
                </button>
              </td>
              <td class="px-4 py-3 text-center">
                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
              </td>
              <td class="px-4 py-3 flex space-x-2">
                <button class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition">
                  <i class="fas fa-pen"></i> <span>Edit</span>
                </button>
                <button class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition">
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
