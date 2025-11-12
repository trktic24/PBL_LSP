@extends('layouts.app-profil')

@section('content')
<div class="container mx-auto px-8 py-12">
    <article class="max-w-3xl mx-auto">
        
        {{-- Judul Berita --}}
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $berita->judul }}</h1>
        
        {{-- Info Meta: Tanggal --}}
        <p class="text-base text-gray-600 mb-6">
            Dipublikasikan pada: {{ $berita->tanggal->format('d F Y') }}
        </p>
        
        {{-- Gambar Utama --}}
        <img src="{{ $berita->gambar }}" 
             alt="{{ $berita->judul }}" 
             class="w-full h-auto max-h-[500px] object-cover rounded-lg shadow-lg mb-8">
        
        {{-- Konten Berita (Dummy) --}}
        <div class="text-gray-800 text-lg leading-relaxed space-y-6">
            <p>Ini adalah paragraf pertama dari konten berita. Karena ini adalah data dummy, kami belum memiliki isi lengkap untuk "{{ $berita->judul }}".</p>
            <p>Dalam implementasi nyata, Anda akan mengambil konten ini dari database (misalnya, kolom `isi_berita` atau `content`).</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-12 pt-8 border-t">
            {{-- Link ini akan kembali ke home dan scroll ke section berita --}}
            <a href="{{ route('home') }}#berita-terbaru"
               class="text-blue-700 font-semibold hover:underline flex items-center gap-2 group">
                <span class="transition-transform duration-200 group-hover:-translate-x-1">&larr;</span>
                Kembali ke Halaman Utama
            </a>
        </div>
    </article>
</div>
@endsection