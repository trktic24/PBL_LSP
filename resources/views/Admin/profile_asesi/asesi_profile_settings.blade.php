<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asesi Profile Settings | LSP Polines</title> 

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

  <x-navbar.navbar_admin/>
  
  <div class="flex pt-0">
    
    @php
        $firstSertifikasi = $asesi->dataSertifikasi->first();
        $defaultBackUrl = route('admin.master_asesi'); // Fallback ke daftar master asesi
    @endphp

    <x-sidebar.sidebar_profile_asesi 
        :asesi="$asesi" 
        :backUrl="$firstSertifikasi ? route('admin.schedule.attendance', $firstSertifikasi->id_jadwal) : $defaultBackUrl" 
    />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100">
        
        <div class="flex flex-col items-center text-center mb-10">
          <h1 class="text-3xl font-bold text-gray-800 mb-3">Profile Settings</h1>
          <div class="mt-6 w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-[0_0_15px_rgba(0,0,0,0.2)] flex items-center justify-center bg-purple-300">
             <span class="text-5xl font-bold text-white select-none">
                 {{ strtoupper(substr($asesi->nama_lengkap, 0, 2)) }}
             </span>
          </div>
          <h2 class="mt-4 font-semibold text-2xl text-gray-800">{{ $asesi->nama_lengkap }}</h2>
          <p class="text-gray-500 text-lg">{{ $asesi->pekerjaan }}</p>
        </div>

        <h3 class="text-xl font-bold text-center text-gray-900 mb-8 uppercase tracking-wide">Rincian Data Pemohon Sertifikasi</h3>

        <section class="mb-10">
          <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-200">Data Pribadi</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" value="{{ $asesi->nama_lengkap }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <input type="text" value="{{ $asesi->nik }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                <input type="text" value="{{ $asesi->tempat_lahir }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" value="{{ $asesi->tanggal_lahir ? $asesi->tanggal_lahir->format('Y-m-d') : '' }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <input type="text" value="{{ $asesi->jenis_kelamin }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kebangsaan</label>
                <input type="text" value="{{ $asesi->kebangsaan }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kualifikasi Pendidikan</label>
                <input type="text" value="{{ $asesi->pendidikan }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                <input type="text" value="{{ $asesi->pekerjaan }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
          </div>
        </section>

        <section class="mb-10">
          <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-200">Alamat Lengkap & Kontak</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                <input type="text" value="{{ $asesi->nomor_hp }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                <input type="text" value="{{ $asesi->kode_pos }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten / Kota</label>
                <input type="text" value="{{ $asesi->kabupaten_kota }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                <input type="text" value="{{ $asesi->provinsi }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Rumah</label>
                <textarea rows="2" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm resize-none" readonly>{{ $asesi->alamat_rumah }}</textarea>
            </div>
          </div>
        </section>

        <section class="mb-10">
          <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-200">Data Pekerjaan Sekarang</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Institusi / Perusahaan</label>
                <input type="text" value="{{ $asesi->dataPekerjaan->nama_institusi_pekerjaan ?? '-' }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <input type="text" value="{{ $asesi->dataPekerjaan->jabatan ?? '-' }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp Institusi</label>
                <input type="text" value="{{ $asesi->dataPekerjaan->no_telepon_institusi ?? '-' }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos Institusi</label>
                <input type="text" value="{{ $asesi->dataPekerjaan->kode_pos_institusi ?? '-' }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Institusi</label>
                <textarea rows="2" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm resize-none" readonly>{{ $asesi->dataPekerjaan->alamat_institusi ?? '-' }}</textarea>
            </div>
          </div>
        </section>

        <section>
          <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-200">Informasi Akun</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Login</label>
                <input type="text" value="{{ $asesi->user->email ?? '-' }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-600 text-sm" readonly>
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