<aside
    x-cloak
    x-init="$nextTick(() => $el.classList.remove('opacity-0'))"
    class="
        opacity-0
        fixed top-0 left-0
        min-h-screen w-80 z-50
        bg-[linear-gradient(135deg,#4F46E5,#0EA5E9)]
        text-white p-8 flex flex-col shadow-lg
        transform transition-transform duration-300 ease-in-out
        overflow-y-auto
    "
    :class="{
        'translate-x-0': $store.sidebar.open,
        '-translate-x-full': !$store.sidebar.open
    }"
>

    {{-- HEADER SIDEBAR (Tombol Close) --}}
    <div class="absolute top-4 right-4 z-50">

        {{-- Tombol Close Desktop (BARU) --}}
        <button
            class="hidden lg:block text-white/70 hover:text-white transition"
            @click="$store.sidebar.setOpen(false)"
            title="Tutup Sidebar"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>

        {{-- Tombol Close Mobile (LAMA) --}}
        <button
            class="lg:hidden text-white text-xl opacity-80 hover:opacity-100"
            @click="$store.sidebar.setOpen(false)"
        >
            ✕
        </button>
    </div>

    {{-- Tombol Kembali --}}
    @php
    // Cek apakah ada parameter 'id_asesi' atau 'id_sertifikasi' di URL saat ini
    $idParam = request()->route('id_asesi') ?? request()->route('id_sertifikasi') ?? request()->route('id');

    // Tentukan Link Tujuan
    // Jika User adalah Asesor DAN ada ID -> Ke Tracker Asesor
    // Jika tidak -> Ke Home
    $backLink = (Auth::check() && Auth::user()->role->nama_role === 'asesor' && $idParam)
                ? route('asesor.tracker', $idParam)
                : route('home');
    @endphp

    <a href="{{ $backLink }}"
    class="flex items-center space-x-2 text-sm font-medium opacity-80 hover:opacity-100 mb-10 transition mt-2">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>{{ $idParam && Auth::user()->role->nama_role === 'asesor' ? 'Kembali ke Tracker' : 'Kembali ke Menu' }}</span>
    </a>

    {{-- SISA KONTEN (Profil dll) --}}
    <div class="flex flex-col items-center text-center">
        <div class="relative w-36 h-36 mb-4">
            {{-- Foto Skema --}}
            <img src="{{ $skema && $skema->gambar ? asset('storage/' . $skema->gambar) : asset('images/junior_web.jpg') }}"
                 alt="{{ $skema->nama_skema ?? 'Skema' }}"
                 class="w-full h-full object-cover rounded-full border-4 border-white shadow-md">
        </div>
        {{-- Nama Skema --}}
        <h2 class="text-2xl font-bold">{{ $skema->nama_skema ?? 'Nama Skema' }}</h2>
        {{-- Deskripsi Skema --}}
        <p class="text-sm mt-2 opacity-80 leading-relaxed">
            {{ $skema->deskripsi_skema ?? 'Deskripsi skema belum tersedia.' }}
        </p>
    </div>

    <div class="flex flex-col items-center text-center mt-10">
        <h4 class="text-s font-semibold uppercase tracking-wider opacity-60 mb-3">ASESI:</h4>
        <div class="flex items-center space-x-3">
            {{-- Foto Asesi --}}
            <img src="{{ $asesi && $asesi->user && $asesi->user->profile_photo_path ? asset('storage/' . $asesi->user->profile_photo_path) : asset('images/asesi.jpeg') }}"
                 alt="{{ $asesi->nama_lengkap ?? 'Peserta' }}"
                 class="w-12 h-12 rounded-full object-cover border-2 border-white/50">
            <div>
                {{-- Nama Asesi --}}
                <p class="font-semibold">{{ $asesi->nama_lengkap ?? 'Nama Peserta' }}</p>
            </div>
        </div>

        <h4 class="text-s font-semibold uppercase tracking-wider opacity-60 mt-6 mb-2">DIMULAI PADA:</h4>
        {{-- Tanggal Mulai Jadwal --}}
        <p class="text-sm font-medium">
            {{ $jadwal && $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('Y-m-d H:i:s') : '-' }}
        </p>
    </div>
</aside>
