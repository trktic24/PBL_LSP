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
    <x-navbar.navbar_admin/>
    
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

          {{-- Reusable File Upload Component Logic with Accordion & Strict Types --}}
          @php
              $fileFields = [
                  ['id' => 'ktp', 'label' => 'KTP', 'required' => false, 'accept' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'PDF/JPG/PNG', 'existing' => $asesor->ktp],
                  ['id' => 'pas_foto', 'label' => 'Foto', 'required' => false, 'is_image' => true, 'accept' => '.jpg,.jpeg,.png', 'desc' => 'JPG/PNG Only', 'existing' => $asesor->pas_foto],
                  ['id' => 'NPWP_foto', 'label' => 'NPWP', 'required' => false, 'accept' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'PDF/JPG/PNG', 'existing' => $asesor->NPWP_foto],
                  ['id' => 'rekening_foto', 'label' => 'Rekening', 'required' => false, 'accept' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'PDF/JPG/PNG', 'existing' => $asesor->rekening_foto],
                  ['id' => 'CV', 'label' => 'Curriculum Vitae (CV)', 'required' => false, 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->CV],
                  ['id' => 'ijazah', 'label' => 'Ijazah Pendidikan', 'required' => false, 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->ijazah],
                  ['id' => 'sertifikat_asesor', 'label' => 'Sertifikat Asesor Kompetensi', 'required' => false, 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->sertifikat_asesor],
                  ['id' => 'sertifikasi_kompetensi', 'label' => 'Sertifikat Kompetensi', 'required' => false, 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->sertifikasi_kompetensi],
                  ['id' => 'tanda_tangan', 'label' => 'Tanda Tangan', 'required' => false, 'is_image' => true, 'accept' => '.jpg,.jpeg,.png', 'desc' => 'JPG/PNG Only', 'existing' => $asesor->tanda_tangan],
              ];
          @endphp

          @foreach($fileFields as $field)
          <div x-data="{ expanded: false, fileName: '', imageUrl: '{{ $field['existing'] ? Storage::url($field['existing']) : '' }}', isImage: {{ isset($field['is_image']) && $field['is_image'] ? 'true' : 'false' }} }" 
               class="border border-gray-200 rounded-xl mb-4 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
            
            <!-- Accordion Header -->
            <div @click="expanded = !expanded" class="bg-gray-50 p-4 flex items-center justify-between cursor-pointer hover:bg-gray-100 transition select-none">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-colors duration-300"
                         :class="(fileName || '{{ $field['existing'] }}') ? 'bg-green-100 text-green-600' : 'bg-white border border-gray-300 text-gray-400'">
                        <i class="fas" :class="(fileName || '{{ $field['existing'] }}') ? 'fa-check' : 'fa-file-upload'"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">
                            {{ $field['label'] }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5" x-text="fileName || ('{{ $field['existing'] }}' ? 'File saat ini: {{ basename($field['existing'] ?? '') }}' : '{{ $field['desc'] }}')"></p>
                    </div>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="{'rotate-180': expanded}"></i>
            </div>

            <!-- Accordion Body -->
            <div x-show="expanded" x-collapse class="bg-white border-t border-gray-200">
                <div class="p-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-blue-50 hover:border-blue-300 transition relative group">
                        
                        <!-- Preview Area (Existing or New) -->
                        <template x-if="imageUrl && isImage">
                            <div class="relative w-full mb-4">
                                <img :src="imageUrl" class="max-h-48 mx-auto rounded-lg shadow-sm object-contain">
                                <button type="button" @click.stop="imageUrl = null; fileName = ''; $refs.fileInput.value = ''" 
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 shadow-md transition-transform hover:scale-110 z-10"
                                        x-show="fileName"> <!-- Only show remove button if it's a NEW file -->
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>

                        <!-- If existing file is NOT an image (PDF), show link -->
                        @if($field['existing'] && (!isset($field['is_image']) || !$field['is_image']))
                        <div x-show="!fileName" class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center">
                            <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                            <a href="{{ Storage::url($field['existing']) }}" target="_blank" class="text-sm text-blue-600 hover:underline font-medium">
                                {{ basename($field['existing']) }}
                            </a>
                        </div>
                        @endif

                        <!-- Placeholder / Upload UI -->
                        <div x-show="!fileName" class="space-y-3 cursor-pointer w-full" @click="$refs.fileInput.click()">
                            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <i class="fas fa-cloud-upload-alt text-3xl"></i>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-700">Klik untuk ganti file atau drag & drop</p>
                                <p class="text-xs text-gray-400 mt-1">Format: {{ $field['desc'] }} (Max 5MB)</p>
                            </div>
                        </div>

                        <!-- New File Selected UI -->
                        <div x-show="fileName" class="flex items-center justify-center text-sm text-gray-700 font-medium bg-gray-100 py-2 px-4 rounded-lg border border-gray-200 mt-2">
                            <i class="fas fa-file-alt mr-2 text-gray-500"></i>
                            <span x-text="fileName"></span>
                            <button type="button" @click.stop="imageUrl = '{{ $field['existing'] ? Storage::url($field['existing']) : '' }}'; fileName = ''; $refs.fileInput.value = ''" class="ml-3 text-red-500 hover:text-red-700">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>

                        <!-- Hidden Input -->
                        <input type="file" id="{{ $field['id'] }}" name="{{ $field['id'] }}" class="hidden" x-ref="fileInput" 
                               accept="{{ $field['accept'] }}"
                               @change="
                                   const file = $event.target.files[0];
                                   fileName = file ? file.name : '';
                                   if (file && file.type.startsWith('image/')) {
                                       const reader = new FileReader();
                                       reader.onload = (e) => imageUrl = e.target.result;
                                       reader.readAsDataURL(file);
                                   } else {
                                       // If not image, reset imageUrl to existing or null
                                       imageUrl = null; 
                                   }
                               ">
                    </div>
                </div>
            </div>
          </div>
          @endforeach

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