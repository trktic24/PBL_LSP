@extends('layouts.app-sidebar-skema')

{{-- Menetapkan Judul Halaman --}}
@section('title', 'Daftar Hadir Peserta')
@section('content')

    <div class="p-0">
            <div class="flex flex-col xl:w-auto flex-1 justify-between mx-auto mb-8">
                <div class="w-full flex flex-col items-center">
                    
                    {{-- Logo di tengah dan lebih besar --}}
                    <img src="{{ asset('images/Logo_LSP_No_BG.png') }}"
                        alt="Logo LSP Polines"
                        class="h-20 md:h-24 w-auto mb-2"> 

                    {{-- Judul di bawah logo dan rata tengah --}}
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6 mt-2">
                        Berita Acara Asesmen
                    </h2>

                </div>


                <div class="w-full xl:w-auto flex-1 flex mx-auto">
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm w-full mx-auto">
                        <div class="flex flex-col gap-3">
                            <div class="w-full flex flex-col items-center text-sm text-gray-600">
                                <p>Pada hari ini, Hari/Tanggal: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }} , Waktu: Pukul {{ \Carbon\Carbon::parse($jadwal->waktu_mulai ?? '10:20:00')->format('H:i') }} s/d Selesai, bertempat di TUK {{ $jadwal->masterTuk->nama_lokasi }}, 
                                    telah dilaksanakan proses asesmen terhadap asesi pada sektor / sub sektor / bidang profesi {{ $jadwal->skema->nama_skema }} yang diikuti oleh <strong>{{ $pendaftar->total() }} orang peserta</strong>. Dari hasil asesmen, peserta yang dinyatakan <strong>kompeten</strong>
                                    adalah <strong>{{ $jumlahKompeten > 0 ? $jumlahKompeten . ' orang peserta' : 'tidak ada' }}</strong> dan yang <strong>belum kompeten</strong> adalah <strong>{{ $jumlahBelumKompeten > 0 ? $jumlahBelumKompeten . ' orang peserta' : 'tidak ada' }}</strong> dengan perincian sebagai berikut:
                                </p>
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
                                <a href="{{ route('asesor.berita_acara', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'id_data_sertifikasi_asesi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-center gap-1">
                                    <span>ID</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'nama_lengkap'; @endphp
                                <a href="{{ route('asesor.berita_acara', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'nama_lengkap', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Nama Peserta</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>
                            
                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'hasil_asesmen'; @endphp
                                <a href="{{ route('asesor.berita_acara', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'institusi', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Hasil Asesmen</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">
                                @php $isCurrent = $sortColumn == 'rekomendasi'; @endphp
                                <a href="{{ route('asesor.berita_acara', array_merge(['id_jadwal' => $jadwal->id_jadwal], $baseParams, ['sort' => 'alamat_rumah', 'direction' => ($isCurrent && $sortDirection == 'asc') ? 'desc' : 'asc'])) }}" class="flex w-full items-center justify-between">
                                    <span>Rekomendasi/Tindak Lanjut</span>
                                    <div class="flex flex-col -space-y-1 text-[10px]"><i class="fas fa-caret-up {{ ($isCurrent && $sortDirection == 'asc') ? 'text-gray-900' : 'text-gray-300' }}"></i><i class="fas fa-caret-down {{ ($isCurrent && $sortDirection == 'desc') ? 'text-gray-900' : 'text-gray-300' }}"></i></div>
                                </a>
                            </th>

                            <th class="px-6 py-3 font-semibold">Keterangan</th>
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
                                @if ($data->komentarAk05 === null)
                                    -
                                @else
                                    {{ $data->komentarAk05->rekomendasi === 'K' ? 'Kompeten' : 'Belum Kompeten' }}
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                @if ($data->komentarAk05 === null)
                                    -
                                @else
                                    {{ $data->komentarAk05->rekomendasi === 'K' ? 'Terbitkan Sertifikat' : 'Mengulang Asesmen' }}
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                @if ($data->komentarAk05 === null)
                                    -
                                @else
                                    {{ $data->komentarAk05->keterangan }}
                                @endif
                            </td>     
                            
                            @empty
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
            </div>

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

            <div class="w-full xl:w-auto flex-1 flex mx-auto mt-10">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm w-full">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-wrap gap-x-8 gap-y-2 text-sm text-gray-600">
                            <p>Demikian berita acara ini dibuat dengan sebenarnya, untuk digunakan sebagaimana mestinya.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 border border-gray-200 rounded-md shadow-sm">
                    {{-- BAGIAN ASESOR --}}
                        <div class="space-y-3">
                            <h3 class="font-semibold text-gray-700 mb-3">Semarang, {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</h3>
                            <h4 class="font-medium text-gray-800">Asesor</h4>
                            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-start">
                                <!-- Baris Nama -->
                                <span class="font-medium text-gray-700">Nama</span>
                                <span class="font-medium">:</span>
                                <span class="font-medium text-gray-700">{{ $asesor->nama_lengkap }}</span>
                                <span class="font-medium text-gray-700">Tanda Tangan</span>
                                <span class="font-medium">:</span>
                                @php
                                    $ttdAsesorBase64 = getTtdBase64($asesor->tanda_tangan ?? null, $asesor->id_user ?? $asesor->user_id ?? null, 'asesor');
                                @endphp
                                @if($ttdAsesorBase64)
                                <img src="data:image/png;base64,{{ $ttdAsesorBase64 }}" 
                                     alt="Tanda Tangan Asesor" 
                                     class="h-20 w-auto object-contain p-1 hover:scale-110 transition cursor-pointer">
                                @else
                                <span class="text-gray-400 text-xs">Belum ada TTD</span>
                                @endif
                            </div>
                        </div>

                    {{-- BAGIAN PJ KEGIATAN --}}
                        <div class="space-y-3 md:mt-10">
                            <h4 class="font-medium text-gray-800">Penanggungjawab Kegiatan</h4>
                            <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-start">
                                <!-- Baris Nama -->
                                <span class="font-medium text-gray-700">Nama</span>
                                <span class="font-medium">:</span>
                                <span class="font-medium text-gray-700">Ajeng Febria H.</span>
                                <span class="font-medium text-gray-700">Tanda Tangan</span>
                                <span class="font-medium">:</span>
                                @if($ttdAsesorBase64)
                                <img src="data:image/png;base64,{{ $ttdAsesorBase64 }}" 
                                    class="w-20 h-auto object-contain p-1 hover:scale-110 transition cursor-pointer">
                                @else
                                <span class="text-gray-400 text-xs">Belum ada TTD</span>
                                @endif
                            </div>
                        </div>
                    
                </div>
            </div>
        </main>
    </div>

@endsection