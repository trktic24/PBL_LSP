@extends('layouts.app-profil')
@section('content')

<div class="container mx-auto px-6 mt-20 mb-12">
        <div class="flex items-center space-x-5 mb-10">
            <img src="{{ Auth::user()->asesor?->url_foto ?? asset('images/profil_asesor.jpeg') }}"
                alt="Foto Profil"
                class="w-20 h-20 rounded-full object-cover border-4 border-blue-500">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Selamat Datang {{ $profile['nama'] }}!</h1>
                <p class="text-xl font-semibold text-gray-800 mt-1">{{ $profile['nama'] }}</p>
                <p class="text-base text-gray-600">{{ $profile['nomor_registrasi'] }}</p>
                <p class="text-base text-gray-600">{{ $profile['kompetensi'] }}</p>
            </div>
        </div>

        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">5</span>
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
                        <span class="text-5xl font-bold">7</span>
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
                        <span class="text-5xl font-bold">4</span>
                        <span class="text-base font-medium mt-1">Asesmen<br>Telah Direview</span>
                    </div>
                    <div class="text-6xl opacity-70">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l-3 3-1.5-1.5" />
                        </svg>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-5xl font-bold">18</span>
                        <span class="text-base font-medium mt-1">Jumlah<br>Asesi</span>
                    </div>
                    <div class="text-6xl opacity-70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Jadwal Anda</h2>
            </div>

    <div class="bg-amber-50 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-amber-200 text-gray-800">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Skema</th>
                        <th class="py-3 px-4 text-center">Waktu Mulai</th>
                        <th class="py-3 px-4 text-center">Tanggal</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    {{-- Loop data dari controller --}}
                    @forelse ($jadwals as $jadwal)
                        <tr class="border-b hover:bg-amber-100">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $jadwal->skema_nama ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->waktu_mulai ? $jadwal->waktu_mulai->format('H:i') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->translatedFormat('d F Y') : 'N/A' }}</td>
                            <td class="py-3 px-4 text-center">{{ $jadwal->status_jadwal ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-center space-x-2">
                                @if ($jadwal->id_jadwal)
                                    <a href="{{ route('daftar_asesi', $jadwal->id_jadwal) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-md text-sm font-medium">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        {{-- Tampil jika $jadwals kosong --}}
                        <tr>
                            <td colspan="9" class="py-4 px-4 text-center text-gray-500">
                                Belum ada jadwal asesmen yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

            <div class="text-right mt-4">
                <a href="#" class="text-sm text-blue-600 hover:underline font-medium">Lihat Selengkapnya</a>
            </div>
        </div>
 </div>

@endsection