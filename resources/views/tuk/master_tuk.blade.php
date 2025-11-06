<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Master TUK | LSP Polines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">
    <x-navbar />
    <main class="p-6">
      <div class="mb-6">
        <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
        <h2 class="text-3xl font-bold text-gray-900">Tempat Uji Kompetensi (TUK)</h2>
      </div>

      <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
        <div class="flex flex-wrap items-center gap-4 w-full lg:w-auto flex-1">
          <div class="relative w-full max-w-sm">
            <input type="text" placeholder="Search"
                   class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
          </div>
        </div>

        <div class="flex items-center space-x-3" x-data="{ open: false }">
          <div class="relative">
            <button @click="open = !open"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
              <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-20"
                 x-transition>
              <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Kota/Kab</a>
            </div>
          </div>
          <a href="{{ route('add_tuk') }}"
             class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Add TUK
          </a> 
        </div>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 w-full overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          
          <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-6 py-3 text-left font-semibold">ID</th>
              <th class="px-6 py-3 text-left font-semibold">Nama TUK (Lokasi)</th>
              <th class="px-6 py-3 text-left font-semibold">Alamat</th>
              <th class="px-6 py-3 text-left font-semibold">Kontak</th>
              <th class="px-6 py-3 text-left font-semibold">Foto TUK</th>
              <th class="px-6 py-3 text-left font-semibold">Link Gmap</th>
              <th class="px-6 py-3 text-left font-semibold">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            
            @forelse ($tuks as $tuk)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4">{{ $tuk->id_tuk }}</td>
              <td class="px-6 py-4 font-medium">{{ $tuk->nama_lokasi }}</td>
              <td class="px-6 py-4">{{ $tuk->alamat_tuk }}</td>
              <td class="px-6 py-4">{{ $tuk->kontak_tuk }}</td>
              <td class="px-6 py-4">
                <a href="{{ Storage::url($tuk->foto_tuk) }}" target="_blank" class="text-blue-600 hover:underline">
                  Lihat Foto
                </a>
              </td>
              <td class="px-6 py-4">
                <a href="{{ $tuk->link_gmap }}" target="_blank" class="text-blue-600 hover:underline">
                  Buka Peta
                </a>
              </td>
              <td class="px-6 py-4 flex space-x-2">
                <a href="{{ route('edit_tuk', $tuk->id_tuk) }}" class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition">
                  <i class="fas fa-pen"></i> <span>Edit</span>
                </a>
                <button class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition">
                  <i class="fas fa-trash"></i> <span>Delete</span>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                Belum ada data TUK yang ditampilkan.
              </td>
            </tr>
            @endforelse

          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>