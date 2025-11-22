<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
      body {
          font-family: 'Poppins', sans-serif;
      }
      ::-webkit-scrollbar {
          width: 0;
      }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <div class="h-screen overflow-y-auto">

    <x-navbar />
    <main class="p-6">
      <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
      <h2 class="text-3xl font-semibold text-gray-900 mb-6">Dashboard</h2>

      <div class="flex items-start justify-between mb-8">
        <div class="relative w-1/4">
          <input type="text" placeholder="Search"
            class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
        </div>
        <div class="flex space-x-1 p-1 bg-white border border-gray-200 rounded-xl shadow-sm">
          <button class="px-4 py-2 text-gray-800 font-semibold rounded-xl text-sm transition-all"
            style="background: linear-gradient(to right, #b4e1ff, #d7f89c); box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
            Today
          </button>
          <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Year</button>
          <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Week</button>
          <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Month</button>
        </div>
      </div>

      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-blue-600/30 min-h-[200px]">
          <div class="flex justify-center items-center w-1/3">
            <i class="far fa-calendar-alt text-8xl text-blue-600/80"></i>
          </div>
          <div class="relative flex-1 h-full flex items-center justify-center">
            <p class="absolute top-4 text-sm text-gray-500">Asesmen Berlangsung</p>
            <p class="text-5xl font-bold text-gray-900">{{ $asesmenBerlangsung }}</p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-green-600/30 min-h-[200px]">
          <div class="flex justify-center items-center w-1/3">
            <i class="far fa-calendar-check text-8xl text-green-500"></i>
          </div>
          <div class="relative flex-1 h-full flex items-center justify-center">
            <p class="absolute top-4 text-sm text-gray-500">Asesmen Selesai</p>
            <p class="text-5xl font-bold text-gray-900">{{ $asesmenSelesai }}</p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-yellow-600/30 min-h-[200px]">
          <div class="flex justify-center items-center w-1/3">
            <i class="fas fa-book-reader text-8xl text-yellow-400"></i>
          </div>
          <div class="relative flex-1 h-full flex items-center justify-center">
            <p class="absolute top-4 text-sm text-gray-500">Jumlah Asesi</p>
            <p class="text-5xl font-bold text-gray-900">{{ $jumlahAsesi }}</p>
          </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-red-600/30 min-h-[200px]">
          <div class="flex justify-center items-center w-1/3">
            <i class="fas fa-chalkboard-teacher text-8xl text-red-500"></i>
          </div>
          <div class="relative flex-1 h-full flex items-center justify-center">
            <p class="absolute top-4 text-sm text-gray-500">Jumlah Asesor</p>
            <p class="text-5xl font-bold text-gray-900">{{ $jumlahAsesor }}</p>
          </div>
        </div>
      </section>

      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-4 rounded-xl shadow-lg">
          <h3 class="text-md font-semibold mb-2">Statistik Skema</h3>
          <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x256/f87171/ffffff?text=Statistik+Skema"
              alt="Line Chart" class="object-cover w-full h-full">
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-lg">
          <h3 class="text-md font-semibold mb-2">Statistik Asesi yang Mengikuti Skema</h3>
          <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x256/3b82f6/ffffff?text=Statistik+Asesi"
              alt="Bar Chart" class="object-cover w-full h-full">
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-lg">
          <h3 class="text-md font-semibold mb-2">Progress Skema</h3>
          <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x256/10b981/ffffff?text=Progress+Skema"
              alt="Doughnut Chart" class="object-cover w-full h-full">
          </div>
        </div>
      </section>

      <section class="bg-white p-4 rounded-xl shadow-lg overflow-x-auto">
        <h3 class="text-lg font-semibold mb-4 px-2">Jadwal Terdekat (Status: Terjadwal)</h3>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">ID Jadwal</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Nama Skema</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Sesi</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Kuota (Min/Max)</th>
              <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Tgl Pelaksanaan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            
            @forelse ($jadwalTerbaru as $jadwal)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                {{ $jadwal->id_jadwal }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 mr-3 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-microchip text-blue-600"></i>
                  </div>
                  <span class="text-sm font-medium text-gray-900">
                    {{ $jadwal->skema?->nama_skema ?? 'N/A' }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 text-gray-700">{{ $jadwal->sesi }}</td>
              
              {{-- INI BAGIAN YANG DIPERBARUI --}}
              <td class="px-6 py-4 text-gray-700">{{ $jadwal->kuota_minimal }} / {{ $jadwal->kuota_maksimal }}</td>
              
              <td class="px-6 py-4 text-gray-700">
                {{ $jadwal->tanggal_pelaksanaan?->format('d/m/Y') ?? 'N/A' }}
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                Tidak ada jadwal yang sedang berlangsung.
              </td>
            </tr>
            @endforelse
            
          </tbody>
        </table>
      </section>
    </main>
  </div>

</body>
</html>