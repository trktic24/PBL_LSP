@extends('layouts.app-sidebar-skema')

{{-- Menetapkan Judul Halaman --}}
@section('title', 'Daftar Hadir Peserta')
@section('content')

    <div class="p-0">
            <div class="flex flex-col xl:flex-row justify-between items-end mb-8 gap-6">
                <div class="w-full xl:max-w-lg">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 mt-10">Daftar Hadir Peserta</h2>

                    <form action="{{ route('daftar_hadir', $jadwal->id_jadwal) }}" method="GET" class="w-full max-w-sm" x-data="{ search: '{{ request('search', '') }}' }">
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
                                    <i class="fas fa-certificate w-6 text-center mt-1 shrink-0"></i>
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
                                        <span class="font-medium text-gray-900">{{ $jadwal->asesor->nama_lengkap }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center mr-2 text-red-600 shrink-0">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Tempat Uji Kompetensi</span>
                                        <span class="font-medium text-gray-900">{{ $jadwal->tuk->nama_lokasi }}</span>
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
                                <a href="{{ route('daftar_hadir', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'id_data_sertifikasi_asesi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'nama_lengkap'; @endphp
                                <a href="{{ route('daftar_hadir', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'nama_lengkap', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Nama Peserta</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'institusi'; @endphp
                                <a href="{{ route('daftar_hadir', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'institusi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Institusi / Perusahaan</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'alamat_rumah'; @endphp
                                <a href="{{ route('daftar_hadir', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'alamat_rumah', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Alamat</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'pekerjaan'; @endphp
                                <a href="{{ route('daftar_hadir', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'pekerjaan', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Pekerjaan</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'nomor_hp'; @endphp
                                <a href="{{ route('daftar_hadir', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'nomor_hp', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>No. HP</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>                            

                            <th class="px-6 py-3 font-semibold text-center">Status Kehadiran</th>

                            <th class="px-6 py-3 font-semibold text-center">Tanda Tangan</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($pendaftar as $index => $data)
                        
                        <tr class="hover:bg-blue-50 transition divide-x divide-gray-200 cursor-pointer group">
                            
                            <td class="px-4 py-4 text-center font-medium text-gray-500">
                                {{ $data->id_data_sertifikasi_asesi }}
                            </td>
                            
                            <td class="px-6 py-4 font-medium text-gray-900 group-hover:text-blue-700 transition-colors">
                                {{ $data->asesi->nama_lengkap }}
                            </td>
                            
                            <td class="px-6 py-4">
                                {{ $data->asesi->pekerjaan->nama_institusi_pekerjaan ?? '-' }}
                            </td>
                            
                            <td class="px-6 py-4 truncate max-w-xs" title="{{ $data->asesi->alamat_rumah }}">
                                {{ Str::limit($data->asesi->alamat_rumah, 30) }}
                            </td>
                            
                            <td class="px-6 py-4">
                                {{ $data->asesi->pekerjaan }}
                            </td>
                            
                            <td class="px-6 py-4">
                                {{ $data->asesi->nomor_hp }}
                            </td>         
                            
                            <td class="px-6 py-4">
                                <input type="checkbox" class="kehadiran h-5 w-5 rounded text-gray-500 mx-auto block" data-id="{{ $data->id_data_sertifikasi_asesi }}" data-ttd="{{ $data->asesi->tanda_tangan }}">
                            </td>                              
                            
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center">
                                    <div id="ttd-{{ $data->id_data_sertifikasi_asesi }}" 
                                        class="h-32 w-32 rounded-md overflow-hidden border border-gray-200 bg-white relative group-img hidden">

                                        <img src="{{ asset($data->asesi->tanda_tangan) }}"
                                            class="w-full h-full object-contain p-1 hover:scale-110 transition-transform duration-200 cursor-pointer"
                                            alt="TTD"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
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
                <div class="form-footer flex justify-end mt-10">
                    <button type="button" id="btnOpenConfirm"
                            class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">
                        Simpan Kehadiran
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="modalPresensi"
        x-data="{ open: false, peserta: [] }"
        x-cloak
        x-on:open-modal.window="
            console.log('Event diterima:', $event.detail);
            peserta = $event.detail.peserta;
            $nextTick(() => open = true)
        ">

        <!-- BACKDROP -->
        <div x-show="open"
            class="fixed inset-0 bg-black bg-opacity-50 z-40"
            x-transition>
        </div>

        <!-- MODAL -->
        <div x-show="open"
            class="fixed inset-0 flex items-center justify-center z-50"
            x-transition>
            <div class="bg-white p-6 rounded-lg w-full max-w-xl shadow-lg">
                <h2 class="text-xl font-bold mb-4">Konfirmasi Penyimpanan Presensi</h2>
                <p class="text-gray-600 mb-4">
                    Apakah Anda yakin ingin menyimpan presensi? <br>
                    <strong>Presensi tidak dapat diedit setelah Anda simpan.</strong>
                </p>

                <div class="border rounded-md max-h-60 overflow-y-auto mb-4">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-left">Nama Peserta</th>
                                <th class="px-3 py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="p in peserta" :key="p.id_data_sertifikasi_asesi">
                                <tr>
                                    <td class="px-3 py-2" x-text="p.nama"></td>
                                    <td class="px-3 py-2 text-center">
                                        <span x-show="p.hadir == 1" class="text-green-600 font-semibold">Hadir</span>
                                        <span x-show="p.hadir == 0" class="text-red-600 font-semibold">Tidak Hadir</span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <form id="formPresensi" method="POST" action="{{ route('simpan_kehadiran', $jadwal->id_jadwal) }}" x-on:submit.prevent="$el.querySelector('button[type=submit]').setAttribute('disabled', true); $el.submit()">
                    @csrf
                    <input type="hidden" name="data_presensi" id="data_presensi">
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="open = false"
                                class="px-4 py-2 bg-gray-300 rounded-md">Batal</button>

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md">
                            Simpan Presensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.kehadiran').forEach(chk => {
            chk.addEventListener('change', function () {
                let id = this.dataset.id;
                let ttdPath = this.dataset.ttd;
                let ttdBox = document.getElementById('ttd-' + id);

                if (this.checked && ttdPath) {
                    ttdBox.classList.remove('hidden');
                } else {
                    ttdBox.classList.add('hidden');
                }
            });
        });
    });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById("btnOpenConfirm");
        if (!btn) return;

        btn.addEventListener("click", function () {
            const peserta = [];

            document.querySelectorAll(".kehadiran").forEach(chk => {
                peserta.push({
                    id_data_sertifikasi_asesi: chk.dataset.id,
                    nama: chk.closest("tr").querySelector("td:nth-child(2)").innerText.trim(),
                    hadir: chk.checked ? 1 : 0
                });
            });

            // isi hidden input untuk dikirim saat submit
            const hidden = document.getElementById("data_presensi");
            if (hidden) hidden.value = JSON.stringify(peserta);

            console.log("Peserta dikirim:", peserta);
            // Kirim CustomEvent yang ditangkap Alpine
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { peserta } }));
        });
    });
    </script>




@endsection