@extends('layouts.app-sidebar-skema')
@section('content')

    <main class="ml-0 lg:ml-50 flex-1 flex flex-col min-h-screen">
        <div class="flex-1 p-2">

            <div class="bg-yellow-50 p-6 rounded-lg shadow-md mb-8">
                <div class="grid grid-cols-[150px,1fr] gap-x-6 gap-y-2 text-sm">
                    <div class="font-semibold text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</div>
                    <div></div>
                    <div class="font-semibold text-gray-700">Judul</div>
                    <div>: {{ $jadwal->skema->nama_skema ?? 'Nama Skema' }}</div>
                    <div class="font-semibold text-gray-700">Nomor</div>
                    <div>: {{ $jadwal->skema->nomor_skema ?? 'Nomor Skema' }}</div>
                    <div class="font-semibold text-gray-700">Tanggal</div>
                    <div>: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</div>
                    <div class="font-semibold text-gray-700">Waktu</div>
                    <div>: {{ \Carbon\Carbon::parse($jadwal->waktu_mulai ?? '10:20:00')->format('H:i') }}</div>
                    <div class="font-semibold text-gray-700">TUK</div>
                    <div>: {{ $jadwal->masterTuk->nama_lokasi ?? 'Nama Skema' }}</div>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Asesi</h2>

            <div>

                <div class="flex flex-col gap-4">

                    <div class="bg-yellow-50 rounded-lg shadow-xl overflow-x-auto w-full">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed">

                            <thead class="bg-yellow-100 border-b-2 border-gray-500 text-gray-800">
                                <th class="p-4 w-1/12 text-left text-sm font-semibold">No</th>
                                <th class="p-4 w-2/12 text-left text-sm font-semibold">Nama Asesi</th>
                                <th class="p-4 w-[12%] text-center text-sm font-semibold">Pra Asesmen</th>
                                <th class="p-4 w-[12%] text-center text-sm font-semibolde">Asesmen</th>
                                <th class="p-4 w-[12%] text-center text-sm font-semibold">Asesmen Mandiri</th>
                                <th class="p-4 w-[14%] text-center text-sm font-semibold">Penyesuaian</th>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                {{-- MODIFIKASI: Menggunakan data dari relasi jadwal --}}
                                @forelse($jadwal->dataSertifikasiAsesi as $index => $item)
                                    <tr class="hover:bg-yellow-100">
                                        <td class="p-4 text-left text-sm text-gray-700">{{ $index + 1 }}</td>

                                        {{-- Nama Asesi diambil dari relasi ->asesi --}}
                                        <td class="p-4 text-left text-sm text-gray-900 font-medium truncate">
                                            {{ $item->asesi->nama_lengkap ?? $item->asesi->nama ?? 'Nama Tidak Ditemukan' }}
                                        </td>

                                        {{-- Pra Asesmen --}}
                                        @php
                                            if (!$item->responApl2Ia01 && !$item->responBuktiAk01) {
                                                $status = 'Belum Direview';
                                                $color = 'text-red-600 hover:text-blue-800';
                                            } elseif ($item->responApl2Ia01 && !$item->responBuktiAk01) {
                                                $status = 'Dalam Proses';
                                                $color = 'text-yellow-600 hover:text-yellow-800';
                                            } else {
                                                $status = 'Sudah Direview';
                                                $color = 'text-green-600 hover:text-green-800';
                                            }
                                        @endphp

                                        <td class="p-4 text-center">
                                            <a href="{{ route('asesor.tracker', $item->id_data_sertifikasi_asesi) }}"
                                                class="font-medium hover:underline cursor-pointer {{ $color }}">
                                                {{ $status }}
                                            </a>
                                        </td>

                                        {{-- Asesmen --}}
                                        @php
                                            if (!$item->lembarJawabIa05 && !$item->komentarAk05) {
                                                $status = 'Belum Direview';
                                                $color = 'text-blue-600 hover:text-blue-800';
                                            } elseif ($item->lembarJawabIa05 && !$item->komentarAk05) {
                                                $status = 'Dalam Proses';
                                                $color = 'text-yellow-600 hover:text-yellow-800';
                                            } else {
                                                $status = 'Sudah Direview';
                                                $color = 'text-green-600 hover:text-green-800';
                                            }
                                        @endphp

                                        <td class="p-4 text-center">
                                            <a href="{{ route('asesor.tracker', $item->id_data_sertifikasi_asesi) }}#asesmen"
                                                class="font-medium hover:underline cursor-pointer {{ $color }}">
                                                {{ $status }}
                                            </a>
                                        </td>

                                        {{-- Checkbox Asesmen Mandiri --}}
                                        <td class="p-4 text-center">
                                            @if ($item->rekomendasi_apl02 == 'diterima')
                                                <span class="text-green-500 px-2 py-1 rounded-md text-s">Diterima</span>
                                            @elseif ($item->rekomendasi_apl02 == 'tidak diterima')
                                                <span class="text-reed-500 px-2 py-1 rounded-md text-s">Diterima</span>
                                            @else
                                                <a href="{{ route('asesor.apl02', $item->id_data_sertifikasi_asesi) }}#btn-verifikasi"
                                                    class="text-yellow-500 px-2 py-1 rounded-md text-s">Verifikasi</a>
                                            @endif
                                        </td>

                                        {{-- Tombol Penyesuaian --}}
                                        <td class="p-4 text-center">
                                            <a href="{{ route('asesor.ak07', $item->id_data_sertifikasi_asesi) }}"
                                                class="bg-yellow-500 text-white px-2 py-1 rounded-md text-xs font-medium hover:bg-yellow-700 whitespace-nowrap">
                                                Lakukan Penyesuaian
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-4 text-center text-gray-500 italic">
                                            Belum ada asesi yang terdaftar di jadwal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex gap-4 justify-between">

                        <!-- Daftar Hadir -->
                        <div class="relative flex-1">
                            <button type="button" onclick="toggleDropdown('daftar-hadir-dropdown')"
                                class="w-full bg-yellow-600 text-white px-5 py-5 rounded-md shadow hover:bg-yellow-700 flex items-center justify-center relative">

                                <!-- Teks di tengah -->
                                <span class="absolute left-1/2 transform -translate-x-1/2">Daftar Hadir</span>

                                <!-- Ikon di kanan -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="daftar-hadir-dropdown"
                                class="hidden absolute right-0 top-full mt-2 w-full bg-white border border-gray-200 rounded-md shadow-xl z-50 overflow-hidden">
                                <a href="{{ route('asesor.daftar_hadir', $jadwal->id_jadwal) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    Verifikasi
                                </a>
                                <a href="{{ route('asesor.daftar_hadir.pdf', $jadwal->id_jadwal) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    Unduh PDF
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('asesor.ak05', $jadwal->id_jadwal) }}"
                            class="bg-yellow-600 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-yellow-700 flex-grow text-center flex items-center justify-center">
                            Laporan Asesmen
                        </a>
                        <a href="{{ route('asesor.ak06', $jadwal->id_jadwal) }}"
                            class="bg-yellow-600 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-yellow-700 flex-grow text-center flex items-center justify-center">
                            Tinjauan Asesmen
                        </a>

                        <!-- Berita Acara -->
                        <div class="relative flex-1">
                            <button type="button"
                                onclick="{{ $sudahVerifikasiValidator ? "toggleDropdown('berita-acara-dropdown')" : "showWarning()" }}"
                                class="w-full px-5 py-5 rounded-md shadow flex items-center justify-center relative
                                {{ $sudahVerifikasiValidator ? 'bg-yellow-600 text-white hover:bg-yellow-700' : 'bg-gray-300 text-gray-600' }}">

                                <span class="absolute left-1/2 transform -translate-x-1/2">
                                    Berita Acara
                                </span>

                                @if($sudahVerifikasiValidator)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </button>

                            @if($sudahVerifikasiValidator)
                                <div id="berita-acara-dropdown"
                                    class="hidden absolute right-0 top-full mt-2 w-full bg-white border border-gray-200 rounded-md shadow-xl z-50 overflow-hidden">

                                    <a href="{{ route('asesor.berita_acara', $jadwal->id_jadwal) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        Verifikasi
                                    </a>

                                    <a href="{{ route('asesor.berita_acara.pdf', $jadwal->id_jadwal) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        Unduh PDF
                                    </a>
                                </div>
                            @endif
                        </div>


                    </div>

                </div>
            </div>
        </div>

        <script>
            function toggleDropdown(id) {
                const dropdown = document.getElementById(id);
                dropdown.classList.toggle('hidden'); // toggle muncul / sembunyi

                // tutup dropdown lain yang mungkin terbuka
                document.querySelectorAll('.relative div[id]').forEach(d => {
                    if (d.id !== id) d.classList.add('hidden');
                });
            }
        </script>

        <script>
            function showWarning() {
                Swal.fire({
                    icon: 'error',
                    title: 'Berita Acara belum diterbitkan',
                    text: 'Asesmen masih dalam proses'
                });
            }
        </script>

    </main>

@endsection