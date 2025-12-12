@extends('layouts.app-profil') 
@section('title', 'Detail Jadwal - ' . ($jadwal->skema->nama_skema ?? 'Sertifikasi'))
@section('description', 'Informasi jadwal sertifikasi ' . ($jadwal->skema->nama_skema ?? '') . ' di ' . ($jadwal->masterTuk->nama_lokasi ?? 'LSP Polines'))

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mx-auto px-4 py-8 pt-24 max-w-6xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        
        {{-- Judul Skema --}}
        <h1 class="text-3xl font-bold mb-4 font-poppins">{{ $jadwal->skema?->nama_skema ?? 'Nama Skema Tidak Ditemukan' }}</h1>
        
        <div class="mb-6">
            <div class="flex flex-wrap gap-6 text-gray-900 text-sm">
                
                {{-- Tanggal Pelaksanaan --}}
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar"></i>
                    <span>
                        {{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d F Y') : 'Tanggal Belum Diatur' }}
                    </span>
                </div>

                {{-- Waktu --}}
                <div class="flex items-center gap-2">
                    <i class="far fa-clock"></i>
                    <span>{{ $jadwal->waktu_mulai ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') : '00:00' }} - Selesai</span>
                </div>

                {{-- Lokasi TUK --}}
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $jadwal->masterTuk?->nama_lokasi ?? 'TUK Tidak Ditemukan' }}</span>
                </div>
            </div>
        </div>

        <hr class="border-gray-900 mb-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                
                {{-- Deskripsi Skema (Tetap Ambil dari Database) --}}
                <div class="mb-12">
                    <h2 class="text-xl font-bold mb-3 font-poppins">Deskripsi Skema</h2>
                    <p class="text-gray-600 leading-relaxed text-justify">
                        {{ $jadwal->skema?->deskripsi_skema ?? 'Deskripsi skema belum tersedia saat ini.' }}
                    </p>
                </div>

                {{-- 🟦 PERBAIKAN: PERSYARATAN PESERTA (STATIS) --}}
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-3 font-poppins">Persyaratan Peserta</h2>
                    <div class="text-gray-600 leading-relaxed">
                        <ul class="list-disc list-inside space-y-2">
                            <li>Kartu Tanda Pengenal(KTP/PASPOR/KTM).</li>
                            <li>Pas foto berwarna (background merah/biru).</li>
                            <li>Curriculum Vitae (CV) terbaru.</li>
                            <li>Fotocopy Ijazah terakhir atau Transkrip Nilai sementara.</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-300 rounded-lg p-12" style="box-shadow: 6px 6px 10px rgba(0, 0, 0, 0.12);">
                    <div class="flex justify-between items-center mb-5">
                        <span class="font-semibold text-gray-700">Harga :</span>
                        <span class="font-bold text-lg">RP. {{ number_format($jadwal->skema?->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-10">
                        <span class="font-semibold text-gray-700">Jumlah Pendaftar :</span>
                        <span class="font-bold">{{ $jumlahPeserta ?? 0 }} Orang</span>
                    </div>
                    @auth
                        @if(Auth::user()->role->nama_role == 'asesi')
                            <form action="{{ route('asesi.daftar.jadwal') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
                                <button type="submit" class="block w-full bg-yellow-400 hover:bg-yellow-500 text-center text-black font-semibold py-3 rounded-lg transition duration-200 mb-3">
                                    Daftar Sekarang
                                </button>
                            </form>
                        @else
                             <button disabled class="block w-full bg-gray-300 text-center text-gray-500 font-semibold py-3 rounded-lg cursor-not-allowed mb-3">
                                Khusus Asesi
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-yellow-400 hover:bg-yellow-500 text-center text-black font-semibold py-3 rounded-lg transition duration-200 mb-3">
                            Daftar Sekarang
                        </a>
                    @endauth
                    
                    <p class="text-center text-gray-500 text-xs">
                        Pendaftaran ditutup tanggal 
                        {{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d F Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection