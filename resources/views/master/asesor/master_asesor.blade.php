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
    [x-cloak] { display: none !important; } 
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
<!-- ... (kode filter tidak berubah) ... -->
      <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
        <form 
          action="{{ route('master_asesor') }}" method="GET" 
          class="w-full max-w-sm"
          x-data="{ search: '{{ $requestData['search'] ?? '' }}' }"
        >
          <div class="relative">
            <input 
              type="text"
              name="search"
              x-model="search"
              placeholder="Cari Asesor..."
              class="w-full pl-10 pr-10 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <button 
              type="submit" 
              class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600"
            >
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

        <div class="flex flex-wrap items-center gap-3">
          
          <!-- Filter Gabungan Dropdown Kustom -->
          <div class="relative" 
               x-data="{ 
                  open: false, 
                  activeFilter: '' 
               }" 
               @click.away="open = false; activeFilter = ''">
            
            <!-- Tombol Filter Utama -->
            <button 
              @click="open = !open; activeFilter = ''"
              class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center"
            >
              <i class="fas fa-filter mr-2"></i> 
              <span>Filter</span>
              <!-- Badge untuk menunjukkan ada filter aktif -->
              @php
                $filterCount = 0;
                if (isset($requestData['skema_id']) && $requestData['skema_id'] != '') $filterCount++;
                if (isset($requestData['jenis_kelamin']) && $requestData['jenis_kelamin'] != '') $filterCount++;
                if (isset($requestData['is_verified']) && $requestData['is_verified'] != '') $filterCount++;
              @endphp

              @if ($filterCount > 0)
                <span class="ml-2 bg-blue-600 text-white rounded-full h-5 w-5 flex items-center justify-center text-xs">{{ $filterCount }}</span>
              @endif
            </button>

            <!-- Kontainer Dropdown -->
            <div 
              x-show="open"
              x-transition
              class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-md shadow-lg z-20 overflow-hidden"
              x-cloak
            >
              
              <!-- Menu Utama (Tampil saat activeFilter == '') -->
              <div x-show="activeFilter === ''" x-transition>
                <div class="px-4 py-3 border-b">
                  <p class="font-semibold text-gray-800">Filter Berdasarkan</p>
                </div>
                
                <!-- Opsi 1: Skema -->
                <button @click="activeFilter = 'skema'" class="w-full text-left flex justify-between items-center px-4 py-3 hover:bg-gray-100">
                  <span class="flex items-center">
                    <i class="fas fa-award fa-fw mr-2 text-gray-400"></i>
                    Sertifikasi (Skema)
                  </span>
                  @if (isset($requestData['skema_id']) && $requestData['skema_id'] != '')
                    <span class="text-xs text-blue-600">Aktif</span>
                  @endif
                  <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </button>

                <!-- Opsi 2: Jenis Kelamin -->
                <button @click="activeFilter = 'jk'" class="w-full text-left flex justify-between items-center px-4 py-3 hover:bg-gray-100">
                  <span class="flex items-center">
                    <i class="fas fa-venus-mars fa-fw mr-2 text-gray-400"></i>
                    Jenis Kelamin
                  </span>
                  @if (isset($requestData['jenis_kelamin']) && $requestData['jenis_kelamin'] != '')
                    <span class="text-xs text-blue-600">Aktif</span>
                  @endif
                  <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </button>
                
                <!-- Opsi 3: Status -->
                <button @click="activeFilter = 'status'" class="w-full text-left flex justify-between items-center px-4 py-3 hover:bg-gray-100">
                  <span class="flex items-center">
                    <i class="fas fa-check-circle fa-fw mr-2 text-gray-400"></i>
                    Status
                  </span>
                  @if (isset($requestData['is_verified']) && $requestData['is_verified'] != '')
                    <span class="text-xs text-blue-600">Aktif</span>
                  @endif
                  <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </button>

                <!-- Tombol Reset Semua Filter -->
                @if ($filterCount > 0)
                <div class="p-2 border-t bg-gray-50">
                  <a href="{{ route('master_asesor', ['search' => $requestData['search'] ?? '']) }}" 
                     class="block w-full text-center px-4 py-2 text-sm text-red-600 hover:bg-red-100 rounded">
                      Reset Semua Filter
                  </a>
                </div>
                @endif
              </div>

              <!-- Sub-menu Skema -->
              <div x-show="activeFilter === 'skema'" class="max-h-80 overflow-y-auto" x-transition>
                <button @click="activeFilter = ''" class="flex items-center px-4 py-3 text-sm font-semibold text-blue-600 hover:bg-gray-100 w-full">
                  <i class="fas fa-chevron-left text-xs mr-2"></i>
                  Kembali ke Filter
                </button>
                <hr>
                <a href="{{ route('master_asesor', array_merge($requestData, ['skema_id' => ''])) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium border-b">
                  Tampilkan Semua
                </a>
                @foreach ($skemas as $skema)
                  <a href="{{ route('master_asesor', array_merge($requestData, ['skema_id' => $skema->id_skema])) }}" 
                     class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ (isset($requestData['skema_id']) && $requestData['skema_id'] == $skema->id_skema) ? 'bg-blue-100' : '' }}">
                    {{ $skema->nama_skema }}
                  </a>
                @endforeach
              </div>

              <!-- Sub-menu Jenis Kelamin -->
              <div x-show="activeFilter === 'jk'" x-transition>
                <button @click="activeFilter = ''" class="flex items-center px-4 py-3 text-sm font-semibold text-blue-600 hover:bg-gray-100 w-full">
                  <i class="fas fa-chevron-left text-xs mr-2"></i>
                  Kembali ke Filter
                </button>
                <hr>
                <a href="{{ route('master_asesor', array_merge($requestData, ['jenis_kelamin' => ''])) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium border-b">
                  Semua
                </a>
                <a href="{{ route('master_asesor', array_merge($requestData, ['jenis_kelamin' => 'Laki-laki'])) }}" 
                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ (isset($requestData['jenis_kelamin']) && $requestData['jenis_kelamin'] == 'Laki-laki') ? 'bg-blue-100' : '' }}">
                  Laki-laki
                </a>
                <a href="{{ route('master_asesor', array_merge($requestData, ['jenis_kelamin' => 'Perempuan'])) }}" 
                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ (isset($requestData['jenis_kelamin']) && $requestData['jenis_kelamin'] == 'Perempuan') ? 'bg-blue-100' : '' }}">
                  Perempuan
                </a>
              </div>

              <!-- Sub-menu Status -->
              <div x-show="activeFilter === 'status'" x-transition>
                <button @click="activeFilter = ''" class="flex items-center px-4 py-3 text-sm font-semibold text-blue-600 hover:bg-gray-100 w-full">
                  <i class="fas fa-chevron-left text-xs mr-2"></i>
                  Kembali ke Filter
                </button>
                <hr>
                <a href="{{ route('master_asesor', array_merge($requestData, ['is_verified' => ''])) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium border-b">
                  Semua
                </a>
                <a href="{{ route('master_asesor', array_merge($requestData, ['is_verified' => '1'])) }}" 
                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ (isset($requestData['is_verified']) && $requestData['is_verified'] == '1') ? 'bg-blue-100' : '' }}">
                  Terverifikasi
                </a>
                <a href="{{ route('master_asesor', array_merge($requestData, ['is_verified' => '0'])) }}" 
                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ (isset($requestData['is_verified']) && $requestData['is_verified'] == '0') ? 'bg-blue-100' : '' }}">
                  Belum
                </a>
              </div>

            </div>
          </div>
          <!-- Akhir Filter Gabungan -->

          <a 
            href="{{ route('add_asesor1') }}"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition flex items-center"
          >
            <i class="fas fa-plus mr-2"></i> Tambah Asesor
          </a>
        </div>
      </div>
      @if (session('success'))
        <div 
          x-data="{ show: true }"
          x-show="show"
          x-init="setTimeout(() => show = false, 5000)"
          x-transition:leave="transition ease-in duration-300"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex justify-between items-center"
          role="alert"
        >
          <span class="font-medium">{{ session('success') }}</span>
          <button 
            @click="show = false" 
            class="ml-4 text-green-900 hover:text-green-700"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
      @endif

      @if (session('error'))
        <div 
          x-data="{ show: true }"
          x-show="show"
          x-init="setTimeout(() => show = false, 5000)"
          x-transition:leave="transition ease-in duration-300"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg flex justify-between items-center"
          role="alert"
        >
          <span class="font-medium">{{ session('error') }}</span>
          <button 
            @click="show = false" 
            class="ml-4 text-red-900 hover:text-red-700"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
      @endif

      <!-- PERBAIKAN: Menggunakan class 'overflow-x-auto' standar dari Tailwind -->
      <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 w-full overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          
          <!-- Kolom tabel lengkap -->
          <thead class="bg-gray-50 text-gray-600 uppercase text-xs"> <tr>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">ID</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Nama Asesor</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Email</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">No. Registrasi</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">NIK</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">No. Telepon</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Bidang Sertifikasi (Skema)</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Tempat, Tgl Lahir</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Jenis Kelamin</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Pekerjaan</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Domisili</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">NPWP</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Informasi Bank</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Status</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Aksi</th>
            </tr>
          </thead>
          
