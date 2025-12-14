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
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar_admin/>
        <main class="p-6">
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
                <h2 class="text-3xl font-bold text-gray-900">Daftar Skema Sertifikasi</h2>
            </div>

            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                <form action="{{ route('admin.master_skema') }}" method="GET" class="w-full max-w-sm" x-data="{ search: '{{ request('search', '') }}' }">
                    <div class="relative">
                        <input type="text" name="search" x-model="search" placeholder="Search..." class="w-full pl-10 pr-10 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600"><i class="fas fa-search"></i></button>
                        <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-gray-600" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak><i class="fas fa-times"></i></button>
                    </div>
                </form>
                <a href="{{ route('admin.add_skema') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Skema
                </a>
            </div>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex justify-between items-center" role="alert">
                    <span class="font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 text-green-900 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
                
                <div x-data="{ perPage: '{{ $perPage }}', changePerPage() { let url = new URL(window.location.href); url.searchParams.set('per_page', this.perPage); url.searchParams.set('page', 1); window.location.href = url.href; } }" class="flex items-center space-x-2 mb-6">
                    <label for="per_page" class="text-sm text-gray-600">Show:</label>
                    <select id="per_page" x-model="perPage" @change="changePerPage()" class="bg-white text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                </div>

                <table class="min-w-full text-xs text-left border border-gray-200">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr class="divide-x divide-gray-200 border-b border-gray-200">
                            @php $baseParams = ['search' => request('search'), 'per_page' => request('per_page')]; @endphp
                            
                            <th class="px-4 py-3 font-semibold w-16">
                                @php $isCurrentColumn = $sortColumn == 'id_skema'; @endphp
                                <a href="{{ route('admin.master_skema', array_merge($baseParams, ['sort' => 'id_skema', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-xs"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-4 py-3 font-semibold w-24">Foto</th>

                            <th class="px-4 py-3 font-semibold">
                                @php $isCurrentColumn = $sortColumn == 'nomor_skema'; @endphp
                                <a href="{{ route('admin.master_skema', array_merge($baseParams, ['sort' => 'nomor_skema', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>No. Skema</span>
                                    <div class="flex flex-col -space-y-1 text-xs"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-4 py-3 font-semibold">
                                @php $isCurrentColumn = $sortColumn == 'nama_skema'; @endphp
                                <a href="{{ route('admin.master_skema', array_merge($baseParams, ['sort' => 'nama_skema', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Nama Skema</span>
                                    <div class="flex flex-col -space-y-1 text-xs"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-4 py-3 font-semibold">
                                @php $isCurrentColumn = $sortColumn == 'category_nama'; @endphp
                                <a href="{{ route('admin.master_skema', array_merge($baseParams, ['sort' => 'category_nama', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Kategori</span>
                                    <div class="flex flex-col -space-y-1 text-xs"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-4 py-3 font-semibold">
                                @php $isCurrentColumn = $sortColumn == 'harga'; @endphp
                                <a href="{{ route('admin.master_skema', array_merge($baseParams, ['sort' => 'harga', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Harga</span>
                                    <div class="flex flex-col -space-y-1 text-xs"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-4 py-3 font-semibold text-center">SKKNI</th>
                            <th class="px-4 py-3 font-semibold text-center w-1 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($skemas as $skema)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            <td class="px-4 py-4">{{ $skema->id_skema }}</td>

                            <td class="px-4 py-4">
                                <div class="h-32 w-32 rounded-md overflow-hidden border border-gray-200 bg-gray-50">
                                    @php
                                        $imgSrc = 'images/default.jpg';
                                        if ($skema->gambar) {
                                            if (str_starts_with($skema->gambar, 'images/')) {
                                                if(file_exists(public_path($skema->gambar))) {
                                                    $imgSrc = $skema->gambar;
                                                }
                                            } elseif (file_exists(public_path('images/skema/foto_skema/' . $skema->gambar))) {
                                                $imgSrc = 'images/skema/foto_skema/' . $skema->gambar;
                                            } elseif (file_exists(public_path('images/skema/' . $skema->gambar))) {
                                                $imgSrc = 'images/skema/' . $skema->gambar;
                                            }
                                        }
                                    @endphp
                                    <img src="{{ asset($imgSrc) }}" 
                                            alt="{{ $skema->nama_skema }}" 
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-200 cursor-pointer"
                                            onclick="window.open(this.src, '_blank')"
                                    >
                                </div>
                            </td>

                            <td class="px-4 py-4 font-medium">{{ $skema->nomor_skema }}</td>
                            <td class="px-4 py-4">{{ $skema->nama_skema }}</td>
                            
                            <td class="px-4 py-4">
                                {{ $skema->category?->nama_kategori ?? '-' }}
                            </td>

                            <td class="px-4 py-4">Rp {{ number_format($skema->harga, 0, ',', '.') }}</td>
                            
                            <td class="px-4 py-4 text-center">
                                @if($skema->SKKNI)
                                    <a href="{{ asset($skema->SKKNI) }}" target="_blank" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-file-pdf fa-2x"></i>
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center space-x-2">
                                    
                                    <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" class="flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition">
                                        <i class="fas fa-list"></i> <span>Detail</span>
                                    </a>
                                    
                                    <a href="{{ route('admin.edit_skema', $skema->id_skema) }}" class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition">
                                        <i class="fas fa-pen"></i> <span>Edit</span>
                                    </a>

                                    <form action="{{ route('admin.delete_skema', $skema->id_skema) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus Skema (ID: {{ $skema->id_skema }}) - {{ addslashes($skema->nama_skema) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition">
                                            <i class="fas fa-trash"></i> <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada data Skema.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($skemas->total() > 0) Showing {{ $skemas->firstItem() }} - {{ $skemas->lastItem() }} of {{ $skemas->total() }} results @else Showing 0 results @endif
                    </div>
                    <div>{{ $skemas->links('components.pagination') }}</div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>