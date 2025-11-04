<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Master Asesor | LSP Polines</title>

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
        <h2 class="text-3xl font-bold text-gray-900">Daftar Asesor</h2>
      </div>

      @if (session('success'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 5000)"
             x-show="show"
             x-transition
             class="fixed top-24 right-10 z-50 bg-green-500 text-white py-3 px-5 rounded-lg shadow-lg flex items-center"
             role="alert">
            <i class="fas fa-check-circle mr-3 text-lg"></i>
            <div>
                <span class="font-semibold">Berhasil!</span>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-4 -mr-1 text-green-100 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
      @endif

      <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
        <div class="relative w-full md:w-1/3">
          <input type="text" placeholder="Cari Asesor..."
                 class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
        </div>

        <div class="flex space-x-3">
          <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300">
            <i class="fas fa-filter mr-2"></i> Filter
          </button>
          <a href="{{ route('add_asesor1') }}" 
             class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Asesor
          </a>
        </div>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-6 py-3">ID</th>
              <th class="px-6 py-3">Nama Asesor</th>
              <th class="px-6 py-3">Email</th>
              <th class="px-6 py-3">No. Telepon</th>
              <th class="px-6 py-3">Bidang Sertifikasi</th>
              <th class="px-6 py-3">Aksi</th>
            </tr>
          </thead>
          
          <tbody class="divide-y divide-gray-200">
            @forelse ($asesors as $asesor)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4">{{ $asesor->id_asesor }}</td>
              <td class="px-6 py-4 font-medium">{{ $asesor->nama_lengkap }}</td>
              <td class="px-6 py-4">{{ $asesor->user->email ?? 'N/A' }}</td>
              <td class="px-6 py-4">{{ $asesor->nomor_hp }}</td>
              <td class="px-6 py-4">{{ $asesor->skema->nama_skema ?? 'N/A' }}</td>
              <td class="px-6 py-4 flex space-x-2">
                
                <a href="{{ route('edit_asesor1', $asesor->id_asesor) }}"
                    class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-lg transition">
                    <i class="fas fa-pen"></i> <span>Edit</span>
                </a>

                <button class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                  <i class="fas fa-trash"></i> <span>Delete</span>
                </button>

                <a href="{{ route('asesor_profile_settings') }}"
                   class="flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition">
                  <i class="fas fa-eye"></i> <span>View</span>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                Belum ada data asesor yang ditampilkan.
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