<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Asesor - Kelengkapan Dokumen | LSP Polines</title>

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
    
    <main class="flex-1 flex justify-center items-start pt-10 pb-12 px-4">

      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">
        
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">ADD ASESOR</h1>
        
        <!-- NOTE: Bagian ini adalah step wizard (indikator langkah 1-3).Mungkin akan ada perubahan urutan atau tampilan di update berikutnya.-->

        <div class="flex items-center justify-between max-w-2xl mx-auto mb-10">
          <div class="flex flex-col items-center text-center w-1/3">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
              <i class="fas fa-check"></i>
            </div>
            <p class="mt-2 text-sm font-medium text-blue-600">Informasi Akun</p>
          </div>
          
          <div class="flex-1 h-0.5 bg-blue-600 mx-4"></div> <div class="flex flex-col items-center text-center w-1/3">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
              <i class="fas fa-check"></i>
            </div>
            <p class="mt-2 text-sm font-medium text-blue-600">Data Pribadi</p>
          </div>
          
          <div class="flex-1 h-0.5 bg-blue-600 mx-4"></div> <div class="flex flex-col items-center text-center w-1/3">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg">3</div>
            <p class="mt-2 text-sm font-medium text-blue-600">Kelengkapan Dokumen</p>
          </div>
        </div>

        <form action="{{ route('asesor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf
          
          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-2">Kelengkapan Dokumen</h3>
          <p class="text-sm text-gray-500 -mt-2">Unggah dokumen dalam format .pdf, .jpg, atau .png. Maksimal ukuran per file adalah 5MB.</p>

          <div x-data="{ fileName: '' }">
            <label for="file_ktp" class="block text-sm font-medium text-gray-700 mb-2">KTP <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_ktp" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_ktp" name="file_ktp" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="file_foto" class="block text-sm font-medium text-gray-700 mb-2">Foto <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_foto" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_foto" name="file_foto" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="file_npwp" class="block text-sm font-medium text-gray-700 mb-2">NPWP <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_npwp" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_npwp" name="file_npwp" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="file_rekening" class="block text-sm font-medium text-gray-700 mb-2">Rekening <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_rekening" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_rekening" name="file_rekening" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="file_cv" class="block text-sm font-medium text-gray-700 mb-2">Curriculum Vitae (CV) <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_cv" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_cv" name="file_cv" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="file_ijazah" class="block text-sm font-medium text-gray-700 mb-2">Ijazah Pendidikan <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_ijazah" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_ijazah" name="file_ijazah" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="file_sert_asesor" class="block text-sm font-medium text-gray-700 mb-2">Sertifikat Asesor Kompetensi <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_sert_asesor" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_sert_asesor" name="file_sert_asesor" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="file_sert_kompetensi" class="block text-sm font-medium text-gray-700 mb-2">Sertifikat Kompetensi <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Tidak ada berkas yang diupload'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="file_sert_kompetensi" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="file_sert_kompetensi" name="file_sert_kompetensi" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div class="flex items-center justify-between pt-6">
            <a href="{{ route('add_asesor2') }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md transition border border-gray-300 flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition flex items-center">
              Tambah
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>