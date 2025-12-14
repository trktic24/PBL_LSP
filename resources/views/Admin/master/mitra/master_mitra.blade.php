<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Master Mitra | LSP Polines</title>
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
                <h2 class="text-3xl font-bold text-gray-900">Data Mitra</h2>
            </div>
            
            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                <form action="{{ route('admin.master_mitra') }}" method="GET" class="w-full max-w-sm" x-data="{ search: '{{ request('search', '') }}' }">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari Mitra..." class="w-full pl-10 pr-10 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" x-model="search" />
                        <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600"><i class="fas fa-search"></i></button>
                        <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-gray-600" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak><i class="fas fa-times"></i></button>
                    </div>
                </form>
                
                <a href="{{ route('admin.add_mitra') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Mitra
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
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                </div>

                <table class="min-w-full divide-y divide-gray-200 text-sm border border-gray-200">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr class="divide-x divide-gray-200 border-b border-gray-200">
                            @php $baseParams = ['search' => request('search'), 'per_page' => request('per_page')]; @endphp
                            <th class="px-4 py-3 text-left font-semibold w-16">
                                <a href="{{ route('admin.master_mitra', array_merge($baseParams, ['sort' => 'id', 'direction' => ($sortColumn == 'id' && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-xs">
                                        <i class="fas fa-caret-up {{ ($sortColumn == 'id' && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ ($sortColumn == 'id' && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left font-semibold">
                                <a href="{{ route('admin.master_mitra', array_merge($baseParams, ['sort' => 'nama_mitra', 'direction' => ($sortColumn == 'nama_mitra' && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Nama Mitra</span>
                                    <div class="flex flex-col -space-y-1 text-xs">
                                        <i class="fas fa-caret-up {{ ($sortColumn == 'nama_mitra' && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ ($sortColumn == 'nama_mitra' && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left font-semibold">URL</th>
                            <th class="px-6 py-3 text-center font-semibold">Logo</th>
                            <th class="px-4 py-3 text-center font-semibold w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($mitras as $mitra)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            <td class="px-4 py-4">{{ $mitra->id }}</td>
                            <td class="px-6 py-4 font-medium">{{ $mitra->nama_mitra }}</td>
                            <td class="px-6 py-4 text-gray-500">
                                @if($mitra->url)
                                    <a href="{{ $mitra->url }}" target="_blank" class="text-blue-600 hover:underline">{{ \Illuminate\Support\Str::limit($mitra->url, 30) }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($mitra->logo)
                                    <img src="{{ asset('storage/' . $mitra->logo) }}" alt="Logo" class="h-10 mx-auto object-contain">
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.edit_mitra', $mitra->id) }}" class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition">
                                        <i class="fas fa-pen"></i> <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.delete_mitra', $mitra->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mitra ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition">
                                            <i class="fas fa-trash"></i> <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada data mitra.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($mitras->total() > 0) Showing {{ $mitras->firstItem() }} - {{ $mitras->lastItem() }} of {{ $mitras->total() }} results @else Showing 0 results @endif
                    </div>
                    <div>{{ $mitras->links('components.pagination') }}</div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
