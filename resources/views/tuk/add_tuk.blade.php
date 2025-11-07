<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add TUK | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

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
  <div class="min-h-screen flex flex-col">

    <x-navbar />
    
    <main class="flex-1 flex justify-center items-start pt-10 pb-12">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <div class="flex items-center justify-between mb-10">
          <a href="{{ route('master_tuk') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Back
          </a>
          <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">ADD TUK</h1>
          <div class="w-[80px]"></div>
        </div>

        <form action="#" method="POST" class="space-y-6" enctype="multipart/form-data">
          @csrf

          <div>
            <label for="nama_lokasi" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Lokasi TUK <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_lokasi" name="nama_lokasi" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Contoh: Lab. Jaringan, Workshop Akuntansi" />
          </div>

          <div>
            <label for="alamat_tuk" class="block text-sm font-medium text-gray-700 mb-2">
              Alamat TUK <span class="text-red-500">*</span>
            </label>
            <textarea id="alamat_tuk" name="alamat_tuk" rows="3" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Masukkan Alamat Lengkap TUK"></textarea>
          </div>
          
          {{-- BAGIAN INI YANG DIMODIFIKASI --}}
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
              <label for="kontak_tuk" class="block text-sm font-medium text-gray-700 mb-2">
                Kontak TUK (No. HP/Email PJ) <span class="text-red-500">*</span>
              </label>
              <input type="text" id="kontak_tuk" name="kontak_tuk" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Masukkan Nomor Telepon atau Email" />
            </div>
            
            <div x-data="{ fileName: '' }">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Foto TUK <span class="text-red-500">*</span>
              </label>
              <label class="w-full flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500">
                <i class="fas fa-upload text-gray-500 mr-3"></i>
                <span x-text="fileName || 'Pilih file untuk di-upload...'" class="text-sm text-gray-600"></span>
                <input type="file" name="foto_tuk" @change="fileName = $event.target.files[0].name" class="opacity-0 absolute w-0 h-0" required />
              </label>
            </div>
          </div>
          {{-- AKHIR BAGIAN MODIFIKASI --}}


          <div>
            <label for="link_gmap" class="block text-sm font-medium text-gray-700 mb-2">
              Link Google Maps <span class="text-red-500">*</span>
            </label>
            <input type="text" id="link_gmap" name="link_gmap" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Masukkan URL Google Maps" />
          </div>

          <div class="pt-4">
            <button type="submit"
              class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
              Tambah TUK
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>