@props(['jadwal'])

<aside class="fixed top-0 left-0 h-screen w-80 bg-[linear-gradient(135deg,#4F46E5,#0EA5E9)] text-white p-8 flex flex-col flex-shrink-0 shadow-lg">
    <!-- Tombol Kembali -->
    <a href="{{ route('home.index') }}"
       class="flex items-center space-x-2 text-sm font-medium opacity-80 hover:opacity-100 mb-10 transition">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span>Kembali</span>
    </a>

    <!-- Profil Skema -->
    <div class="flex flex-col items-center text-center">
        <div class="relative w-36 h-36 mb-4">
            <img src="{{ $jadwal->skema->gambar ? asset('uploads/skema/' . $jadwal->skema->gambar_skema) : asset('images/default_skema.jpg') }}"
                 alt="{{ $jadwal->skema->nama_skema ?? 'Skema' }}"
                 class="w-full h-full object-cover rounded-full border-4 border-white shadow-md">
        </div>
        <h2 class="text-2xl font-bold">{{ $jadwal->skema->nama_skema ?? 'Nama Skema' }}</h2>
        <p class="text-sm mt-2 opacity-80 leading-relaxed">
            {{ $jadwal->skema->deskripsi_skema ?? 'Deskripsi skema belum tersedia' }}
        </p>
    </div>
</aside>