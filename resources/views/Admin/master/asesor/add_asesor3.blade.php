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
    <x-navbar.navbar_admin />
    
    <main class="flex-1 flex justify-center items-start pt-10 pb-12 px-4">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">
        <div class="flex items-center justify-between mb-10 relative">
            <a href="{{ route('admin.master_asesor') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
              <i class="fas fa-arrow-left mr-2"></i> Back
            </a> 
            <h1 class="text-3xl font-bold text-gray-900 text-center absolute left-1/2 -translate-x-1/2">
              ADD ASESOR
            </h1>
            <div class="w-[80px]"></div> 
        </div>
        
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <span class="font-bold">Error Validasi!</span> Periksa kembali data yang Anda masukkan:
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <span class="font-bold">Gagal Menyimpan!</span> {{ session('error') }}
            </div>
        @endif

        <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium"><i class="fas fa-check"></i></div>
                <p class="mt-2 text-xs font-medium text-green-500">Informasi Akun</p>
            </div>
            <div class="flex-1 h-0.5 bg-green-400 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium"><i class="fas fa-check"></i></div>
                <p class="mt-2 text-xs font-medium text-green-500">Data Pribadi</p>
            </div>
            <div class="flex-1 h-0.5 bg-green-400 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">3</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Kelengkapan Dokumen</p>
            </div>
        </div>

        <form action="{{ route('admin.asesor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf
          
          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-2">Kelengkapan Dokumen</h3>
          <p class="text-sm text-gray-500 -mt-2">Unggah dokumen dalam format .pdf, .jpg, atau .png. Maksimal ukuran per file adalah 2MB.</p>

          <div x-data="{ fileName: '' }">
            <label for="ktp" class="block text-sm font-medium text-gray-700 mb-2">KTP <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="ktp" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="ktp" name="ktp" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="pas_foto" class="block text-sm font-medium text-gray-700 mb-2">Foto <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="pas_foto" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="pas_foto" name="pas_foto" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="NPWP_foto" class="block text-sm font-medium text-gray-700 mb-2">NPWP <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="NPWP_foto" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="NPWP_foto" name="NPWP_foto" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="rekening_foto" class="block text-sm font-medium text-gray-700 mb-2">Rekening <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="rekening_foto" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="rekening_foto" name="rekening_foto" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="CV" class="block text-sm font-medium text-gray-700 mb-2">Curriculum Vitae (CV) <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="CV" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="CV" name="CV" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="ijazah" class="block text-sm font-medium text-gray-700 mb-2">Ijazah Pendidikan <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="ijazah" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="ijazah" name="ijazah" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="sertifikat_asesor" class="block text-sm font-medium text-gray-700 mb-2">Sertifikat Asesor Kompetensi <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="sertifikat_asesor" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="sertifikat_asesor" name="sertifikat_asesor" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="sertifikasi_kompetensi" class="block text-sm font-medium text-gray-700 mb-2">Sertifikat Kompetensi <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="sertifikasi_kompetensi" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="sertifikasi_kompetensi" name="sertifikasi_kompetensi" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="tanda_tangan" class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan <span class="text-red-500">*</span></label>
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file...'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="tanda_tangan" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="tanda_tangan" name="tanda_tangan" class="hidden" required
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div class="flex items-center justify-between pt-6">
            <a href="{{ route('admin.add_asesor2') }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md transition border border-gray-300 flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition flex items-center">
              Simpan Data
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>