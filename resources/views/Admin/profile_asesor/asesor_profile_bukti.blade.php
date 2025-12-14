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

          <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
          
          <div class="space-y-4">
          @foreach ($documents as $doc)
          <div x-data="{ expanded: false }" class="border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
            
            <!-- Accordion Header -->
            <div @click="expanded = !expanded" class="bg-gray-50 p-4 flex items-center justify-between cursor-pointer hover:bg-gray-100 transition select-none">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-colors duration-300"
                         class="{{ $doc['file_path'] ? 'bg-green-100 text-green-600' : 'bg-white border border-gray-300 text-gray-400' }}">
                        <i class="fas {{ $doc['file_path'] ? 'fa-check' : 'fa-file-upload' }}"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">
                            {{ $doc['title'] }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $doc['subtitle'] }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if ($doc['file_path'])
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-800">
                            Terupload
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-800">
                            Kosong
                        </span>
                    @endif
                    <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="{'rotate-180': expanded}"></i>
                </div>
            </div>

            <!-- Accordion Body -->
            <div x-show="expanded" x-collapse class="bg-white border-t border-gray-200">
                <div class="p-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-blue-50 hover:border-blue-300 transition relative group">
                        
                        @if ($doc['file_path'])
                            <!-- File Preview Logic -->
                            @php
                                $ext = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                            @endphp

                            @if ($isImage)
                                <div class="relative w-full mb-4">
                                    <img src="{{ Storage::url($doc['file_path']) }}" class="max-h-64 mx-auto rounded-lg shadow-sm object-contain">
                                </div>
                            @else
                                <div class="mb-4">
                                    <i class="fas fa-file-pdf text-red-500 text-5xl"></i>
                                    <p class="mt-2 text-sm font-medium text-gray-700">{{ basename($doc['file_path']) }}</p>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-center space-x-3 mt-2">
                                <a href="{{ Storage::url($doc['file_path']) }}" target="_blank" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg shadow-sm transition flex items-center">
                                    <i class="fas fa-eye mr-2"></i> Lihat
                                </a>
                                <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}" 
                                   class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-medium rounded-lg shadow-sm transition flex items-center">
                                    <i class="fas fa-pen mr-2"></i> Edit
                                </a>
                                <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}" 
                                   class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg shadow-sm transition flex items-center">
                                    <i class="fas fa-trash mr-2"></i> Hapus
                                </a>
                            </div>

                        @else
                            <!-- Empty State / Upload Placeholder -->
                            <a href="{{ route('admin.edit_asesor3', $asesor->id_asesor) }}" class="space-y-3 cursor-pointer w-full block">
                                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Belum ada dokumen</p>
                                    <p class="text-xs text-gray-400 mt-1">Klik untuk mengunggah dokumen ini</p>
                                </div>
                            </a>
                        @endif

                    </div>
                </div>
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
          
          <div class="bg-gray-50 rounded-xl p-6 max-w-3xl mx-auto border border-gray-200 mb-8">
              <p class="text-sm font-semibold text-gray-800 mb-4">Saya yang bertanda tangan di bawah ini:</p>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                  {{-- Item 1 --}}
                  <div>
                      <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                      <p class="text-gray-900 font-medium text-sm">{{ $asesor->nama_lengkap }}</p>
                  </div>
                  
                  {{-- Item 2 --}}
                  <div>
                      <label class="block text-xs text-gray-500 mb-1">Pekerjaan</label>
                      <p class="text-gray-900 font-medium text-sm">{{ $asesor->pekerjaan }}</p>
                  </div>
                  
                  {{-- Item 3 --}}
                  <div class="md:col-span-2">
                      <label class="block text-xs text-gray-500 mb-1">Alamat Korespondensi</label>
                      <p class="text-gray-900 font-medium text-sm">{{ $asesor->alamat_rumah ?? '-' }}</p>
                  </div>
              </div>

              <div class="mt-5 pt-4 border-t border-gray-200 text-gray-600 text-[11px] leading-relaxed">
                  Dengan ini saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan. 
                  Dokumen ini digunakan sebagai bukti pemenuhan syarat Sertifikasi Kompetensi.
              </div>
          </div>

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