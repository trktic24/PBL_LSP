@extends('layouts.app-profil')

@section('title', 'Struktur Organisasi')

@section('content')
<section id="struktur" class="pt-24 pb-0 -mb-60 bg-white min-h-screen">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-12">Struktur Organisasi</h2>

        {{-- DATA MAPPING --}}
        @php
            $struktur = \App\Models\StrukturOrganisasi::all(); 

            function getDataById($koleksi, $id) {
                return $koleksi->find($id) ?? (object)[
                    'nama' => '',
                    'jabatan' => 'Jabatan Kosong', 
                    'gambar' => null
                ];
            }

            // HELPER PEMBERSIH STRING UNTUK JS
            function clean($str) {
                if (!$str) return '';
                $str = str_replace(["'", '"'], "`", $str);
                $str = preg_replace('/\s+/', ' ', $str);
                return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
            }

            $dewan          = getDataById($struktur, 1);
            $ketua_lsp      = getDataById($struktur, 2);
            $ketua_admin    = getDataById($struktur, 3);
            $anggota_admin  = getDataById($struktur, 4);
            $ketua_sertif   = getDataById($struktur, 5);
            $anggota_sertif = getDataById($struktur, 6);
            $ketua_komite   = getDataById($struktur, 7);
            $anggota_komite = getDataById($struktur, 8);
            $ketua_kerjasama = getDataById($struktur, 9);
            $ketua_mutu     = getDataById($struktur, 10);
            $anggota_mutu   = getDataById($struktur, 11);
        @endphp

        <div class="w-full flex flex-col items-center">
            
            {{-- ================= LEVEL 1: DEWAN PENGARAH ================= --}}
            <div class="relative z-30">
                <div onclick="openOrgModal('{{ clean($dewan->nama) }}', '{{ clean($dewan->jabatan) }}', '{{ $dewan->gambar }}')" 
                     class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-4 px-4 md:px-10 w-64 md:w-80 shadow-sm mx-auto relative z-30">
                    {{ strtoupper($dewan->jabatan) }}
                </div>
            </div>

            {{-- Garis Vertikal 1 --}}
            <div class="h-10 w-[2px] bg-black relative z-0"></div>

            {{-- ================= LEVEL 2: KETUA LSP & KOMITE ================= --}}
            <div class="relative w-full flex flex-col items-center lg:block lg:h-32 mb-0 z-40">
                
                {{-- Box Ketua LSP --}}
                <div class="relative z-50 lg:absolute lg:left-1/2 lg:-translate-x-1/2 lg:top-0">
                    <div onclick="openOrgModal('{{ clean($ketua_lsp->nama) }}', '{{ clean($ketua_lsp->jabatan) }}', '{{ $ketua_lsp->gambar }}')" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-4 px-4 md:px-10 w-64 md:w-80 shadow-sm mx-auto relative z-50">
                        {{ strtoupper($ketua_lsp->jabatan) }}
                    </div>
                </div>

                {{-- Konektor Komite --}}
                <div class="block lg:hidden h-8 w-[2px] bg-black mx-auto"></div> 
                <div class="hidden lg:block absolute top-1/2 left-1/2 w-80 h-[2px] border-t-[2px] border-dashed border-black pointer-events-none z-0"></div> 

                {{-- Box Komite Skema --}}
                <div class="relative z-[100] lg:absolute lg:top-1/2 lg:-translate-y-1/2 lg:left-1/2 lg:ml-80">
                     <div onclick="openOrgModal(
                            '{{ clean($ketua_komite->nama) }}', '{{ clean($ketua_komite->jabatan) }}', '{{ $ketua_komite->gambar }}',
                            '{{ clean($anggota_komite->nama) }}', '{{ clean($anggota_komite->jabatan) }}', '{{ $anggota_komite->gambar }}'
                          )" 
                          class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-4 w-56 text-sm shadow-sm mx-auto flex flex-col items-center justify-center h-24 relative z-[100]" style="pointer-events: auto;">
                        <span>KOMITE SKEMA</span>
                        @if($anggota_komite->nama !== 'Belum Terisi' && $anggota_komite->nama !== '')
                            <span class="text-xs font-normal mt-1 text-gray-200">(Ketua & Anggota)</span>
                        @endif
                    </div>
                </div>

                {{-- Garis Vertikal Turun (Di dalam container Level 2) --}}
                <div class="hidden lg:block h-full w-[2px] bg-black absolute left-1/2 -translate-x-1/2 top-1/2 pointer-events-none z-0"></div>
            </div>

            {{-- 
                🟦 PERBAIKAN GARIS KELEWAT:
                Saya mengubah tingginya dari h-12 menjadi h-8. 
                Ini akan membuat garis berhenti tepat sebelum menabrak garis horizontal di bawahnya.
            --}}
            <div class="hidden lg:block h-16 w-[2px] bg-black relative z-0"></div>
            
            <div class="block lg:hidden h-8 w-[2px] bg-black"></div>


            {{-- ================= LEVEL 3: 4 BIDANG ================= --}}
            
            {{-- Garis Cabang 4 --}}
            <div class="hidden lg:block relative w-[1000px] h-8 z-0 pointer-events-none">
                <div class="absolute top-0 border-t-[2px] border-black left-[125px] right-[125px]"></div>
                <div class="absolute top-0 left-[125px] h-8 w-[2px] bg-black"></div>
                <div class="absolute top-0 left-[375px] h-8 w-[2px] bg-black"></div>
                <div class="absolute top-0 right-[375px] h-8 w-[2px] bg-black"></div>
                <div class="absolute top-0 right-[125px] h-8 w-[2px] bg-black"></div>
            </div>

            {{-- Container Bidang --}}
            <div class="grid grid-cols-1 gap-6 lg:flex lg:justify-center lg:gap-6 w-full lg:w-auto relative z-30">
                
                {{-- 1. MANAJEMEN MUTU --}}
                <div class="flex flex-col items-center relative z-30">
                    <div class="block lg:hidden h-4 w-[2px] bg-black mb-0"></div>
                    <div onclick="openOrgModal(
                            '{{ clean($ketua_mutu->nama) }}', '{{ clean($ketua_mutu->jabatan) }}', '{{ $ketua_mutu->gambar }}',
                            '{{ clean($anggota_mutu->nama) }}', '{{ clean($anggota_mutu->jabatan) }}', '{{ $anggota_mutu->gambar }}'
                         )" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-64 lg:w-56 h-24 flex flex-col items-center justify-center shadow-sm relative z-30">
                        <span class="text-center">BIDANG<br>MANAJEMEN MUTU</span>
                        @if($anggota_mutu->nama !== 'Belum Terisi' && $anggota_mutu->nama !== '')
                            <span class="text-xs font-normal mt-1 text-gray-200">(Ketua & Anggota)</span>
                        @endif
                    </div>
                </div>

                {{-- 2. SERTIFIKASI --}}
                <div class="flex flex-col items-center relative z-30">
                    <div class="block lg:hidden h-4 w-[2px] bg-black mb-0"></div>
                    <div onclick="openOrgModal(
                            '{{ clean($ketua_sertif->nama) }}', '{{ clean($ketua_sertif->jabatan) }}', '{{ $ketua_sertif->gambar }}',
                            '{{ clean($anggota_sertif->nama) }}', '{{ clean($anggota_sertif->jabatan) }}', '{{ $anggota_sertif->gambar }}'
                         )" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-64 lg:w-56 h-24 flex flex-col items-center justify-center shadow-sm relative z-30">
                        <span class="text-center">BIDANG<br>SERTIFIKASI</span>
                        @if($anggota_sertif->nama !== 'Belum Terisi' && $anggota_sertif->nama !== '')
                            <span class="text-xs font-normal mt-1 text-gray-200">(Ketua & Anggota)</span>
                        @endif
                    </div>
                </div>

                {{-- 3. KERJASAMA --}}
                <div class="flex flex-col items-center relative z-30">
                    <div class="block lg:hidden h-4 w-[2px] bg-black mb-0"></div>
                    <div onclick="openOrgModal('{{ clean($ketua_kerjasama->nama) }}', '{{ clean($ketua_kerjasama->jabatan) }}', '{{ $ketua_kerjasama->gambar }}')" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-64 lg:w-56 h-24 flex items-center justify-center shadow-sm relative z-30">
                        <span class="text-center">BIDANG<br>KERJASAMA</span>
                    </div>
                </div>

                {{-- 4. ADMINISTRASI --}}
                <div class="flex flex-col items-center relative z-30">
                    <div class="block lg:hidden h-4 w-[2px] bg-black mb-0"></div>
                    <div onclick="openOrgModal(
                            '{{ clean($ketua_admin->nama) }}', '{{ clean($ketua_admin->jabatan) }}', '{{ $ketua_admin->gambar }}',
                            '{{ clean($anggota_admin->nama) }}', '{{ clean($anggota_admin->jabatan) }}', '{{ $anggota_admin->gambar }}'
                         )" 
                         class="cursor-pointer bg-blue-800 text-white border-4 border-yellow-400 rounded-2xl hover:bg-blue-700 transition-all duration-300 font-bold py-3 px-2 w-64 lg:w-56 h-24 flex flex-col items-center justify-center shadow-sm relative z-30">
                        <span class="text-center">BIDANG<br>ADMINISTRASI</span>
                        @if($anggota_admin->nama !== 'Belum Terisi' && $anggota_admin->nama !== '')
                            <span class="text-xs font-normal mt-1 text-gray-200">(Ketua & Anggota)</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= MODAL POPUP (Script Sama) ================= --}}
    <div id="orgModal" class="fixed inset-0 z-[200] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity backdrop-blur-sm" onclick="closeOrgModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md scale-100 opacity-100">
                <div class="bg-blue-600 px-4 py-6 sm:px-6 text-center relative">
                    <button onclick="closeOrgModal()" class="absolute top-4 right-4 text-blue-200 hover:text-white z-20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <button id="prevPersonBtn" onclick="prevPerson()" class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 hidden z-20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button id="nextPersonBtn" onclick="nextPerson()" class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 hidden z-20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div class="mx-auto h-24 w-24 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white relative z-10">
                        <img id="modal-img" src="" alt="Foto Profil" class="h-full w-full object-cover">
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-white" id="modal-nama">Nama Pejabat</h3>
                    <p class="text-blue-200 text-sm font-medium uppercase tracking-wider" id="modal-jabatan">JABATAN</p>
                    <div id="slide-indicators" class="flex justify-center gap-2 mt-4 hidden">
                        <span id="dot-0" class="w-2 h-2 rounded-full bg-white"></span>
                        <span id="dot-1" class="w-2 h-2 rounded-full bg-white/50"></span>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeOrgModal()" class="inline-flex w-full justify-center rounded-full bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let currentProfiles = [];
    let currentIndex = 0;

    function openOrgModal(nama1, jabatan1, gambar1, nama2 = '', jabatan2 = '', gambar2 = '') {
        currentProfiles = [];
        currentIndex = 0;
        currentProfiles.push({ nama: nama1, jabatan: jabatan1, gambar: gambar1 });
        if (nama2 && nama2 !== '') {
            currentProfiles.push({ nama: nama2, jabatan: jabatan2, gambar: gambar2 });
        }
        updateModalDisplay();
        document.getElementById('orgModal').classList.remove('hidden');
    }

    function updateModalDisplay() {
        const profile = currentProfiles[currentIndex];
        document.getElementById('modal-nama').innerText = profile.nama || 'Nama Tidak Tersedia';
        document.getElementById('modal-jabatan').innerText = profile.jabatan || '-';
        
        const imgElement = document.getElementById('modal-img');
        if (profile.gambar && profile.gambar !== 'null') {
            imgElement.src = "{{ asset('storage/struktur') }}/" + profile.gambar;
        } else {
            imgElement.src = "https://ui-avatars.com/api/?name=" + encodeURIComponent(profile.nama) + "&background=random";
        }

        const prevBtn = document.getElementById('prevPersonBtn');
        const nextBtn = document.getElementById('nextPersonBtn');
        const indicators = document.getElementById('slide-indicators');
        const dot0 = document.getElementById('dot-0');
        const dot1 = document.getElementById('dot-1');

        if (currentProfiles.length > 1) {
            prevBtn.classList.remove('hidden');
            nextBtn.classList.remove('hidden');
            indicators.classList.remove('hidden');
            
            if (currentIndex === 0) {
                prevBtn.classList.add('opacity-30', 'cursor-not-allowed');
                nextBtn.classList.remove('opacity-30', 'cursor-not-allowed');
                dot0.classList.replace('bg-white/50', 'bg-white');
                dot1.classList.replace('bg-white', 'bg-white/50');
            } else {
                prevBtn.classList.remove('opacity-30', 'cursor-not-allowed');
                nextBtn.classList.add('opacity-30', 'cursor-not-allowed');
                dot0.classList.replace('bg-white', 'bg-white/50');
                dot1.classList.replace('bg-white/50', 'bg-white');
            }
        } else {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
            indicators.classList.add('hidden');
        }
    }

    function nextPerson() {
        if (currentIndex < currentProfiles.length - 1) {
            currentIndex++;
            updateModalDisplay();
        }
    }

    function prevPerson() {
        if (currentIndex > 0) {
            currentIndex--;
            updateModalDisplay();
        }
    }

    function closeOrgModal() {
        document.getElementById('orgModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") { closeModal(); }
        if (!document.getElementById('orgModal').classList.contains('hidden')) {
            if (event.key === "ArrowRight") nextPerson();
            if (event.key === "ArrowLeft") prevPerson();
        }
    });
</script>
@endsection