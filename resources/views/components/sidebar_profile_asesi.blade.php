@props(['asesi'])

<aside class="fixed top-[80px] left-0 h-[calc(100vh-80px)] w-[22%] 
              bg-gradient-to-b from-[#e8f0ff] via-[#f3f8ff] to-[#ffffff]
              shadow-inner border-r border-gray-200 flex flex-col items-center pt-8 z-40">

    <a href="{{ url()->previous() ?? route('dashboard') }}" 
       class="absolute top-4 left-6 flex items-center text-gray-500 hover:text-blue-600 transition-all duration-200 cursor-pointer z-50 hover:-translate-x-1">
        <i class="fas fa-arrow-left text-lg"></i>
        <span class="ml-2 font-medium text-sm">Kembali</span>
    </a>

    <h2 class="text-xl font-bold text-gray-900 mb-3 mt-8">Biodata Peserta</h2>

    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-[0_0_15px_rgba(0,0,0,0.2)] mb-4 bg-purple-300 flex items-center justify-center relative z-10">
        <span class="text-4xl font-bold text-white select-none">
            {{ strtoupper(substr($asesi->nama_lengkap ?? 'User', 0, 2)) }}
        </span>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 text-center px-4 relative z-10">{{ $asesi->nama_lengkap ?? 'Nama Asesi' }}</h3>
    <div class="w-[85%] border-t-2 border-gray-300 opacity-50 mt-4 -mb-2 relative z-10"></div>

@php
    // Logika: Ambil sertifikasi terakhir yang didaftarkan user
    $sertifikasiTerbaru = $asesi->dataSertifikasi->sortByDesc('created_at')->first();
    $namaSkema = $sertifikasiTerbaru?->jadwal?->skema?->nama_skema ?? '-';
    $nomorSkema = $sertifikasiTerbaru?->jadwal?->skema?->nomor_skema ?? '-';
@endphp

<!-- [REVISI] Menampilkan Nama Skema & Nomor Skema -->
<div class="text-center mt-4 mb-8 relative z-10 px-4">
    <!-- Nama Skema: Font Tebal (Bold) & Gelap -->
    <p class="text-gray-900 font-semibold text-sm leading-snug">
        {{ $namaSkema }}
    </p>
    <!-- Nomor Skema: Font Tipis, Kecil & Abu-abu -->
    <p class="text-gray-500 text-xs mt-1 font-medium tracking-wide">
        {{ $nomorSkema }}
    </p>
</div>

    <div class="w-[85%] bg-white/40 backdrop-blur-md rounded-2xl p-4 
                shadow-[0_0_15px_rgba(0,0,0,0.15)] mb-4 relative z-10">
    
        <div class="flex flex-col space-y-4">
            <a href="{{ route('asesi.profile.settings', $asesi->id_asesi) }}" 
                class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ Route::is('asesi.profile.settings') ? 'text-blue-600 bg-blue-50' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-user-gear text-l mr-3"></i> Profile Settings
            </a>

            <a href="{{ route('asesi.profile.form', $asesi->id_asesi) }}" 
                class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ Route::is('asesi.profile.form') ? 'text-blue-600 bg-blue-50' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-clipboard text-l mr-3"></i> Form
            </a>

            <a href="{{ route('asesi.profile.tracker', $asesi->id_asesi) }}" 
                class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ Route::is('asesi.profile.tracker') ? 'text-blue-600 bg-blue-50' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-chart-line text-l mr-3"></i> Lacak Aktivitas
            </a>

            <a href="{{ route('asesi.profile.bukti', $asesi->id_asesi) }}" 
                class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ Route::is('asesi.profile.bukti') ? 'text-blue-600 bg-blue-50' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-check text-l mr-3"></i> Bukti Kelengkapan
            </a>

        </div>
    </div>
    
    <div class="w-[85%] grid grid-cols-2 gap-x-5 relative z-10">
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesi</button>
        <button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesor</button>
    </div>

</aside>