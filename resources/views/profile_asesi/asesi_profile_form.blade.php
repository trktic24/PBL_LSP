<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asesi Form | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <x-navbar />
  <main class="flex">
    <x-sidebar_profile_asesi />

    <section class="ml-[22%] flex-1 p-8 h-[calc(100vh-80px)] overflow-y-auto">
      <div x-data="{ open: false, active: null }" class="bg-white rounded-2xl shadow-xl p-10">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Formulir Pendaftaran Sertifikasi</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
          <template x-for="form in ['FR.APL.01','FR.APL.02','FR.MAPA.01','FR.AK.01','FR.AK.02','FR.AK.03','FR.AK.04','FR.AK.05', 'FR.AK.06','FR.IA.01']" :key="form">
            <button @click="active = form"
              :class="active === form ? 'bg-blue-600 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' 
              : 'bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_15px_rgba(59,130,246,0.4)]'"
              class="py-4 rounded-xl font-semibold text-center transition-all duration-300 shadow">
              <i class="fas fa-file-alt mr-2"></i> <span x-text="form"></span>
            </button>
          </template>
        </div>

        <div x-show="open" x-transition class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
          <template x-for="form in ['FR.IA.02','FR.IA.03','FR.IA.04','FR.IA.05','FR.IA.06','FR.IA.07','FR.IA.08','FR.IA.09','FR.IA.10','FR.IA.11']" :key="form">
            <button @click="active = form"
              :class="active === form ? 'bg-blue-600 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]' 
              : 'bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_15px_rgba(59,130,246,0.4)]'"
              class="py-4 rounded-xl font-semibold text-center transition-all duration-300 shadow">
              <i class="fas fa-file-alt mr-2"></i> <span x-text="form"></span>
            </button>
          </template>

          <div class="col-span-full text-center mt-6">
            <button @click="open = false" class="text-blue-600 font-semibold text-sm hover:underline transition-all">
              View Less <i class="fas fa-chevron-up"></i>
            </button>
          </div>
        </div>

        <div class="mt-8 text-center" x-show="!open">
          <button @click="open = true" class="text-blue-600 font-semibold text-sm hover:underline transition-all">
            View More <i class="fas fa-chevron-down"></i>
          </button>
        </div>

        <div class="mt-10 border-t pt-10">
        
          <div x-show="active === null" class="text-center text-gray-500 py-10">
            <i class="fas fa-hand-pointer text-4xl mb-4"></i>
            <p class="text-lg">Silakan pilih salah satu formulir di atas.</p>
            <p class="text-sm">Konten form yang Anda pilih akan muncul di sini.</p>
          </div>

          <div x-show="active === 'FR.APL.01'" x-transition>
            <h3 class="text-2xl font-semibold mb-4">Formulir FR.APL.01</h3>
            <p>...Letakkan konten form FR.APL.01 Anda di sini...</p>
            </div>
        
          <div x-show="active === 'FR.APL.02'" x-transition>
            <h3 class="text-2xl font-semibold mb-4">Formulir FR.APL.02</h3>
            <p>...Letakkan konten form FR.APL.02 Anda di sini...</p>
          </div>

          <div x-show="active === 'FR.MAPA.01'" x-transition>
            <h3 class="text-2xl font-semibold mb-4">Formulir FR.MAPA.01</h3>
            <p>...Letakkan konten form FR.MAPA.01 Anda di sini...</p>
          </div>
          
          </div>
        
      </div>
    </section>
  </main>
</body>
</html>