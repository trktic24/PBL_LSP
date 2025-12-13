@php
    use Illuminate\Support\Str; // <-- TAMBAHKAN BARIS INI
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Master Berita | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
        [x-cloak] { display: none !important; }
        th.sortable { cursor: pointer; user-select: none; }
        th.sortable:hover { background-color: #f3f4f6; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar_admin/>

        <main class="p-6">
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
                <h2 class="text-3xl font-bold text-gray-900">Daftar Berita Terbaru</h2>
            </div>
            
            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                
                <form action="{{ route('admin.master_berita') }}" method="GET" class="w-full max-w-sm" x-data="{ search: '{{ request('search', '') }}' }">
                    <div class="relative">
                        <input type="text" name="search" x-model="search" placeholder="Cari Judul Berita..."
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

                <a href="{{ route('admin.add_berita') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Berita
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
                                    // Logika default: Jika tidak ada request 'sort', anggap sedang sort 'id'
                                    $isCurrentColumn = request('sort', 'id') == 'id';
                                    // Logika default: Jika tidak ada request 'direction', anggap 'asc'
                                    $direction = request('direction', 'asc');
                                @endphp
                                <a href="{{ route('admin.master_berita', ['sort' => 'id', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2 group">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-xs text-gray-400">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-left font-semibold w-24">Gambar</th>

                            <th class="px-6 py-3 text-left font-semibold w-1/4">
                                @php
                                    $isCurrentColumn = request('sort') == 'judul';
                                @endphp
                                <a href="{{ route('admin.master_berita', ['sort' => 'judul', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2 group">
                                    <span>Judul Berita</span>
                                    <div class="flex flex-col -space-y-1 text-xs text-gray-400">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-left font-semibold">
                                @php
                                    $isCurrentColumn = request('sort') == 'isi';
                                @endphp
                                <a href="{{ route('admin.master_berita', ['sort' => 'isi', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2 group">
                                    <span>Deskripsi</span>
                                    <div class="flex flex-col -space-y-1 text-xs text-gray-400">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-left font-semibold w-40">
                                @php
                                    $isCurrentColumn = request('sort') == 'created_at';
                                @endphp
                                <a href="{{ route('admin.master_berita', ['sort' => 'created_at', 'direction' => $isCurrentColumn && $direction == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                                    class="flex w-full items-center justify-between gap-2 group">
                                    <span>Tanggal Publish</span>
                                    <div class="flex flex-col -space-y-1 text-xs text-gray-400">
                                        <i class="fas fa-caret-up {{ $isCurrentColumn && $direction == 'asc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ $isCurrentColumn && $direction == 'desc' ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>

                            <th class="px-6 py-3 text-center font-semibold w-32">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($beritas as $index => $berita)
                            <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                                <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $berita->id }}</td>

                                <td class="px-6 py-4">
                                    <div class="h-32 w-32 rounded-md overflow-hidden border border-gray-200 bg-gray-50 relative group mx-auto">
                                        @if($berita->gambar)
                                            <img src="{{ asset($berita->gambar) }}" 
                                                 alt="Thumbnail" 
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

                                <td class="px-6 py-4 font-medium text-gray-900">{{ $berita->judul }}</td>
                                
                                <td class="px-6 py-4 text-gray-600 text-xs leading-relaxed">
                                    {{ Str::limit($berita->isi, 100, '...') }}
                                </td>
                                
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $berita->created_at->format('d M Y') }}
                                    <br>
                                    <span class="text-[10px] text-gray-400">{{ $berita->created_at->format('H:i') }} WIB</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.edit_berita', $berita->id) }}"
                                            class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-md transition shadow-sm">
                                            <i class="fas fa-pen"></i> <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.delete_berita', $berita->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition shadow-sm">
                                                <i class="fas fa-trash"></i> <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="far fa-newspaper text-4xl mb-2 text-gray-300"></i>
                                        <p>Belum ada berita yang ditambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($beritas->total() > 0)
                            Showing {{ $beritas->firstItem() }} - {{ $beritas->lastItem() }} of {{ $beritas->total() }} results
                        @else
                            Showing 0 results
                        @endif
                    </div>
                    
                    <div>
                        {{ $beritas->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>