<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Hadir | LSP Polines</title>

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

        <x-navbar.navbar_admin/>

        <main class="p-6">

            <div class="flex flex-col xl:flex-row justify-between items-end mb-8 gap-6">
                <div class="w-full xl:max-w-lg">
                    @php
                        // Default: Kembali ke Master Schedule
                        $routeKembali = route('admin.master_schedule');

                        // Cek parameter 'from' dari URL
                        if (request('from') == 'schedule_admin') {
                            // GANTI 'admin.schedule_admin' DENGAN NAMA ROUTE YANG BENAR UNTUK PAGE SCHEDULE ADMIN ANDA
                            $routeKembali = route('admin.schedule_admin'); 
                        }
                    @endphp

                    <p class="text-sm text-gray-500 mb-2">
                        {{ request('from') == 'schedule_admin' ? 'Schedule Admin' : 'Master Schedule' }} > Daftar Hadir
                    </p>

                    <a href="{{ $routeKembali }}" class="flex items-center text-gray-700 hover:text-blue-600 text-base font-medium w-fit transition mb-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Daftar Hadir Peserta</h2>

                    <form action="{{ route('admin.schedule.attendance', $jadwal->id_jadwal) }}" method="GET" class="w-full max-w-sm" x-data="{ search: '{{ request('search', '') }}' }">
                        <div class="relative">
                            <input type="text" name="search" x-model="search" placeholder="Search..." class="w-full pl-10 pr-10 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm" />
                            <button type="submit" class="absolute left-3 top-0 h-full text-gray-400 hover:text-gray-600"><i class="fas fa-search"></i></button>
                            <button type="button" class="absolute right-3 top-0 h-full text-gray-400 hover:text-gray-600" x-show="search.length > 0" @click="search = ''; $nextTick(() => $el.form.submit())" x-cloak><i class="fas fa-times"></i></button>
                        </div>
                    </form>
                </div>

                <div class="w-full xl:w-auto flex-1 xl:max-w-xl flex justify-end">
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm w-full">
                        <div class="flex flex-col gap-3">
                            <div class="flex justify-between items-start gap-4">

                                <div class="flex items-start text-blue-700 font-semibold text-lg flex-1">
                                    <i class="fas fa-certificate w-6 text-center mr-2 mt-1 shrink-0"></i>
                                    <span class="leading-tight">
                                        {{ $jadwal->skema->nama_skema }}
                                    </span>
                                </div>

                                <div class="flex items-center text-gray-600 text-xs bg-gray-50 px-3 py-1 rounded-full border border-gray-100 shrink-0 whitespace-nowrap">
                                    <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                    <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</span>
                                </div>
                            </div>
                            <div class="h-px bg-gray-100 my-1"></div>
                            <div class="flex flex-wrap gap-x-8 gap-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center mr-2 text-blue-600 shrink-0">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Asesor</span>
                                        
                                        <a href="{{ route('admin.asesor.profile', $jadwal->id_asesor) }}" 
                                        class="font-medium text-gray-900 hover:text-blue-600 transition-colors cursor-pointer"
                                        title="Lihat Profil Asesor">
                                            {{ $jadwal->asesor->nama_lengkap }}
                                        </a>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center mr-2 text-red-600 shrink-0">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Tempat Uji Kompetensi</span>
                                        <span class="font-medium text-gray-900">{{ $jadwal->masterTuk->nama_lokasi }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

                            @php
                            $baseParams = ['search' => request('search'), 'per_page' => request('per_page')];
                            @endphp

                            <th class="px-4 py-3 font-semibold w-16 text-center">
                                @php $isCurrent = $sortColumn == 'id_data_sertifikasi_asesi'; @endphp
                                <a href="{{ route('admin.schedule.attendance', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'id_data_sertifikasi_asesi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'nama_lengkap'; @endphp
                                <a href="{{ route('admin.schedule.attendance', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'nama_lengkap', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Nama Peserta</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'institusi'; @endphp
                                <a href="{{ route('admin.schedule.attendance', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'institusi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Institusi / Perusahaan</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'alamat_rumah'; @endphp
                                <a href="{{ route('admin.schedule.attendance', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'alamat_rumah', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Alamat</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'pekerjaan'; @endphp
                                <a href="{{ route('admin.schedule.attendance', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'pekerjaan', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Pekerjaan</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'nomor_hp'; @endphp
                                <a href="{{ route('admin.schedule.attendance', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'nomor_hp', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>No. HP</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold text-center">Tanda Tangan</th>

                            <th class="px-4 py-3 font-semibold text-center">
                                Kehadiran
                            </th>

                            <th class="px-6 py-3 font-semibold text-center w-24">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pendaftar as $index => $data)

                        <tr class="hover:bg-blue-50 transition divide-x divide-gray-200 cursor-pointer group"
                            onclick="window.location='{{ route('admin.asesi.profile.settings', [
                                'id_asesi' => $data->asesi->id_asesi, // <--- SUDAH DIPERBAIKI (Sesuaikan dengan nama parameter di Route)
                                'sertifikasi_id' => $data->id_data_sertifikasi_asesi
                            ]) }}'">

                            <td class="px-4 py-4 text-center font-medium text-gray-500">
                                {{ $data->id_data_sertifikasi_asesi }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900 group-hover:text-blue-700 transition-colors">
                                {{ $data->asesi->nama_lengkap }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $data->asesi->dataPekerjaan->nama_institusi_pekerjaan ?? '-' }}
                            </td>

                            <td class="px-6 py-4 truncate max-w-xs" title="{{ $data->asesi->alamat_rumah }}">
                                {{ Illuminate\Support\Str::limit($data->asesi->alamat_rumah, 30) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $data->asesi->pekerjaan }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $data->asesi->nomor_hp }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div onclick="event.stopPropagation()" class="flex justify-center">
                                    <div class="h-32 w-32 rounded-md overflow-hidden border border-gray-200 bg-white relative group-img">
                                        @if($data->asesi->tanda_tangan)
                                        <img src="{{ route('secure.file', ['path' => $data->asesi->tanda_tangan]) }}"
                                            class="w-full h-full object-contain p-1 hover:scale-110 transition-transform duration-200 cursor-pointer"
                                            alt="TTD"
                                            onclick="window.open(this.src, '_blank')">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-center" onclick="event.stopPropagation()">
                                <input type="checkbox"
                                        name="selected_pendaftar[]"
                                        value="{{ $data->id_data_sertifikasi_asesi }}"
                                        class="w-6 h-6 rounded-full border-gray-300 shadow-sm accent-green-600 cursor-pointer focus:ring-0 focus:outline-none focus:border-gray-300">
                            </td>

                            <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                                <form action="{{ route('admin.schedule.attendance.destroy', ['id_jadwal' => $jadwal->id_jadwal, 'id' => $data->id_data_sertifikasi_asesi])}}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta {{ $data->asesi->nama_lengkap }} dari jadwal ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center space-x-1 px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition">
                                        <i class="fas fa-trash"></i> <span>Delete</span>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada peserta yang mendaftar di jadwal ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500 font-bold">
                        @if ($pendaftar->total() > 0)
                        Showing {{ $pendaftar->firstItem() }} - {{ $pendaftar->lastItem() }} of {{ $pendaftar->total() }} results
                        @else
                        Showing 0 results
                        @endif
                    </div>
                    <div>
                        {{ $pendaftar->links('components.pagination') }}
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>

</html>