<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asesi Bukti Kelengkapan | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    ::-webkit-scrollbar {
      width: 0;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen overflow-y-auto">

    <nav class="fixed top-0 left-0 w-full flex items-center justify-between px-10 
              bg-white shadow-md border-b border-gray-200 h-[80px] z-50">
      <div class="flex items-center space-x-4">
        <a href="{{ route('dashboard') }}">
          <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
        </a>
      </div>

      <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
        <a href="{{ route('dashboard') }}"
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
            <a href="{{ route('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
            <a href="{{ route('master_asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
            <a href="{{ route('master_asesi') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
          </div>
        </div>

        <a href="{{ route('schedule_admin') }}"
          class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('schedule_admin') ? 'text-blue-600' : '' }}">
          Schedule
          @if (request()->is('schedule_admin'))
          <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
          @endif
        </a>

        <a href="{{ route('tuk_sewaktu') }}"
          class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('tuk*') ? 'text-blue-600' : '' }}">
          TUK
          @if (request()->is('tuk*'))
          <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
          @endif
        </a>
      </div>

      <div class="flex items-center space-x-6">
        <a href="{{ route('notifications') }}"
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

        <a href="{{ route('profile_admin') }}"
          class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
                hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
          <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
          <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
            <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
          </div>
        </a>
      </div>
    </nav>

    <main class="flex pt-[80px] min-h-[calc(100vh-80px)]">

      <aside class="fixed top-[80px] left-0 h-[calc(100vh-80px)] w-[22%]
                 bg-gradient-to-b from-[#e8f0ff] via-[#f3f8ff] to-[#ffffff]
                 shadow-inner border-r border-gray-200 flex flex-col items-center pt-8 overflow-y-auto">

        <h2 class="text-lg font-bold text-gray-900 mb-3">Biodata</h2>
        <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white shadow-[0_0_15px_rgba(0,0,0,0.2)] mb-4">
          <img src="{{ asset('images/asesi.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
        </div>
        <h3 class="text-lg font-semibold text-gray-900">{{ $user->nama_lengkap ?? 'Roihan Enrico' }}</h3>
        <p class="text-gray-500 text-sm mb-8">{{ $user->pekerjaan ?? 'Data Scientist' }}</p>

        <div class="w-[90%] bg-white/40 backdrop-blur-md rounded-2xl p-4 shadow-[0_0_15px_rgba(0,0,0,0.15)] mb-6">
          <div class="flex flex-col space-y-4 mt-3 mb-3">

            <a href="{{ route('asesi_profile_settings') }}"
              class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->routeIs('asesi_profile_settings') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
              <i class="fas fa-user-gear text-l mr-3"></i> Profile Settings
            </a>

            <a href="{{ route('asesi_profile_form') }}"
              class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->routeIs('asesi_profile_form') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
              <i class="fas fa-clipboard text-l mr-3"></i> Form
            </a>

            <a href="#"
              class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    text-gray-800 hover:text-blue-600'">
              <i class="fas fa-chart-line text-l mr-3"></i> Lacak Aktivitas
            </a>

            <a href="{{ route('asesi_profile_bukti') }}"
              class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
                    bg-white shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] 
                    hover:bg-[#e0ecff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]
                    {{ request()->routeIs('asesi_profile_bukti') ? 'text-blue-600' : 'text-gray-800 hover:text-blue-600' }}">
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
        <div class="bg-white p-10 rounded-2xl shadow-xl">
          <h2 class="text-2xl font-bold text-gray-700 mb-8 text-center">Bukti Kelengkapan</h2>

          <div class="space-y-6">
            @for($i = 0; $i < 3; $i++)
              <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden" x-data="{ open: true }">

              <div @click="open = !open" class="flex items-center justify-between px-6 py-4 bg-gray-50 cursor-pointer">
                <div>
                  <h3 class="font-semibold text-gray-700">Sertifikasi Pelatihan Polines</h3>
                  <p class="text-sm text-gray-500">Sertifikat Kompetensi Polines</p>
                </div>
                <div class="flex items-center space-x-3">
                  <span class="text-sm text-red-500 font-semibold">1 berkas</span>
                  <i class="fas text-gray-500 transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </div>
              </div>

              <div x-show="open" x-transition class="p-6 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                  <div class="w-14 h-14 flex items-center justify-center bg-blue-100 text-blue-600 font-semibold rounded-lg text-lg">
                    .png
                  </div>
                  <div class="flex-1">
                    <p class="font-medium text-gray-700 text-sm">Sertifikat Kompetensi Polines</g p>
                    <p class="text-gray-500 text-xs mt-1">Keterangan:</p>
                    <p class="text-gray-600 text-xs">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                  </div>
                  <div class="flex flex-col space-y-2"> <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow flex items-center justify-center">
                      <i class="fas fa-edit mr-1.5"></i> Edit
                    </button>
                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-xs font-medium shadow flex items-center justify-center">
                      <i class="fas fa-trash mr-1.5"></i> Delete
                    </button>
                  </div>
                </div>
              </div>
          </div>
          @endfor
        </div>
        <div class="mt-10 bg-white rounded-2xl shadow-xl p-8 relative border border-gray-200">

          <div class="absolute top-6 right-6 flex space-x-2">
            <button class="bg-yellow-400 hover:bg-yellow-500 text-white font-medium px-3 py-1 rounded-full text-sm flex items-center shadow">
              <i class="fas fa-edit mr-1.5"></i> Edit
            </button>
            <button class="bg-red-500 hover:bg-red-600 text-white font-medium px-3 py-1 rounded-full text-sm flex items-center shadow">
              <i class="fas fa-trash mr-1.5"></i> Delete
            </button>
          </div>

          <h3 class="text-3xl font-bold text-gray-800 text-center mb-8">Tanda Tangan Pemohon</h3>

          <p class="font-semibold text-gray-800 mb-4">Saya yang bertanda tangan di bawah ini</p>

          <div class="space-y-2 text-sm text-gray-700 mb-6">
            <p><span class="font-bold w-36 inline-block">Nama</span> <span class="font-bold mr-2">:</span> Rafa Saputra</p>
            <p><span class="font-bold w-36 inline-block">Jabatan</span> <span class="font-bold mr-2">:</span> Wakil Presiden</p>
            <p><span class="font-bold w-36 inline-block">Perusahaan</span> <span class="font-bold mr-2">:</span> Roihan Company</p>
            <p><span class="font-bold w-36 inline-block">Alamat Perusahaan</span> <span class="font-bold mr-2">:</span> Jakarta, Mranggen</p>
          </div>

          <p class="mt-6 text-gray-700 text-sm mb-6 max-w-2xl">
            Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan
            sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
          </p>

          <div class="mt-6 border border-gray-300 rounded-lg w-full max-w-md h-40 flex items-center justify-center relative">
            <img src="{{ asset('images/ttd_sample.png') }}" alt="Tanda Tangan" class="object-contain h-24">
          </div>
          <p class="text-xs text-red-500 mt-2">*Tanda tangan di sini</p>
        </div>
  </div>
  </section>
  </main>
  </div>
</body>

</html>