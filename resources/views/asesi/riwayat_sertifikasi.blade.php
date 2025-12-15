@extends('layouts.app-profil') 
{{-- Menggunakan layout yang sama dengan Profile (tanpa sidebar) atau sesuaikan layout Anda --}}

@section('content')
<div class="min-h-screen pt-24 pb-20 px-6 bg-gray-50 font-[Poppins]">
    
    <div class="max-w-6xl mx-auto">
        
        {{-- Header Judul --}}
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Riwayat & Status Sertifikasi</h1>

        @if($sertifikasiList->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <img src="{{ asset('images/empty.svg') }}" onerror="this.style.display='none'" class="h-48 mx-auto mb-6" alt="No Data">
                <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Sertifikasi</h3>
                <p class="text-gray-500 mb-6">Anda belum mendaftar di skema sertifikasi apapun.</p>
                
                <a href="{{ url('/jadwal') }}" class="inline-block bg-blue-600 text-white font-semibold py-3 px-8 rounded-full hover:bg-blue-700 transition">
                    Cari Jadwal Sertifikasi
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sertifikasiList as $sertifikasi)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                        
                        <a href="{{ route('asesi.tracker', ['jadwal_id' => $sertifikasi->id_jadwal]) }}" class="block h-full p-6">
                            
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start mb-4">
                                <div class="bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                    {{ $sertifikasi->jadwal->skema->nomor_skema ?? 'SKEMA' }}
                                </div>
                                <span class="text-gray-400 text-xs">
                                    {{ $sertifikasi->created_at->format('d M Y') }}
                                </span>
                            </div>

                            {{-- Judul Skema --}}
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                {{ $sertifikasi->jadwal->skema->nama_skema ?? 'Nama Skema Tidak Diketahui' }}
                            </h3>

                            {{-- Info TUK --}}
                            <div class="flex items-center text-gray-500 text-sm mb-6">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                <span class="truncate">
                                    {{ $sertifikasi->jadwal->masterTuk->nama_lokasi ?? 'TUK Belum Ditentukan' }}
                                </span>
                            </div>

                            {{-- Progress / Status Bar Kecil --}}
                            <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                                @php
                                    // Hitung progress kasar (Contoh Logic)
                                    $progress = 10;
                                    if($sertifikasi->rekomendasi_apl01 == 'diterima') $progress += 20;
                                    if($sertifikasi->rekomendasi_apl02 == 'diterima') $progress += 30;
                                    if($sertifikasi->status_rekomendasi == 'kompeten') $progress = 100;
                                @endphp
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 font-medium">
                                <span>Proses</span>
                                <span>{{ $progress }}%</span>
                            </div>

                        </a>

                        {{-- Decoration Bubble --}}
                        <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-blue-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>
@endsection
