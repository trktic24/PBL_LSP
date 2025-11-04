<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Master Skema | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
    
    <x-navbar />
    <main class="p-6">
      <div class="mb-6">
        <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
        <h2 class="text-3xl font-bold text-gray-900">Daftar Skema</h2>
      </div>

      <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div class="relative w-full sm:w-1/2 md:w-1/3">
          <input type="text" placeholder="Search"
                 class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
        </div>

        <div class="flex space-x-3">
          <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
            <i class="fas fa-filter mr-2"></i> Filter
          </button>
          <a href="{{ route('add_skema') }}" 
             class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center transition">
            <i class="fas fa-plus mr-2"></i> Add Skema
          </a>

        </div>
      </div>

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