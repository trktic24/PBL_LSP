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
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />
    <main class="p-6">
      <div class="mb-6">
        <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
        <h2 class="text-3xl font-bold text-gray-900">Jadwal Uji Kompetensi</h2>
      </div>

      <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
        
        <form 
          action="{{ route('master_schedule') }}" 
          method="GET" 
          class="w-full max-w-sm"
          x-data="{ search: '{{ request('search', '') }}' }" 
        >
          <div class="relative">
            <input 
              type="text" 
              name="search"
              placeholder="Cari Skema, Asesor, TUK..."
              class="w-full pl-10 pr-10 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              x-model="search"
            />
            <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600">
              <i class="fas fa-search"></i>
            </button>
            <button 
              type="button" 
              class="absolute right-3 top-0 h-full text-gray-400 hover:text-gray-600"
              x-show="search.length > 0"
              @click="search = ''; $nextTick(() => $el.form.submit())"
              x-cloak 
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
        </form>

        <div class="flex items-center space-x-3" x-data="{ open: false }">
          <div class="relative">
            <button @click="open = !open"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center">
              <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-20"
                 x-transition>
              <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Status: Terjadwal</a>
              <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Status: Selesai</a>
            </div>
          </div>
          
          <a href="{{ route('add_schedule') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center shadow-md">
            <i class="fas fa-plus mr-2"></i> Add Schedule
          </a>
        </div>
      </div>

      @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex justify-between items-center" role="alert">
          <span class="font-medium">{{ session('success') }}</span>
          <button @click="show = false" class="ml-4 text-green-900 hover:text-green-700"><i class="fas fa-times"></i></button>
        </div>
      @endif

      @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg flex justify-between items-center" role="alert">
          <span class="font-medium">{{ session('error') }}</span>
          <button @click="show = false" class="ml-4 text-red-900 hover:text-red-700"><i class="fas fa-times"></i></button>
        </div>
      @endif


      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
        
        <table class="min-w-full divide-y divide-gray-200 text-xs">
          
          <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
              <th class="px-4 py-3 text-left">ID</th>
              <th class="px-4 py-3 text-left">Skema</th>
              <th class="px-4 py-3 text-left">Asesor</th>
              <th class="px-4 py-3 text-left">TUK</th>
              <th class="px-4 py-3 text-left">Pendaftaran</th>
              <th class="px-4 py-3 text-left">Pelaksanaan</th>
              <th class="px-4 py-3 text-left">Jenis TUK</th>
              <th class="px-4 py-3 text-left">Sesi</th>
              <th class="px-4 py-3 text-left">Kuota (Min/Max)</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-center">Daftar Hadir</th>
              <th class="px-4 py-3 text-center">Berita Acara</th>
              <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
          </thead>
          
          <tbody class="divide-y divide-gray-100">
            @forelse ($jadwals as $jadwal)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-4 py-3">{{ $jadwal->id_jadwal }}</td>
              <td class="px-4 py-3">{{ $jadwal->skema?->nama_skema ?? 'N/A' }}</td>
              <td class="px-4 py-3">{{ $jadwal->asesor?->nama_lengkap ?? 'N/A' }}</td>
              <td class="px-4 py-3">{{ $jadwal->tuk?->nama_lokasi ?? 'N/A' }}</td>
              
              <td class="px-4 py-3">{{ $jadwal->tanggal_mulai?->format('d/m/Y') ?? 'N/A' }} - {{ $jadwal->tanggal_selesai?->format('d/m/Y') ?? 'N/A' }}</td>
              
              <td class="px-4 py-3">{{ $jadwal->tanggal_pelaksanaan?->format('d/m/Y') ?? 'N/A' }} ({{ $jadwal->waktu_mulai?->format('H:i') ?? 'N/A' }})</td>
              
              <td class="px-4 py-3">{{ $jadwal->jenisTuk?->jenis_tuk ?? 'N/A' }}</td>
              
              <td class="px-4 py-3">{{ $jadwal->sesi }}</td>
              
              <td class="px-4 py-3">{{ $jadwal->kuota_minimal }} / {{ $jadwal->kuota_maksimal }}</td>

              <td class="px-4 py-3">
                @if($jadwal->Status_jadwal == 'Selesai')
                  <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Selesai</span>
                @elseif($jadwal->Status_jadwal == 'Dibatalkan')
                  <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Dibatalkan</span>
                @else
                  <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">Terjadwal</span>
                @endif
              </td>

              <td class="px-4 py-3 text-center">
                <a href="#" class="flex items-center justify-center space-x-1 px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded-md transition">
                    <i class="fas fa-list-check"></i> <span>Lihat</span>
                </a>
              </td>
            
              <td class="px-4 py-3 text-center">
                <a href="#" class="flex items-center justify-center space-x-1 px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded-md transition">
                    <i class="fas fa-file-alt"></i> <span>Lihat</span>
                </a>
              </td>

              <td class="px-4 py-3 flex space-x-2">
                <a href="{{ route('edit_schedule', $jadwal->id_jadwal) }}" 
                   class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition">
                    <i class="fas fa-pen"></i> <span>Edit</span>
                </a>
                
                <form action="{{ route('delete_schedule', $jadwal->id_jadwal) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus jadwal (ID: {{ $jadwal->id_jadwal }}) ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition">
                    <i class="fas fa-trash"></i> <span>Delete</span>
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="13" class="px-6 py-4 text-center text-gray-500">
                Belum ada data jadwal yang ditampilkan.
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