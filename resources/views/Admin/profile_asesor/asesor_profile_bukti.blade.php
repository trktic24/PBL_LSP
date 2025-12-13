<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asesor Bukti Kelengkapan | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  {{-- Tambahan: Load Storage facade untuk URL --}}
  @php use Illuminate\Support\Facades\Storage; @endphp

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="text-gray-800">

  <x-navbar.navbar_admin/>
  <div class="flex pt-0">
    
    <aside class="fixed top-[80px] left-0 h-[calc(100vh-80px)] w-[22%] 
                 bg-gradient-to-b from-[#e8f0ff] via-[#f3f8ff] to-[#ffffff]
                 shadow-inner border-r border-gray-200 flex flex-col items-center pt-8">

      <h2 class="text-lg font-bold text-gray-900 mb-3">Biodata</h2>

      <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white shadow-[0_0_15px_rgba(0,0,0,0.2)] mb-4">
        {{-- Foto profil dinamis --}}
        <img src="{{ $asesor->pas_foto ? Storage::url($asesor->pas_foto) : asset('images/asesi.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
      </div>

      {{-- Nama dinamis --}}
      <h3 class="text-lg font-semibold text-gray-900">{{ $asesor->nama_lengkap }}</h3>
      <p class="text-gray-500 text-sm mb-8">Asesor</p>

      <div class="w-[90%] bg-white/40 backdrop-blur-md rounded-2xl p-4 
                  shadow-[0_0_15px_rgba(0,0,0,0.15)] mb-6">
        
        <div class="flex flex-col space-y-4 mt-3 mb-3">
            {{-- Link 'Profile Settings' dinamis --}}
            <a href="{{ route('asesor.profile', $asesor->id_asesor) }}" 
               class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->routeIs('asesor.profile') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-user-gear text-l mr-3"></i> Profile Settings
            </a>

            {{-- Link 'Tinjauan' dan 'Tracker' statis (sesuai routes/web.php) --}}
            <a href="{{ route('asesor_profile_tinjauan') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->routeIs('asesor_profile_tinjauan') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-clipboard-list text-l mr-3"></i> Tinjauan Asesmen
            </a>
            <a href="{{ route('asesor_profile_tracker') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->routeIs('asesor_profile_tracker') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-chart-line text-l mr-3"></i> Lacak Aktivitas
            </a>

            {{-- Link 'Bukti Kelengkapan' dinamis --}}
            <a href="{{ route('asesor.bukti', $asesor->id_asesor) }}" 
               class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->routeIs('asesor.bukti') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
                <i class="fas fa-check text-l mr-3"></i> Bukti Kelengkapan
            </a>
        </div>
      </div>
      
      <div class="w-[90%] grid grid-cols-2 gap-x-5">
        <button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesi</button>
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesor</button>
      </div>

    </aside>

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      <div class="bg-white rounded-2xl shadow-xl p-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Bukti Kelengkapan</h2>

        <div class="space-y-6">
          
          {{-- File ini mengharapkan variabel $documents dari controller --}}

          {{-- Loop dimulai di sini --}}
          @foreach ($documents as $doc)
          <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden" x-data="{ open: true }">
            <div @click="open = !open" class="flex items-center justify-between px-6 py-4 bg-gray-50 cursor-pointer">
              <div>
                <h3 class="font-semibold text-gray-700">{{ $doc['title'] }}</h3>
                <p class="text-sm text-gray-500">{{ $doc['subtitle'] }}</p>
              </div>
              <div class="flex items-center space-x-3">
                @if ($doc['file_path'])
                  <span class="text-sm text-green-600 font-semibold">1 berkas</span>
                @else
                  <span class="text-sm text-red-500 font-semibold">0 berkas</span>
                @endif
                <i class="fas text-gray-500 transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
              </div>
            </div>
            
            <div x-show="open" x-transition class="p-6 border-t border-gray-200">
              @if ($doc['file_path'])
                <div class="flex items-center space-x-4">
                  <div class="w-14 h-14 flex items-center justify-center bg-blue-100 text-blue-600 font-semibold rounded-lg text-lg">
                    {{-- Menampilkan ekstensi file --}}
                    .{{ pathinfo($doc['file_path'], PATHINFO_EXTENSION) }}
                  </div>
                  <div class="flex-1">
                    {{-- Menampilkan nama file --}}
                    <p class="font-medium text-gray-700 text-sm">{{ basename($doc['file_path']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Keterangan:</p>
                    <p class="text-gray-600 text-xs">File telah diupload.</p>
                  </div>
                  <div class="flex flex-col space-y-2">
                    {{-- Tombol View untuk melihat file di tab baru --}}
                    <a href="{{ Storage::url($doc['file_path']) }}" target="_blank"
                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium shadow flex items-center justify-center">
                      <i class="fas fa-eye mr-1.5"></i> View
                    </a>
                    {{-- Tombol Edit mengarah ke halaman edit step 3 --}}
                    <a href="{{ route('edit_asesor3', $asesor->id_asesor) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow flex items-center justify-center">
                      <i class="fas fa-edit mr-1.5"></i> Edit
                    </a>
                  </div>
                </div>
              @else
                {{-- Tampilan jika file belum diupload --}}
                <div class="flex items-center justify-between">
                  <p class="text-sm text-gray-500">Berkas belum diupload.</p>
                  <a href="{{ route('edit_asesor3', $asesor->id_asesor) }}"
                     class="bg-green-500 hover:bg-green-600 text-white px-4 py-1.5 rounded-full text-xs font-medium shadow flex items-center">
                    <i class="fas fa-upload mr-1.5"></i> Upload
                  </a>
                </div>
              @endif
            </div>
          </div>
          @endforeach

        </div>
        
        {{-- Bagian Tanda Tangan --}}
        <div class="mt-10 bg-white rounded-2xl shadow-xl p-8 relative border border-gray-200">
          
          <div class="absolute top-6 right-6 flex space-x-2">
            {{-- Tombol Edit mengarah ke halaman edit step 3 --}}
            <a href="{{ route('edit_asesor3', $asesor->id_asesor) }}" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 font-semibold px-4 py-1.5 rounded-full text-xs flex items-center shadow-sm">
              <i class="fas fa-edit mr-1.5 text-xs"></i> Edit
            </a>
          </div>

          <h3 class="text-3xl font-bold text-gray-800 text-center mb-8">Tanda Tangan Pemohon</h3>
          
          <p class="font-semibold text-gray-800 mb-4">Saya yang bertanda tangan di bawah ini</p>

          {{-- Data dinamis untuk tanda tangan --}}
          <div class="space-y-2 text-sm text-gray-700 mb-6">
            <p><span class="font-bold w-36 inline-block">Nama</span> <span class="font-bold mr-2">:</span> {{ $asesor->nama_lengkap }}</p>
            <p><span class="font-bold w-36 inline-block">Pekerjaan</span> <span class="font-bold mr-2">:</span> {{ $asesor->pekerjaan }}</p>
            <p><span class="font-bold w-36 inline-block">Alamat</span> <span class="font-bold mr-2">:</span> {{ $asesor->alamat_rumah ?? '-' }}</p>
          </div>

          <p class="mt-6 text-gray-700 text-sm mb-6 max-w-2xl">
            Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan
            sebagai bukti pemenuhan syarat Sertifikasi.
          </p>

          {{-- Tanda tangan dinamis --}}
          <div class="mt-6 border border-gray-300 rounded-lg w-full max-w-md h-40 flex items-center justify-center relative mx-auto bg-white">
            @if($asesor->tanda_tangan)
              <img src="{{ Storage::url($asesor->tanda_tangan) }}" alt="Tanda Tangan" class="object-contain h-24">
            @else
              <p class="text-xs text-red-500">*Tanda Tangan belum diupload</p>
            @endif
          </div>
          <p class="text-xs text-gray-500 mt-2 text-center">*Tanda Tangan Pemohon</p>

          <div class="mt-8">
            <span class="bg-green-500 text-white font-bold px-6 py-2 rounded-lg shadow-md text-sm">
                ACCEPTED
            </span>
          </div>
        </div>
        
      </div>
    </main>
  </div>
</body>
</html>