<tbody class="divide-y divide-gray-100"> @forelse ($asesors as $asesor)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->id_asesor }}</td>
              <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $asesor->nama_lengkap }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->user->email ?? 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->nomor_regis }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->nik }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->nomor_hp }}</td>
              
              <td class="px-6 py-4 whitespace-nowrap">
                <ul class="list-disc list-inside">
                  @forelse($asesor->skemas as $skema)
                    <li>{{ $skema->nama_skema }}</li>
                  @empty
                    <li>N/A</li>
                  @endforelse
                </ul>
              </td>
              
              <td class="px-6 py-4 whitespace-nowrap">
                {{ $asesor->tempat_lahir }}, 
                @if($asesor->tanggal_lahir)
                  {{ \Carbon\Carbon::parse($asesor->tanggal_lahir)->isoFormat('D MMM YYYY') }}
                @else
                  N/A
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->jenis_kelamin }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->pekerjaan }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->kabupaten_kota }}, {{ $asesor->provinsi }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->NPWP }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $asesor->nama_bank }} ({{ $asesor->norek }})</td>
              
              <td class="px-6 py-4 whitespace-nowrap">
                @if($asesor->is_verified)
                  <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Terverifikasi</span>
                @else
                  <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Belum</span>
                @endif
              </td>
              
              <td class="px-6 py-4 flex space-x-2">
                
                <a href="{{ route('edit_asesor1', $asesor->id_asesor) }}"
                    class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition"> <i class="fas fa-pen"></i> <span>Edit</span>
                </a>

                <form action="{{ route('asesor.destroy', $asesor->id_asesor) }}" method="POST" class="inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus asesor {{ $asesor->nama_lengkap }}? Tindakan ini akan menghapus data asesor, akun user, dan semua file terkait.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition"> <i class="fas fa-trash"></i> <span>Delete</span>
                  </button>
                </form>

                <a href="{{ route('asesor.profile', $asesor->id_asesor) }}"
                   class="flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition"> <i class="fas fa-eye"></i> <span>View</span>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="15" class="px-6 py-4 text-center text-gray-500">
                Belum ada data asesor yang ditampilkan.
              </td>
            </tr>
            @endforelse
          </tbody>
          </table>
      </div>
      
      <!-- Navigasi pagination -->
      <div class="mt-6">
        <!-- Pagination links akan otomatis menyertakan parameter filter/search -->
        {{ $asesors->links() }}
      </div>
      
    </main>
  </div>
</body>
</html>