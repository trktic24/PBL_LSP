@extends('layouts.app-sidebar-skema')
@section('content')

<main class="ml-0 lg:ml-50 flex-1 flex flex-col min-h-screen">
    <div class="flex-1 p-2">
        
        <div class="bg-yellow-50 p-6 rounded-lg shadow-md mb-8">
            <div class="grid grid-cols-[150px,1fr] gap-x-6 gap-y-2 text-sm">
                <div class="font-semibold text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</div>
                <div></div>
                <div class="font-semibold text-gray-700">Judul</div>
                <div>:   {{ $jadwal->skema->nama_skema ?? 'Nama Skema' }}</div>
                <div class="font-semibold text-gray-700">Nomor</div>
                <div>:   {{ $jadwal->skema->nomor_skema ?? 'Nomor Skema' }}</div>
                <div class="font-semibold text-gray-700">Tanggal</div>
                <div>:   {{ $jadwal->tanggal_pelaksanaan ?? 'Tanggal' }}</div>
                <div class="font-semibold text-gray-700">Waktu</div>
                <div>:   {{ $jadwal->waktu_mulai ?? 'Waktu' }}</div>
                <div class="font-semibold text-gray-700">TUK</div>
                <div>:   {{ $jadwal->tuk->nama_lokasi ?? 'Nama Skema' }}</div>
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
                            <th class="p-4 w-[12%] text-center text-sm font-semibold">Semua</th>
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
                                    <td class="p-4 text-center">
                                        {{-- Menggunakan tag <a> agar bisa diklik --}}
                                        <a href="{{ route('tracker', $item->id_data_sertifikasi_asesi) }}" class="text-yellow-600 font-medium hover:text-yellow-800 hover:underline cursor-pointer">
                                            Dalam Proses
                                        </a>
                                    </td>

                                    {{-- Asesmen --}}
                                    <td class="p-4 text-center">
                                        <a href="{{ route('tracker', $item->id_data_sertifikasi_asesi) }}" class="text-yellow-600 font-medium hover:text-yellow-800 hover:underline cursor-pointer">
                                            Dalam Proses
                                        </a>
                                    </td>

                                    {{-- Semua --}}
                                    <td class="p-4 text-center">
                                        <a href="{{ route('tracker', $item->id_data_sertifikasi_asesi) }}" class="text-yellow-600 font-medium hover:text-yellow-800 hover:underline cursor-pointer">
                                            Dalam Proses
                                        </a>
                                    </td>
                                    
                                    {{-- Checkbox Asesmen Mandiri (Statis Checked) --}}
                                    <td class="p-4 text-center">
                                        <input type="checkbox" class="h-5 w-5 rounded text-yellow-600 mx-auto block" checked>
                                    </td>
                                    
                                    {{-- Tombol Penyesuaian --}}
                                    <td class="p-4 text-center">
                                        <button class="bg-yellow-500 text-white px-2 py-1 rounded-md text-xs font-medium hover:bg-yellow-700 whitespace-nowrap">
                                            Lakukan Penyesuaian
                                        </button>
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
                    <button class="bg-yellow-600 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-yellow-700 flex-grow">
                        Daftar Hadir
                    </button>
                    <button class="bg-yellow-600 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-yellow-700 flex-grow">
                        Laporan Asesmen
                    </button>
                    <button class="bg-yellow-600 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-yellow-700 flex-grow">
                        Tinjauan Asesmen
                    </button>
                    <button class="bg-yellow-600 text-white px-5 py-5 rounded-md text-xs font-medium hover:bg-yellow-700 flex-grow">
                        Berita Acara
                    </button>
                </div>
            </div> 
        </div> 
    </div>
</main>

@endsection