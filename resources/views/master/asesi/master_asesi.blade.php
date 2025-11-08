<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Master Asesi | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
  <div class="min-h-screen flex flex-col">
    
    <x-navbar />
    <main class="p-6">
      <div class="mb-6">
        <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
        <h2 class="text-3xl font-bold text-gray-900">Daftar Asesi</h2>
      </div>

      <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
        <div class="relative w-full md:w-1/3">
          <input type="text" placeholder="Cari Asesi..."
                 class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
        </div>

        <div class="flex space-x-3">
          <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300">
            <i class="fas fa-filter mr-2"></i> Filter
          </button>
          <a href="{{ route('add_asesi1') }}" 
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
            <i class="fas fa-plus mr-2"></i> Tambah Asesi
          </a>
        </div>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-6 py-3">ID</th>
              <th class="px-6 py-3">Nama Asesi</th>
              <th class="px-6 py-3">Email</th>
              <th class="px-6 py-3">No. Telepon</th>
              <th class="px-6 py-3">Skema Sertifikasi</th>
              <th class="px-6 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse ($asesis as $asesi)
            <tr>
              <td class="px-6 py-4">{{ $asesi['id'] }}</td>
              <td class="px-6 py-4 font-medium">{{ $asesi['nama_lengkap'] }}</td>
              <td class="px-6 py-4">{{ $asesi['email'] }}</td>
              <td class="px-6 py-4">{{ $asesi['nomor_hp'] }}</td>
              <td class="px-6 py-4">{{ $asesi['skema_sertifikasi'] }}</td>
              <td class="px-6 py-4 flex space-x-2">
                <a href="{{ route('edit_asesi1', $asesi['id']) }}" class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-lg transition">
                  <i class="fas fa-pen"></i> <span>Edit</span>
                </a>

                <button class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                  <i class="fas fa-trash"></i> <span>Delete</span>
                </button>

                <a href="{{ route('asesi_profile_settings') }}"
                   class="flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition">
                  <i class="fas fa-eye"></i> <span>View</span>
                </a>
              </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada data Asesi yang ditemukan.
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