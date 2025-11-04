<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Asesi (Step 3) | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-1 flex justify-center items-start pt-10 pb-12">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <div class="flex items-center justify-center mb-10">
          <h1 class="text-3xl font-bold text-gray-900 text-center">ADD ASESI</h1>
        </div>

        <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">1</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Informasi Akun</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div>
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">2</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Data Pribadi</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium">3</div>
                <p class="mt-2 text-xs font-medium text-green-500">Bukti Kelengkapan</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">4</div>
                <p class="mt-2 text-xs font-medium text-gray-500">Tanda Tangan</p>
            </div>
        </div>

        <form action="#" method="POST" class="space-y-6">
          <h2 class="text-2xl font-semibold text-gray-900 mb-2">Bukti Kelengkapan Pemohon</h2>
          <p class="text-sm text-gray-600 mb-6">
            Bukti kelengkapan persyaratan dasar pemohon
          </p>

          <div class="border border-gray-300 rounded-lg bg-white shadow-sm" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="flex justify-between items-center w-full p-4">
              <span class="font-medium text-gray-800">Kartu Hasil Studi</span>
              <div class="flex items-center space-x-2">
                <span class="text-sm text-red-500">0 berkas</span>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
              </div>
            </button>
            <div x-show="open" class="p-6 border-t border-gray-200 bg-gray-50 space-y-4">
              <p class="text-xs text-gray-500">Kartu hasil studi semester terakhir yang relevan</p>
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                <p class="text-sm text-gray-600">Drag & drop file di sini, atau klik untuk upload</p>
                <input type="file" class="hidden" id="file_khs">
                <label for="file_khs" class="cursor-pointer text-blue-600 hover:underline text-sm font-medium">Pilih file</label>
              </div>
              <div class="flex items-center justify-between text-sm bg-white p-3 border rounded-md">
                <div class="flex items-center space-x-2">
                  <i class="fas fa-file-image text-gray-500"></i>
                  <span class="text-gray-700">pasdiri.jpg</span>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
              </div>
              <textarea class="w-full p-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="2" placeholder="Tambahkan Keterangan..."></textarea>
              <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg text-sm">Cancel</button>
                <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm">Upload</button>
              </div>
            </div>
          </div>
          
          <div class="border border-gray-300 rounded-lg bg-white shadow-sm" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex justify-between items-center w-full p-4">
              <span class="font-medium text-gray-800">Sertifikasi Pelatihan Polines</span>
              <div class="flex items-center space-x-2">
                <span class="text-sm text-red-500">0 berkas</span>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
              </div>
            </button>
            <div x-show="open" class="p-6 border-t border-gray-200 bg-gray-50 space-y-4">
               <p class="text-xs text-gray-500">Sertifikat pelatihan / sertfikat kompetensi Polines</p>
               </div>
          </div>
          
          <div class="border border-gray-300 rounded-lg bg-white shadow-sm" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex justify-between items-center w-full p-4">
              <span class="font-medium text-gray-800">Surat Keterangan Kerja</span>
              <div class="flex items-center space-x-2">
                <span class="text-sm text-red-500">0 berkas</span>
                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
              </div>
            </button>
            <div x-show="open" class="p-6 border-t border-gray-200 bg-gray-50 space-y-4">
               <p class="text-xs text-gray-500">Pengalaman kerja minimal 2 tahun</p>
               </div>
          </div>

          <div class="flex justify-between items-center pt-6 border-t mt-10">
            <a href="{{ route('add_asesi2') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg text-sm shadow-sm transition">
              Sebelumnya
            </a>
            <a href="{{ route('add_asesi4') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm shadow-md transition">
              Selanjutnya
            </a>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>