<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Asesor - Kelengkapan Dokumen | LSP Polines</title>
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
    <x-navbar.navbar-admin />
    
    <main class="flex-1 flex justify-center items-start pt-10 pb-12 px-4">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">
        <div class="flex items-center justify-between mb-10 relative">
            <a href="{{ route('admin.master_asesor') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
              <i class="fas fa-arrow-left mr-2"></i> Back
            </a> 
            <h1 class="text-3xl font-bold text-gray-900 text-center absolute left-1/2 -translate-x-1/2">
              EDIT ASESOR
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
        
        @if (session('success-step'))
            <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700" role="alert">
                <span class="font-bold">Sukses!</span> {{ session('success-step') }}
            </div>
        @endif

        <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium">1</div>
                <p class="mt-2 text-xs font-medium text-green-500">Informasi Akun</p>
            </div>
            <div class="flex-1 h-0.5 bg-green-400 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium">2</div>
                <p class="mt-2 text-xs font-medium text-green-500">Data Pribadi</p>
            </div>
            <div class="flex-1 h-0.5 bg-green-400 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">3</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Kelengkapan Dokumen</p>
            </div>
        </div>

        <form action="{{ route('admin.asesor.update.step3', $asesor->id_asesor) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf
          @method('PATCH') 
          
          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-2">Kelengkapan Dokumen</h3>
          <p class="text-sm text-gray-500 -mt-2">Unggah dokumen dalam format .pdf, .jpg, atau .png. Maksimal ukuran per file adalah 5MB.</p>

          <div x-data="{ fileName: '' }">
            <label for="ktp" class="block text-sm font-medium text-gray-700 mb-2">KTP</label>
            @if($asesor->ktp)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->ktp) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->ktp) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="ktp" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="ktp" name="ktp" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="pas_foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
            @if($asesor->pas_foto)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->pas_foto) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->pas_foto) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="pas_foto" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="pas_foto" name="pas_foto" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="NPWP_foto" class="block text-sm font-medium text-gray-700 mb-2">NPWP</label>
             @if($asesor->NPWP_foto)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->NPWP_foto) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->NPWP_foto) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="NPWP_foto" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="NPWP_foto" name="NPWP_foto" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="rekening_foto" class="block text-sm font-medium text-gray-700 mb-2">Rekening</label>
            @if($asesor->rekening_foto)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->rekening_foto) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->rekening_foto) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="rekening_foto" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="rekening_foto" name="rekening_foto" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="CV" class="block text-sm font-medium text-gray-700 mb-2">Curriculum Vitae (CV)</label>
            @if($asesor->CV)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->CV) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->CV) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="CV" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="CV" name="CV" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="ijazah" class="block text-sm font-medium text-gray-700 mb-2">Ijazah Pendidikan</label>
            @if($asesor->ijazah)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->ijazah) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->ijazah) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="ijazah" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="ijazah" name="ijazah" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="sertifikat_asesor" class="block text-sm font-medium text-gray-700 mb-2">Sertifikat Asesor Kompetensi</label>
            @if($asesor->sertifikat_asesor)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->sertifikat_asesor) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->sertifikat_asesor) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="sertifikat_asesor" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="sertifikat_asesor" name="sertifikat_asesor" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="sertifikasi_kompetensi" class="block text-sm font-medium text-gray-700 mb-2">Sertifikat Kompetensi</label>
            @if($asesor->sertifikasi_kompetensi)
            <div class="text-sm text-gray-600 mb-2">
                File saat ini: <a href="{{ Storage::url($asesor->sertifikasi_kompetensi) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($asesor->sertifikasi_kompetensi) }}</a>
            </div>
            @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="sertifikasi_kompetensi" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="sertifikasi_kompetensi" name="sertifikasi_kompetensi" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div x-data="{ fileName: '' }">
            <label for="tanda_tangan" class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan</label>
            @if($asesor->tanda_tangan)
                @php
                    $ttdAsesorBase64 = getTtdBase64($asesor->tanda_tangan ?? null, $asesor->id_user ?? $asesor->user_id ?? null, 'asesor');
                @endphp
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs text-gray-500">Tanda tangan saat ini:</span>
                    @if($ttdAsesorBase64)
                        <img src="data:image/png;base64,{{ $ttdAsesorBase64 }}" class="h-12 w-auto object-contain border rounded" alt="TTD">
                    @else
                        <span class="text-xs text-red-400">Tidak dapat dimuat</span>
                    @endif
                </div>        @endif
            <div class="flex items-center justify-between w-full p-2.5 border border-gray-300 rounded-lg">
              <span x-text="fileName || 'Pilih file baru (opsional)'" class="text-gray-500 text-sm pl-2 truncate w-full max-w-xs sm:max-w-md"></span>
              <label for="tanda_tangan" class="cursor-pointer px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium border border-gray-300 shrink-0">
                Pilih File
              </label>
              <input type="file" id="tanda_tangan" name="tanda_tangan" class="hidden"
                     @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
            </div>
          </div>

          <div class="flex items-center justify-between pt-6">
            <a href="{{ route('admin.edit_asesor2', $asesor->id_asesor) }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md transition border border-gray-300 flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition flex items-center">
              Simpan
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>