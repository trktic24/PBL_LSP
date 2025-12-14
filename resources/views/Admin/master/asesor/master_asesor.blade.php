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
        
        <x-navbar.navbar_admin/>
        <main class="p-6">
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
                <h2 class="text-3xl font-bold text-gray-900">Daftar Asesor</h2>
            </div>
            
            @php
                // Menggunakan requestData dari controller untuk kompatibilitas
                $allParams = $requestData;
            @endphp
            
            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                
                {{-- Search Form --}}
                <form 
                    action="{{ route('admin.master_asesor') }}" 
                    method="GET" 
                    class="w-full max-w-sm"
                    x-data="{ search: '{{ $requestData['search'] ?? '' }}' }" 
                >
                    {{-- Hidden inputs untuk menjaga parameter filter saat search --}}
                    @foreach ($allParams as $key => $value)
                        @if ($key != 'search' && $key != 'page')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <div class="relative">
                        <input 
                            type="text" 
                            name="search"
                            placeholder="Cari Asesor (Nama, NIK, Email...)"
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

                <div class="flex space-x-3">
                    {{-- Filter Dropdown Complex (Dipertahankan logikanya, disesuaikan UI-nya) --}}
                    <div class="relative" x-data="{ open: false, activeFilter: '' }" @click.away="open = false; activeFilter = ''">
                        <button
                            @click="open = !open; activeFilter = ''"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center"
                        >
                            <i class="fas fa-filter mr-2"></i> Filter
                            
                            @php
                                $filterCount = 0;
                                if (!empty($requestData['skema_id'])) $filterCount++;
                                if (!empty($requestData['jenis_kelamin'])) $filterCount++;
                                if (!empty($requestData['status_verifikasi'])) $filterCount++;
                            @endphp

                            @if ($filterCount > 0)
                                <span class="ml-2 w-5 h-5 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs">{{ $filterCount }}</span>
                            @endif
                        </button>
                        
                        {{-- Dropdown Content --}}
                        <div
                            x-show="open"
                            x-transition
                            class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-md shadow-lg z-20 overflow-hidden"
                            x-cloak
                        >
                            {{-- Main Filter Menu --}}
                            <div x-show="activeFilter === ''" x-transition>
                                <div class="px-4 py-3 border-b text-xs text-gray-500 font-semibold uppercase">Filter Berdasarkan</div>
                                
                                <button @click="activeFilter = 'skema'" class="w-full text-left flex justify-between items-center px-4 py-3 hover:bg-gray-50 text-sm text-gray-700">
                                    <span class="flex items-center"><i class="fas fa-award fa-fw mr-2 text-gray-400"></i>Sertifikasi (Skema)</span>
                                    @if (!empty($requestData['skema_id'])) <span class="text-xs text-blue-600 font-semibold">Aktif</span> @endif
                                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                                </button>

                                <button @click="activeFilter = 'jk'" class="w-full text-left flex justify-between items-center px-4 py-3 hover:bg-gray-50 text-sm text-gray-700">
                                    <span class="flex items-center"><i class="fas fa-venus-mars fa-fw mr-2 text-gray-400"></i>Jenis Kelamin</span>
                                    @if (!empty($requestData['jenis_kelamin'])) <span class="text-xs text-blue-600 font-semibold">Aktif</span> @endif
                                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                                </button>
                                
                                <button @click="activeFilter = 'status'" class="w-full text-left flex justify-between items-center px-4 py-3 hover:bg-gray-50 text-sm text-gray-700">
                                    <span class="flex items-center"><i class="fas fa-check-circle fa-fw mr-2 text-gray-400"></i>Status</span>
                                    @if (!empty($requestData['status_verifikasi'])) <span class="text-xs text-blue-600 font-semibold">Aktif</span> @endif
                                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                                </button>

                                @if ($filterCount > 0)
                                <div class="border-t border-gray-100">
                                    <a href="{{ route('admin.master_asesor', ['search' => $requestData['search'] ?? '', 'sort' => $sortColumn, 'direction' => $sortDirection, 'per_page' => $perPage]) }}" 
                                       class="block w-full text-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-times-circle mr-1"></i> Reset Semua Filter
                                    </a>
                                </div>
                                @endif
                            </div>

                            {{-- Sub-menu Skema --}}
                            <div x-show="activeFilter === 'skema'" class="max-h-80 overflow-y-auto bg-white" x-transition>
                                <button @click="activeFilter = ''" class="flex items-center px-4 py-3 text-sm font-semibold text-blue-600 hover:bg-gray-50 w-full border-b"><i class="fas fa-chevron-left text-xs mr-2"></i>Kembali</button>
                                <a href="{{ route('admin.master_asesor', array_merge($allParams, ['skema_id' => '', 'page' => 1])) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm">Tampilkan Semua</a>
                                @foreach ($skemas as $skema)
                                    <a href="{{ route('admin.master_asesor', array_merge($allParams, ['skema_id' => $skema->id_skema, 'page' => 1])) }}" 
                                       class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm {{ (isset($requestData['skema_id']) && $requestData['skema_id'] == $skema->id_skema) ? 'bg-blue-50 font-semibold text-blue-600' : '' }}">
                                        {{ $skema->nama_skema }}
                                    </a>
                                @endforeach
                            </div>

                            {{-- Sub-menu Jenis Kelamin --}}
                            <div x-show="activeFilter === 'jk'" class="bg-white" x-transition>
                                <button @click="activeFilter = ''" class="flex items-center px-4 py-3 text-sm font-semibold text-blue-600 hover:bg-gray-50 w-full border-b"><i class="fas fa-chevron-left text-xs mr-2"></i>Kembali</button>
                                <a href="{{ route('admin.master_asesor', array_merge($allParams, ['jenis_kelamin' => '', 'page' => 1])) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm">Semua</a>
                                <a href="{{ route('admin.master_asesor', array_merge($allParams, ['jenis_kelamin' => 'Laki-laki', 'page' => 1])) }}" 
                                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm {{ (isset($requestData['jenis_kelamin']) && $requestData['jenis_kelamin'] == 'Laki-laki') ? 'bg-blue-50 font-semibold text-blue-600' : '' }}">Laki-laki</a>
                                <a href="{{ route('admin.master_asesor', array_merge($allParams, ['jenis_kelamin' => 'Perempuan', 'page' => 1])) }}" 
                                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm {{ (isset($requestData['jenis_kelamin']) && $requestData['jenis_kelamin'] == 'Perempuan') ? 'bg-blue-50 font-semibold text-blue-600' : '' }}">Perempuan</a>
                            </div>

                            {{-- Sub-menu Status --}}
                            <div x-show="activeFilter === 'status'" class="bg-white" x-transition>
                                <button @click="activeFilter = ''" class="flex items-center px-4 py-3 text-sm font-semibold text-blue-600 hover:bg-gray-50 w-full border-b"><i class="fas fa-chevron-left text-xs mr-2"></i>Kembali</button>
                                <a href="{{ route('admin.master_asesor', array_merge($allParams, ['status_verifikasi' => '', 'page' => 1])) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm">Semua</a>
                                
                                <a href="{{ route('admin.master_asesor', array_merge($allParams, ['status_verifikasi' => 'approved', 'page' => 1])) }}" 
                                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm {{ (isset($requestData['status_verifikasi']) && $requestData['status_verifikasi'] == 'approved') ? 'bg-blue-50 font-semibold text-blue-600' : '' }}">
                                   <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>Disetujui
                                </a>
                                
                                <a href="{{ route('admin.master_asesor', array_merge($allParams, ['status_verifikasi' => 'pending', 'page' => 1])) }}" 
                                   class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm {{ (isset($requestData['status_verifikasi']) && $requestData['status_verifikasi'] == 'pending') ? 'bg-blue-50 font-semibold text-blue-600' : '' }}">
                                   <span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>Menunggu
                                </a>
                                
                                 <a href="{{ route('admin.master_asesor', array_merge($allParams, ['status_verifikasi' => 'rejected', 'page' => 1])) }}" 
                                    class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm {{ (isset($requestData['status_verifikasi']) && $requestData['status_verifikasi'] == 'rejected') ? 'bg-blue-50 font-semibold text-blue-600' : '' }}">
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>Ditolak
                                 </a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.add_asesor1') }}" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Tambah Asesor
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
                
                <div
                    x-data="{
                        perPage: '{{ $perPage }}',
                        changePerPage() {
                            let url = new URL(window.location.href);
                            url.searchParams.set('per_page', this.perPage);
                            url.searchParams.set('page', 1);
                            window.location.href = url.href;
                        }
                    }"
                    class="flex items-center space-x-2 mb-6"
                >
                    <label for="per_page" class="text-sm text-gray-600">Show:</label>
                    <select
                        id="per_page"
                        x-model="perPage"
                        @change="changePerPage()"
                        class="bg-white text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                </div>

                {{-- TABEL ASESOR DENGAN UI BARU (Divide-x, border, text-xs) --}}
                <table class="min-w-full text-xs text-left border border-gray-200">
                    
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr class="divide-x divide-gray-200 border-b border-gray-200">
                            @php
                                $baseParams = $allParams;
                                unset($baseParams['page']);
                            @endphp
                            
                            {{-- ID --}}
                            <th class="px-4 py-3 font-semibold w-16 whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'id_asesor'; @endphp
                                <a href="{{ route('admin.master_asesor', array_merge($baseParams, ['sort' => 'id_asesor', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            {{-- Nama Asesor --}}
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'nama_lengkap'; @endphp
                                <a href="{{ route('admin.master_asesor', array_merge($baseParams, ['sort' => 'nama_lengkap', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>Nama Asesor</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            {{-- Email --}}
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'email'; @endphp
                                <a href="{{ route('admin.master_asesor', array_merge($baseParams, ['sort' => 'email', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>Email</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            {{-- No. Registrasi --}}
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'nomor_regis'; @endphp
                                <a href="{{ route('admin.master_asesor', array_merge($baseParams, ['sort' => 'nomor_regis', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>No. Registrasi</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            {{-- No. Telepon --}}
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'nomor_hp'; @endphp
                                <a href="{{ route('admin.master_asesor', array_merge($baseParams, ['sort' => 'nomor_hp', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>No. Telepon</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            
                            {{-- Skema (Non-sortable) --}}
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                Bidang Sertifikasi (Skema)
                            </th>

                             {{-- Status (Non-sortable) --}}
                             <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                Status Verifikasi
                            </th>
                            
                            {{-- Aksi --}}
                            <th class="px-6 py-3 font-semibold text-center w-1 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($asesors as $asesor)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            <td class="px-4 py-4">{{ $asesor->id_asesor }}</td>
                            <td class="px-6 py-4 font-medium">{{ $asesor->nama_lengkap }}</td>
                            <td class="px-6 py-4">{{ $asesor->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $asesor->nomor_regis }}</td>
                            <td class="px-6 py-4">{{ $asesor->nomor_hp }}</td>
                            <td class="px-6 py-4">
                                <ul class="list-disc list-inside text-gray-600">
                                    @forelse($asesor->skemas as $skema)
                                        <li>{{ $skema->nama_skema }}</li>
                                    @empty
                                        <li class="list-none text-gray-400 italic">Belum ada skema</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($asesor->status_verifikasi == 'approved')
                                  <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full flex items-center w-fit">
                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                  </span>
                                @elseif($asesor->status_verifikasi == 'rejected')
                                  <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full flex items-center w-fit">
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                  </span>
                                @else
                                  <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full flex items-center w-fit">
                                    <i class="fas fa-clock mr-1"></i> Menunggu
                                  </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.edit_asesor1', $asesor->id_asesor) }}" class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-lg transition">
                                        <i class="fas fa-pen"></i> <span>Edit</span>
                                    </a>
                                    <form 
                                        action="{{ route('admin.asesor.destroy', $asesor->id_asesor) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus asesor {{ $asesor->nama_lengkap }}? Tindakan ini akan menghapus data asesor, akun user, dan semua file terkait.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                                            <i class="fas fa-trash"></i> <span>Delete</span>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.asesor.profile', $asesor->id_asesor) }}"
                                       class="flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition"> <i class="fas fa-eye"></i> <span>View</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data Asesor yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($asesors->total() > 0)
                        Showing {{ $asesors->firstItem() }} - {{ $asesors->lastItem() }} of {{ $asesors->total() }} results
                        @else
                        Showing 0 results
                        @endif
                    </div>
                    <div>
                        {{ $asesors->links('components.pagination') }}
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>