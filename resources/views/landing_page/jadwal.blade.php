{{-- 1. Mewarisi layout utama (yang sudah punya header, footer, & CSS) --}}
@extends('layouts.app-profil')

{{-- 2. Mengatur judul halaman (menggantikan tag <title>) --}}
@section('title', 'Jadwal Asesmen')

{{-- 3. Memasukkan semua konten Anda ke 'slot' konten di layout --}}
@section('content')

    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Asesmen</h1>
            <p class="text-gray-600">Lorem ipsum dolor<br>sit amet</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Div pembungkus tabel dengan latar belakang kuning --}}
        <div class="bg-yellow-50 rounded-lg shadow-md overflow-x-auto border border-gray-200">

            {{-- Header Tabel --}}
            <div class="grid grid-cols-6 bg-yellow-50 border-b-2 border-gray-900">
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Skema Sertifikasi</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Pendaftaran</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Tanggal Asesmen</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">TUK</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Kuota</div>
                <div class="px-6 py-4 text-sm font-bold text-gray-900 text-center">Status</div>
            </div>

            {{-- Menggunakan @forelse untuk menangani jika data kosong --}}
            @forelse($jadwalList as $jadwal)
                {{-- Baris tabel dengan latar kuning dan hover --}}
                <div class="grid grid-cols-6 border-b border-gray-200 bg-yellow-50 hover:bg-yellow-100 transition items-center">

                    {{-- Kolom 1: Skema Sertifikasi --}}
                    <div class="px-6 py-4 text-sm text-gray-900 text-left font-medium">
                        {{-- FIX: Menggunakan object access (->) dan relasi 'skema' --}}
                        {{ $jadwal->skema->nama_skema ?? 'Skema Tidak Ditemukan' }}
                    </div>

                    {{-- Kolom 2: Pendaftaran --}}
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">
                        {{-- FIX: Menggunakan object (->) & nullsafe operator (?->) --}}
                        {{ $jadwal->pendaftaran_mulai?->format('d M') ?? 'N/A' }} -
                        {{ $jadwal->pendaftaran_selesai?->format('d M Y') ?? 'N/A' }}
                    </div>

                    {{-- Kolom 3: Tanggal Asesmen --}}
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">
                        {{-- FIX: Menggunakan object (->) & nullsafe operator (?->) --}}
                        {{-- Asumsi 'tanggal_asesmen' adalah 'tanggal_mulai' dari database --}}
                        {{ $jadwal->tanggal_mulai?->format('d M Y') ?? 'Belum Ditentukan' }}
                    </div>

                    {{-- Kolom 4: TUK --}}
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">
                        {{-- FIX: Menggunakan object access (->) dan relasi 'tuk' --}}
                        {{ $jadwal->tuk->nama_tuk ?? 'TUK Tidak Ditemukan' }}
                    </div>

                    {{-- Kolom 5: Kuota --}}
                    <div class="px-6 py-4 text-sm text-gray-700 text-center">
                        {{-- FIX: Menggunakan object (->) dan fallback (??) --}}
                        {{ $jadwal->terisi ?? 0 }}/{{ $jadwal->kuota ?? 'N/A' }}
                    </div>

                    {{-- Kolom 6: Status --}}
                    <div class="px-6 py-4 text-center">

                        {{-- FIX: Logika status direplikasi menggunakan data Eloquent --}}
                        @php
                            $now = now();
                            $isBuka = $jadwal->pendaftaran_mulai && $jadwal->pendaftaran_selesai &&
                                      $now->between($jadwal->pendaftaran_mulai, $jadwal->pendaftaran_selesai);
                            $isPenuh = ($jadwal->terisi ?? 0) >= ($jadwal->kuota ?? 999);
                            $dapatDaftar = false;

                            if ($isBuka && !$isPenuh) {
                                $status = 'Buka';
                                $statusColor = 'text-green-700';
                                $statusBg = 'bg-green-100';
                                $dapatDaftar = true;
                            } elseif ($isBuka && $isPenuh) {
                                $status = 'Penuh';
                                $statusColor = 'text-yellow-700';
                                $statusBg = 'bg-yellow-100';
                            } else {
                                $status = 'Tutup';
                                $statusColor = 'text-red-700';
                                $statusBg = 'bg-red-100';
                            }
                        @endphp

                        @if($dapatDaftar)
                            {{-- FIX: Menggunakan route 'jadwal.detail' (dari web.php) dan 'id_jadwal' --}}
                            <a href="{{ route('jadwal.detail', ['id' => $jadwal->id_jadwal]) }}"
                               class="inline-block px-4 py-2 rounded-full text-sm font-medium {{ $statusColor }} {{ $statusBg }} hover:opacity-80 transition cursor-pointer">
                                Daftar
                            </a>
                        @else
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-medium {{ $statusColor }} {{ $statusBg }} cursor-not-allowed">
                                {{ $status }}
                            </span>
                        @endif

                    </div>
                </div>
            @empty
                {{-- Tampilan jika tidak ada jadwal ditemukan --}}
                <div class="grid grid-cols-1">
                    <p class="text-gray-500 text-center py-10">
                        Tidak ada jadwal asesmen yang tersedia saat ini.
                    </p>
                </div>
            @endforelse
            {{-- Akhir @forelse --}}

        </div>

        {{-- Tautan Pagination --}}
        <div class="mt-8">
            {{ $jadwalList->links() }}
        </div>

    </div>
@endsection