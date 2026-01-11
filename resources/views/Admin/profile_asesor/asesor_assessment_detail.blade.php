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
  <script>
    document.addEventListener("alpine:init", () => {
        Alpine.store("sidebar", {
            open: true,
            toggle() {
                this.open = !this.open
            },
            setOpen(val) {
                this.open = val
            }
        })
    })
  </script>
  <style>[x-cloak] { display: none !important; }</style>
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
            
            // CONFIG SHOW/HIDE
            $listForm = optional($dataSertifikasi->jadwal->skema->listForm);
            $show = [
                'ia01' => $listForm->fr_ia_01 ?? 1,
                'ia02' => $listForm->fr_ia_02 ?? 1,
                'ia03' => $listForm->fr_ia_03 ?? 1,
                'ia04' => $listForm->fr_ia_04 ?? 1,
                'ia05' => $listForm->fr_ia_05 ?? 1,
                'ia06' => $listForm->fr_ia_06 ?? 1,
                'ia07' => $listForm->fr_ia_07 ?? 1,
                'ia08' => $listForm->fr_ia_08 ?? 1,
                'ia09' => $listForm->fr_ia_09 ?? 1,
                'ia10' => $listForm->fr_ia_10 ?? 1,
                'ia11' => $listForm->fr_ia_11 ?? 1,
            ];

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
                'status' => $isMapa01Done ? 'DONE' : ($isMapa01Rejected ? 'REJECTED' : 'ACTIVE'),
                'status_label' => $isMapa01Done ? 'Diterima' : ($isMapa01Rejected ? 'Ditolak' : 'Belum Terisi'),
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
                'status' => $isMapa02Done ? 'DONE' : ($isMapa02Rejected ? 'REJECTED' : 'ACTIVE'),
                'status_label' => $isMapa02Done ? 'Diterima' : ($isMapa02Rejected ? 'Ditolak' : 'Belum Terisi'),
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
                'status' => $isApl02Done ? 'DONE' : 'LOCKED',
                'status_label' => $dataSertifikasi->rekomendasi_apl02 == 'diterima' ? 'Diterima' : 'Terkunci',
                'verify_url' => route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('apl02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => false,
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
                'status' => $isAk01Done ? 'DONE' : ($isAk01Rejected ? 'REJECTED' : 'ACTIVE'),
                'status_label' => $isAk01Done ? 'Diterima' : ($isAk01Rejected ? 'Ditolak' : 'Belum Terisi'),
                'verify_url' => route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('ak01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => true,
                'can_pdf' => $isAk01Done
            ];


            // 2. Pelaksanaan Asesmen Items
            $pelaksanaanItems = [];

            // IA.01
            if ($show['ia01'] == 1) {
                $ia01Done = optional($dataSertifikasi->ia01)->exists() ?? false;
                $stIa01 = $ia01Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');
                
                $pelaksanaanItems[] = [
                    'id' => 'IA01',
                    'title' => 'FR.IA.01 - Ceklis Observasi Aktivitas',
                    'desc' => 'Daftar ceklis observasi untuk asesmen.',
                    'status' => $stIa01,
                    'status_label' => $ia01Done ? 'Sudah Dinilai' : ($stIa01 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'),
                    'verify_url' => route('ia01.admin.view', $dataSertifikasi->id_sertifikasi ?? $dataSertifikasi->id_data_sertifikasi_asesi), // Note: Verify route for admin might differ
                    'pdf_url' => route('ia01.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => false, // View only typically for IA01 in Admin unless specified
                    'can_pdf' => true
                ];
            }

            // IA.02
            if ($show['ia02'] == 1) {
                // Logic updated: IA02 is considered done if IA01 is done (mirroring tracker) OR if ia02 exists
                $ia02Done = (optional($dataSertifikasi->ia02)->exists() ?? false);
                $statusIa02 = $dataSertifikasi->rekomendasi_ia02;
                $isIa02Done = $statusIa02 == 'diterima';
                $isIa02Rejected = $statusIa02 == 'tidak diterima';
                $stIa02 = $isIa02Done ? 'DONE' : ($isIa02Rejected ? 'REJECTED' : ($ia02Done ? 'WAITING' : 'ACTIVE'));

                $pelaksanaanItems[] = [
                    'id' => 'IA02',
                    'title' => 'FR.IA.02 - Tugas Praktik Demonstrasi',
                    'desc' => 'Ceklis observasi tugas praktik.',
                    'status' => $stIa02,
                    'status_label' => $isIa02Done ? 'Diterima' : ($isIa02Rejected ? 'Ditolak' : ($ia02Done ? 'Menunggu' : 'Belum Terisi')),
                    'verify_url' => route('fr-ia-02.show', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'pdf_url' => route('ia02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => $stIa02 !== 'LOCKED' && $stIa02 !== 'ACTIVE',
                    'can_pdf' => true
                ];
            }

            // IA.03
            if ($show['ia03'] == 1) {
                $ia03Done = optional($dataSertifikasi->ia03)->exists() ?? false;
                $stIa03 = $ia03Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

                $pelaksanaanItems[] = [
                    'id' => 'IA03',
                    'title' => 'FR.IA.03 - Pertanyaan Mendukung Observasi',
                    'desc' => 'Daftar pertanyaan untuk mendukung observasi.',
                    'status' => $stIa03,
                    'status_label' => $ia03Done ? 'Sudah Dinilai' : ($stIa03 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'),
                    'verify_url' => route('ia03.index', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'pdf_url' => route('ia03.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => false,
                    'can_pdf' => true
                ];
            }

            // IA.04
            if ($show['ia04'] == 1) {
                $ia04Done = optional($dataSertifikasi->ia04)->exists() ?? false;
                $stIa04 = $ia04Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

                $pelaksanaanItems[] = [
                    'id' => 'IA04',
                    'title' => 'FR.IA.04 - Penjelasan Singkat Proyek',
                    'desc' => 'Daftar pertanyaan terkait penjelasan proyek.',
                    'status' => $stIa04,
                    'status_label' => $ia04Done ? 'Sudah Dinilai' : ($stIa04 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'),
                    'verify_url' => route('fria04a.show', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'pdf_url' => route('ia04.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => false,
                    'can_pdf' => true
                ];
            }

            // IA.05
            if ($show['ia05'] == 1) {
                $ia05Filled = $dataSertifikasi->lembarJawabIa05()->whereNotNull('pencapaian_ia05')->exists();
                $statusIa05 = $dataSertifikasi->rekomendasi_ia05;
                $isIa05Done = $statusIa05 == 'diterima';
                $isIa05Rejected = $statusIa05 == 'tidak diterima';
                $stIa05 = $isIa05Done ? 'DONE' : ($isIa05Rejected ? 'REJECTED' : ($ia05Filled ? 'WAITING' : 'ACTIVE'));
                
                $pelaksanaanItems[] = [
                    'id' => 'IA05',
                    'title' => 'FR.IA.05 - Pertanyaan Tertulis',
                    'desc' => 'Daftar pertanyaan tertulis esai.',
                    'status' => $stIa05,
                    'status_label' => $isIa05Done ? 'Diterima' : ($isIa05Rejected ? 'Ditolak' : ($ia05Filled ? 'Menunggu' : 'Belum Terisi')),
                    'verify_url' => route('FR_IA_05_C', $asesi->id_asesi),
                    'pdf_url' => route('ia05.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => $stIa05 !== 'LOCKED' && $stIa05 !== 'ACTIVE',
                    'can_pdf' => true
                ];
            }

            // IA.06
            if ($show['ia06'] == 1) {
                $ia06Filled = $dataSertifikasi->ia06Answers()->whereNotNull('pencapaian')->exists(); 
                $statusIa06 = $dataSertifikasi->rekomendasi_ia06;
                $isIa06Done = $statusIa06 == 'diterima';
                $isIa06Rejected = $statusIa06 == 'tidak diterima';
                $stIa06 = $isIa06Done ? 'DONE' : ($isIa06Rejected ? 'REJECTED' : 'LOCKED'); // IA06 is interview, likely filled by asesor

                $pelaksanaanItems[] = [
                    'id' => 'IA06',
                    'title' => 'FR.IA.06 - Pertanyaan Lisan',
                    'desc' => 'Daftar pertanyaan lisan.',
                    'status' => $stIa06,
                    'status_label' => $isIa06Done ? 'Diterima' : ($isIa06Rejected ? 'Ditolak' : 'Terkunci/Proses'),
                    'verify_url' => route('asesor.ia06.edit', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'pdf_url' => route('ia06.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => false,
                    'can_pdf' => true
                ];
            }

            // IA.07
            if ($show['ia07'] == 1) {
                $ia07Filled = $dataSertifikasi->ia07()->whereNotNull('pencapaian')->exists();
                $statusIa07 = $dataSertifikasi->rekomendasi_ia07;
                $isIa07Done = $statusIa07 == 'diterima';
                $isIa07Rejected = $statusIa07 == 'tidak diterima';
                $stIa07 = $isIa07Done ? 'DONE' : ($isIa07Rejected ? 'REJECTED' : ($ia07Filled ? 'WAITING' : 'ACTIVE'));

                $pelaksanaanItems[] = [
                    'id' => 'IA07',
                    'title' => 'FR.IA.07 - Daftar Pertanyaan Lisan',
                    'desc' => 'Daftar pertanyaan lisan (alternatif).',
                    'status' => $stIa07,
                    'status_label' => $isIa07Done ? 'Diterima' : ($isIa07Rejected ? 'Ditolak' : ($ia07Filled ? 'Menunggu' : 'Belum Terisi')),
                    'verify_url' => route('FR_IA_07', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'pdf_url' => route('ia07.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => $stIa07 !== 'LOCKED' && $stIa07 !== 'ACTIVE',
                    'can_pdf' => $level >= 40
                ];
            }

            // IA.08
            if ($show['ia08'] == 1) {
                $ia08Done = optional($dataSertifikasi->ia08)->exists() ?? false;
                $stIa08 = $ia08Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

                $pelaksanaanItems[] = [
                    'id' => 'IA08',
                    'title' => 'FR.IA.08 - Ceklis Verifikasi Portofolio',
                    'desc' => 'Verifikasi portofolio asesi.',
                    'status' => $stIa08,
                    'status_label' => $ia08Done ? 'Sudah Dinilai' : ($stIa08 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'),
                    'verify_url' => route('ia08.show', $dataSertifikasi->id_data_sertifikasi_asesi), // Assuming route exists
                    'pdf_url' => route('ia08.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => false,
                    'can_pdf' => true
                ];
            }

            // IA.09
            if ($show['ia09'] == 1) {
                $ia09Done = !empty(optional($dataSertifikasi->ia09)->pencapaian_ia09);
                $stIa09 = $ia09Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

                $pelaksanaanItems[] = [
                    'id' => 'IA09',
                    'title' => 'FR.IA.09 - Pertanyaan Wawancara',
                    'desc' => 'Pertanyaan wawancara dengan asesi.',
                    'status' => $stIa09,
                    'status_label' => $ia09Done ? 'Sudah Dinilai' : ($stIa09 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'),
                    'verify_url' => route('ia09.admin.view', $dataSertifikasi->id_data_sertifikasi_asesi), // Using Admin View route defined in auth.php
                    'pdf_url' => route('ia09.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => false,
                    'can_pdf' => true
                ];
            }

            // IA.10
            if ($show['ia10'] == 1) {
                $ia10Filled = $dataSertifikasi->ia10()->exists();
                $statusIa10 = $dataSertifikasi->rekomendasi_ia10;
                $isIa10Done = $statusIa10 == 'diterima';
                $isIa10Rejected = $statusIa10 == 'tidak diterima';
                $stIa10 = $isIa10Done ? 'DONE' : ($isIa10Rejected ? 'REJECTED' : ($ia10Filled ? 'WAITING' : 'ACTIVE'));

                $pelaksanaanItems[] = [
                    'id' => 'IA10',
                    'title' => 'FR.IA.10 - Verifikasi Pihak Ketiga',
                    'desc' => 'Verifikasi portofolio dari pihak ketiga.',
                    'status' => $stIa10,
                    'status_label' => $isIa10Done ? 'Diterima' : ($isIa10Rejected ? 'Ditolak' : ($ia10Filled ? 'Menunggu' : 'Belum Terisi')),
                    'verify_url' => route('fr-ia-10.create', $asesi->id_asesi),
                    'pdf_url' => route('ia10.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => $stIa10 !== 'LOCKED' && $stIa10 !== 'ACTIVE',
                    'can_pdf' => true
                ];
            }

            // IA.11
            if ($show['ia11'] == 1) {
                $ia11Done = optional($dataSertifikasi->ia11)->exists() ?? false;
                $stIa11 = $ia11Done ? 'DONE' : ($level >= 40 ? 'ACTIVE' : 'LOCKED');

                $pelaksanaanItems[] = [
                    'id' => 'IA11',
                    'title' => 'FR.IA.11 - Ceklis Meninjau Instrumen',
                    'desc' => 'Ceklis tinjauan instrumen asesmen.',
                    'status' => $stIa11,
                    'status_label' => $ia11Done ? 'Sudah Dinilai' : ($stIa11 == 'ACTIVE' ? 'Belum Dinilai' : 'Terkunci'),
                    'verify_url' => route('ia11.index', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'pdf_url' => route('ia11.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                    'can_verify' => false,
                    'can_pdf' => true
                ];
            }

            // AK.02
            $pelaksanaanItems[] = [
                'id' => 'AK02',
                'title' => 'FR.AK.02 - Keputusan Asesmen',
                'desc' => 'Rekaman keputusan asesmen kompetensi.',
                'status' => $isFinalized ? 'DONE' : 'LOCKED',
                'status_label' => $isFinalized ? 'Final / Terkirim' : 'Terkunci',
                'verify_url' => route('ak02.edit', $dataSertifikasi->id_data_sertifikasi_asesi),
                'pdf_url' => route('ak02.cetak_pdf', $dataSertifikasi->id_data_sertifikasi_asesi),
                'can_verify' => false, 
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
            <div class="grid grid-cols-1 gap-8">
                
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
                                             :class="item.status === 'DONE' ? 'bg-green-100 text-green-600' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-600' : (item.status === 'WAITING' ? 'bg-yellow-100 text-yellow-600' : (item.status === 'ACTIVE' ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500')))">
                                            <i class="fas" :class="item.status === 'DONE' ? 'fa-check' : (item.status === 'REJECTED' ? 'fa-times' : (item.status === 'WAITING' ? 'fa-clock' : (item.status === 'ACTIVE' ? 'fa-pen' : 'fa-lock')))"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-700 transition" x-text="item.title"></h3>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-medium"
                                                  :class="item.status === 'DONE' ? 'bg-green-100 text-green-700' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-700' : (item.status === 'WAITING' ? 'bg-yellow-100 text-yellow-700' : (item.status === 'ACTIVE' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-500')))"
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
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
                                             :class="item.status === 'DONE' ? 'bg-green-100 text-green-600' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-600' : (item.status === 'WAITING' ? 'bg-yellow-100 text-yellow-600' : (item.status === 'ACTIVE' ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500')))">
                                            <i class="fas" :class="item.status === 'DONE' ? 'fa-check' : (item.status === 'REJECTED' ? 'fa-times' : (item.status === 'WAITING' ? 'fa-clock' : (item.status === 'ACTIVE' ? 'fa-pen' : 'fa-lock')))"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-700 transition" x-text="item.title"></h3>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-medium"
                                                  :class="item.status === 'DONE' ? 'bg-green-100 text-green-700' : (item.status === 'REJECTED' ? 'bg-red-100 text-red-700' : (item.status === 'WAITING' ? 'bg-yellow-100 text-yellow-700' : (item.status === 'ACTIVE' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-500')))"
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

                            {{-- Tolak Verifikasi Button (Enabled only for Verifiable items) --}}
                            <button type="button" 
                                    @click="updateStatus('reject')"
                                    :disabled="loading || !activeItem?.can_verify"
                                    x-show="activeItem && ['DONE', 'REJECTED', 'WAITING', 'LOCKED', 'ACTIVE'].includes(activeItem.status)"
                                    class="px-6 py-2.5 rounded-lg font-bold text-sm shadow-sm flex items-center gap-2 transition"
                                    :class="activeItem?.can_verify && !loading ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none'">
                                <i class="fas fa-times-circle"></i>
                                Tolak Verifikasi
                                <i x-show="loading" class="fas fa-spinner fa-spin ml-2"></i>
                            </button>

                            {{-- Verifikasi Button (Global Action) --}}
                            <button @click="updateStatus('verify')"
                                    type="button"
                                    x-show="activeItem && ['DONE', 'REJECTED', 'WAITING', 'LOCKED', 'ACTIVE'].includes(activeItem.status)"
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