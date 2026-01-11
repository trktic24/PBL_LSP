<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Master TUK | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 0;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin/>

        <main class="p-6">
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
                <h2 class="text-3xl font-bold text-gray-900">Tempat Uji Kompetensi (TUK)</h2>
            </div>
            
            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                
                <form action="{{ route('admin.master_tuk') }}" method="GET" class="w-full max-w-sm" x-data="{ search: '{{ request('search', '') }}' }">
                    <div class="relative">
                        <input type="text" name="search" x-model="search" placeholder="Search..."
                            class="w-full pl-10 pr-10 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-gray-600"
                            x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </form>

                <a href="{{ route('admin.add_tuk') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah TUK
                </a>
            </div>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex justify-between items-center"
                    role="alert">
                    <span class="font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 text-green-900 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg flex justify-between items-center"
                    role="alert">
                    <span class="font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-4 text-red-900 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 w-full overflow-x-auto">
                
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

                <table class="min-w-full divide-y divide-gray-200 text-xs border border-gray-200">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr class="divide-x divide-gray-200 border-b border-gray-200">
                            <th class="px-6 py-3 text-left font-semibold w-16">
                                @php
                                    $isCurrentColumn = request('sort', 'id_tuk') == 'id_tuk';
                                    $direction = request('direction', 'asc');
                                @endphp
                                <a href="{{ route('admin.master_tuk', ['sort' => 'id_tuk', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-xs">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-left font-semibold w-24">Foto</th>

                            <th class="px-6 py-3 text-left font-semibold">
                                @php
                                    $isCurrentColumn = request('sort') == 'nama_lokasi';
                                    $direction = request('direction', 'asc');
                                @endphp
                                <a href="{{ route('admin.master_tuk', ['sort' => 'nama_lokasi', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2">
                                    <span>Nama TUK</span>
                                    <div class="flex flex-col -space-y-1 text-xs">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-left font-semibold">
                                @php
                                    $isCurrentColumn = request('sort') == 'alamat_tuk';
                                    $direction = request('direction', 'asc');
                                @endphp
                                <a href="{{ route('admin.master_tuk', ['sort' => 'alamat_tuk', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2">
                                    <span>Alamat</span>
                                    <div class="flex flex-col -space-y-1 text-xs">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-left font-semibold">
                                @php
                                    $isCurrentColumn = request('sort') == 'kontak_tuk';
                                    $direction = request('direction', 'asc');
                                @endphp
                                <a href="{{ route('admin.master_tuk', ['sort' => 'kontak_tuk', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2">
                                    <span>Kontak</span>
                                    <div class="flex flex-col -space-y-1 text-xs">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-left font-semibold">Link Gmap</th>
                            
                            <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($tuks as $tuk)
                            <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                                <td class="px-6 py-4">{{ $tuk->id_tuk }}</td>

                                <td class="px-6 py-4">
                                    <div class="h-32 w-32 rounded-md overflow-hidden border border-gray-200 bg-gray-50 relative group">
                                        <img src="{{ $tuk->foto_tuk ? asset('storage/' . $tuk->foto_tuk) : asset('images/default_pic.jpeg') }}" 
                                             alt="Foto {{ $tuk->nama_lokasi ?? 'TUK' }}" 
                                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-200 cursor-pointer"
                                             onclick="window.open(this.src, '_blank')"
                                             onerror="this.onerror=null; this.src='{{ asset('images/default_pic.jpeg') }}';">
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-medium">{{ $tuk->nama_lokasi }}</td>
                                <td class="px-6 py-4">{{ $tuk->alamat_tuk }}</td>
                                <td class="px-6 py-4">{{ $tuk->kontak_tuk }}</td>
                                
                                <td class="px-6 py-4">
                                    @if ($tuk->link_gmap)
                                        <button onclick="openMapModal('{{ $tuk->link_gmap }}', '{{ addslashes($tuk->nama_lokasi) }}')"
                                            class="text-blue-600 hover:underline bg-transparent border-none cursor-pointer">
                                            Buka Peta
                                        </button>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.edit_tuk', $tuk->id_tuk) }}"
                                            class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition">
                                            <i class="fas fa-pen"></i> <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.delete_tuk', $tuk->id_tuk) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus TUK (ID: {{ $tuk->id_tuk }}) - {{ addslashes($tuk->nama_lokasi) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition">
                                                <i class="fas fa-trash"></i> <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data TUK yang ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($tuks->total() > 0)
                            Showing {{ $tuks->firstItem() }} - {{ $tuks->lastItem() }} of {{ $tuks->total() }} results
                        @else
                            Showing 0 results
                        @endif
                    </div>
                    
                    <div>
                        {{ $tuks->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </main>
    </div>
    {{-- MODAL MAPS --}}
    <div id="mapModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 overflow-hidden relative">
            {{-- Header Modal --}}
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800" id="mapModalTitle">Lokasi: TUK</h3>
                <button onclick="closeMapModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="p-0 h-[400px]">
                <iframe id="mapFrame" src="" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <script>
        function openMapModal(url, name) {
            if (!url) {
                alert('Link Google Maps tidak tersedia.');
                return;
            }
            document.getElementById('mapFrame').src = url;
            document.getElementById('mapModalTitle').innerText = 'Lokasi: ' + name;
            document.getElementById('mapModal').classList.remove('hidden');
        }

        function closeMapModal() {
            document.getElementById('mapModal').classList.add('hidden');
            document.getElementById('mapFrame').src = ''; // Stop video/iframe loading
        }

        // Close modal when clicking outside
        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMapModal();
            }
        });
    </script>
</body>

</html>