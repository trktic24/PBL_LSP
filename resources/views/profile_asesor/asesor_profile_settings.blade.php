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

  @php use Illuminate\Support\Facades\Storage; @endphp

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="text-gray-800">

  <x-navbar />
  
  {{-- Layout Utama --}}
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- 1. Panggil Component Sidebar Asesor --}}
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    {{-- 2. Konten Utama --}}
    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100">
        
        {{-- Header Profil --}}
        <div class="flex flex-col items-center text-center mb-10">
          <h1 class="text-3xl font-bold text-gray-800 mb-3">Profile Settings</h1>
          
          <div class="mt-6 w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-[0_0_15px_rgba(0,0,0,0.2)] bg-gray-100 flex items-center justify-center">
             @if($asesor->pas_foto)
                <img src="{{ Storage::url($asesor->pas_foto) }}" alt="Foto Profil" class="w-full h-full object-cover"
                     onerror="this.onerror=null;this.src='{{ asset('images/default_pic.jpeg') }}';">
             @else
                <span class="text-5xl font-bold text-gray-400 select-none">
                    {{ strtoupper(substr($asesor->nama_lengkap, 0, 2)) }}
                </span>
             @endif
          </div>
          
          <h2 class="mt-4 font-semibold text-2xl text-gray-800">{{ $asesor->nama_lengkap }}</h2>
          <p class="text-gray-500 text-lg">{{ $asesor->pekerjaan }}</p>

          {{-- NEW: Status Badge di Header Settings --}}
          <div class="mt-3">
            @if($asesor->status_verifikasi == 'approved')
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                    <i class="fas fa-check-circle mr-1.5"></i> Akun Terverifikasi
                </span>
            @elseif($asesor->status_verifikasi == 'rejected')
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200 shadow-sm">
                    <i class="fas fa-times-circle mr-1.5"></i> Verifikasi Ditolak
                </span>
            @else
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                    <i class="fas fa-clock mr-1.5"></i> Menunggu Verifikasi
                </span>
            @endif
          </div>

        </div>

        <h3 class="text-xl font-bold text-center text-gray-900 mb-8 uppercase tracking-wide">Rincian Data Asesor</h3>

        {{-- Section 1: Data Pribadi --}}
        <section class="mb-10">
          <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-200">Data Pribadi</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" value="{{ $asesor->nama_lengkap }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Keluarga (NIK)</label>
                <input type="text" value="{{ $asesor->nik }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                <input type="text" value="{{ $asesor->tempat_lahir }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" value="{{ $asesor->tanggal_lahir }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <input type="text" value="{{ $asesor->jenis_kelamin }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kebangsaan</label>
                <input type="text" value="{{ $asesor->kebangsaan }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                <input type="text" value="{{ $asesor->pekerjaan }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
          </div>
        </section>

        {{-- Section 2: Alamat Lengkap --}}
        <section class="mb-10">
          <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-200">Alamat Lengkap</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten / Kota</label>
                <input type="text" value="{{ $asesor->kabupaten_kota }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                <input type="text" value="{{ $asesor->provinsi }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                <input type="text" value="{{ $asesor->kode_pos }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Korespondensi</label>
                <textarea rows="2" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm resize-none" readonly>{{ $asesor->alamat_rumah }}</textarea>
            </div>
          </div>
        </section>
        {{-- Section 4: Kontak --}}
        <section class="mb-10">
          <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-200">Kontak & Akun</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / HP</label>
                <input type="text" value="{{ $asesor->nomor_hp }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Login</label>
                <input type="text" value="{{ $asesor->user->email ?? '' }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" value="********" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
          </div>
        </section>

      </div>
    </main>
  </div>
</body>
</html>