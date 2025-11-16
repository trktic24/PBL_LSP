@extends('layouts.app-profil')
@section('content')

    {{-- HERO SECTION --}}
    <section class="container mx-auto px-8 mt-20">
        <div class="relative h-[500px] rounded-[2rem] overflow-hidden shadow-xl">
            {{-- Menggunakan $skema->gambar (Menggunakan isset untuk keamanan) --}}
            <img src="{{ asset('images/' . ($skema->gambar ?? 'placeholder_default.jpg')) }}"
                alt=""
                class="w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/90 via-blue-400/40 to-transparent"></div>

            <div class="absolute inset-0 flex flex-col justify-center px-12 text-white">
                <h1 class="text-6xl font-bold mb-4">{{ strtoupper($skema->nama_skema ?? $skema->nama ?? 'Skema Sertifikasi') }}</h1>
                <p class="text-lg max-w-md">{{ $skema->deskripsi ?? 'Deskripsi skema belum tersedia.' }}</p>
            </div>
        </div>
    </section>

    ---

    {{-- Jadwal Sertifikasi Terkait --}}
    <section class="container mx-auto px-8 py-10">
        <h2 class="text-3xl font-bold mb-6">Jadwal Pelaksanaan</h2>

        <div class="max-w-3xl mx-auto space-y-4">
            @forelse($skema->jadwals as $jadwal)
                <div class="bg-white rounded-2xl shadow-[6px_6px_20px_rgba(0,0,0,0.25)] p-8 border-2 border-blue-500">
                    
                    <h3 class="font-bold text-xl text-center mb-6">{{ strtoupper($skema->nama_skema ?? 'Jadwal Skema') }}</h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $jadwal->tanggal_pelaksanaan ? $jadwal->tanggal_pelaksanaan->format('d F Y') : 'Tanggal belum diatur' }}</span>
                        </div>

                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $jadwal->tuk?->nama_tuk ?? 'TUK belum diatur' }}</span>
                        </div>

                        <div class="flex items-center gap-3 text-base">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 10a7 7 0 1114 0A7 7 0 013 10zm7-3a1 1 0 100 2h2a1 1 0 100-2h-2z"
                                    clip-rule="evenodd"/>
                            </svg>
                            <span>Waktu: {{ $jadwal->tanggal_mulai ? $jadwal->tanggal_mulai->format('H:i') : 'N/A' }} - {{ $jadwal->tanggal_selesai ? $jadwal->tanggal_selesai->format('H:i') : 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        {{-- [PERUBAHAN SESUAI REVISI ANDA] --}}
                        {{-- Tombol ini sekarang menjadi link ke halaman detail jadwal --}}
                        <a href="{{ route('jadwal.detail', $jadwal->id_jadwal) }}"
                            class="w-auto px-8 bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 rounded-lg transition">
                            Daftar Jadwal Ini
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">Belum ada jadwal untuk skema ini.</p>
            @endforelse
        </div>
    </section>

    ---

    {{-- ========================================================== --}}
    {{-- [BAGIAN INI DIKEMBALIKAN] --}}
    {{-- ========================================================== --}}

    {{-- Unit Kompetensi (Pengecekan isset untuk keamanan) --}}
    <section class="container mx-auto px-8 py-10">
        <h2 class="text-3xl font-bold mb-6">Unit Kompetensi</h2>

        <div class="space-y-4">
            @if(isset($skema->unit_kompetensi) && count($skema->unit_kompetensi) > 0)
                @foreach($skema->unit_kompetensi as $unit)
                    <div class="bg-white rounded-2xl p-6 border-2 border-blue-500 shadow-xl">
                        <h3 class="text-lg font-bold text-blue-600 mb-3">Kode Unit : {{ $unit->kode ?? 'N/A' }}</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            {{-- Looping judul unit --}}
                            @if(isset($unit->judul))
                                @foreach($unit->judul as $judul)
                                    <li>{{ $judul }}</li>
                                @endforeach
                            @else
                                <li>Judul unit tidak tersedia.</li>
                            @endif
                        </ul>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">Data Unit Kompetensi tidak tersedia.</p>
            @endif
        </div>
    </section>

    ---

    {{-- SKKNI (Pengecekan isset untuk keamanan) --}}
    <section class="container mx-auto px-8 py-10">
        <h2 class="text-2xl font-bold mb-6">SKKNI (Standar Kompetensi Kerja Nasional Indonesia)</h2>

        <div class="space-y-4">
            @if(isset($skema->skkni) && count($skema->skkni) > 0)
                @foreach($skema->skkni as $skkni)
                    <div class="bg-blue-50 rounded-2xl shadow-md p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                        clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $skkni->nama ?? 'Dokumen SKKNI' }}</span>
                        </div>
                        <a href="{{ $skkni->link_pdf ?? '#' }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Lihat PDF</a>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">Data SKKNI tidak tersedia.</p>
            @endif
        </div>
    </section>

@endsection

{{-- 
    [PERUBAHAN SESUAI REVISI ANDA]
    Bagian @push('scripts') dihapus dari sini karena
    fungsinya sudah dipindahkan ke 'detail_jadwal.blade.php' 
--}}