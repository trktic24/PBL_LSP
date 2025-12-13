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
      body { font-family: 'Poppins', sans-serif; }
      ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <div class="h-screen overflow-y-auto">

    <x-navbar_admin/>
    
    <main class="p-6">
      <!-- Statistik Filter (Hari, Minggu, Bulan, Tahun) -->
      <div class="flex flex-col sm:flex-row justify-between items-end mb-6 gap-4">
        <div>
            <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
            <h2 class="text-3xl font-semibold text-gray-900">Dashboard</h2>
        </div>

        <div class="flex space-x-1 p-1 bg-white border border-gray-200 rounded-xl shadow-sm">
            <button class="px-4 py-2 text-gray-800 font-semibold rounded-xl text-sm transition-all"
              style="background: linear-gradient(to right, #b4e1ff, #d7f89c); box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
              Today
            </button>
            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm transition-colors">Week</button>
            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm transition-colors">Month</button>
            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm transition-colors">Year</button>
        </div>
      </div>
      <!-- Statistik Cards -->
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
        <!-- Grafik Placeholder -->
        <div class="bg-white p-4 rounded-xl shadow-lg">
          <h3 class="text-md font-semibold mb-2">Statistik Skema</h3>
          <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x256/f87171/ffffff?text=Statistik+Skema" alt="Line Chart" class="object-cover w-full h-full">
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-lg">
          <h3 class="text-md font-semibold mb-2">Statistik Asesi</h3>
          <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x256/3b82f6/ffffff?text=Statistik+Asesi" alt="Bar Chart" class="object-cover w-full h-full">
          </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-lg">
          <h3 class="text-md font-semibold mb-2">Progress Skema</h3>
          <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x256/10b981/ffffff?text=Progress+Skema" alt="Doughnut Chart" class="object-cover w-full h-full">
          </div>
        </div>
      </section>

      <!-- TABLE SECTION (IMPLEMENTASI FULL: Sorting, Search, Filter, Pagination) -->
      <section class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-visible">
        
        <div class="flex items-center justify-between mb-6">
             <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-list-ul text-blue-600"></i>
                Jadwal Terdekat
            </h3>
        </div>
        
        <!-- Control Bar -->
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
            
            <!-- GRUP KIRI: Search & Show Entries -->
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                
                <!-- Search -->
                <form action="{{ route('dashboard') }}" method="GET" class="w-full sm:w-96" x-data="{ search: '{{ request('search', '') }}' }">
                    <!-- Input hidden untuk menjaga filter lain saat search -->
                    @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                    @if(request('filter_jenis_tuk')) <input type="hidden" name="filter_jenis_tuk" value="{{ request('filter_jenis_tuk') }}"> @endif
                    
                    <div class="relative">
                        <input type="text" name="search" x-model="search" placeholder="Search..." 
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600"><i class="fas fa-search"></i></button>
                        <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-gray-600" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak><i class="fas fa-times"></i></button>
                    </div>
                </form>

                <!-- Show Entries -->
                <div x-data="{ perPage: '{{ $perPage ?? 5 }}', changePerPage() { 
                        let url = new URL(window.location.href); 
                        url.searchParams.set('per_page', this.perPage); 
                        url.searchParams.set('page', 1); 
                        window.location.href = url.href; 
                    } }" 
                    class="flex items-center space-x-2 w-full sm:w-auto">
                    <label for="per_page" class="text-sm text-gray-600 whitespace-nowrap">Show:</label>
                    <select id="per_page" x-model="perPage" @change="changePerPage()" class="bg-white text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                </div>
            </div>

            <!-- GRUP KANAN: Filter Jenis TUK -->
            @php
                // Ambil parameter saat ini untuk link sorting & filter
                $allParams = request()->query();
                $filterJenisTuk = request('filter_jenis_tuk');
                // Setup sorting vars
                $sortColumn = request('sort', 'tanggal_pelaksanaan');
                $sortDirection = request('direction', 'asc');
            @endphp

            <div class="relative w-full lg:w-auto flex justify-end" x-data="{ openFilter: false }">
                <button 
                    @click="openFilter = !openFilter"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center transition-colors w-full sm:w-auto justify-center"
                >
                    <i class="fas fa-filter mr-2"></i> Filter
                    @if($filterJenisTuk)
                        <span class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                    @endif
                </button>

                <!-- Dropdown Filter -->
                <div 
                    x-show="openFilter" 
                    @click.away="openFilter = false"
                    class="absolute right-0 top-full mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50"
                    x-transition
                    style="display: none;"
                >
                    <div class="p-2">
                        <div class="px-2 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pilih Jenis</div>
                        
                        <a href="{{ route('dashboard', array_merge($allParams, ['filter_jenis_tuk' => ($filterJenisTuk == 1 ? null : 1), 'page' => 1])) }}"
                           class="block w-full text-left px-3 py-2 rounded-md text-sm transition-colors {{ $filterJenisTuk == 1 ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                           Sewaktu
                        </a>
                        <a href="{{ route('dashboard', array_merge($allParams, ['filter_jenis_tuk' => ($filterJenisTuk == 2 ? null : 2), 'page' => 1])) }}"
                           class="block w-full text-left px-3 py-2 rounded-md text-sm transition-colors {{ $filterJenisTuk == 2 ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                           Tempat Kerja
                        </a>
                    </div>

                    @if($filterJenisTuk)
                        <div class="p-2 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                            <a href="{{ route('dashboard', array_merge($allParams, ['filter_jenis_tuk' => null, 'page' => 1])) }}" 
                               class="block w-full text-center text-xs text-red-600 font-medium hover:underline">
                                Hapus Filter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left border border-gray-200">
              <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr class="divide-x divide-gray-200 border-b border-gray-200">
                  @php 
                    // Base params untuk link sorting
                    $baseParams = ['search' => request('search'), 'per_page' => request('per_page'), 'filter_jenis_tuk' => request('filter_jenis_tuk')]; 
                  @endphp

                  <!-- ID -->
                  <th class="px-4 py-3 font-semibold w-16 text-center">
                      @php $isCurrent = $sortColumn == 'id_jadwal'; @endphp
                      <a href="{{ route('dashboard', array_merge($baseParams, ['sort' => 'id_jadwal', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                          <span>ID</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <!-- Foto Skema -->
                  <th class="px-4 py-3 font-semibold w-24 text-center">Foto Skema</th>

                  <!-- No Skema -->
                   <th class="px-4 py-3 font-semibold">
                      <span class="block">No. Skema</span>
                  </th>

                  <!-- Nama Skema -->
                  <th class="px-6 py-3 font-semibold">
                      @php $isCurrent = $sortColumn == 'skema_nama'; @endphp
                      <a href="{{ route('dashboard', array_merge($baseParams, ['sort' => 'skema_nama', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>Nama Skema</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <!-- TUK -->
                  <th class="px-6 py-3 font-semibold">
                      @php $isCurrent = $sortColumn == 'tuk_nama'; @endphp
                      <a href="{{ route('dashboard', array_merge($baseParams, ['sort' => 'tuk_nama', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>TUK</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <!-- Jenis TUK -->
                  <th class="px-6 py-3 font-semibold">
                      @php $isCurrent = $sortColumn == 'jenis_tuk'; @endphp
                      <a href="{{ route('dashboard', array_merge($baseParams, ['sort' => 'jenis_tuk', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>Jenis TUK</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <!-- Sesi -->
                  <th class="px-4 py-3 font-semibold text-center">
                      @php $isCurrent = $sortColumn == 'sesi'; @endphp
                      <a href="{{ route('dashboard', array_merge($baseParams, ['sort' => 'sesi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                          <span>Sesi</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>
                  
                  <!-- Kuota -->
                  <th class="px-4 py-3 font-semibold text-center">
                      @php $isCurrent = $sortColumn == 'kuota_maksimal'; @endphp
                      <a href="{{ route('dashboard', array_merge($baseParams, ['sort' => 'kuota_maksimal', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                          <span>Kuota</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>
                  
                  <!-- Tanggal -->
                  <th class="px-4 py-3 font-semibold w-32">
                      @php $isCurrent = $sortColumn == 'tanggal_pelaksanaan'; @endphp
                      <a href="{{ route('dashboard', array_merge($baseParams, ['sort' => 'tanggal_pelaksanaan', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>Tgl Pelaksanaan</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                
                @forelse ($jadwalTerbaru as $jadwal)
                <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                  <!-- ID -->
                  <td class="px-4 py-4 text-center font-medium text-gray-500">
                    {{ $jadwal->id_jadwal }}
                  </td>

                  <!-- Foto Skema -->
                  <td class="px-4 py-4">
                      <div class="h-32 w-32 rounded-md overflow-hidden border border-gray-200 bg-gray-50 mx-auto">
                          @if($jadwal->skema && $jadwal->skema->gambar)
                              <img src="{{ asset($jadwal->skema->gambar) }}" 
                                   alt="{{ $jadwal->skema->nama_skema }}" 
                                   class="w-full h-full object-cover hover:scale-110 transition-transform duration-200 cursor-pointer"
                                   onclick="window.open(this.src, '_blank')"
                              >
                          @else
                              <div class="w-full h-full flex items-center justify-center text-gray-400">
                                  <i class="fas fa-image text-lg"></i>
                              </div>
                          @endif
                      </div>
                  </td>

                  <!-- No Skema -->
                  <td class="px-4 py-4 font-medium text-gray-700">
                      {{ $jadwal->skema->nomor_skema ?? '-' }}
                  </td>

                  <!-- Nama Skema -->
                  <td class="px-6 py-4 font-medium text-gray-900">
                    {{ $jadwal->skema->nama_skema ?? '-' }}
                  </td>

                  <!-- TUK -->
                  <td class="px-6 py-4 text-gray-700">
                    {{ $jadwal->tuk->nama_lokasi ?? '-' }}
                  </td>

                  <!-- Jenis TUK -->
                  <td class="px-6 py-4 text-gray-700">
                    {{ $jadwal->jenisTuk->jenis_tuk ?? '-' }}
                  </td>

                  <!-- Sesi -->
                  <td class="px-4 py-4 text-center font-bold text-gray-700">
                    {{ $jadwal->sesi }}
                  </td>
                  
                  <!-- Kuota -->
                  <td class="px-4 py-4 text-center text-gray-700 whitespace-nowrap">
                    {{ $jadwal->kuota_minimal }} / {{ $jadwal->kuota_maksimal }}
                  </td>
                  
                  <!-- Tanggal -->
                  <td class="px-4 py-4 font-medium text-blue-700 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="9" class="px-6 py-8 text-center text-gray-400 italic">
                    Tidak ada jadwal yang sedang berlangsung saat ini.
                  </td>
                </tr>
                @endforelse
                
              </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-500 font-bold">
                @if ($jadwalTerbaru->total() > 0)
                    Showing {{ $jadwalTerbaru->firstItem() }} - {{ $jadwalTerbaru->lastItem() }} of {{ $jadwalTerbaru->total() }} results
                @else
                    Showing 0 results
                @endif
            </div>
            <div>
                {{ $jadwalTerbaru->links('components.pagination') }}
            </div>
        </div>

      </section>
    </main>
  </div>

</body>
</html>