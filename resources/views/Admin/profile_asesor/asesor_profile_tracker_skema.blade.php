@php
    // Helper Checkmark
    if (!function_exists('renderCheckmark')) {
        function renderCheckmark() {
            return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>';
        }
    }

    // Unify data source
    $finalTimelineData = $schemeTimelineData ?? $timelineData ?? [];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lacak Aktivitas Skema | LSP Polines</title> 
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>
<body class="text-gray-800" x-data="{ activeUrl: null, activeTitle: '' }">

  <x-navbar.navbar-admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 h-fit">
        
        {{-- Header Page --}}
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ route('admin.asesor.tracker', $asesor->id_asesor) }}" class="absolute left-0 top-1 text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Detail Aktivitas Skema</h1>
                <p class="text-sm text-gray-500 mt-1">Lacak progres asesmen untuk skema ini.</p>
            </div>
        </div>
        @if(isset($jadwal))
        <p class="text-sm text-gray-500 mb-8">
            Timeline aktivitas untuk Skema: 
            <span class="font-semibold text-gray-700">{{ $jadwal->skema->nama_skema ?? '-' }}</span> 
            (Jadwal: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d M Y') }})
        </p>
        @endif

        @if(empty($finalTimelineData) && !isset($dataSertifikasi))
            <div class="text-center py-20 text-gray-500">
                <i class="fas fa-info-circle text-4xl mb-3 text-gray-300"></i>
                <p>Belum ada data aktivitas timeline.</p>
            </div>
        @else
            <div class="max-w-4xl mx-auto px-4 mt-8">
                
                {{-- MODE 1: SINGLE ASESI VIEW --}}
                @if(isset($dataSertifikasi))
                    @php
                        $level = $dataSertifikasi->level_status;
                        $isFinalized = ($level >= 100);

                        function adminBtnState($isDone) {
                            return $isDone 
                                ? 'bg-green-100 text-green-700 hover:bg-green-200 cursor-pointer' 
                                : 'bg-blue-100 text-blue-600 hover:bg-blue-200 cursor-pointer';
                        }
                    @endphp

                    <div class="mb-8">
                         <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-clipboard-check text-blue-600 mr-2"></i> Pra-Asesmen
                        </h3>
                        <div class="relative border-l-2 border-gray-200 ml-6 space-y-8 pb-8">

                            {{-- 1. APL-01 --}}
                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm
                                    {{ $dataSertifikasi->rekomendasi_apl01 == 'diterima' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                                    @if($dataSertifikasi->rekomendasi_apl01 == 'diterima') <i class="fas fa-check text-xs"></i> @else <span class="font-bold text-[10px]">01</span> @endif
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">FR.APL.01 - Pendaftaran</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Permohonan Sertifikasi Kompetensi</p>
                                        </div>
                                        <a href="{{ route('APL_01_1', $dataSertifikasi->id_data_sertifikasi_asesi) }}" 
                                           @click.prevent="activeUrl = '{{ route('APL_01_1', $dataSertifikasi->id_data_sertifikasi_asesi) }}'; activeTitle = 'FR.APL.01 - Pendaftaran'; $nextTick(() => $refs.previewSection.scrollIntoView({behavior: 'smooth'}))"
                                           class="text-[10px] font-bold py-1 px-3 rounded-md {{ adminBtnState($dataSertifikasi->rekomendasi_apl01 == 'diterima') }}">
                                            Verifikasi
                                        </a>
                                    </div>
                                    <div class="mt-2 text-xs">
                                        @if($dataSertifikasi->rekomendasi_apl01 == 'diterima') 
                                            <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded font-medium">Diterima</span>
                                        @else 
                                            <span class="bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded font-medium">Menunggu</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- 2. MAPA-01 --}}
                            @php $isMapa01 = $level >= 20; @endphp
                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm
                                    {{ $isMapa01 ? 'bg-green-500 text-white' : 'bg-gray-300 text-white' }}">
                                    @if($isMapa01) <i class="fas fa-check text-xs"></i> @else <span class="font-bold text-[10px]">M1</span> @endif
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">FR.MAPA.01 - Merencanakan</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Merencanakan Aktivitas dan Proses Asesmen</p>
                                        </div>
                                        <a href="{{ route('mapa01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}" 
                                           @click.prevent="activeUrl = '{{ route('mapa01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}'; activeTitle = 'FR.MAPA.01 - Merencanakan Aktivitas'; $nextTick(() => $refs.previewSection.scrollIntoView({behavior: 'smooth'}))"
                                           class="text-[10px] font-bold py-1 px-3 rounded-md {{ adminBtnState($isMapa01) }}">
                                            Verifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. MAPA-02 --}}
                            @php $isMapa02 = $level >= 20; @endphp
                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm
                                    {{ $isMapa02 ? 'bg-green-500 text-white' : 'bg-gray-300 text-white' }}">
                                    @if($isMapa02) <i class="fas fa-check text-xs"></i> @else <span class="font-bold text-[10px]">M2</span> @endif
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">FR.MAPA.02 - Peta Instrumen</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Peta Instrumen Asesmen</p>
                                        </div>
                                        <a href="{{ route('mapa02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}" 
                                           @click.prevent="activeUrl = '{{ route('mapa02.show', $dataSertifikasi->id_data_sertifikasi_asesi) }}'; activeTitle = 'FR.MAPA.02 - Peta Instrumen'; $nextTick(() => $refs.previewSection.scrollIntoView({behavior: 'smooth'}))"
                                           class="text-[10px] font-bold py-1 px-3 rounded-md {{ adminBtnState($isMapa02) }}">
                                            Verifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. APL-02 --}}
                            @php $isApl02 = $dataSertifikasi->rekomendasi_apl02 == 'diterima'; @endphp
                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm
                                    {{ $isApl02 ? 'bg-green-500 text-white' : 'bg-gray-300 text-white' }}">
                                    @if($isApl02) <i class="fas fa-check text-xs"></i> @else <span class="font-bold text-[10px]">02</span> @endif
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">FR.APL.02 - Asesmen Mandiri</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Asesmen Mandiri Asesi</p>
                                        </div>
                                        <a href="{{ route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi) }}" 
                                           @click.prevent="activeUrl = '{{ route('asesor.apl02', $dataSertifikasi->id_data_sertifikasi_asesi) }}'; activeTitle = 'FR.APL.02 - Asesmen Mandiri'; $nextTick(() => $refs.previewSection.scrollIntoView({behavior: 'smooth'}))"
                                           class="text-[10px] font-bold py-1 px-3 rounded-md {{ adminBtnState($isApl02) }}">
                                            Verifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- 5. AK-01 --}}
                            @php $isAk01 = ($level >= 40 || $dataSertifikasi->status_sertifikasi == 'persetujuan_asesmen_disetujui'); @endphp
                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm
                                    {{ $isAk01 ? 'bg-green-500 text-white' : 'bg-gray-300 text-white' }}">
                                    @if($isAk01) <i class="fas fa-check text-xs"></i> @else <span class="font-bold text-[10px]">AK1</span> @endif
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">FR.AK.01 - Persetujuan</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Persetujuan Asesmen dan Kerahasiaan</p>
                                        </div>
                                        <a href="{{ route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}" 
                                           @click.prevent="activeUrl = '{{ route('ak01.index', $dataSertifikasi->id_data_sertifikasi_asesi) }}'; activeTitle = 'FR.AK.01 - Persetujuan'; $nextTick(() => $refs.previewSection.scrollIntoView({behavior: 'smooth'}))"
                                           class="text-[10px] font-bold py-1 px-3 rounded-md {{ adminBtnState($isAk01) }}">
                                            Verifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- SECTION: ASESMEN --}}
                    <div class="mb-8">
                         <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-edit text-purple-600 mr-2"></i> Asesmen
                        </h3>
                        <div class="relative border-l-2 border-gray-200 ml-6 space-y-8 pb-8">
                            
                            {{-- 6. PELAKSANAAN ASESMEN --}}
                            @php 
                                $hasAk05 = $dataSertifikasi->komentarAk05()->exists(); 
                                $isAsesmenStart = $level >= 40;
                            @endphp
                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm
                                    {{ $hasAk05 ? 'bg-green-500 text-white' : ($isAsesmenStart ? 'bg-blue-500 text-white' : 'bg-gray-300 text-white') }}">
                                    <i class="fas fa-pen-nib text-xs"></i>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">Pelaksanaan Asesmen</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Hasil Observasi & Tes Tertulis (IA.02, IA.05, dll)</p>
                                        </div>
                                        <a href="{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $dataSertifikasi->id_data_sertifikasi_asesi]) }}" 
                                           @click.prevent="activeUrl = '{{ route('admin.asesor.assessment.detail', ['id_asesor' => $asesor->id_asesor, 'id_data_sertifikasi_asesi' => $dataSertifikasi->id_data_sertifikasi_asesi]) }}'; activeTitle = 'Pelaksanaan Asesmen'; $nextTick(() => $refs.previewSection.scrollIntoView({behavior: 'smooth'}))"
                                           class="text-[10px] font-bold py-1 px-3 rounded-md bg-purple-100 text-purple-600 hover:bg-purple-200">
                                            Lihat Detail
                                        </a>
                                    </div>
                                    <div class="mt-2 text-xs">
                                        @if($hasAk05)
                                            <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded font-medium">Selesai Dinilai</span>
                                        @elseif($isAsesmenStart)
                                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-medium">Sedang Berlangsung</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- 7. KEPUTUSAN (AK-02) --}}
                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm
                                    {{ $isFinalized ? 'bg-green-600 text-white' : 'bg-gray-300 text-white' }}">
                                    <span class="font-bold text-[10px]">AK2</span>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">FR.AK.02 - Keputusan</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Keputusan Asesmen & Umpan Balik</p>
                                        </div>
                                        <a href="{{ route('asesor.ak02.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}" 
                                           @click.prevent="activeUrl = '{{ route('asesor.ak02.edit', $dataSertifikasi->id_data_sertifikasi_asesi) }}'; activeTitle = 'FR.AK.02 - Keputusan'; $nextTick(() => $refs.previewSection.scrollIntoView({behavior: 'smooth'}))"
                                           class="text-[10px] font-bold py-1 px-3 rounded-md {{ adminBtnState($isFinalized) }}">
                                            Verifikasi
                                        </a>
                                    </div>
                                    <div class="mt-2 text-xs">
                                        @if($isFinalized)
                                            <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded font-medium">Final</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                {{-- MODE 2: GENERIC AGGREGATE VIEW (FALLBACK / SCHEDULE TRACKER) --}}
                @else
                    <div class="relative border-l-2 border-gray-200 ml-6 space-y-8 pb-8">
                        @foreach($finalTimelineData as $index => $item)
                            @php
                                $isCompleted = $item['is_completed'] ?? false;
                                if (!isset($item['is_completed'])) {
                                    $statusLower = strtolower($item['status_text'] ?? '');
                                    if (str_contains($statusLower, 'selesai') || str_contains($statusLower, 'diterima') || str_contains($statusLower, 'kompeten') || str_contains($statusLower, 'disetujui') || str_contains($statusLower, 'jadwal dibuat') || str_contains($statusLower, 'dapat dicetak')) {
                                        $isCompleted = true;
                                    }
                                }
                                $iconBg = $isCompleted ? 'bg-green-500 text-white' : 'bg-gray-300 text-white';
                            @endphp

                            <div class="relative pl-12 group">
                                <div class="absolute -left-[17px] top-0 z-10 w-9 h-9 rounded-full flex items-center justify-center border-4 border-white shadow-sm {{ $iconBg }}">
                                    @if(isset($item['icon']) && str_contains($item['icon'], 'fa-')) 
                                        <i class="{{ $item['icon'] }} text-xs"></i>
                                    @elseif($isCompleted)
                                        <i class="fas fa-check text-xs"></i>
                                    @else
                                        <span class="font-bold text-[10px]">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                
                                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">{{ $item['title'] ?? 'Aktivitas' }}</h4>
                                            @php
                                                $statusText = $item['status_text'] ?? '';
                                                $isShortStatus = in_array(strtolower($statusText), ['selesai', 'terjadwal', 'berlangsung', 'kompeten', 'belum kompeten', 'diterima', 'menunggu', 'siap dibuat', 'dapat dicetak', 'disetujui', 'jadwal dibuat']);
                                            @endphp
                                            
                                            @if(!$isShortStatus && $statusText != '-' && $statusText != '')
                                                <p class="text-xs text-gray-500 mt-1">{{ $statusText }}</p>
                                            @endif
                                            
                                            <p class="text-[10px] text-gray-400 mt-2">
                                                <i class="far fa-calendar-alt mr-1"></i> {{ $item['date'] ?? '-' }}
                                            </p>
                                        </div>

                                        @if($isShortStatus && $statusText != '-')
                                            <span class="text-[10px] px-2 py-0.5 rounded font-medium {{ $isCompleted ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600' }}">
                                                {{ $statusText }}
                                            </span>
                                        @endif
                                    </div>
    
                                    @if(isset($item['action_url']))
                                        <div class="mt-3">
                                            <a href="{{ $item['action_url'] }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-medium rounded-md hover:bg-blue-100 transition border border-blue-100">
                                                <i class="fas fa-eye mr-1.5"></i> {{ $item['action_label'] ?? 'Lihat Detail' }}
                                            </a>
                                        </div>
                                    @endif
    
                                    @if(isset($item['sub_items']) && is_array($item['sub_items']))
                                        <div class="mt-3 pt-3 border-t border-gray-50">
                                            <ul class="space-y-1">
                                                @foreach($item['sub_items'] as $sub)
                                                    <li class="text-xs text-gray-600 flex items-center">
                                                        <i class="fas fa-angle-right mr-2 text-gray-400"></i>
                                                        <span class="font-medium mr-1">{{ $sub['title'] }}:</span> {{ $sub['status'] }} ({{ $sub['date'] }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- INLINE IFRAME PREVIEW SECTION --}}
        <div x-show="activeUrl" x-transition.opacity 
             x-ref="previewSection"
             class="mt-12 bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            
            {{-- Header Preview --}}
            <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg" x-text="activeTitle"></h3>
                        <p class="text-sm text-gray-500">Mode Pratinjau & Verifikasi</p>
                    </div>
                </div>
                <button @click="activeUrl = null" class="text-gray-400 hover:text-red-500 transition duration-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            {{-- Iframe Content --}}
            <div class="relative bg-gray-100 p-4 min-h-[800px]">
                <div class="absolute inset-0 flex items-center justify-center z-0 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-4xl"></i>
                    <span class="ml-3 font-medium">Memuat Dokumen...</span>
                </div>
                {{-- z-10 ensures iframe is on top of loader --}}
                <iframe :src="activeUrl" class="w-full h-[800px] rounded-xl shadow-inner border border-gray-200 relative z-10 bg-white"></iframe>
            </div>
        </div>

      </div>
    </main>
  </div>
</body>
</html>