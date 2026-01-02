<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Asesmen Asesi | LSP Polines</title> 

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="text-gray-800">

  <x-navbar.navbar-admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-12 border border-gray-100 min-h-full">
        
        {{-- Header Page --}}
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ route('admin.asesor.daftar_asesi', ['id_asesor' => $asesor->id_asesor, 'id_jadwal' => $dataSertifikasi->jadwal->id_jadwal]) }}" class="absolute left-0 top-1 text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Detail Asesmen</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola penilaian dan verifikasi untuk asesi ini.</p>
            </div>
        </div>

        @php
            // --- LOGIC SETUP ---
            $level = $dataSertifikasi->level_status;
            if ($dataSertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui') {
                if ($level < 40) $level = 40;
            }
            $isFinalized = ($level >= 100);

            // --- HELPER FUNCTIONS ---
            function getStatusColor($isDone, $isActive = false, $isRejected = false) {
                if ($isRejected) return 'bg-red-100 text-red-800';
                if ($isDone) return 'bg-green-100 text-green-800';
                if ($isActive) return 'bg-yellow-100 text-yellow-800';
                return 'bg-gray-100 text-gray-500';
            }

            // ... (inside item definitions)
            
            // FR.MAPA.01
            $statusMapa01 = $dataSertifikasi->rekomendasi_mapa01;
            $isMapa01Done = $statusMapa01 == 'diterima';
            $isMapa01Rejected = $statusMapa01 == 'tidak diterima';
            
            $praAsesmenItems[] = [
                'id' => 'MAPA01',
                // ...
                'status' => $isMapa01Done ? 'DONE' : ($isMapa01Rejected ? 'REJECTED' : 'WAITING'),
                'status_label' => $isMapa01Done ? 'Diterima' : ($isMapa01Rejected ? 'Ditolak' : 'Menunggu'),
                // ...
            ];

            // FR.MAPA.02
            $statusMapa02 = $dataSertifikasi->rekomendasi_mapa02;
            $isMapa02Done = $statusMapa02 == 'diterima';
            $isMapa02Rejected = $statusMapa02 == 'tidak diterima';

            $praAsesmenItems[] = [
                'id' => 'MAPA02',
                // ...
                'status' => $isMapa02Done ? 'DONE' : ($isMapa02Rejected ? 'REJECTED' : 'WAITING'),
                'status_label' => $isMapa02Done ? 'Diterima' : ($isMapa02Rejected ? 'Ditolak' : 'Menunggu'),
                // ...
            ];
            
            // ... (Similar for APL01/APL02)

            function getStatusText($isDone, $isActive = false) {
                if ($isDone) return 'Selesai / Diterima';
                if ($isActive) return 'Menunggu / Aktif';
                return 'Belum Tersedia';
            }

            // --- DATA PREPARATION ---
            
            // 1. Pra Asesmen Items
            $praAsesmenItems = [];

            // FR.APL.01
            $statusApl01 = $dataSertifikasi->rekomendasi_apl01;
            $isApl01Done = $statusApl01 == 'diterima';
            $isApl01Rejected = $statusApl01 == 'tidak diterima';

            $praAsesmenItems[] = [
                'id' => 'APL01',
                'title' => 'FR.APL.01 - Permohonan Sertifikasi',
                'desc' => 'Formulir permohonan sertifikasi kompetensi.',
                'status' => $isApl01Done ? 'DONE' : ($isApl01Rejected ? 'REJECTED' : 'WAITING'),
                'status_label' => $isApl01Done ? 'Diterima' : ($isApl01Rejected ? 'Ditolak' : 'Menunggu'),
                'verify_url' => route('APL_01_1', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('apl01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => true, 
                'can_pdf' => true
            ];

            // FR.MAPA.01
            $statusMapa01 = $dataSertifikasi->rekomendasi_mapa01;
            $isMapa01Done = $statusMapa01 == 'diterima';
            $isMapa01Rejected = $statusMapa01 == 'tidak diterima';

            $praAsesmenItems[] = [
                'id' => 'MAPA01',
                'title' => 'FR.MAPA.01 - Merencanakan Aktivitas',
                'desc' => 'Perencanaan aktivitas dan proses asesmen.',
                'status' => $isMapa01Done ? 'DONE' : ($isMapa01Rejected ? 'REJECTED' : 'WAITING'),
                'status_label' => $isMapa01Done ? 'Diterima' : ($isMapa01Rejected ? 'Ditolak' : 'Menunggu'),
                'verify_url' => route('mapa01.index', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('mapa01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => true,
                'can_pdf' => true
            ];

            // FR.MAPA.02
            $statusMapa02 = $dataSertifikasi->rekomendasi_mapa02;
            $isMapa02Done = $statusMapa02 == 'diterima';
            $isMapa02Rejected = $statusMapa02 == 'tidak diterima';

            $praAsesmenItems[] = [
                'id' => 'MAPA02',
                'title' => 'FR.MAPA.02 - Peta Instrumen',
                'desc' => 'Peta instrumen asesmen yang akan digunakan.',
                'status' => $isMapa02Done ? 'DONE' : ($isMapa02Rejected ? 'REJECTED' : 'WAITING'),
                'status_label' => $isMapa02Done ? 'Diterima' : ($isMapa02Rejected ? 'Ditolak' : 'Menunggu'),
                'verify_url' => route('mapa02.show', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('mapa02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => true, 
                'can_pdf' => true
            ];

            // FR.APL.02
            $isApl02Done = $level >= 30;
            $praAsesmenItems[] = [
                'id' => 'APL02',
                'title' => 'FR.APL.02 - Asesmen Mandiri',
                'desc' => 'Asesmen mandiri oleh asesi.',
                'status' => $isApl02Done ? 'DONE' : ($level >= 20 ? 'WAITING' : 'LOCKED'),
                'status_label' => $dataSertifikasi->rekomendasi_apl02 == 'diterima' ? 'Diterima' : ($level >= 20 ? 'Menunggu' : 'Terkunci'),
                'verify_url' => route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('apl02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => $level >= 20,
                'can_pdf' => $level >= 20
            ];

            // FR.AK.01
            $statusAk01 = $dataSertifikasi->rekomendasi_ak01;
            $isAk01Done = $statusAk01 == 'diterima';
            $isAk01Rejected = $statusAk01 == 'tidak diterima';
            
            $praAsesmenItems[] = [
                'id' => 'AK01',
                'title' => 'FR.AK.01 - Persetujuan & Kerahasiaan',
                'desc' => 'Persetujuan asesmen dan kerahasiaan.',
                'status' => $isAk01Done ? 'DONE' : ($isAk01Rejected ? 'REJECTED' : 'WAITING'),
                'status_label' => $isAk01Done ? 'Diterima' : ($isAk01Rejected ? 'Ditolak' : 'Menunggu'),
                'verify_url' => route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('ak01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => true,
                'can_pdf' => $isAk01Done
            ];


            // 2. Pelaksanaan Asesmen Items
            $pelaksanaanItems = [];

            // Helper to check if unlocking is allowed (AK01 Accepted OR Level >= 40)
            // Using AK01 recommendation directly is safer than level
            $isPelaksanaanUnlocked = ($dataSertifikasi->rekomendasi_ak01 == 'diterima') || ($level >= 40);

            // IA.05
            $ia05Filled = $dataSertifikasi->lembarJawabIa05()->whereNotNull('pencapaian_ia05')->exists();
            $statusIa05 = $dataSertifikasi->rekomendasi_ia05;
            $isIa05Done = $statusIa05 == 'diterima';
            $isIa05Rejected = $statusIa05 == 'tidak diterima';
            
            $stIa05 = $isIa05Done ? 'DONE' : ($isIa05Rejected ? 'REJECTED' : ($ia05Filled ? 'WAITING' : ($isPelaksanaanUnlocked ? 'ACTIVE' : 'LOCKED')));
            
            $pelaksanaanItems[] = [
                'id' => 'IA05',
                'title' => 'FR.IA.05 - Pertanyaan Tertulis',
                'desc' => 'Daftar pertanyaan tertulis esai.',
                'status' => $stIa05,
                'status_label' => $isIa05Done ? 'Diterima' : ($isIa05Rejected ? 'Ditolak' : ($ia05Filled ? 'Menunggu' : ($stIa05 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'))),
                'verify_url' => route('FR_IA_05_C', $asesi->id_asesi),
                'pdf_url' => route('ia05.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => $stIa05 !== 'LOCKED',
                'can_pdf' => $level >= 40
            ];

            // IA.10
            $ia10Filled = $dataSertifikasi->ia10()->exists();
            $statusIa10 = $dataSertifikasi->rekomendasi_ia10;
            $isIa10Done = $statusIa10 == 'diterima';
            $isIa10Rejected = $statusIa10 == 'tidak diterima';
            
            $stIa10 = $isIa10Done ? 'DONE' : ($isIa10Rejected ? 'REJECTED' : ($ia10Filled ? 'WAITING' : ($isPelaksanaanUnlocked ? 'ACTIVE' : 'LOCKED')));

            $pelaksanaanItems[] = [
                'id' => 'IA10',
                'title' => 'FR.IA.10 - Verifikasi Pihak Ketiga',
                'desc' => 'Verifikasi portofolio dari pihak ketiga.',
                'status' => $stIa10,
                'status_label' => $isIa10Done ? 'Diterima' : ($isIa10Rejected ? 'Ditolak' : ($ia10Filled ? 'Menunggu' : ($stIa10 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'))),
                'verify_url' => route('fr-ia-10.create', $asesi->id_asesi),
                'pdf_url' => route('ia10.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => $stIa10 !== 'LOCKED',
                'can_pdf' => $level >= 40
            ];

            // IA.02
            $ia02Filled = $dataSertifikasi->ia02()->exists();
            $statusIa02 = $dataSertifikasi->rekomendasi_ia02;
            $isIa02Done = $statusIa02 == 'diterima';
            $isIa02Rejected = $statusIa02 == 'tidak diterima';

            $stIa02 = $isIa02Done ? 'DONE' : ($isIa02Rejected ? 'REJECTED' : ($ia02Filled ? 'WAITING' : ($isPelaksanaanUnlocked ? 'ACTIVE' : 'LOCKED')));

            $pelaksanaanItems[] = [
                'id' => 'IA02',
                'title' => 'FR.IA.02 - Tugas Praktik Demonstrasi',
                'desc' => 'Ceklis observasi tugas praktik.',
                'status' => $stIa02,
                'status_label' => $isIa02Done ? 'Diterima' : ($isIa02Rejected ? 'Ditolak' : ($ia02Filled ? 'Menunggu' : ($stIa02 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'))),
                'verify_url' => route('fr-ia-02.show', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('ia02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => $stIa02 !== 'LOCKED',
                'can_pdf' => $level >= 40
            ];

            // IA.06
            $ia06Filled = $dataSertifikasi->ia06Answers()->whereNotNull('pencapaian')->exists(); 
            $statusIa06 = $dataSertifikasi->rekomendasi_ia06;
            $isIa06Done = $statusIa06 == 'diterima';
            $isIa06Rejected = $statusIa06 == 'tidak diterima';

            $stIa06 = $isIa06Done ? 'DONE' : ($isIa06Rejected ? 'REJECTED' : ($ia06Filled ? 'WAITING' : ($isPelaksanaanUnlocked ? 'ACTIVE' : 'LOCKED')));

            $pelaksanaanItems[] = [
                'id' => 'IA06',
                'title' => 'FR.IA.06 - Pertanyaan Lisan',
                'desc' => 'Daftar pertanyaan lisan.',
                'status' => $stIa06,
                'status_label' => $isIa06Done ? 'Diterima' : ($isIa06Rejected ? 'Ditolak' : ($ia06Filled ? 'Menunggu' : ($stIa06 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'))),
                'verify_url' => route('asesor.ia06.edit', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('ia06.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => $stIa06 !== 'LOCKED',
                'can_pdf' => $level >= 40
            ];

            // IA.07
            $ia07Filled = $dataSertifikasi->ia07()->whereNotNull('pencapaian')->exists();
            $statusIa07 = $dataSertifikasi->rekomendasi_ia07;
            $isIa07Done = $statusIa07 == 'diterima';
            $isIa07Rejected = $statusIa07 == 'tidak diterima';

            $stIa07 = $isIa07Done ? 'DONE' : ($isIa07Rejected ? 'REJECTED' : ($ia07Filled ? 'WAITING' : ($isPelaksanaanUnlocked ? 'ACTIVE' : 'LOCKED')));

            $pelaksanaanItems[] = [
                'id' => 'IA07',
                'title' => 'FR.IA.07 - Daftar Pertanyaan Lisan',
                'desc' => 'Daftar pertanyaan lisan (alternatif).',
                'status' => $stIa07,
                'status_label' => $isIa07Done ? 'Diterima' : ($isIa07Rejected ? 'Ditolak' : ($ia07Filled ? 'Menunggu' : ($stIa07 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'))),
                'verify_url' => route('FR_IA_07'),
                'pdf_url' => route('ia07.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => $stIa07 !== 'LOCKED',
                'can_pdf' => $level >= 40
            ];

            // AK.02
            // Must use $isIaXXDone (Verification status) or $iaXXFilled (Submission status)?
            // Assuming we require Verification before decision:
            $allIADone = ($isIa05Done && $isIa10Done && $isIa02Done && $isIa06Done && $isIa07Done);
            
            $pelaksanaanItems[] = [
                'id' => 'AK02',
                'title' => 'FR.AK.02 - Keputusan Asesmen',
                'desc' => 'Rekaman keputusan asesmen kompetensi.',
                'status' => $isFinalized ? 'DONE' : ($allIADone ? 'ACTIVE' : 'LOCKED'),
                'status_label' => $isFinalized ? 'Final / Terkirim' : ($allIADone ? 'Siap Diputuskan' : 'Terkunci'),
                'verify_url' => route('asesor.ak02.edit', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('ak02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => $allIADone,
                'can_pdf' => $isFinalized
            ];

        @endphp

        {{-- MAIN CONTENT WITH ALPINE --}}
        <div x-data="{ 
                activeItem: null,
                showAllPra: false,
                showAllPelaksanaan: false,
                praItems: {{ json_encode($praAsesmenItems) }},
                pelItems: {{ json_encode($pelaksanaanItems) }},
                embedUrl: null,
                showEmbed: false,
                loading: false,
                openVerify(url) {
                    if (!url) return;
                    this.embedUrl = url + (url.includes('?') ? '&' : '?') + 'embed=true';
                    this.showEmbed = true;
                    // Scroll to embed view
                    setTimeout(() => {
                        document.getElementById('embed-section').scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                },
                selectItem(item) {
                    this.activeItem = item;
                    // Automatically open verify if available
                    if (item.verify_url) {
                        this.openVerify(item.verify_url);
                    }
                },
                async updateStatus(action) {
                    if (!this.activeItem) {
                        alert('Dokumen belum dipilih.');
                        return;
                    }
                    
                    if (!confirm('Apakah anda yakin ingin mengubah status dokumen ini?')) return;

                    this.loading = true;
                    const endpoint = `{{ route('asesor.document.verify', $asesor->id_asesor) }}`;
                    const items = this.praItems.concat(this.pelItems);
                    
                    try {
                        let response = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id_data_sertifikasi_asesi: '{{ $dataSertifikasi->id_data_sertifikasi_asesi }}',
                                document_id: this.activeItem.id,
                                action: action
                            })
                        });

                        let result = await response.json();

                        if (result.success) {
                            // Update UI
                            this.activeItem.status_label = result.new_label;
                            this.activeItem.status = 'DONE'; // Force done status or depend on backend
                            if (result.new_status === 'ditolak') {
                                this.activeItem.status = 'REJECTED'; // Custom status if needed
                            }
                            alert(result.message);
                        } else {
                            alert(result.message);
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Terjadi kesalahan saat memproses permintaan.');
                    } finally {
                        this.loading = false;
                    }
                }
             }"
             class="space-y-8">

            {{-- SECTION 1: TOP TRACKERS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- PRA ASESMEN LIST --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-800">Pra Asesmen</h2>
                        <button @click="showAllPra = !showAllPra" 
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1"
                                x-show="praItems.length > 2">
                            <span x-text="showAllPra ? 'Sembunyikan' : 'Lihat Semua'"></span>
                            <i class="fas" :class="showAllPra ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in praItems" :key="item.id">
                            <div x-show="showAllPra || index < 2" 
                                 class="p-4 rounded-xl border transition-all duration-200 cursor-pointer group"
                                 :class="activeItem && activeItem.id === item.id ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-gray-100 hover:border-blue-300 hover:shadow-sm bg-gray-50'"
                                 @click="selectItem(item)">
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold"
                                             :class="item.status === 'DONE' ? 'bg-green-100 text-green-600' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-600' : (item.status === 'WAITING' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-200 text-gray-500'))">
                                            <i class="fas" :class="item.status === 'DONE' ? 'fa-check' : (item.status === 'REJECTED' ? 'fa-times' : (item.status === 'WAITING' ? 'fa-clock' : 'fa-lock'))"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-700 transition" x-text="item.title"></h3>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-medium"
                                                  :class="item.status === 'DONE' ? 'bg-green-100 text-green-700' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-700' : (item.status === 'WAITING' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-500'))"
                                                  x-text="item.status_label"></span>
                                        </div>
                                    </div>
                                    <button class="w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 hover:border-blue-400 flex items-center justify-center transition shadow-sm">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- PELAKSANAAN ASESMEN LIST --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 {{ $level < 40 ? 'opacity-75' : '' }}">
                    <div class="flex items-center justify-between mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-800">Pelaksanaan Asesmen</h2>
                        <button @click="showAllPelaksanaan = !showAllPelaksanaan" 
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1"
                                x-show="pelItems.length > 2">
                            <span x-text="showAllPelaksanaan ? 'Sembunyikan' : 'Lihat Semua'"></span>
                            <i class="fas" :class="showAllPelaksanaan ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in pelItems" :key="item.id">
                            <div x-show="showAllPelaksanaan || index < 2" 
                                 class="p-4 rounded-xl border transition-all duration-200 cursor-pointer group"
                                 :class="activeItem && activeItem.id === item.id ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-gray-100 hover:border-blue-300 hover:shadow-sm bg-gray-50'"
                                 @click="selectItem(item)">
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold"
                                             :class="item.status === 'DONE' ? 'bg-green-100 text-green-600' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-600' : (item.status === 'ACTIVE' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-200 text-gray-500'))">
                                            <i class="fas" :class="item.status === 'DONE' ? 'fa-check' : (item.status === 'REJECTED' ? 'fa-times' : (item.status === 'ACTIVE' ? 'fa-pen' : 'fa-lock'))"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-700 transition" x-text="item.title"></h3>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-medium"
                                                  :class="item.status === 'DONE' ? 'bg-green-100 text-green-700' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-700' : (item.status === 'ACTIVE' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-500'))"
                                                  x-text="item.status_label"></span>
                                        </div>
                                    </div>
                                    <button class="w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 hover:border-blue-400 flex items-center justify-center transition shadow-sm">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- SECTION 2: BOTTOM DETAIL VIEW --}}
            <div class="mt-8">
                
                {{-- EMPTY STATE --}}
                <div x-show="!activeItem" 
                     x-transition:enter="transition ease-out duration-500"
                     class="flex flex-col items-center justify-center text-gray-400 py-16 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                        <i class="fas fa-mouse-pointer text-2xl text-blue-300"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-600">Belum ada formulir yang dipilih</p>
                    <p class="text-sm">Silakan klik tombol "Lihat" pada salah satu kartu di atas untuk melihat detail.</p>
                </div>

                {{-- DETAIL CARD --}}
                <div x-show="activeItem" 
                     x-transition:enter="transition ease-out duration-300 transform" 
                     x-transition:enter-start="opacity-0 translate-y-4" 
                     class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden relative"
                     x-cloak>
                    
                    {{-- Card Header --}}
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-white">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-file-alt text-2xl text-white"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold" x-text="activeItem?.title"></h2>
                                    <p class="text-blue-100 text-sm mt-1" x-text="activeItem?.desc"></p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-sm border border-white/30"
                                  x-text="activeItem?.status_label"></span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-8">
                        <div class="prose max-w-none text-gray-600 mb-8">
                            <p class="p-4 bg-blue-50 border border-blue-100 rounded-lg text-blue-800 flex items-start gap-3">
                                <i class="fas fa-info-circle mt-1"></i>
                                <span>
                                    Anda sedang melihat detail untuk <strong x-text="activeItem?.title"></strong>. 
                                    Silakan gunakan tombol verifikasi di bawah untuk melakukan penilaian atau validasi data asesi.
                                </span>
                            </p>
                        </div>

                        {{-- EMBED SECTION (Moved Inside) --}}
                        <div x-show="showEmbed" 
                             class="mb-8 border border-gray-200 rounded-xl overflow-hidden shadow-sm"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2">
                            
                            {{-- Embed Toolbar --}}
                            <div class="bg-gray-100 px-4 py-2 flex items-center justify-between border-b border-gray-200">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pratinjau Dokumen</span>
                                <div class="flex items-center gap-2">
                                     <a :href="embedUrl" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 flex items-center gap-1 font-medium">
                                        <i class="fas fa-external-link-alt"></i> Buka Fullscreen
                                    </a>
                                </div>
                            </div>

                            <div class="relative w-full h-[800px] bg-gray-50">
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400 z-0">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-circle-notch fa-spin text-3xl mb-2 text-blue-500"></i>
                                        <span class="text-sm">Memuat Dokumen...</span>
                                    </div>
                                </div>
                                <iframe :src="embedUrl" class="relative z-10 w-full h-full border-0" allowfullscreen></iframe>
                            </div>
                        </div>

                        {{-- Action Buttons (Bottom Right) --}}
                        <div class="flex justify-end items-center gap-4 pt-6 border-t border-gray-100">
                            
                            {{-- PDF Button --}}
                            <a :href="activeItem?.pdf_url" target="_blank"
                               class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all flex items-center gap-2"
                               :class="activeItem?.can_pdf ? 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-gray-900' : 'bg-gray-50 text-gray-300 cursor-not-allowed pointer-events-none'"
                               :disabled="loading">
                                <i class="fas fa-file-pdf text-red-500"></i>
                                Download PDF
                            </a>

                            {{-- Tolak Verifikasi Button (Enabled) --}}
                            <button type="button" 
                                    @click="updateStatus('reject')"
                                    :disabled="loading"
                                    class="px-6 py-2.5 rounded-lg font-bold text-sm shadow-sm flex items-center gap-2 bg-red-100 text-red-600 hover:bg-red-200 transition">
                                <i class="fas fa-times-circle"></i>
                                Tolak Verifikasi
                                <i x-show="loading" class="fas fa-spinner fa-spin ml-2"></i>
                            </button>

                            {{-- Verifikasi Button (Global Action) --}}
                            <button @click="updateStatus('verify')"
                                    type="button"
                                    :disabled="loading || !activeItem?.can_verify"
                                    class="px-6 py-2.5 rounded-lg font-bold text-sm shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2"
                                    :class="activeItem?.can_verify && !loading ? 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg' : 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none'">
                                <i class="fas fa-check-double"></i>
                                Verifikasi Formulir
                                <i x-show="loading" class="fas fa-spinner fa-spin ml-2"></i>
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

      </div>
    </main>
  </div>
</body>
</html>