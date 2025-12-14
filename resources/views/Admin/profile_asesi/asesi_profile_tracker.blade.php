@php
    // --- DEFINISI LEVEL (Sama dengan Asesi) ---
    $level = $asesi->dataSertifikasi->first()->progres_level ?? 0;
    
    $LVL_DAFTAR_SELESAI = 10;
    $LVL_TUNGGU_BAYAR = 20;
    $LVL_LUNAS = 30;
    $LVL_PRA_ASESMEN = 40;
    $LVL_SETUJU = 50;
    $LVL_ASESMEN = 70;
    $LVL_UMPAN_BALIK = 80;
    $LVL_BANDING = 90;
    $LVL_REKOMENDASI = 100;

    // --- Helper Checkmark ---
    if (!function_exists('renderCheckmark')) {
        function renderCheckmark() {
            return '<div class="absolute -top-1 -left-1.5 z-10 bg-green-500 rounded-full p-0.5 border-2 border-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>';
        }
    }
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tracker Sertifikasi Asesi | LSP Polines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 text-sm">

  <x-navbar.navbar_admin/>
  
  <main class="flex min-h-[calc(100vh-80px)]">
    
    @php
        $firstSertifikasi = $asesi->dataSertifikasi->first();
        $defaultBackUrl = route('admin.master_asesi'); 
    @endphp

    <x-sidebar.sidebar_profile_asesi 
        :asesi="$asesi" 
        :backUrl="$firstSertifikasi ? route('admin.schedule.attendance', $firstSertifikasi->id_jadwal) : $defaultBackUrl" 
    />

    <section class="ml-[22%] flex-1 p-8 h-[calc(100vh-80px)] overflow-y-auto bg-gray-50">
      
      <div class="bg-white p-10 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 min-h-[500px]">
        
        <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">Tracker Status Sertifikasi</h1>

        @if($firstSertifikasi)
            <div class="max-w-3xl mx-auto px-4">
                
                <ol class="relative z-10 border-l-2 border-gray-200 ml-4 space-y-8">

                    {{-- ITEM 1: Formulir Pendaftaran --}}
                    <li class="mb-10 ml-8 relative">
                        <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 
                                    {{ $level >= $LVL_DAFTAR_SELESAI ? '!border-green-500 bg-green-500' : '' }}">
                            @if($level >= $LVL_DAFTAR_SELESAI) {!! renderCheckmark() !!} @endif
                        </div>
                        <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                            Pendaftaran Akun & Formulir
                            @if($level >= $LVL_DAFTAR_SELESAI)
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span>
                            @endif
                        </h3>
                        <p class="mb-2 text-sm font-normal text-gray-500">
                            Mengisi data diri dan formulir APL-01.
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $firstSertifikasi->tanggal_daftar ? $firstSertifikasi->tanggal_daftar->format('d M Y, H:i') : '-' }}
                        </p>
                    </li>

                    {{-- ITEM 2: Pembayaran --}}
                    <li class="mb-10 ml-8 relative">
                        <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 
                                    {{ $level >= $LVL_LUNAS ? '!border-green-500 bg-green-500' : '' }}">
                            @if($level >= $LVL_LUNAS) {!! renderCheckmark() !!} @endif
                        </div>
                        <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                            Pembayaran
                            @if($level >= $LVL_LUNAS)
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Lunas</span>
                            @elseif($level == $LVL_TUNGGU_BAYAR)
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Menunggu Verifikasi</span>
                            @endif
                        </h3>
                        <p class="text-sm font-normal text-gray-500">Biaya sertifikasi telah dibayarkan.</p>
                    </li>

                    {{-- ITEM 3: Pra-Asesmen --}}
                    <li class="mb-10 ml-8 relative">
                        <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 
                                    {{ $level >= $LVL_PRA_ASESMEN ? '!border-green-500 bg-green-500' : '' }}">
                            @if($level >= $LVL_PRA_ASESMEN) {!! renderCheckmark() !!} @endif
                        </div>
                        <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Pra-Asesmen</h3>
                        <p class="text-sm font-normal text-gray-500">Pengisian Asesmen Mandiri (APL-02).</p>
                    </li>

                    {{-- ITEM 4: Jadwal & TUK --}}
                    <li class="mb-10 ml-8 relative">
                        <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 
                                    {{ $level >= $LVL_PRA_ASESMEN ? '!border-green-500 bg-green-500' : '' }}">
                             @if($level >= $LVL_PRA_ASESMEN) {!! renderCheckmark() !!} @endif
                        </div>
                        <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Jadwal & TUK</h3>
                        <p class="text-sm font-normal text-gray-500">Penetapan jadwal dan Tempat Uji Kompetensi.</p>
                    </li>

                    {{-- ITEM 5: Asesmen --}}
                    <li class="mb-10 ml-8 relative">
                        <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 
                                    {{ $level >= $LVL_ASESMEN ? '!border-green-500 bg-green-500' : '' }}">
                             @if($level >= $LVL_ASESMEN) {!! renderCheckmark() !!} @endif
                        </div>
                        <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                            Proses Asesmen
                            @if($level >= $LVL_ASESMEN)
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span>
                            @elseif($level >= $LVL_SETUJU)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Berlangsung</span>
                            @endif
                        </h3>
                        <p class="text-sm font-normal text-gray-500">Uji Kompetensi (Tertulis, Praktik, Wawancara).</p>
                    </li>

                    {{-- ITEM 6: Keputusan Komite --}}
                    <li class="mb-10 ml-8 relative">
                        <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 
                                    {{ $level >= $LVL_REKOMENDASI ? '!border-green-500 bg-green-500' : '' }}">
                             @if($level >= $LVL_REKOMENDASI) {!! renderCheckmark() !!} @endif
                        </div>
                        <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                            Keputusan Komite
                            @if($level == $LVL_REKOMENDASI)
                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Kompeten</span>
                            @endif
                        </h3>
                        <p class="text-sm font-normal text-gray-500">Hasil akhir rekomendasi sertifikasi.</p>
                    </li>

                </ol>

            </div>
        @else
            {{-- Jika Belum Ada Data --}}
            <div class="text-center py-20">
                <i class="fas fa-clipboard-list text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada data sertifikasi untuk asesi ini.</p>
            </div>
        @endif

      </div>

    </section>
  </main>

</body>
</html>