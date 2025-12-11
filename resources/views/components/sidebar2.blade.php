@props(['idAsesi', 'sertifikasi' => null, 'backUrl' => null])

{{-- 
    SIDEBAR COMPONENT 2 (FIXED / NO SCROLL)
    - Menghapus 'overflow-y-auto' dan 'no-scrollbar'
    - Menambah 'overflow-hidden' agar konten terkunci di dalam ukuran layar
--}}
<aside class="w-80 bg-gradient-to-b from-yellow-100 via-blue-100 to-blue-300 p-6 relative z-10 shadow-[8px_0_20px_-5px_rgba(0,0,0,0.15)] h-screen overflow-hidden flex-shrink-0 hidden md:flex md:flex-col font-sans">
    
    {{-- WRAPPER KONTEN --}}
    <div class="flex-grow flex flex-col h-full">

        {{-- 1. TOMBOL KEMBALI --}}
        <div class="mb-6 flex-shrink-0">
            <a href="{{ $backUrl ?? ($sertifikasi ? '/asesi/tracker/' . $sertifikasi->id_jadwal : '/dashboard') }}" 
               class="flex items-center text-gray-700 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="font-medium">Kembali</span>
            </a>
        </div>

        {{-- 2. JUDUL HALAMAN --}}
        <h1 class="text-3xl font-bold mb-2 text-gray-800 flex-shrink-0">
            @if($sertifikasi)
                Skema Sertifikat
            @else
                Profil Asesi
            @endif
        </h1>

        {{-- 3. GAMBAR PROFIL / SKEMA --}}
        <div class="flex justify-center my-6 flex-shrink-0">
            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-800 shadow-lg border-4 border-white">
                @if($sertifikasi && $sertifikasi->jadwal && $sertifikasi->jadwal->skema)
                    <img src="{{ asset('images/skema/' . ($sertifikasi->jadwal->skema->gambar ?? 'default_skema.jpg')) }}" 
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
        <div class="text-center mb-4 flex-shrink-0">
            @if($sertifikasi && $sertifikasi->jadwal && $sertifikasi->jadwal->skema)
                <h2 class="text-xl font-bold text-gray-900 leading-tight">
                    {{ $sertifikasi->jadwal->skema->nama_skema }}
                </h2>
                
                <p class="text-gray-600 text-sm mt-2 font-mono inline-block px-2 py-1 rounded-md">
                    {{ $sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-' }}
                </p>
                
                {{-- Nama Peserta --}}
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

        {{-- Spacer (Biar footer kedorong ke bawah tapi tetep fit screen) --}}
        <div class="flex-grow min-h-0"></div>

        {{-- 5. INFO ASESOR --}}
        @if($sertifikasi && $sertifikasi->jadwal)
            <div class="mt-4 mb-4 flex-shrink-0">
                <div class="bg-white p-4 rounded-2xl shadow-sm text-center">
                    
                    <div class="flex justify-center mb-2">
                        <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Asesor Penguji</h3>
                    
                    <p class="text-base font-bold text-gray-900 leading-tight mb-2">
                        {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                    </p>
                    
                    @if(!empty($sertifikasi->jadwal->asesor->nomor_regis))
                        <div class="inline-block bg-blue-50 px-3 py-1 rounded-full">
                            <p class="text-[10px] text-blue-600 font-medium font-mono tracking-wide">
                                Reg: {{ $sertifikasi->jadwal->asesor->nomor_regis }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        @endif

        {{-- FOOTER --}}
        <div class="mt-auto text-center text-xs text-gray-500 pt-2 flex-shrink-0">
            &copy; {{ date('Y') }} LSP Polines
        </div>

    </div>

</aside>