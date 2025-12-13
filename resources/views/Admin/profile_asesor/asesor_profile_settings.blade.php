<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asesor Profile Settings | LSP Polines</title> 

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
    
    {{-- ========================================================= --}}
    {{-- SIDEBAR DINAMIS                                           --}}
    {{-- ========================================================= --}}
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

          {{-- Link 'Tinjauan' dan 'Tracker' statis --}}
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

    {{-- ========================================================= --}}
    {{-- KONTEN UTAMA DINAMIS                                    --}}
    {{-- ========================================================= --}}
    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      <div class="bg-white rounded-2xl shadow-xl p-10">
        
        <div class="flex flex-col items-center text-center mb-10">
          <h1 class="text-3xl font-bold text-gray-800 mb-3">Profile Settings</h1>
          <div class="mt-10 w-60 h-60 rounded-full overflow-hidden border-4 border-gray-300 shadow-md">
            {{-- Foto dinamis --}}
            <img src="{{ $asesor->pas_foto ? Storage::url($asesor->pas_foto) : asset('images/asesi.jpg') }}" class="w-full h-full object-cover">
          </div>
          {{-- Nama dinamis --}}
          <h2 class="mt-3 font-bold text-xl text-gray-800">{{ $asesor->nama_lengkap }}</h2>
          <p class="text-gray-500 text-sm">Asesor</p>
        </div>

        <h3 class="text-xl font-bold text-center text-gray-900 mb-8">Rincian Data Asesor</h3>

        {{-- Semua field input di bawah ini dinamis dan 'readonly' --}}
        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Nama Lengkap</label><input type="text" value="{{ $asesor->nama_lengkap }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Nomor Induk Keluarga</label><input type="text" value="{{ $asesor->nik }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Tempat Lahir</label><input type="text" value="{{ $asesor->tempat_lahir }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Tanggal Lahir</label><input type="date" value="{{ $asesor->tanggal_lahir }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Nomor Telepon</label><input type="text" value="{{ $asesor->nomor_hp }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Nomor Telepon Rumah</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Kualifikasi Pendidikan</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Pekerjaan</label><input type="text" value="{{ $asesor->pekerjaan }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            
            {{-- PERBAIKAN: Logika disesuaikan dengan migrasi (String, bukan angka 1) --}}
            <div><label class="font-medium">Jenis Kelamin</label><input type="text" value="{{ $asesor->jenis_kelamin }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            
            <div><label class="font-medium">Kebangsaan</label><input type="text" value="{{ $asesor->kebangsaan }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
          </div>
        </section>

        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">Alamat Lengkap</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Kota / Kabupaten</label><input type="text" value="{{ $asesor->kabupaten_kota }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Provinsi</label><input type="text" value="{{ $asesor->provinsi }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Kode Pos</label><input type="text" value="{{ $asesor->kode_pos }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Alamat Korespondensi</label><input type="text" value="{{ $asesor->alamat_rumah }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
          </div>
        </section>

        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">Data Pekerjaan Sekarang</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Nama Lembaga / Perusahaan</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Alamat Kantor</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Jabatan</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Kode Pos</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
          </div>
        </section>

        <section class="space-y-4 mb-8">
          <h4 class="text-lg font-semibold text-gray-800">No. Telp / Fax / E-mail</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Telp</label><input type="text" value="{{ $asesor->nomor_hp }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Fax</label><input type="text" value="-" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">E-mail</label><input type="text" value="{{ $asesor->user->email ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
          </div>
        </section>

        <section class="space-y-4">
          <h4 class="text-lg font-semibold text-gray-800">Akun Google / Password</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="font-medium">Email</label><input type="text" value="{{ $asesor->user->email ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
            <div><label class="font-medium">Password</label><input type="text" value="********" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" readonly></div>
          </div>
        </section>
      </div>
    </main>

  </div>
</body>
</html>