@extends('layouts.app-profil')
@section('content')

<div class="container mx-auto px-6 mt-20 mb-12">
        <div class="flex items-center space-x-5 mb-10">
            
            {{-- DIUBAH: Menggunakan $profile['foto_url'] dari controller --}}
            <img src="{{ $profile['foto_url'] }}"
                alt="Foto Profil"
                class="w-20 h-20 rounded-full object-cover border-4 border-blue-500">
            <div>
            
                {{-- DIUBAH: Menggunakan $profile['nama'] dari controller --}}
                <h1 class="text-3xl font-bold text-gray-900">Selamat Datang {{ explode(' ', $profile['nama'])[0] }}!</h1>
                
                {{-- DIUBAH: Menggunakan $profile['nama'] dari controller --}}
                <p class="text-xl font-semibold text-gray-800 mt-1">{{ $profile['nama'] }}</p>
                
                {{-- DIUBAH: Menggunakan $profile['nomor_registrasi'] dari controller --}}
                <p class="text-base text-gray-600">{{ $profile['nomor_registrasi'] }}</p>
                
                {{-- DIUBAH: Menggunakan $profile['kompetensi'] dari controller --}}
                <p class="text-base text-gray-600">{{ $profile['kompetensi'] }}</p>
            </div>
        </div>

        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        {{-- DIUBAH: Menggunakan $summary dari controller --}}
                        <span class="text-5xl font-bold">{{ $summary['belum_direview'] }}</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Belum Direview</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        {{-- DIUBAH: Menggunakan $summary dari controller --}}
                        <span class="text-5xl font-bold">{{ $summary['dalam_proses'] }}</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Dalam Proses</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        {{-- DIUBAH: Menggunakan $summary dari controller --}}
                        <span class="text-5xl font-bold">{{ $summary['telah_direview'] }}</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Telah Direview</span>
                    </div>
                    <div class="text-6xl opacity-70">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  _       <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l-3 3-1.5-1.5" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        {{-- DIUBAH: Menggunakan $summary dari controller --}}
                        <span class="text-5xl font-bold">{{ $summary['jumlah_asesi'] }}</span>
                        <span class="text-base font-medium mt-1">Jumlah<br>Asesi</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
l                       </svg>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Jadwal Anda</h2>
            </div>

            <div class="bg-amber-50 rounded-xl border border-gray-300 shadow-sm overflow-hidden">
                <div class="flex px-6 py-3 border-b-2 border-black">
                    <span class="w-1/12 text-sm font-semibold text-gray-700">No</span>
                    <span class="w-5/12 text-sm font-semibold text-gray-700">Skema Sertifikasi</span>
                    <span class="w-4/12 text-sm font-semibold text-gray-700">Tanggal</span>
                    <span class="w-2/12 text-sm font-semibold text-gray-700">Aksi</span>
                </div>

                <div class="divide-y divide-gray-200">
                    
                    {{-- DIUBAH: Menghapus 5 item statis dan menggantinya dengan loop dinamis --}}
                    @forelse ($jadwal as $item)
                        <div class="flex px-6 py-4 items-center">
                            <span class="w-1/12 text-sm text-gray-800">{{ $loop->iteration }}</span>
                            <span class="w-5/12 text-sm text-gray-800 font-medium">{{ $item->skema_nama }}</span>
                            <span class="w-4/12 text-sm text-gray-800">{{ $item->tanggal }}</span>
                            <span class="w-2/12">
                                {{-- Kita cek jika ID-nya ada (bukan data dummy) --}}
                                @if ($item->id_jadwal)
                                <a href="{{ route('daftar_asesi') }}" class="bg-yellow-400 text-black text-xs font-bold py-1 px-3 rounded-md hover:bg-yellow-500">Lihat Detail</a>
                                @else
                                    <span classclass="bg-gray-300 text-gray-600 text-xs font-bold py-1 px-3 rounded-md">N/A</span>
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="flex px-6 py-4 items-center">
                            <span class="w-full text-center text-sm text-gray-500">Belum ada jadwal untuk Anda.</span>
                        </div>
                    @endforelse
                    
                </div>
            </div>

            <div class="text-right mt-4">
                <a href="#" class="text-sm text-blue-600 hover:underline font-medium">Lihat Selengkapnya</a>
            </div>
        </div>
 </div>

@endsection