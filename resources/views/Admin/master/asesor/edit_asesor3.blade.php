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

        <form action="{{ route('admin.asesor.update.step3', $asesor->id_asesor) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
          @csrf
          @method('PATCH') 
          
          <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
              <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Bukti Kelengkapan</h3>
              <p class="text-sm text-gray-500 text-center mb-8 -mt-4">Unggah dokumen yang diperlukan. Format: .pdf, .jpg, .png (Max 5MB)</p>

              <div class="space-y-6">
              @php
                  $fileFields = [
                      ['id' => 'ktp', 'label' => 'KTP', 'accept' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'PDF/JPG/PNG', 'existing' => $asesor->ktp],
                      ['id' => 'pas_foto', 'label' => 'Foto', 'is_image' => true, 'accept' => '.jpg,.jpeg,.png', 'desc' => 'JPG/PNG Only', 'existing' => $asesor->pas_foto],
                      ['id' => 'NPWP_foto', 'label' => 'NPWP', 'accept' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'PDF/JPG/PNG', 'existing' => $asesor->NPWP_foto],
                      ['id' => 'rekening_foto', 'label' => 'Rekening', 'accept' => '.pdf,.jpg,.jpeg,.png', 'desc' => 'PDF/JPG/PNG', 'existing' => $asesor->rekening_foto],
                      ['id' => 'CV', 'label' => 'Curriculum Vitae (CV)', 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->CV],
                      ['id' => 'ijazah', 'label' => 'Ijazah Pendidikan', 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->ijazah],
                      ['id' => 'sertifikat_asesor', 'label' => 'Sertifikat Asesor Kompetensi', 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->sertifikat_asesor],
                      ['id' => 'sertifikasi_kompetensi', 'label' => 'Sertifikat Kompetensi', 'accept' => '.pdf', 'desc' => 'PDF Only', 'existing' => $asesor->sertifikasi_kompetensi],
                      ['id' => 'tanda_tangan', 'label' => 'Tanda Tangan', 'is_image' => true, 'accept' => '.png', 'desc' => 'PNG Only', 'existing' => $asesor->tanda_tangan],
                  ];
              @endphp

              @foreach($fileFields as $field)
              <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md"
                   x-data="{ 
                      open: false,
                      fileName: '',
                      imageUrl: '{{ $field['existing'] ? route('secure.file', ['path' => $field['existing']]) : '' }}',
                      isImage: {{ isset($field['is_image']) && $field['is_image'] ? 'true' : 'false' }},
                      isDeleted: false,
                      fileExt: '{{ $field['existing'] ? pathinfo($field['existing'], PATHINFO_EXTENSION) : '' }}'
                   }">
                
                {{-- HEADER ACCORDION --}}
                <div @click="open = !open" class="flex items-center justify-between px-4 py-3 bg-gray-50 cursor-pointer hover:bg-gray-100 transition select-none">
                    <div>
                        <h3 class="font-semibold text-gray-700 text-sm">{{ $field['label'] }}</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5 leading-tight">{{ $field['desc'] }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <template x-if="(imageUrl || fileName) && !isDeleted">
                            <span class="text-[11px] text-green-600 font-bold bg-green-100 px-2.5 py-0.5 rounded-full flex items-center">
                                <i class="fas fa-check mr-1 text-[10px]"></i> Terunggah
                            </span>
                        </template>
                        <template x-if="(!imageUrl && !fileName) || isDeleted">
                            <span class="text-[11px] text-red-500 font-semibold">Belum diunggah</span>
                        </template>
                        <i class="fas text-gray-400 text-xs transition-transform duration-300" :class="open ? 'fa-chevron-up rotate-180' : 'fa-chevron-down'"></i>
                    </div>
                </div>

                {{-- CONTENT --}}
                <div x-show="open" x-collapse class="p-4 border-t border-gray-200 bg-white">
                    <div class="flex flex-col md:flex-row items-start gap-4">
                        
                        {{-- PREVIEW BOX --}}
                        <div class="w-full md:w-40 md:h-40 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative group shrink-0">
                            
                            <!-- Existing File / New File Preview -->
                            <template x-if="(imageUrl || fileName) && !isDeleted">
                                <div class="w-full h-full flex items-center justify-center">
                                    <template x-if="isImage || (fileName && fileName.match(/\.(jpg|jpeg|png)$/i)) || (fileExt && ['jpg','jpeg','png'].includes(fileExt.toLowerCase()))">
                                        <img :src="imageUrl" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" 
                                             @click="window.open(imageUrl, '_blank')"
                                             x-on:error="imageUrl = '{{ asset('images/placeholder_default.jpg') }}'">
                                    </template>
                                    <template x-if="!isImage && !(fileName && fileName.match(/\.(jpg|jpeg|png)$/i)) && !(fileExt && ['jpg','jpeg','png'].includes(fileExt.toLowerCase()))">
                                        <div class="text-center p-2">
                                            <i class="fas fa-file-pdf text-red-500 text-2xl mb-1"></i>
                                            <span class="text-[10px] font-bold text-gray-600 block uppercase truncate w-16" x-text="fileName || 'PDF'"></span>
                                            <a :href="imageUrl" target="_blank" class="text-blue-600 text-[10px] hover:underline mt-0.5 inline-block" x-show="imageUrl">Buka</a>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Placeholder -->
                            <template x-if="(!imageUrl && !fileName) || isDeleted">
                                <div class="text-center text-gray-400">
                                    <i class="fas fa-image text-xl mb-1 opacity-50"></i>
                                    <span class="text-[10px] block">Preview</span>
                                </div>
                            </template>
                        </div>

                        {{-- FORM ACTION --}}
                        <div class="flex-1 w-full space-y-2">
                            <input type="file" name="{{ $field['id'] }}" class="hidden" x-ref="fileInput" accept="{{ $field['accept'] }}"
                                   @change="
                                       const file = $event.target.files[0];
                                       if(file) {
                                           fileName = file.name;
                                           isDeleted = false;
                                           if(file.type.startsWith('image/')) {
                                               const reader = new FileReader();
                                               reader.onload = (e) => imageUrl = e.target.result;
                                               reader.readAsDataURL(file);
                                           } else {
                                               imageUrl = null; // No preview for PDF upload (unless we want to show icon)
                                           }
                                       }
                                   ">
                            
                            <!-- Hidden Input for Deletion -->
                            <input type="hidden" name="delete_{{ $field['id'] }}" :value="isDeleted ? '1' : '0'">

                            <div class="flex flex-wrap gap-2 mt-2">
                                {{-- Tombol Pilih File --}}
                                <button type="button" @click="$refs.fileInput.click()" 
                                        class="px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-md hover:bg-blue-100 transition-colors shadow-sm"
                                        x-show="(!imageUrl && !fileName) || isDeleted">
                                    <i class="fas fa-upload mr-1.5"></i> Pilih File
                                </button>

                                {{-- Tombol Ganti / Hapus --}}
                                <div class="flex gap-2" x-show="(imageUrl || fileName) && !isDeleted">
                                    <button type="button" @click="$refs.fileInput.click()" class="px-3 py-1.5 bg-yellow-400 text-white text-xs font-bold rounded-md hover:bg-yellow-500 transition-colors shadow-sm flex items-center">
                                        <i class="fas fa-edit mr-1.5"></i> Ganti
                                    </button>
                                    <button type="button" @click="isDeleted = true; fileName = ''; imageUrl = ''; $refs.fileInput.value = ''" 
                                            class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-md hover:bg-red-600 transition-colors shadow-sm flex items-center">
                                        <i class="fas fa-trash mr-1.5"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <p class="text-[10px] text-gray-400 mt-1" x-show="fileName">File terpilih: <span x-text="fileName" class="font-medium text-gray-600"></span></p>
                        </div>
                    </div>
                </div>
              </div>
              @endforeach
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