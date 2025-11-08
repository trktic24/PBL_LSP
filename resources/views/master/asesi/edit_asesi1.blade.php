<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Asesi (Step 1) | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0.5;
        cursor: pointer;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-1 flex justify-center items-start pt-10 pb-12">
      <div class="w-full max-w-5xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <div class="flex items-center justify-between mb-10 relative">
            <a href="{{ route('master_asesi') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
              <i class="fas fa-arrow-left mr-2"></i> Back
            </a> 
            <h1 class="text-3xl font-bold text-gray-900 text-center absolute left-1/2 -translate-x-1/2">
              EDIT ASESI
            </h1>
            <div class="w-[80px]"></div> 
        </div>

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
                <p class="mt-2 text-xs font-medium text-gray-500">Bukti Kelengkapan</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">4</div>
                <p class="mt-2 text-xs font-medium text-gray-500">Tanda Tangan</p>
            </div>
        </div>

        <form action="#" method="POST" class="space-y-8">
          <h2 class="text-2xl font-semibold text-gray-900 mb-6">Rincian Data Pemohon Sertifikasi</h2>

          <section>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Nama Lengkap">
              </div>
              <div>
                <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Keluarga</label>
                <input type="text" id="nik" name="nik" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan NIK">
              </div>
              <div>
                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Tempat Lahir">
              </div>
              <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-500">
              </div>
              <div>
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Nomor Telepon">
              </div>
              <div>
                <label for="nomor_rumah" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rumah</label>
                <input type="text" id="nomor_rumah" name="nomor_rumah" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Nomor Rumah">
              </div>
              <div>
                <label for="kualifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kualifikasi / Pendidikan</label>
                <input type="text" id="kualifikasi" name="kualifikasi" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Pendidikan Terakhir">
              </div>
              <div>
                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                <input type="text" id="pekerjaan" name="pekerjaan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Pekerjaan Saat Ini">
              </div>
               <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <input type="text" id="jenis_kelamin" name="jenis_kelamin" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Laki-laki / Perempuan">
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" id="email" name="email" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan E-mail">
              </div>
               <div>
                <label for="kebangsaan" class="block text-sm font-medium text-gray-700 mb-1">Kebangsaan</label>
                <select id="kebangsaan" name="kebangsaan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="WNI">Warga Negara Indonesia (WNI)</option>
                    <option value="WNA">Warga Negara Asing (WNA)</option>
                </select>
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Alamat Lengkap</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota / Kabupaten</label>
                <input type="text" id="kota" name="kota" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                <input type="text" id="provinsi" name="provinsi" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1">Kode POS</label>
                <input type="text" id="kode_pos" name="kode_pos" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="alamat_korespondensi" class="block text-sm font-medium text-gray-700 mb-1">Alamat Korespondensi</label>
                <input type="text" id="alamat_korespondensi" name="alamat_korespondensi" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Data Pekerjaan Sekarang</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="nama_lembaga" class="block text-sm font-medium text-gray-700 mb-1">Nama Lembaga / Perusahaan</label>
                <input type="text" id="nama_lembaga" name="nama_lembaga" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="alamat_kantor" class="block text-sm font-medium text-gray-700 mb-1">Alamat Kantor</label>
                <input type="text" id="alamat_kantor" name="alamat_kantor" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="kode_pos_kantor" class="block text-sm font-medium text-gray-700 mb-1">Kode POS</label>
                <input type="text" id="kode_pos_kantor" name="kode_pos_kantor" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Nomor Telepon / Fax / E-mail (Pekerjaan)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
              <div>
                <label for="no_telepon_kantor" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text" id="no_telepon_kantor" name="no_telepon_kantor" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="fax_kantor" class="block text-sm font-medium text-gray-700 mb-1">Fax</label>
                <input type="text" id="fax_kantor" name="fax_kantor" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
              <div>
                <label for="email_kantor" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" id="email_kantor" name="email_kantor" class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
              </div>
            </div>
          </section>

          <div class="flex justify-end items-center pt-6 border-t mt-10">
            <a href="{{ route('edit_asesi2') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm shadow-md transition">
              Selanjutnya
            </a>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>