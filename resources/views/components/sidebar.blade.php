@props(['idAsesi', 'sertifikasi' => null, 'backUrl' => null])

{{-- 
    SIDEBAR COMPONENT 
    - h-screen: Tingginya mentok setinggi layar.
    - overflow-y-auto: Kalau konten sidebar kepanjangan, dia bisa scroll sendiri (independen).
    - flex flex-col: Biar kita bisa dorong footer ke bawah.
--}}
<aside class="w-80 bg-gradient-to-b from-yellow-100 via-blue-100 to-blue-300 p-6 relative z-10 shadow-[8px_0_20px_-5px_rgba(0,0,0,0.15)] h-screen overflow-y-auto flex-shrink-0 hidden md:flex md:flex-col">
    
    {{-- WRAPPER KONTEN UTAMA (Agar Footer bisa didorong ke bawah) --}}
    <div class="flex-grow">

        {{-- 1. TOMBOL KEMBALI --}}
        <div class="mb-6">
            <a href="{{ $backUrl ?? ($sertifikasi ? '/asesi/tracker/' . $sertifikasi->id_jadwal : '/dashboard') }}" 
            class="flex items-center text-gray-700 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="font-medium">Kembali</span>
            </a>
        </div>

        {{-- 2. JUDUL HALAMAN --}}
        <h1 class="text-3xl font-bold mb-2 text-gray-800">
            @if($sertifikasi)
                Skema Sertifikat
            @else
                Profil Asesi
            @endif
        </h1>

        {{-- 3. GAMBAR PROFIL / SKEMA --}}
        <div class="flex justify-center my-6">
            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-800 shadow-lg border-4 border-white">
                @if($sertifikasi && $sertifikasi->jadwal && $sertifikasi->jadwal->skema)
                    @php
                        $imgSrc = 'images/default.jpg';
                        $gambar = $sertifikasi->jadwal->skema->gambar;
                        if ($gambar) {
                            if (str_starts_with($gambar, 'images/')) {
                                $imgSrc = $gambar;
                            } elseif (file_exists(public_path('images/skema/foto_skema/' . $gambar))) {
                                $imgSrc = 'images/skema/foto_skema/' . $gambar;
                            } else {
                                $imgSrc = 'images/skema/' . $gambar;
                            }
                        }
                    @endphp
                    <img src="{{ asset($imgSrc) }}" 
                        alt="Logo Skema" 
                        class="w-full h-full object-cover"
                        onerror="this.src='https://ui-avatars.com/api/?name=Skema&background=random'">
                @else
                    <img src="https://ui-avatars.com/api/?name=User&background=random&size=128" 
                        alt="Foto Profil" 
                        class="w-full h-full object-cover">
                @endif
            </div>
        </div>

        {{-- 4. INFORMASI TEKS --}}
        <div class="text-center mb-8">
            @if($sertifikasi && $sertifikasi->jadwal && $sertifikasi->jadwal->skema)
                <h2 class="text-xl font-bold text-gray-900 leading-tight">
                    {{ $sertifikasi->jadwal->skema->nama_skema }}
                </h2>
                <p class="text-gray-600 text-sm mt-2 font-mono">
                    {{ $sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-' }}
                </p>
                
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Peserta</p>
                    <p class="text-sm font-bold text-gray-800">
                        {{ $sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta' }}
                    </p>
                </div>
            @else
                <h2 class="text-xl font-bold text-gray-900">Menu Asesi</h2>
                <p class="text-gray-600 text-sm mt-2">Selamat Datang</p>
            @endif
        </div>

        {{-- 5. LIST PERSYARATAN UTAMA (STATIS) --}}
        {{-- Hanya muncul jika dalam konteks sertifikasi --}}
        @if($sertifikasi)
        <div>
            <h3 class="font-bold text-lg mb-4 text-gray-800 border-b border-gray-300 pb-2">Persyaratan Utama</h3>
            <ul class="space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-gray-700">Data Sertifikasi</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-gray-700">Bukti Kelengkapan Pemohon</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-gray-700">Bukti Pembayaran</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-gray-700">Persetujuan Assesmen dan Kerahasiaan</span>
                </li>
            </ul>
        </div>
        @endif
        
    </div>

    {{-- FOOTER SIDEBAR (Mepet Bawah karena mt-auto di parent flex column) --}}
    <div class="mt-auto text-center text-xs text-gray-500 pt-6">
        &copy; {{ date('Y') }} LSP Polines
    </div>

</aside>