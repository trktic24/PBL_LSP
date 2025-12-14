<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asesor Bukti Kelengkapan | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" /> 
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  @php use Illuminate\Support\Facades\Storage; @endphp

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="text-gray-800">

  {{-- PERBAIKAN: Menggunakan Navbar Admin --}}
  <x-navbar.navbar_admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- 1. Panggil Component Sidebar Asesor --}}
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    {{-- 2. Konten Utama (Main Section) --}}
    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full">
        
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Bukti Kelengkapan</h2>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="mb-6 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg flex justify-between items-center">
                <span class="font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-900 hover:text-green-700"><i class="fas fa-times"></i></button>
            </div>
        @endif

        <div class="space-y-6">
          
          @foreach ($documents as $doc)
          <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden transition-shadow hover:shadow-md" x-data="{ open: true }">
            
            <div @click="open = !open" class="flex items-center justify-between px-6 py-4 bg-gray-50 cursor-pointer select-none group">
              <div>
                <h3 class="font-semibold text-gray-700 group-hover:text-blue-600 transition-colors">{{ $doc['title'] }}</h3>
                <p class="text-sm text-gray-500">{{ $doc['subtitle'] }}</p>
              </div>
              <div class="flex items-center space-x-4">
                @if ($doc['file_path'])
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> 1 berkas
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-1"></i> 0 berkas
                  </span>
                @endif
                <i class="fas text-gray-400 transition-transform duration-300 group-hover:text-blue-500" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
              </div>
            </div>
            
            <div x-show="open" x-transition class="p-6 border-t border-gray-200 bg-white">
              @if ($doc['file_path'])
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 flex items-center justify-center bg-blue-50 text-blue-600 font-bold rounded-lg text-sm border border-blue-100 uppercase tracking-wide">
                    .{{ pathinfo($doc['file_path'], PATHINFO_EXTENSION) }}
                  </div>
                  
                  <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 text-sm truncate" title="{{ basename($doc['file_path']) }}">
                        {{ basename($doc['file_path']) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5 flex items-center">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> File telah diupload
                    </p>
                  </div>
                  
                  <div class="flex items-center space-x-2">
                    {{-- Tombol View --}}
                    <a href="{{ Storage::url($doc['file_path']) }}" target="_blank"
                       class="bg-blue-500 hover:bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-sm transition-transform hover:scale-110" 
                       title="Lihat File">
                      <i class="fas fa-eye text-xs"></i>
                    </a>

                    {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
                    <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-sm transition-transform hover:scale-110"
                       title="Edit File">
                      <i class="fas fa-pen text-xs"></i>
                    </a>
                    
                    {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
                    <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}"
                       class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-sm transition-transform hover:scale-110"
                       title="Hapus File">
                      <i class="fas fa-trash text-xs"></i>
                    </a>
                  </div>
                </div>
              @else
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                  <div class="flex items-center text-gray-500">
                      <i class="fas fa-file-upload text-xl mr-3 text-gray-400"></i>
                      <span class="text-sm font-medium">Belum ada dokumen yang diunggah.</span>
                  </div>
                  {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
                  <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}"
                     class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-upload mr-2"></i> Upload Sekarang
                  </a>
                </div>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      
        <div class="mt-10 p-10 border border-gray-200 rounded-2xl shadow-md relative">
          
          <div class="absolute top-6 right-6 flex space-x-2">
            {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
            <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}" 
               class="group bg-white border border-gray-200 hover:border-yellow-400 text-gray-600 hover:text-yellow-600 font-medium px-3 py-1.5 rounded-lg text-xs flex items-center shadow-sm transition-all duration-200">
              <i class="fas fa-edit mr-1.5 group-hover:scale-110 transition-transform"></i> Edit
            </a>
             {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
             <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}" 
               class="group bg-white border border-gray-200 hover:border-red-500 text-gray-600 hover:text-red-500 font-medium px-3 py-1.5 rounded-lg text-xs flex items-center shadow-sm transition-all duration-200">
              <i class="fas fa-trash mr-1.5 group-hover:scale-110 transition-transform"></i> Hapus
            </a>
          </div>

          <h3 class="text-2xl font-bold text-gray-800 mb-8 text-center">Tanda Tangan Pemohon</h3>
          
          <p class="font-semibold text-gray-800 mb-4 text-center md:text-left">Saya yang bertanda tangan di bawah ini:</p>

          <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 mb-6">
              <div class="space-y-3 text-sm text-gray-700">
                <div class="flex flex-col sm:flex-row">
                    <span class="font-bold w-36 inline-block text-gray-900">Nama</span> 
                    <span class="hidden sm:inline font-bold mr-2">:</span> 
                    <span>{{ $asesor->nama_lengkap }}</span>
                </div>
                <div class="flex flex-col sm:flex-row">
                    <span class="font-bold w-36 inline-block text-gray-900">Pekerjaan</span> 
                    <span class="hidden sm:inline font-bold mr-2">:</span> 
                    <span>{{ $asesor->pekerjaan }}</span>
                </div>
                <div class="flex flex-col sm:flex-row">
                    <span class="font-bold w-36 inline-block text-gray-900">Alamat Korespondensi</span> 
                    <span class="hidden sm:inline font-bold mr-2">:</span> 
                    <span>{{ $asesor->alamat_rumah ?? '-' }}</span>
                </div>
              </div>
          </div>

          <p class="mt-6 text-gray-600 text-sm mb-8 leading-relaxed">
            Dengan ini saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan. 
            Dokumen ini digunakan sebagai bukti pemenuhan syarat Sertifikasi Kompetensi.
          </p>

          <div class="flex flex-col items-center justify-center">
              <div class="group relative mt-2 border-2 border-dashed border-gray-300 hover:border-blue-400 rounded-xl w-full max-w-md h-48 flex items-center justify-center bg-gray-50 transition-colors duration-300">
                  @if($asesor->tanda_tangan)
                    <img src="{{ Storage::url($asesor->tanda_tangan) }}" alt="Tanda Tangan" class="object-contain h-32 transition-transform duration-300 group-hover:scale-105">
                  @else
                    <div class="text-center p-4">
                        <i class="fas fa-signature text-gray-300 text-4xl mb-2"></i>
                        <p class="text-xs text-red-500 font-medium bg-red-50 px-3 py-1 rounded-full">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Tanda Tangan belum diupload
                        </p>
                    </div>
                  @endif
                  
                  @if(!$asesor->tanda_tangan)
                  {{-- PERBAIKAN ROUTE: Menambahkan prefix admin. --}}
                  <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}" class="absolute inset-0 flex items-center justify-center bg-white/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
                      <span class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md">Upload Tanda Tangan</span>
                  </a>
                  @endif
              </div>
              <p class="text-xs text-gray-400 mt-3 font-medium italic">*Tanda Tangan Digital yang sah</p>
          </div>

        </div>

        {{-- NEW: Tombol Verifikasi (Accepted / Rejected) --}}
        <div class="mt-8 flex flex-col sm:flex-row justify-end items-center gap-4">
            
            {{-- PERBAIKAN ROUTE: Menggunakan admin.asesor.update_status --}}
            <form action="{{ route('admin.asesor.update_status', $asesor->id_asesor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak verifikasi asesor ini?');">
                @csrf
                <input type="hidden" name="status_verifikasi" value="rejected">
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-times-circle mr-2"></i> Rejected Asesor
                </button>
            </form>

            {{-- PERBAIKAN ROUTE: Menggunakan admin.asesor.update_status --}}
            <form action="{{ route('admin.asesor.update_status', $asesor->id_asesor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui verifikasi asesor ini?');">
                @csrf
                <input type="hidden" name="status_verifikasi" value="approved">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Accepted Asesor
                </button>
            </form>
        </div>
        
      </div>
    </main>
  </div>
</body>
</html>