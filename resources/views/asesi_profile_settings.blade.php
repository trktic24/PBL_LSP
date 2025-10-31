<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="text-gray-800">

  <!-- ================= NAVBAR ================= -->
  <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
    <!-- LOGO -->
    <div class="flex items-center space-x-4">
      <a href="{{ route('dashboard') }}">
        <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
      </a>
    </div>

    <!-- MENU -->
    <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
      <!-- Dashboard -->
      <a href="{{ route('dashboard') }}"
         class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('dashboard') ? 'text-blue-600' : '' }}">
        Dashboard
        @if (request()->is('dashboard'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
        @endif
      </a>

      <!-- Dropdown Master -->
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

      <!-- Schedule -->
      <a href="{{ route('schedule_admin') }}"
         class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('schedule_admin') ? 'text-blue-600' : '' }}">
        Schedule
        @if (request()->is('schedule_admin'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
        @endif
      </a>

      <!-- TUK -->
      <a href="{{ route('tuk_sewaktu') }}"
         class="relative h-full flex items-center text-gray-600 hover:text-blue-600 transition {{ request()->is('tuk*') ? 'text-blue-600' : '' }}">
        TUK
        @if (request()->is('tuk*'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
        @endif
      </a>
    </div>

    <!-- PROFIL & NOTIF -->
    <div class="flex items-center space-x-6">
      <!-- Notifikasi -->
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

      <!-- Profil -->
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

  <!-- ================= MAIN LAYOUT ================= -->
  <div class="flex pt-0">
    <!-- ================= SIDEBAR ================= -->
    <aside class="fixed top-[80px] left-0 h-[calc(100vh-80px)] w-[22%] 
                bg-gradient-to-b from-[#e8f0ff] via-[#f3f8ff] to-[#ffffff]
                shadow-inner border-r border-gray-200 flex flex-col items-center pt-8">

      <h2 class="text-lg font-bold text-gray-900 mb-3">Biodata</h2>

      <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white shadow-[0_0_15px_rgba(0,0,0,0.2)] mb-4">
        <img src="{{ asset('images/asesi.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
      </div>

      <h3 class="text-lg font-semibold text-gray-900">Roihan Enrico</h3>
      <p class="text-gray-500 text-sm mb-8">Data Scientist</p>

      <!-- CARD MENU -->
      <div class="w-[90%] bg-white/40 backdrop-blur-md rounded-2xl p-4 
                  shadow-[0_0_15px_rgba(0,0,0,0.15)] mb-6">
        <div class="flex flex-col space-y-4 mt-3 mb-3">

          <!-- PROFILE SETTINGS (aktif) -->
          <a href="{{ route('asesi_profile_settings') }}" 
            class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
              {{ request()->routeIs('asesi_profile_settings') 
                ? 'bg-blue-50 text-blue-600 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.1),_inset_-2px_-2px_4px_rgba(255,255,255,0.9),_0_0_10px_rgba(0,0,0,0.15)] hover:bg-[#dce8ff] hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]' 
                : 'bg-white text-gray-800 shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] hover:bg-[#e0ecff] hover:text-blue-600 hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]' }}">
            <i class="fas fa-user-gear text-l mr-3"></i> Profile Settings
          </a>

          <a href="{{ route('asesi_profile_form') }}" 
            class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
              {{ request()->routeIs('asesi_profile_form') 
                ? 'bg-blue-50 text-blue-600 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.1),_inset_-2px_-2px_4px_rgba(255,255,255,0.9),_0_0_10px_rgba(0,0,0,0.15)]' 
                : 'bg-white text-gray-800 shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] hover:bg-[#d5e6ff] hover:text-blue-600 hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]' }}">
            <i class="fas fa-clipboard text-l mr-3"></i> Form
          </a>

          <a href="#" 
            class="flex items-center px-4 py-3 rounded-xl font-medium bg-white text-gray-800 transition-all duration-300
                  shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] hover:bg-[#d5e6ff] hover:text-blue-600 hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]">
            <i class="fas fa-chart-line text-l mr-3"></i> Lacak Aktivitas
          </a>

          <a href="{{ route('asesi_profile_bukti') }}" 
            class="flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300
              {{ request()->routeIs('asesi_profile_bukti') 
                ? 'bg-blue-50 text-blue-600 shadow-[inset_2px_2px_4px_rgba(0,0,0,0.1),_inset_-2px_-2px_4px_rgba(255,255,255,0.9),_0_0_10px_rgba(0,0,0,0.15)]' 
                : 'bg-white text-gray-800 shadow-[inset_2px_2px_5px_rgba(255,255,255,0.9),_inset_-2px_-2px_5px_rgba(0,0,0,0.1),_0_0_10px_rgba(0,0,0,0.15)] hover:bg-[#d5e6ff] hover:text-blue-600 hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.15),_inset_-2px_-2px_5px_rgba(255,255,255,1),_0_0_12px_rgba(0,0,0,0.25)]' }}">
            <i class="fas fa-check text-l mr-3"></i> Bukti Kelengkapan
          </a>

        </div>
      </div>

      <div class="flex space-x-4">
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-11 py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesi</button>
        <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-11 py-2 rounded-lg shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300">Asesor</button>
      </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <main class="ml-[22%] h-[calc(100vh-70px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      <div class="bg-white rounded-2xl shadow-xl p-10 mt-[-10px]">
        <div class="flex flex-col items-center text-center mb-10">
          <h1 class="text-3xl font-bold text-gray-800 mb-3">Profile Settings</h1>
          <div class="mt-10 w-auto h-60 rounded-full overflow-hidden border-4 border-gray-300 shadow-md">
            <img src="{{ asset('images/asesi.jpg') }}" class="w-full h-full object-cover">
          </div>
          <h2 class="mt-3 font-bold text-xl text-gray-800">Roihan Enrico</h2>
          <p class="text-gray-500 text-sm">Data Scientist</p>
        </div>

        <h3 class="text-xl font-bold text-center text-gray-900 mb-8">Rincian Data Pemohon Sertifikasi</h3>

        <!-- Informasi Pribadi -->
        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Nama Lengkap</label><input type="text" value="Roihan Enrico" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Nomor Induk Keluarga</label><input type="text" value="1234567890000000000000000" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Tempat Lahir</label><input type="text" value="Jakarta Mranggen" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Tanggal Lahir</label><input type="date" value="2005-07-21" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Nomor Telepon</label><input type="text" value="085864361258" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Nomor Telepon Rumah</label><input type="text" value="0897867894356" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Kualifikasi Pendidikan</label><input type="text" value="D4 - Teknologi Rekayasa Komputer" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Pekerjaan</label><input type="text" value="Nganggur" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Jenis Kelamin</label><input type="text" value="Laki - Laki" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Kebangsaan</label><input type="text" value="Brown Canyon" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
          </div>
        </section>

        <!-- Alamat -->
        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">Alamat Lengkap</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Kota / Kabupaten</label><input type="text" value="Jakarta Mranggen" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Provinsi</label><input type="text" value="Brown Condet" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Kode Pos</label><input type="text" value="12345" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Alamat Korespondensi</label><input type="text" value="Brown Canyon" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
          </div>
        </section>

        <!-- Data Pekerjaan -->
        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">Data Pekerjaan Sekarang</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Nama Lembaga / Perusahaan</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Alamat Kantor</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Jabatan</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Kode Pos</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
          </div>
        </section>

        <!-- Kontak -->
        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">No. Telp / Fax / E-mail</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Telp</label><input type="text" value="0987654678909876" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Fax</label><input type="text" value="FAX MRANGGEN JAKARTA" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">E-mail</label><input type="text" value="RoihanEnrico21@sgymail.cum" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
          </div>
        </section>

        <!-- Akun -->
        <section class="space-y-4">
          <h4 class="text-lg font-semibold text-gray-800">Akun Google / Password</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Email</label><input type="text" value="RoihanEnrico21@sgymail.cum" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
            <div><label class="font-medium">Password</label><input type="text" value="AmbatukamT12C" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></div>
          </div>
        </section>
      </div>
    </main>
  </div>
</body>
</html>
