@props([
    'title' => 'Judul Halaman',
    'code' => null,
    'name' => null,
    'image' =>null,
    'sertifikasi' => null,
    'backUrl' => null,
])

{{-- PERUBAHAN 1: Padding atas (pt) dikurangi drastis dari pt-20 ke pt-6. Padding bawah (pb) dikurangi dikit ke pb-20 --}}
<div class="block md:hidden bg-gradient-to-b from-[#FFF9E5] to-[#3fa1f649] pb-20 pt-6 px-4 rounded-b-[30px] shadow-sm relative z-30">
    
    {{-- PERUBAHAN 2: Posisi tombol disesuaikan dengan padding baru (top-6 left-4) dan tambah z-40 biar pasti di atas --}}
    {{-- 1. Tombol Kembali --}}
    <div class="absolute top-6 left-4 z-40">
        {{-- Logika URL Back tetap dipertahankan --}}
        @php
            $finalBackUrl = '#'; // Default fallback
            if (isset($backUrl)) {
                $finalBackUrl = $backUrl;
            } elseif ($sertifikasi && isset($sertifikasi->id_jadwal)) {
                 // Asumsi route tracker menggunakan parameter 'jadwal_id', sesuaikan jika beda
                $finalBackUrl = route('asesi.tracker', ['jadwal_id' => $sertifikasi->id_jadwal]);
            } else {
                $finalBackUrl = '/';
            }
        @endphp

        <a href="{{ $finalBackUrl }}" class="flex items-center justify-center p-2 backdrop-blur-sm rounded-full text-gray-700 hover:text-gray-900 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    {{-- PERUBAHAN 3: Hapus mt-2, tambahkan px-10 agar teks tidak nabrak tombol kembali jika panjang --}}
    {{-- 2. Informasi Teks --}}
    <div class="text-center px-10 relative z-10">
        {{-- Judul Utama (Nama Skema) --}}
        {{-- Ukuran font sedikit disesuaikan jadi text-xl atau leading-tight biar lebih pas --}}
        <h1 class="text-xl font-bold text-black tracking-tight leading-tight">
            {{ $title }}
        </h1>
        
        {{-- Sub Judul (Kode Skema) --}}
        @if($code)
            <p class="text-gray-600 font-medium mt-1 text-xs font-mono">
                {{ $code }}
            </p>
        @endif

        {{-- Nama User (Opsional) --}}
        @if($name)
            <p class="text-[10px] text-gray-400 mt-3 uppercase tracking-wider font-bold">
                Peserta
            </p>
            <p class="text-[12px] text-gray-600 mt-0 uppercase tracking-wider font-bold">
                {{ $name }}
            </p>
        @endif
    </div>

    {{-- 3. Foto Profil / Logo (Floating) --}}
    {{-- Posisi bottom disesuaikan sedikit (-bottom-12) agar pas dengan padding baru --}}
    <div class="absolute left-1/2 transform -translate-x-1/2 -bottom-12 z-30">
        <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white ring-1 ring-gray-100">
            @if($image)
                <img src="{{ $image }}"
                     onerror="this.onerror=null;this.src='{{ asset('images/default_pic.jpeg') }}';" 
                     alt="Logo" 
                     class="w-full h-full object-cover">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($title) }}&background=0D8ABC&color=fff" 
                     alt="Placeholder" 
                     class="w-full h-full object-cover">
            @endif
        </div>
    </div>

</div>