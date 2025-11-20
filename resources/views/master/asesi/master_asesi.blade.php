<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Master Asesi | LSP Polines</title>

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
                <h2 class="text-3xl font-bold text-gray-900">Daftar Asesi</h2>
            </div>
            
            @php
                $allParams = request()->query();
            @endphp
            
            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                
                <form 
                    action="{{ route('master_asesi') }}" 
                    method="GET" 
                    class="w-full max-w-sm"
                    x-data="{ search: '{{ request('search', '') }}' }" 
                >
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search"
                            placeholder="Cari Nama, NIK, Email, No. HP..."
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

                <div class="flex space-x-3" x-data="{ open: false }">
                    <div class="relative">
                        <button
                            @click="open = !open"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 flex items-center"
                        >
                            <i class="fas fa-filter mr-2"></i> Filter
                            @if($filterGender)
                                <span class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                            @endif
                        </button>
                        <div
                            x-show="open"
                            @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-20"
                            x-transition x-cloak
                        >
                            <div class="px-4 py-2 text-xs text-gray-500 font-semibold uppercase">Filter Gender</div>
                            <a 
                                href="{{ route('master_asesi', array_merge($allParams, ['filter_gender' => ($filterGender == 'Laki-laki') ? null : 'Laki-laki', 'page' => 1])) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ $filterGender == 'Laki-laki' ? 'bg-blue-50 font-semibold' : '' }}"
                            >Laki-laki</a>
                            <a 
                                href="{{ route('master_asesi', array_merge($allParams, ['filter_gender' => ($filterGender == 'Perempuan') ? null : 'Perempuan', 'page' => 1])) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 {{ $filterGender == 'Perempuan' ? 'bg-blue-50 font-semibold' : '' }}"
                            >Perempuan</a>

                            @if($filterGender)
                                <div class="border-t border-gray-100 my-1"></div>
                                <a 
                                    href="{{ route('master_asesi', array_merge($allParams, ['filter_gender' => null, 'page' => 1])) }}"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    <i class="fas fa-times-circle mr-1"></i> Hapus Filter
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <a href="{{ route('add_asesi') }}" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Tambah Asesi
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

                <table class="min-w-full text-xs text-left border border-gray-200">
                    
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr class="divide-x divide-gray-200 border-b border-gray-200">
                            @php
                                $baseParams = ['search' => request('search'), 'per_page' => request('per_page'), 'filter_gender' => $filterGender];
                            @endphp
                            
                            <th class="px-4 py-3 font-semibold w-16 whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'id_asesi'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'id_asesi', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'nama_lengkap'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'nama_lengkap', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>Nama Asesi</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'nik'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'nik', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>NIK</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'email'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'email', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>Email</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'nomor_hp'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'nomor_hp', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>No. Telepon</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'jenis_kelamin'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'jenis_kelamin', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>Gender</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'pekerjaan'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'pekerjaan', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>Pekerjaan</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">
                                @php $isCurrentColumn = $sortColumn == 'pendidikan'; @endphp
                                <a href="{{ route('master_asesi', array_merge($baseParams, ['sort' => 'pendidikan', 'direction' => ($isCurrentColumn && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between gap-2">
                                    <span>Pendidikan</span>
                                    <div class="flex flex-col -space-y-1 text-sm"><i class="fas fa-caret-up {{ ($isCurrentColumn && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrentColumn && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            <th class="px-6 py-3 font-semibold text-center w-1 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($asesis as $asesi)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            <td class="px-4 py-4">{{ $asesi->id_asesi }}</td>
                            <td class="px-6 py-4 font-medium">{{ $asesi->nama_lengkap }}</td>
                            <td class="px-6 py-4">{{ $asesi->nik }}</td>
                            <td class="px-6 py-4">{{ $asesi->user?->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $asesi->nomor_hp }}</td>
                            <td class="px-6 py-4">{{ $asesi->jenis_kelamin }}</td>
                            <td class="px-6 py-4">{{ $asesi->pekerjaan }}</td>
                            <td class="px-6 py-4">{{ $asesi->pendidikan }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('edit_asesi', $asesi->id_asesi) }}" class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-xs rounded-lg transition">
                                        <i class="fas fa-pen"></i> <span>Edit</span>
                                    </a>
                                    <form 
                                        action="{{ route('delete_asesi', $asesi->id_asesi) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Anda yakin ingin menghapus asesi {{ addslashes($asesi->nama_lengkap) }} (ID: {{ $asesi->id_asesi }})? Ini akan menghapus akun login dan data pribadi asesi.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                                            <i class="fas fa-trash"></i> <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data Asesi yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($asesis->total() > 0)
                        Showing {{ $asesis->firstItem() }} - {{ $asesis->lastItem() }} of {{ $asesis->total() }} results
                        @else
                        Showing 0 results
                        @endif
                    </div>
                    <div>
                        {{ $asesis->links('components.pagination') }}
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>