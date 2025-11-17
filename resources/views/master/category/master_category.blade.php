<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Master Kategori | LSP Polines</title>

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
        <x-navbar />

        <main class="p-6">
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
                <h2 class="text-3xl font-bold text-gray-900">Category Skema</h2>
            </div>
            
            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                
                <form
                    action="{{ route('master_category') }}"
                    method="GET"
                    class="w-full max-w-sm"
                    x-data="{ search: '{{ request('search', '') }}' }"
                >
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search..."
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
                
                <a
                    href="{{ route('add_category') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition flex items-center"
                >
                    <i class="fas fa-plus mr-2"></i> Add Kategori
                </a>
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
                    <button @click="show = false" class="ml-4 text-green-900 hover:text-green-700"><i class="fas fa-times"></i></button>
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


                <table class="min-w-full divide-y divide-gray-200 text-sm">

                    <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                        <tr>
                            @php
                                $baseParams = ['search' => request('search'), 'per_page' => request('per_page')];
                            @endphp

                            <!-- [PERBAIKAN] Lebar diubah ke w-16 (lebih sempit) -->
                            <th class="px-4 py-3 text-left font-semibold w-16">
                                @php
                                    $isCurrentColumn = $sortColumn == 'id';
                                @endphp
                                <a
                                    href="{{ route('master_category', array_merge($baseParams, ['sort' => 'id', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}"
                                    class="flex w-full items-center justify-between"
                                >
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-sm">
                                        <i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 text-left font-semibold">
                                @php
                                    $isCurrentColumn = $sortColumn == 'nama_kategori';
                                @endphp
                                <a
                                    href="{{ route('master_category', array_merge($baseParams, ['sort' => 'nama_kategori', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}"
                                    class="flex w-full items-center justify-between"
                                >
                                    <span>Nama Kategori</span>
                                    <div class="flex flex-col -space-y-1 text-sm">
                                        <i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 text-left font-semibold">
                                @php
                                    $isCurrentColumn = $sortColumn == 'slug';
                                @endphp
                                <a
                                    href="{{ route('master_category', array_merge($baseParams, ['sort' => 'slug', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}"
                                    class="flex w-full items-center justify-between"
                                >
                                    <span>Slug</span>
                                    <div class="flex flex-col -space-y-1 text-sm">
                                        <i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                        <i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i>
                                    </div>
                                </a>
                            </th>
                            
                            <th class="px-4 py-3 text-center font-semibold w-36">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4">{{ $category->id }}</td>
                            <td class="px-6 py-4 font-medium">{{ $category->nama_kategori }}</td>
                            <td class="px-6 py-4 text-gray-500 italic">{{ $category->slug }}</td>
                            <td class="px-4 py-4 flex space-x-2">
                                
                                <!-- [PERBAIKAN] Tombol Edit yang hilang dikembalikan -->
                                <a
                                    href="{{ route('edit_category', $category->id) }}"
                                    class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-sm rounded-md transition"
                                >
                                    <i class="fas fa-pen"></i> <span>Edit</span>
                                </a>
                                
                                <form
                                    action="{{ route('delete_category', $category->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('PERINGATAN: Menghapus kategori \'{{ addslashes($category->nama_kategori) }}\' juga akan MENGHAPUS SEMUA SKEMA yang terhubung dengannya. Apakah Anda yakin ingin melanjutkan?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded-md transition"
                                    >
                                        <i class="fas fa-trash"></i> <span>Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                Belum ada data kategori yang ditampilkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($categories->total() > 0)
                        Showing {{ $categories->firstItem() }} - {{ $categories->lastItem() }} of {{ $categories->total() }} results
                        @else
                        Showing 0 results
                        @endif
                    </div>
                    <div>
                        {{ $categories->links('components.pagination') }}
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>