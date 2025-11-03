<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Asesor - Data Pribadi | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    /* Style kustom untuk select agar konsisten */
    select {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 0.5rem center;
      background-repeat: no-repeat;
      background-size: 1.5em 1.5em;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />
    
    <main class="flex-1 flex justify-center items-start pt-10 pb-12 px-4">

      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">
        
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">ADD ASESOR</h1>
        
        <!-- NOTE: Bagian ini adalah step wizard (indikator langkah 1-3).Mungkin akan ada perubahan urutan atau tampilan di update berikutnya.-->

      <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
          <div class="flex flex-col items-center text-center w-32">
              <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium">1</div>
              <p class="mt-2 text-xs font-medium text-green-500">Informasi Akun</p>
          </div>
          <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
          <div class="flex flex-col items-center text-center w-32">
              <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">2</div>
              <p class="mt-2 text-xs font-medium text-blue-600">Data Pribadi</p>
          </div>
          <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
          <div class="flex flex-col items-center text-center w-32">
              <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">3</div>
              <p class="mt-2 text-xs font-medium text-gray-500">Kelengkapan Dokumen</p>
          </div>
      </div>

        <form action="#" method="POST" class="space-y-6">
          @csrf
          
          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Data Pribadi</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="no_reg" class="block text-sm font-medium text-gray-700 mb-2">No Registrasi Asesor <span class="text-red-500">*</span></label>
              <input type="text" id="no_reg" name="no_reg" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                     placeholder="Masukkan No Registrasi">
            </div>
            <div>
              <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
              <input type="text" id="nama_lengkap" name="nama_lengkap" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                     placeholder="Masukkan Nama Lengkap">
            </div>
            <div class="md:col-span-2">
              <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
              <input type="text" id="nik" name="nik" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                     placeholder="Masukkan NIK">
            </div>
          </div>

          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4 pt-4">Informasi Pribadi</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Tanggal Lahir <span class="text-red-500">*</span></label>
              <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Kota"
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none mb-3">
              <div class="grid grid-cols-3 gap-4">
                <select id="tanggal_lahir" name="tanggal_lahir" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                  <option value="">Tanggal</option>
                  @for ($i = 1; $i <= 31; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
                <select id="bulan_lahir" name="bulan_lahir" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                  <option value="">Bulan</option>
                  @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $key => $bulan)
                    <option value="{{ $key + 1 }}">{{ $bulan }}</option>
                  @endforeach
                </select>
                <select id="tahun_lahir" name="tahun_lahir" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                  <option value="">Tahun</option>
                  @for ($i = date('Y'); $i >= date('Y') - 70; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>
            <div>
              <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
              <select id="jenis_kelamin" name="jenis_kelamin" required
                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
              
              <label for="kebangsaan" class="block text-sm font-medium text-gray-700 mb-2 mt-6">Kebangsaan</label>
              <select id="kebangsaan" name="kebangsaan"
                      class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="WNI">Warga Negara Indonesia</option>
                <option value="WNA">Warga Negara Asing</option>
              </select>
            </div>
          </div>
          
          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4 pt-4">Alamat & Kontak</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Rumah <span class="text-red-500">*</span></label>
              <textarea id="alamat" name="alamat" required rows="3"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"
                        placeholder="Masukkan Alamat Rumah"></textarea>
            </div>
            <div>
              <label for="kab_kota" class="block text-sm font-medium text-gray-700 mb-2">Kabupaten / Kota <span class="text-red-500">*</span></label>
              <input type="text" id="kab_kota" name="kab_kota" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
              <input type="text" id="provinsi" name="provinsi" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">Kode POS</label>
              <input type="text" id="kode_pos" name="kode_pos"
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP <span class="text-red-500">*</span></label>
              <input type="text" id="no_hp" name="no_hp" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                     placeholder="0812...">
            </div>
            <div class="md:col-span-2">
              <label for="npwp" class="block text-sm font-medium text-gray-700 mb-2">NPWP <span class="text-red-500">*</span></label>
              <input type="text" id="npwp" name="npwp" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                     placeholder="Masukkan NPWP">
            </div>
          </div>

          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4 pt-4">Informasi Bank</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank <span class="text-red-500">*</span></label>
              <input type="text" id="nama_bank" name="nama_bank" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
              <label for="no_rekening" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening <span class="text-red-500">*</span></label>
              <input type="text" id="no_rekening" name="no_rekening" required
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
          </div>

          <div class="flex items-center justify-between pt-6">
            <a href="{{ route('add_asesor1') }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md transition border border-gray-300 flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <a href="{{ route('add_asesor3') }}"
               class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition flex items-center">
              Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>