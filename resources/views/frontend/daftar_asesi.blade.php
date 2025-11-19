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
                        
                        <thead class="bg-yellow-50 border-b-2 border-gray-500">
                            <th class="p-4 w-1/12 text-left text-sm font-semibold text-gray-600 uppercase">No</th>
                            <th class="p-4 w-2/12 text-left text-sm font-semibold text-gray-600 uppercase">Nama Asesi</th>
                            <th class="p-4 w-[12%] text-center text-sm font-semibold text-gray-600 uppercase">Pra Asesmen</th>
                            <th class="p-4 w-[12%] text-center text-sm font-semibold text-gray-600 uppercase">Asesmen</th>
                            <th class="p-4 w-[12%] text-center text-sm font-semibold text-gray-600 uppercase">Semua</th>
                            <th class="p-4 w-[12%] text-center text-sm font-semibold text-gray-600 uppercase">Asesmen Mandiri</th>
                            <th class="p-4 w-[14%] text-center text-sm font-semibold text-gray-600 uppercase">Penyesuaian</th>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            {{-- Gunakan LOOP untuk menampilkan data asesi --}}
                            @php
                                $asesis = [
                                    ['no' => 1, 'nama' => 'Tatang Sitartang', 'status' => 'Dalam Proses', 'check' => false],
                                    ['no' => 2, 'nama' => 'Jojon Sudarman', 'status' => 'Dalam Proses', 'check' => false],
                                    ['no' => 3, 'nama' => 'Abdul Sidarta M.', 'status' => 'Sudah Diverifikasi', 'check' => true],
                                    ['no' => 4, 'nama' => 'Mustika Pujastuti', 'status' => 'Belum Diverifikasi', 'check' => false],
                                ];
                            @endphp

                            @foreach($asesis as $asesi)
                                <tr class="hover:bg-yellow-100">
                                    <td class="p-4 text-left text-sm text-gray-700">{{ $asesi['no'] }}</td>
                                    <td class="p-4 text-left text-sm text-gray-900 font-medium truncate">{{ $asesi['nama'] }}</td>
                                    
                                    @php
                                        $color = match($asesi['status']) {
                                            'Sudah Diverifikasi' => 'text-green-600',
                                            'Belum Diverifikasi' => 'text-red-600',
                                            default => 'text-yellow-600',
                                        };
                                    @endphp

                                    <td class="p-4 text-left text-sm font-medium {{ $color }}">
                                        @if($asesi['no'] == 1)
                                            <a href="{{ route('tracker') }}" class="{{ $color }}">{{ $asesi['status'] }}</a>
                                        @else
                                            {{ $asesi['status'] }}
                                        @endif
                                    </td>
                                    <td class="p-4 text-left text-sm font-medium {{ $color }}">{{ $asesi['status'] }}</td>
                                    <td class="p-4 text-left text-sm font-medium {{ $color }}">{{ $asesi['status'] }}</td>
                                    
                                    <td class="p-4 text-center">
                                        <input type="checkbox" class="h-5 w-5 rounded text-yellow-600 mx-auto block" @if($asesi['check']) checked @endif>
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <button class="bg-yellow-500 text-white px-2 py-1 rounded-md text-xs font-medium hover:bg-yellow-700 whitespace-nowrap">
                                            Lakukan Penyesuaian
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            
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