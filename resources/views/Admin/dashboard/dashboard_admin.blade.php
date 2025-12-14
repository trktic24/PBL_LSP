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
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
      body { font-family: 'Poppins', sans-serif; }
      ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <div class="h-screen overflow-y-auto">

    <x-navbar.navbar_admin/>
    
    <main class="p-6">
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

      <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-white to-indigo-50 p-6 rounded-xl shadow-md border border-indigo-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
             <div class="flex justify-between items-start z-10 relative">
                <div>
                    <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-1">Pendaftaran Baru (Hari Ini)</p>
                    <div class="flex items-baseline gap-2">
                        <h4 class="text-3xl font-bold text-gray-800">{{ $asesiBaruHariIni ?? 0 }}</h4>
                        <span class="text-xs text-gray-500">Asesi</span>
                    </div>
                    <div class="mt-3 flex items-center text-sm font-medium {{ ($trenPendaftaran ?? 0) >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        <span class="flex items-center bg-white px-2 py-0.5 rounded-full shadow-sm">
                             @if(($trenPendaftaran ?? 0) >= 0)
                                <i class="fas fa-arrow-trend-up mr-1.5"></i> +{{ $trenPendaftaran ?? 0 }}%
                             @else
                                <i class="fas fa-arrow-trend-down mr-1.5"></i> {{ $trenPendaftaran ?? 0 }}%
                             @endif
                        </span>
                        <span class="text-gray-400 ml-2 text-xs font-normal">vs kemarin</span>
                    </div>
                </div>
                <div class="p-3 bg-indigo-100 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
             </div>
             <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-100 rounded-full opacity-50 blur-2xl group-hover:bg-indigo-200 transition-colors"></div>
        </div>

        <div class="bg-gradient-to-br from-white to-emerald-50 p-6 rounded-xl shadow-md border border-emerald-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
             <div class="flex justify-between items-start z-10 relative">
                <div class="flex-1 pr-4"> 
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Tingkat Kelulusan (Kompeten)</p>
                    <div class="flex items-baseline gap-2">
                        <h4 class="text-3xl font-bold text-gray-800">{{ $persentaseKelulusan ?? 0 }}%</h4>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                        <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $persentaseKelulusan ?? 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-right">Target: 95%</p>
                </div>

                <div class="p-3 bg-emerald-100 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-award text-xl"></i>
                </div>
             </div>
        </div>

        <div class="bg-gradient-to-br from-white to-orange-50 p-6 rounded-xl shadow-md border border-orange-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
             <div class="flex justify-between items-start z-10 relative">
                <div>
                    <p class="text-xs font-bold text-orange-500 uppercase tracking-wider mb-1">Asesor Aktif Bertugas</p>
                    <div class="flex items-baseline gap-2">
                        <h4 class="text-3xl font-bold text-gray-800">{{ $asesorAktif ?? 0 }}</h4>
                        <span class="text-sm text-gray-400 font-medium">/ {{ $jumlahAsesor ?? 0 }} Total</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-orange-400"></i>
                        <span class="text-xs">Sedang menilai asesmen</span>
                    </p>
                </div>
                <div class="p-3 bg-orange-100 rounded-xl text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-briefcase text-xl"></i>
                </div>
             </div>
             <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-orange-100 rounded-full opacity-50 blur-2xl group-hover:bg-orange-200 transition-colors"></div>
        </div>
      </section>

      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-4 rounded-xl shadow-lg flex flex-col">
          <h3 class="text-md font-semibold mb-2">Statistik Skema</h3>
          <div class="relative h-64 w-full border border-gray-100 rounded-lg p-2">
             <canvas id="chartSkema"></canvas>
          </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-lg flex flex-col">
          <h3 class="text-md font-semibold mb-2">Statistik Asesi</h3>
          <div class="relative h-64 w-full border border-gray-100 rounded-lg p-2">
            <canvas id="chartAsesi"></canvas>
          </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-lg flex flex-col">
          <h3 class="text-md font-semibold mb-2">Progress Skema</h3>
          <div class="relative h-64 w-full border border-gray-100 rounded-lg p-2 flex justify-center">
             <canvas id="chartProgress"></canvas>
          </div>
        </div>

      </section>
      <section class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-visible">
        
        <div class="flex items-center justify-between mb-6">
             <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-list-ul text-blue-600"></i>
                Jadwal Terdekat
            </h3>
        </div>
        
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
            
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                
                <form action="{{ route('admin.dashboard') }}" method="GET" class="w-full sm:w-96" x-data="{ search: '{{ request('search', '') }}' }">
                    @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                    @if(request('filter_jenis_tuk')) <input type="hidden" name="filter_jenis_tuk" value="{{ request('filter_jenis_tuk') }}"> @endif
                    
                    <div class="relative">
                        <input type="text" name="search" x-model="search" placeholder="Search..." 
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600"><i class="fas fa-search"></i></button>
                        <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-gray-600" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak><i class="fas fa-times"></i></button>
                    </div>
                </form>

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

            @php
                $allParams = request()->query();
                $filterJenisTuk = request('filter_jenis_tuk');
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

                <div 
                    x-show="openFilter" 
                    @click.away="openFilter = false"
                    class="absolute right-0 top-full mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50"
                    x-transition
                    style="display: none;"
                >
                    <div class="p-2">
                        <div class="px-2 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pilih Jenis</div>
                        
                        <a href="{{ route('admin.dashboard', array_merge($allParams, ['filter_jenis_tuk' => ($filterJenisTuk == 1 ? null : 1), 'page' => 1])) }}"
                           class="block w-full text-left px-3 py-2 rounded-md text-sm transition-colors {{ $filterJenisTuk == 1 ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                           Sewaktu
                        </a>
                        <a href="{{ route('admin.dashboard', array_merge($allParams, ['filter_jenis_tuk' => ($filterJenisTuk == 2 ? null : 2), 'page' => 1])) }}"
                           class="block w-full text-left px-3 py-2 rounded-md text-sm transition-colors {{ $filterJenisTuk == 2 ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                           Tempat Kerja
                        </a>
                    </div>

                    @if($filterJenisTuk)
                        <div class="p-2 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                            <a href="{{ route('admin.dashboard', array_merge($allParams, ['filter_jenis_tuk' => null, 'page' => 1])) }}" 
                               class="block w-full text-center text-xs text-red-600 font-medium hover:underline">
                                Hapus Filter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left border border-gray-200">
              <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr class="divide-x divide-gray-200 border-b border-gray-200">
                  @php 
                    $baseParams = ['search' => request('search'), 'per_page' => request('per_page'), 'filter_jenis_tuk' => request('filter_jenis_tuk')]; 
                  @endphp

                  <th class="px-4 py-3 font-semibold w-16 text-center">
                      @php $isCurrent = $sortColumn == 'id_jadwal'; @endphp
                      <a href="{{ route('admin.dashboard', array_merge($baseParams, ['sort' => 'id_jadwal', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                          <span>ID</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <th class="px-4 py-3 font-semibold w-24 text-center">Foto Skema</th>

                  <th class="px-4 py-3 font-semibold">
                      <span class="block">No. Skema</span>
                  </th>

                  <th class="px-6 py-3 font-semibold">
                      @php $isCurrent = $sortColumn == 'skema_nama'; @endphp
                      <a href="{{ route('admin.dashboard', array_merge($baseParams, ['sort' => 'skema_nama', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>Nama Skema</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <th class="px-6 py-3 font-semibold">
                      @php $isCurrent = $sortColumn == 'tuk_nama'; @endphp
                      <a href="{{ route('admin.dashboard', array_merge($baseParams, ['sort' => 'tuk_nama', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>TUK</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <th class="px-6 py-3 font-semibold">
                      @php $isCurrent = $sortColumn == 'jenis_tuk'; @endphp
                      <a href="{{ route('admin.dashboard', array_merge($baseParams, ['sort' => 'jenis_tuk', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>Jenis TUK</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>

                  <th class="px-4 py-3 font-semibold text-center">
                      @php $isCurrent = $sortColumn == 'sesi'; @endphp
                      <a href="{{ route('admin.dashboard', array_merge($baseParams, ['sort' => 'sesi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                          <span>Sesi</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>
                  
                  <th class="px-4 py-3 font-semibold text-center">
                      @php $isCurrent = $sortColumn == 'kuota_maksimal'; @endphp
                      <a href="{{ route('admin.dashboard', array_merge($baseParams, ['sort' => 'kuota_maksimal', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                          <span>Kuota</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>
                  
                  <th class="px-4 py-3 font-semibold w-32">
                      @php $isCurrent = $sortColumn == 'tanggal_pelaksanaan'; @endphp
                      <a href="{{ route('admin.dashboard', array_merge($baseParams, ['sort' => 'tanggal_pelaksanaan', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                          <span>Tgl Pelaksanaan</span>
                          <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                      </a>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                
                @forelse ($jadwalTerbaru as $jadwal)
                <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                  <td class="px-4 py-4 text-center font-medium text-gray-500">{{ $jadwal->id_jadwal }}</td>
                  <td class="px-4 py-4">
                      <div class="h-32 w-32 rounded-md overflow-hidden border border-gray-200 bg-gray-50 mx-auto">
                          @if($jadwal->skema && $jadwal->skema->gambar)
                              <img src="{{ asset($jadwal->skema->gambar) }}" alt="{{ $jadwal->skema->nama_skema }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-200 cursor-pointer" onclick="window.open(this.src, '_blank')">
                          @else
                              <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-image text-lg"></i></div>
                          @endif
                      </div>
                  </td>
                  <td class="px-4 py-4 font-medium text-gray-700">{{ $jadwal->skema->nomor_skema ?? '-' }}</td>
                  <td class="px-6 py-4 font-medium text-gray-900">{{ $jadwal->skema->nama_skema ?? '-' }}</td>
                  <td class="px-6 py-4 text-gray-700">{{ $jadwal->masterTuk->nama_lokasi ?? '-' }}</td>
                  <td class="px-6 py-4 text-gray-700">{{ $jadwal->jenisTuk->jenis_tuk ?? '-' }}</td>
                  <td class="px-4 py-4 text-center font-bold text-gray-700">{{ $jadwal->sesi }}</td>
                  <td class="px-4 py-4 text-center text-gray-700 whitespace-nowrap">{{ $jadwal->kuota_minimal }} / {{ $jadwal->kuota_maksimal }}</td>
                  <td class="px-4 py-4 font-medium text-blue-700 whitespace-nowrap">{{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada jadwal yang sedang berlangsung saat ini.</td></tr>
                @endforelse
                
              </tbody>
            </table>
        </div>

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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. Statistik Skema (Line Chart) ---
        // X: Bulan, Y: Jumlah Skema
        const ctxSkema = document.getElementById('chartSkema').getContext('2d');
        new Chart(ctxSkema, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Skema Terlaksana',
                    data: [5, 8, 12, 15, 10, 18, 22, 20, 25, 28, 30, 35], // Data dummy
                    borderColor: '#f87171', // Merah (sesuai hint placeholder sebelumnya)
                    backgroundColor: 'rgba(248, 113, 113, 0.2)',
                    borderWidth: 2,
                    tension: 0.4, // Membuat garis melengkung
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Skema' }
                    },
                    x: {
                        title: { display: true, text: 'Bulan' }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // --- 2. Statistik Asesi (Bar Chart) ---
        // X: Bulan, Y: Jumlah Asesi Terdaftar
        const ctxAsesi = document.getElementById('chartAsesi').getContext('2d');
        new Chart(ctxAsesi, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Asesi Terdaftar',
                    data: [40, 55, 30, 60, 80, 75, 90, 85, 100, 120, 110, 130], // Data dummy
                    backgroundColor: '#3b82f6', // Biru
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Asesi' }
                    },
                    x: {
                        title: { display: true, text: 'Bulan' }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // --- 3. Progress Skema (Doughnut Chart) ---
        // Segments: Terjadwal (Biru), Selesai (Hijau), Dibatalkan (Merah)
        const ctxProgress = document.getElementById('chartProgress').getContext('2d');
        new Chart(ctxProgress, {
            type: 'doughnut',
            data: {
                labels: ['Terjadwal', 'Selesai', 'Dibatalkan'],
                datasets: [{
                    data: [25, 65, 10], // Data dummy (total 100%)
                    backgroundColor: [
                        '#3b82f6', // Biru
                        '#10b981', // Hijau
                        '#ef4444'  // Merah
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true }
                    }
                }
            }
        });
    });
  </script>

</body>
</html>