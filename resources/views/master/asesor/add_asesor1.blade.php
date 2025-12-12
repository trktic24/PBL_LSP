<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Asesor - Informasi Akun | LSP Polines</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    /* Menghapus style 'select' default agar multi-select box terlihat normal */
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">
    <x-navbar.navbar-admin />
    
    <main class="flex-1 flex flex-col items-center pt-10 pb-12 px-4">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <!-- PERBAIKAN: Tata letak header dirapikan -->
        <div class="flex items-center justify-between mb-10">
          <a href="{{ route('master_asesor') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
              <i class="fas fa-arrow-left mr-2"></i> Back
          </a>
          <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">ADD ASESOR</h1>
          <!-- Spacer element to balance the flex container -->
          <div class="w-[80px]"></div> 
        </div>
        
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <span class="font-bold">Error Validasi!</span>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <span class="font-bold">Gagal Menyimpan!</span> {{ session('error') }}
            </div>
        @endif
        <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">1</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Informasi Akun</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">2</div>
                <p class="mt-2 text-xs font-medium text-gray-500">Data Pribadi</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">3</div>
                <p class="mt-2 text-xs font-medium text-gray-500">Kelengkapan Dokumen</p>
            </div>
        </div>

        <form action="{{ route('add_asesor1.post') }}" method="POST" class="space-y-6 max-w-lg mx-auto" x-data="{ showPassword: false }">
          @csrf
          <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Informasi Akun</h2>
          
          <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
            <input type="text" id="username" name="username" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   placeholder="Masukkan Username" value="{{ old('username', $asesor->username ?? '') }}">
          </div>
          
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   placeholder="Masukkan Alamat Email" value="{{ old('email', $asesor->email ?? '') }}">
          </div>

          <!-- PERBAIKAN: Input Skema diubah menjadi Dropdown Multi-select Kustom dengan Alpine.js -->
          @php
              // PERBAIKAN BUG FINAL:
              // Cek data session dengan lebih aman untuk menghindari error "Undefined property"
              // yang menyebabkan Alpine.js crash dan data 'old()' gagal terisi.
              $session_skemas = [];
              if (isset($asesor) && property_exists($asesor, 'skema_ids') && is_array($asesor->skema_ids)) {
                  $session_skemas = $asesor->skema_ids;
              }
              $old_skemas = old('skema_ids', $session_skemas);
          @endphp
          <div x-data="{ 
                  open: false, 
                  selectedSkemas: @json($old_skemas) 
               }" 
               @click.away="open = false" 
               class="relative">
            
            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Sertifikasi (Skema) <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-500 mb-2">Pilih satu atau lebih skema yang dikuasai.</p>
            
            <!-- Tombol Dropdown Palsu -->
            <!-- PERBAIKAN: Menambahkan .stop untuk memastikan toggle tidak berbenturan -->
            <button type="button" @click.stop="open = !open" 
                    class="w-full p-3 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 focus:outline-none">
              <span x-text="selectedSkemas.length > 0 ? selectedSkemas.length + ' skema dipilih' : 'Pilih skema...'"
                    :class="selectedSkemas.length > 0 ? 'text-gray-900' : 'text-gray-400'"></span>
              <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Daftar Checkbox (Dropdown Asli) -->
            <div x-show="open" 
                 x-transition 
                 x-cloak
                 class="absolute z-10 w-full mt-1 border border-gray-300 rounded-lg bg-white h-48 overflow-y-auto space-y-2 p-3 shadow-lg">
              
              @forelse ($skemas as $skema)
                <div class="flex items-center">
                  <input type="checkbox" id="add_skema_{{ $skema->id_skema }}" 
                         name="skema_ids[]" 
                         value="{{ $skema->id_skema }}"
                         x-model="selectedSkemas"
                         class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                  <label for="add_skema_{{ $skema->id_skema }}" class="ml-2 block text-sm text-gray-900">
                    {{ $skema->nama_skema }}
                  </label>
                </div>
              @empty
                <p class="text-sm text-gray-500">Belum ada data skema. Harap tambahkan skema terlebih dahulu.</p>
              @endforelse
            </div>
          </div>
          <!-- Akhir Dropdown Kustom -->

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
              <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
              <input :type="showPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
          </div>
          
          <p class="text-xs text-gray-500">Gunakan 8 karakter atau lebih dengan kombinasi huruf, angka, dan simbol.</p>

          <div class="flex items-center">
            <input type="checkbox" id="show_password" x-model="showPassword" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="show_password" class="ml-2 block text-sm text-gray-900">Tampilkan Password</label>
          </div>

          <div class="pt-4">
            <button type="submit"
                    class="w-full flex justify-center py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition text-center">
              Selanjutnya
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>