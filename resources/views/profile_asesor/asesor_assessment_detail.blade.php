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
  </style>
</head>

<body class="text-gray-800">

  <x-navbar />
  
  {{-- Layout Utama Flex --}}
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- Sidebar --}}
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    {{-- Konten Utama --}}
    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-12 border border-gray-100 min-h-full">
        
        {{-- Header Page --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Asesmen</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Asesi: <span class="font-bold text-blue-600">{{ $asesi->nama_lengkap ?? 'Nama Asesi' }}</span>
                </p>
            </div>
            
            {{-- Tombol Kembali --}}
            <a href="{{ url()->previous() }}" class="flex items-center text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        @php
            $level = $dataSertifikasi->level_status; 
            $LVL_APL01_SUBMIT = 10;
            $LVL_APL01_VERIF = 20; 
            $LVL_APL02_VERIF    = 30; 
            $LVL_SIAP_ASESMEN = 40; 
            $LVL_SELESAI_IA   = 90; 
            $LVL_LULUS        = 100; 

            function getStepStatus($currentLevel, $targetDone, $targetActive) {
                if ($currentLevel >= $targetDone) return 'DONE';
                if ($currentLevel >= $targetActive) return 'ACTIVE';
                return 'LOCKED';
            }
        @endphp

        <div class="max-w-6xl mx-auto">

            {{-- SECTION 1: PRA-ASESMEN --}}
            <section id="pra-asesmen">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Pra Asesmen</h2>
                    <div class="relative">
                        
                        <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                        {{-- ITEM 1: FR.APL.01 --}}
                        @php $stAPL01 = getStepStatus($level, 20, $LVL_APL01_SUBMIT); @endphp
                        <div class="relative pl-20 pb-8 group">
                            @if($stAPL01 == 'DONE') <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div> @endif
                            <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                                {{ $stAPL01 == 'DONE' ? 'bg-green-500 text-white' : ($stAPL01 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                                <span class="font-bold text-xs">01</span>
                            </div>
                            <div>
                                <div class="flex flex-col justify-start items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.APL.01 - Permohonan Sertifikasi Kompetensi</h3>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <a href="#" class="bg-green-100 text-green-700 hover:bg-green-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-check"></i> Verifikasi</a>
                                        <a href="#" class="bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-times"></i> Tolak</a>
                                        <a href="#" class="bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-eye"></i> Lihat</a>
                                        <a href="#" class="bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                                <p class="text-xs text-green-500 mt-1">Diterima</p>
                            </div>
                        </div>

                        {{-- ITEM 2: FR.APL.02 --}}
                        @php $stAPL02 = getStepStatus($level, $LVL_APL01_VERIF, $LVL_APL02_VERIF); @endphp
                        <div class="relative pl-20 pb-8 group">
                            @if($stAPL02 == 'DONE') <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div> @endif
                            <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white
                                {{ $stAPL02 == 'DONE' ? 'bg-green-500 text-white' : ($stAPL02 == 'ACTIVE' ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-400') }}">
                                <span class="font-bold text-xs">02</span>
                            </div>
                            <div>
                                <div class="flex flex-col justify-start items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.APL.02 - Asesmen Mandiri</h3>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <a href="#" class="bg-green-100 text-green-700 hover:bg-green-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-check"></i> Verifikasi</a>
                                        <a href="#" class="bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-times"></i> Tolak</a>
                                        <a href="#" class="bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-eye"></i> Lihat</a>
                                        <a href="#" class="bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                                <p class="text-xs text-green-500 mt-1">Diterima</p>
                            </div>
                        </div>

                        {{-- ITEM: FR.MAPA.01 --}}
                        <div class="relative pl-20 pb-8 group">
                            <div class="absolute left-6 top-4 h-full w-0.5 bg-green-500 z-0"></div>
                            <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white bg-green-500 text-white">
                                <span class="font-bold text-xs">03</span>
                            </div>
                            <div>
                                <div class="flex flex-col justify-start items-start">
                                    <h3 class="text-lg font-semibold text-gray-800">FR.MAPA.01 - Merencanakan Aktivitas</h3>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <a href="#" class="bg-green-100 text-green-700 hover:bg-green-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-check"></i> Verifikasi</a>
                                        <a href="#" class="bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-times"></i> Tolak</a>
                                        <a href="#" class="bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-eye"></i> Lihat</a>
                                        <a href="#" class="bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                                <p class="text-xs text-green-500 mt-1">Diterima</p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- SECTION 2: ASESMEN REAL --}}
            <section id="asesmen" class="mt-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Pelaksanaan Asesmen</h2>
                    <div class="relative">
                        <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-gray-200" aria-hidden="true"></div>

                        {{-- IA.05 --}}
                        <div class="relative pl-20 pb-8">
                            <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white bg-yellow-400 text-white">
                                <span class="font-bold text-[10px]">IA.05</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">FR.IA.05 - Pertanyaan Tertulis</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <a href="#" class="bg-green-100 text-green-700 hover:bg-green-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-check"></i> Verifikasi</a>
                                    <a href="#" class="bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-times"></i> Tolak</a>
                                    <a href="#" class="bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-eye"></i> Lihat</a>
                                    <a href="#" class="bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold py-1 px-3 rounded-md flex items-center gap-1 transition"><i class="fas fa-file-pdf"></i> PDF</a>
                                    <a href="#" class="w-full mt-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 rounded-md text-center">Lakukan Penilaian</a>
                                </div>
                            </div>
                        </div>

                        {{-- IA.02 --}}
                        <div class="relative pl-20 pb-8">
                            <div class="absolute left-0 top-2 z-10 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white bg-gray-200 text-gray-500">
                                <span class="font-bold text-[10px]">IA.02</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-400">FR.IA.02 - Demonstrasi Praktik</h3>
                                <div class="flex flex-wrap gap-2 mt-2 opacity-50">
                                    <span class="text-xs text-gray-400 italic">Belum tersedia</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </div>
      </div>
    </main>
  </div>
</body>
</html>