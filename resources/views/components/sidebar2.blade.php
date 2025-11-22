@props(['idAsesi', 'sertifikasi' => null, 'backUrl' => null])

<aside class="w-80 bg-gradient-to-b from-yellow-100 via-blue-100 to-blue-300 p-6 relative z-10 shadow-[8px_0_20px_-5px_rgba(0,0,0,0.15)] h-screen overflow-y-auto flex-shrink-0 hidden md:flex md:flex-col">
    
    {{-- WRAPPER KONTEN --}}
    <div class="flex-grow flex flex-col">

        {{-- 1. TOMBOL KEMBALI --}}
        <div class="mb-6">
            <a href="{{ $backUrl ?? ($sertifikasi ? '/tracker/' . $sertifikasi->id_jadwal : '/dashboard') }}" 
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
                    <img src="{{ asset('images/' . ($sertifikasi->jadwal->skema->gambar ?? 'default_skema.jpg')) }}" 
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

        {{-- 4. INFORMASI SKEMA --}}
        <div class="text-center mb-8">
            @if($sertifikasi && $sertifikasi->jadwal && $sertifikasi->jadwal->skema)
                <h2 class="text-xl font-bold text-gray-900 leading-tight">
                    {{ $sertifikasi->jadwal->skema->nama_skema }}
                </h2>
                {{-- Prioritaskan 'nomor_skema', fallback ke 'kode_unit' --}}
                <p class="text-gray-600 text-sm mt-2 font-mono rounded-md">
                    {{ $sertifikasi->jadwal->skema->nomor_skema ?? $sertifikasi->jadwal->skema->kode_unit ?? '-' }}
                </p>
                
                {{-- 
                    [UPDATE] GARIS ATAS & BAWAH UNTUK NAMA PESERTA 
                    Gunakan 'border-y' (top & bottom) dan 'py-4' biar ada jarak napas 
                --}}
                <div class="mt-6 py-4 border-y border-blue-300/50">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Peserta</p>
                    <p class="text-sm font-bold text-gray-800">
                        {{ $sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta' }}
                    </p>
                </div>
            @else
                <h2 class="text-xl font-bold text-gray-900">Menu Asesi</h2>
            @endif
        </div>

        {{-- Spacer --}}
        <div class="flex-grow"></div>

        {{-- 5. INFO ASESOR (DESIGN BARU DENGAN TOPI WISUDA) --}}
        @if($sertifikasi && $sertifikasi->jadwal)
            <div class="mt-4 mb-4">
                <div class="text-center p-5 bg-white/70 rounded-xl shadow-sm border border-white backdrop-blur-sm">
                    
                    <div class="flex flex-col items-center mb-3">
                        <div class="p-2 bg-blue-100 rounded-full mb-2 text-blue-600">
                            {{-- [UPDATE] IKON TOPI WISUDA (Academic Cap) --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Asesor</h3>
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-base font-bold text-gray-900">
                            {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                        </p>
                        
                        @if(isset($sertifikasi->jadwal->asesor->nomor_regis))
                            <div class="inline-block bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                <p class="text-xs text-blue-800 font-mono">
                                    No. Reg: {{ $sertifikasi->jadwal->asesor->nomor_regis }}
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @endif

    </div>

    {{-- FOOTER --}}
    <div class="mt-auto text-center text-xs text-gray-500 pt-4 border-t border-white/20">
        &copy; {{ date('Y') }} LSP Polines
    </div>

</aside>