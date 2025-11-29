@props([
    'title' => 'Judul Halaman',
    'code' => null,
    'name' => null,
    'image' => null,
    'sertifikasi' => null,
])

{{-- 
    COMPONENT HEADER MOBILE
    -----------------------
    Digunakan untuk tampilan mobile dengan style:
    - Background Gradient Kuning (#FFF9E5)
    - Foto Profil Melayang (Floating)
    - Tombol Kembali di Pojok Kiri
--}}

<div class="block md:hidden bg-gradient-to-b from-[#FFF9E5] to-[#3fa1f649] pb-24 pt-20 px-6 rounded-b-[40px] shadow-sm relative z-30">
    
    {{-- 1. Tombol Kembali --}}
    <div class="absolute top-8 left-6">
        <a href="{{ $backUrl ?? ($sertifikasi ? '/tracker/' . $sertifikasi->id_jadwal : '/dashboard') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    {{-- 2. Informasi Teks --}}
    <div class="text-center mt-2">
        {{-- Judul Utama (Nama Skema) --}}
        <h1 class="text-2xl font-bold text-black tracking-tight leading-tight">
            {{ $title }}
        </h1>
        
        {{-- Sub Judul (Kode Skema) --}}
        @if($code)
            <p class="text-gray-500 font-medium mt-2 text-sm font-mono">
                {{ $code }}
            </p>
        @endif

        {{-- Nama User (Opsional) --}}
        @if($name)
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider font-semibold">
                {{ $name }}
            </p>
        @endif
    </div>

    {{-- 3. Foto Profil / Logo (Floating) --}}
    <div class="absolute left-1/2 transform -translate-x-1/2 -bottom-10 z-30">
        <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white ring-1 ring-gray-100">
            @if($image)
                <img src="{{ $image }}" 
                     alt="Logo" 
                     class="w-full h-full object-cover"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($title) }}&background=0D8ABC&color=fff'">
            @else
                {{-- Fallback jika tidak ada gambar --}}
                <img src="https://ui-avatars.com/api/?name={{ urlencode($title) }}&background=0D8ABC&color=fff" 
                     alt="Placeholder" 
                     class="w-full h-full object-cover">
            @endif
        </div>
    </div>

</div>