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

  {{-- 1. NAVBAR --}}
  <x-navbar.navbar_admin/>
  
  <main class="flex min-h-[calc(100vh-80px)]">
    
    {{-- LOGIKA TOMBOL KEMBALI (Sama seperti halaman Bukti) --}}
    @php
        $firstSertifikasi = $asesi->dataSertifikasi->first();
        $defaultBackUrl = route('admin.master_asesi'); 
    @endphp

    {{-- 2. SIDEBAR --}}
    <x-sidebar.sidebar_profile_asesi 
        :asesi="$asesi" 
        :backUrl="$firstSertifikasi ? route('admin.schedule.attendance', $firstSertifikasi->id_jadwal) : $defaultBackUrl" 
    />

    {{-- 3. KONTEN UTAMA --}}
    <section class="ml-[22%] flex-1 p-8 h-[calc(100vh-80px)] overflow-y-auto bg-gray-50">
      
      {{-- 4. CARD PUTIH UTAMA (Style konsisten dengan halaman Bukti) --}}
      <div class="bg-white p-10 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 min-h-[500px]">
        
        {{-- JUDUL --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-10 text-center">Tracker Status Sertifikasi</h1>

        {{-- AREA KONTEN TRACKER (Placeholder Visual Timeline) --}}
        <div class="max-w-3xl mx-auto">
            
            {{-- Contoh Item 1: Selesai --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-md z-10">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <div class="w-0.5 h-full bg-green-200 -mt-1 -mb-1"></div>
                </div>
                <div class="pb-10">
                    <h3 class="text-base font-bold text-gray-800">Pendaftaran Akun</h3>
                    <p class="text-xs text-gray-500 mt-1">12 Desember 2024, 09:00 WIB</p>
                    <div class="mt-2 bg-green-50 text-green-700 px-3 py-2 rounded-lg text-xs border border-green-100">
                        Akun berhasil dibuat dan diverifikasi.
                    </div>
                </div>
            </div>

            {{-- Contoh Item 2: Selesai --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-md z-10">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <div class="w-0.5 h-full bg-gray-200 -mt-1 -mb-1"></div>
                </div>
                <div class="pb-10">
                    <h3 class="text-base font-bold text-gray-800">Upload Dokumen Persyaratan</h3>
                    <p class="text-xs text-gray-500 mt-1">14 Desember 2024, 14:30 WIB</p>
                    <div class="mt-2 bg-green-50 text-green-700 px-3 py-2 rounded-lg text-xs border border-green-100">
                        Semua dokumen wajib telah diunggah.
                    </div>
                </div>
            </div>

            {{-- Contoh Item 3: Proses Sekarang (Aktif) --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg ring-4 ring-blue-100 z-10">
                        <i class="fas fa-spinner fa-spin text-xs"></i>
                    </div>
                    <div class="w-0.5 h-full bg-gray-200 -mt-1 -mb-1"></div>
                </div>
                <div class="pb-10">
                    <h3 class="text-base font-bold text-blue-600">Verifikasi Admin</h3>
                    <p class="text-xs text-gray-500 mt-1">Sedang berlangsung...</p>
                    <div class="mt-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg text-xs border border-blue-100">
                        Admin sedang memverifikasi kelengkapan dokumen (APL-01 & APL-02).
                    </div>
                </div>
            </div>

            {{-- Contoh Item 4: Belum --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 z-10">
                        <span class="text-xs font-bold">4</span>
                    </div>
                </div>
                <div class="pb-10">
                    <h3 class="text-base font-bold text-gray-400">Pembayaran</h3>
                    <p class="text-xs text-gray-400 mt-1">Menunggu verifikasi selesai</p>
                </div>
            </div>

        </div>

      </div>

    </section>
  </main>

</body>
</html>