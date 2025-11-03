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
<div class="min-h-screen overflow-y-auto">

  <nav class="fixed top-0 left-0 w-full flex items-center justify-between px-10 
              bg-white shadow-md border-b border-gray-200 h-[80px] z-50">
    <div class="flex items-center space-x-4">
      <a href="{{ url('dashboard') }}">
        <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
      </a>
    </div>

    <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
      <a href="{{ url('dashboard') }}"
         class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('dashboard') ? 'text-blue-600' : '' }}">
        Dashboard
        @if (request()->is('dashboard'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
        @endif
      </a>

      <div x-data="{ open: false }" class="relative h-full flex items-center">
        <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition">
          <span>Master</span>
          <i :class="open ? 'fas fa-caret-up ml-2.5 text-sm' : 'fas fa-caret-down ml-2.5 text-sm'"></i>
        </button>
        <div x-show="open" @click.away="open = false"
             class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
             x-transition>
          <a href="{{ url('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
          <a href="{{ url('master_asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
          <a href="{{ url('master_asesi') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
        </div>
      </div>

      <a href="{{ url('schedule_admin') }}"
         class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('schedule_admin') ? 'text-blue-600' : '' }}">
        Schedule
        @if (request()->is('schedule_admin'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
        @endif
      </a>

      <a href="{{ url('tuk_sewaktu') }}"
         class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('tuk*') ? 'text-blue-600' : '' }}">
        TUK
        @if (request()->is('tuk*'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
        @endif
      </a>
    </div>

    <div class="flex items-center space-x-6">
      <a href="{{ url('notifications') }}" 
         class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)] 
                hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
        <i class="fas fa-bell text-xl text-gray-600 relative top-[1px]"></i>
        <span class="absolute top-[9px] right-[9px]">
          <span class="relative flex w-2 h-2">
            <span class="absolute inline-flex w-full h-full animate-ping rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex w-2 h-2 rounded-full bg-red-500"></span>
          </span>
        </span>
      </a>

      <a href="{{ url('profile_admin') }}" 
         class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
                hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
        <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
        <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
          <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
        </div>
      </a>
    </div>
  </nav>

  <main class="flex min-h-[calc(100vh-80px)] pt-[80px]">
    
    <aside class="fixed top-[80px] left-0 h-[calc(100vh-80px)] w-[22%] 
                 bg-gradient-to-b from-[#e8f0ff] via-[#f3f8ff] to-[#ffffff]
                 shadow-inner border-r border-gray-200 flex flex-col items-center pt-8">
      <h2 class="text-lg font-bold text-gray-900 mb-3">Biodata</h2>

      <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white shadow-[0_0_15px_rgba(0,0,0,0.2)] mb-4">
        <img src="{{ asset('images/asesi.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
      </div>

      <h3 class="text-lg font-semibold text-gray-900">Roihan Enrico</h3>
      <p class="text-gray-500 text-sm mb-8">Data Scientist</p>

      <div class="w-[90%] bg-white/40 backdrop-blur-md rounded-2xl p-4 shadow-[0_0_15px_rgba(0,0,0,0.15)] mb-6">
        <div class="flex flex-col space-y-4 mt-3 mb-3">
          
          <a href="{{ url('asesi_profile_settings') }}" 
             class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->is('asesi_profile_settings') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
            <i class="fas fa-user-gear text-l mr-3"></i> Profile Settings
          </a>

          <a href="{{ url('asesi_profile_form') }}" 
             class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->is('asesi_profile_form') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
            <i class="fas fa-clipboard text-l mr-3"></i> Form
          </a>

          <a href="#" 
             class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    text-gray-800 hover:text-blue-600'">
            <i class="fas fa-chart-line text-l mr-3"></i> Lacak Aktivitas
          </a>

          <a href="{{ url('asesi_profile_bukti') }}" 
             class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->is('asesi_profile_bukti') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
            <i class="fas fa-check text-l mr-3"></i> Bukti Kelengkapan
          </a>
        </div>
      </div>

      <div class="w-[90%] grid grid-cols-2 gap-x-5">
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesi</button>
        <button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesor</button>
      </div>
    </aside>

    <section class="ml-[22%] flex-1 p-8">
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
</div>
</body>
</html>