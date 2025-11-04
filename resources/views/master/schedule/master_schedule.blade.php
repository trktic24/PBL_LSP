<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Master Schedule | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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
        <a href="{{ route('schedule_admin') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
        <h2 class="text-3xl font-bold text-gray-900">Schedule</h2>
      </div>

      <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <div class="relative w-full max-w-xs">
          <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>

        <div class="flex items-center space-x-3">
          <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
            <i class="fas fa-filter mr-2"></i> Filter
          </button>

          <a href="{{ url('add_schedule') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center shadow-md">
            <i class="fas fa-plus mr-2"></i> Add Schedule
          </a>
        </div>
      </div>

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
                <a href="{{ url('edit_schedule') }}" 
                  class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition">
                    <i class="fas fa-pen"></i> <span>Edit</span>
                </a>
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