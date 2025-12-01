@extends('layouts.app-profil')

@section('title', $berita->judul)
@section('description', Str::limit(strip_tags($berita->isi), 150))

@section('content')
<div class="container mx-auto px-8 py-12">
    <article class="max-w-3xl mx-auto">
        
        {{-- Judul Berita --}}
        <h1 class="text-4xl font-bold text-gray-900 mb-4 font-poppins">{{ $berita->judul }}</h1>
        
        {{-- Info Meta: Tanggal --}}
        <p class="text-base text-gray-600 mb-6">
            Dipublikasikan pada: {{ $berita->created_at->format('d F Y') }}
        </p>
        
        {{-- Gambar Utama --}}
        <img src="{{ asset('storage/berita/' . $berita->gambar) }}" 
             alt="{{ $berita->judul }}" 
             class="w-full h-auto max-h-[500px] object-cover rounded-lg shadow-lg mb-8">
        
        {{-- Konten Berita (Dari Database) --}}
        <div class="text-gray-800 text-lg leading-relaxed space-y-6">
            {!! nl2br(e($berita->isi)) !!}
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