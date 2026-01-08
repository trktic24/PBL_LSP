<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Asesi | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }

        ::-webkit-scrollbar {
            width: 0;
        }

        [x-cloak] {
            display: none !important;
        }

        .content-area {
            /* padding-left dihilangkan/dikurangi karena tidak ada sidebar fixed */
            padding-left: 0; 
            min-height: 100vh;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <x-navbar.navbar-admin/>

    <main class="content-area p-8">

        {{-- Breadcrumb & Header --}}
        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">
                @if(isset($isMasterView) && $isMasterView)
                   <a href="{{ route('admin.skema.detail', $jadwal->skema->id_skema) }}" class="hover:text-blue-600">Detail Skema</a> / 
                @endif
                Daftar Asesi
            </p>
            <h2 class="text-3xl font-bold text-gray-900">Daftar Asesi</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar asesi yang terdaftar pada skema ini.</p>
        </div>

        {{-- Card Informasi Jadwal (Optional, disembunyikan jika Master View terlihat aneh dengan Dummy Jadwal, tapi kita tampilkan skema saja) --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                   <h3 class="text-lg font-bold text-gray-900">{{ $jadwal->skema->nama_skema ?? '-' }}</h3>
                   <span class="text-sm text-gray-500">{{ $jadwal->skema->nomor_skema ?? '-' }}</span>
                </div>
                
                @if(!isset($isMasterView) || !$isMasterView)
                <div class="text-sm text-gray-600 text-right">
                    <p><i class="far fa-calendar-alt mr-2 text-blue-500"></i>{{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</p>
                    <p class="mt-1"><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>{{ $jadwal->masterTuk->nama_lokasi ?? '-' }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- TOOLBAR: SEARCH & PER PAGE --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
            {{-- Show Entries --}}
            <div x-data="{ perPage: '{{ request('per_page', 10) }}', changePerPage() { let url = new URL(window.location.href); url.searchParams.set('per_page', this.perPage); url.searchParams.set('page', 1); window.location.href = url.href; } }" class="flex items-center space-x-2">
                <label for="per_page" class="text-sm text-gray-600 font-medium">Tampilkan:</label>
                <select id="per_page" x-model="perPage" @change="changePerPage()" class="bg-white text-sm border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 py-2 px-3">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-600">data</span>
            </div>

            {{-- Search Form --}}
            <form action="{{ url()->current() }}" method="GET" class="w-full md:w-auto" x-data="{ search: '{{ request('search', '') }}' }">
                <!-- Keep existing params -->
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                
                <div class="relative w-full md:w-72">
                    <input type="text" name="search" x-model="search" placeholder="Cari nama asesi..." class="w-full pl-10 pr-10 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm transition" />
                    <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-blue-600"><i class="fas fa-search"></i></button>
                    <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-red-500" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak><i class="fas fa-times"></i></button>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Asesi --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200 border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 font-semibold border border-gray-200 w-16 text-center">No</th>
                        <th class="px-4 py-3 font-semibold border border-gray-200">Nama Asesi</th>
                        <th class="px-4 py-3 font-semibold border border-gray-200 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pendaftar as $index => $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 text-center border border-gray-200">
                                {{ ($pendaftar->currentPage() - 1) * $pendaftar->perPage() + $loop->iteration }}
                            </td>
                            
                            <td class="px-4 py-4 border border-gray-200">
                                <span class="font-bold text-gray-800 block">
                                    {{ $item->asesi->nama_lengkap ?? 'Nama Tidak Ditemukan' }}
                                </span>
                                @if($item->asesi->dataPekerjaan)
                                    <span class="text-xs text-gray-500 block mt-1">{{ $item->asesi->dataPekerjaan->nama_institusi_pekerjaan }}</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4 text-center border border-gray-200">
                                <a href="{{ route($targetRoute, $item->id_data_sertifikasi_asesi) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs flex items-center justify-center">
                                    <i class="fas fa-file-alt mr-1"></i> {{ $buttonLabel ?? 'Buka Form' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                Data asesi tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{-- PAGINATION FOOTER --}}
            <div class="mt-6">
                {{ $pendaftar->appends(request()->query())->links('components.pagination') }}
            </div>
        </div>

    </main>

</body>
</html>