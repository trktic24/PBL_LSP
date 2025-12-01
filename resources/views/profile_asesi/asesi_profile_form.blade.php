<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asesi Form | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <x-navbar />
  
  <div class="flex pt-0">
    
    <x-sidebar_profile_asesi :asesi="$asesi" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div x-data="{ open: false, active: null }" class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100">
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Formulir Pendaftaran</h2>
            <p class="text-gray-500 text-sm mt-2">Pilih formulir di bawah untuk mengisi atau melihat detail.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          <template x-for="form in ['FR.APL.01','FR.APL.02','FR.MAPA.01','FR.AK.01','FR.AK.02','FR.AK.03','FR.AK.04','FR.AK.05', 'FR.AK.06','FR.IA.01']" :key="form">
            <button @click="active = form"
              :class="active === form 
                ? 'bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30 ring-2 ring-blue-500 ring-offset-2 transform scale-105' 
                : 'bg-white border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600 hover:shadow-md hover:-translate-y-1'"
              class="py-4 px-2 rounded-xl font-semibold text-sm transition-all duration-300 flex flex-col items-center justify-center gap-2 group">
              
              <div :class="active === form ? 'bg-white/20' : 'bg-blue-50 group-hover:bg-blue-100'" 
                   class="w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                  <i class="fas fa-file-alt text-lg" :class="active === form ? 'text-white' : 'text-blue-600'"></i>
              </div>
              <span x-text="form"></span>
            </button>
          </template>
        </div>

        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
             
          <template x-for="form in ['FR.IA.02','FR.IA.03','FR.IA.04','FR.IA.05','FR.IA.06','FR.IA.07','FR.IA.08','FR.IA.09','FR.IA.10','FR.IA.11']" :key="form">
            <button @click="active = form"
              :class="active === form 
                ? 'bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30 ring-2 ring-blue-500 ring-offset-2 transform scale-105' 
                : 'bg-white border border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600 hover:shadow-md hover:-translate-y-1'"
              class="py-4 px-2 rounded-xl font-semibold text-sm transition-all duration-300 flex flex-col items-center justify-center gap-2 group">
              
              <div :class="active === form ? 'bg-white/20' : 'bg-blue-50 group-hover:bg-blue-100'" 
                   class="w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                  <i class="fas fa-file-alt text-lg" :class="active === form ? 'text-white' : 'text-blue-600'"></i>
              </div>
              <span x-text="form"></span>
            </button>
          </template>
        </div>

        <div class="mt-8 flex justify-center relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <button @click="open = !open" class="relative flex items-center gap-2 px-4 py-1.5 bg-white border border-gray-300 rounded-full text-sm font-medium text-gray-500 hover:text-blue-600 hover:border-blue-400 transition-all shadow-sm z-10">
                <span x-text="open ? 'Sembunyikan Lainnya' : 'Lihat Semua Form'"></span>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
        </div>

        <div class="mt-8">
        
          <div x-show="active === null" 
               x-transition:enter="transition ease-out duration-500"
               class="flex flex-col items-center justify-center text-gray-400 py-16 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                <i class="fas fa-mouse-pointer text-2xl text-blue-300"></i>
            </div>
            <p class="text-lg font-medium text-gray-600">Belum ada formulir yang dipilih</p>
            <p class="text-sm">Silakan klik salah satu kartu di atas untuk melihat detail.</p>
          </div>

          <div x-show="active === 'FR.APL.01'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" class="bg-white p-1">
            <div class="flex items-center gap-3 mb-6 border-b pb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-file-invoice text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Formulir FR.APL.01</h3>
                    <p class="text-gray-500 text-sm">Permohonan Sertifikasi Kompetensi</p>
                </div>
            </div>
            <div class="prose max-w-none text-gray-600">
                <p class="p-4 bg-blue-50 border border-blue-100 rounded-lg text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i> Konten form APL-01 akan dimuat di sini.
                </p>
                </div>
          </div>
        
          <div x-show="active === 'FR.APL.02'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" class="bg-white p-1">
            <div class="flex items-center gap-3 mb-6 border-b pb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-check-double text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Formulir FR.APL.02</h3>
                    <p class="text-gray-500 text-sm">Asesmen Mandiri</p>
                </div>
            </div>
            <div class="prose max-w-none text-gray-600">
                <p class="p-4 bg-green-50 border border-green-100 rounded-lg text-green-800">
                    <i class="fas fa-info-circle mr-2"></i> Konten form APL-02 akan dimuat di sini.
                </p>
            </div>
          </div>

          <div x-show="active === 'FR.MAPA.01'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" class="bg-white p-1">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Formulir FR.MAPA.01</h3>
            <p>...Letakkan konten form FR.MAPA.01 Anda di sini...</p>
          </div>
          
        </div>
        
      </div>
    </main>
  </div>

</body>
</html>