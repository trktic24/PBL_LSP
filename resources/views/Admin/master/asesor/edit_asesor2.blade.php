<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Asesor - Data Pribadi | LSP Polines</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
    /* Style kustom untuk select */
    select {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 0.5rem center;
      background-repeat: no-repeat;
      background-size: 1.5em 1.5em;
      appearance: none;
    }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">
    <x-navbar.navbar_admin/>
    
    <main class="flex-1 flex flex-col items-center pt-10 pb-12 px-4">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-10">
            <a href="{{ route('admin.master_asesor') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
              <i class="fas fa-arrow-left mr-2"></i> Back
            </a> 
            <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">EDIT ASESOR</h1>
            <div class="w-[80px]"></div> 
        </div>
        
        <!-- Error Handling -->
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700" role="alert">
                <span class="font-bold">Error Validasi!</span>
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
        
        <!-- Stepper -->
        <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
            <a href="{{ route('admin.edit_asesor1', $asesor->id_asesor) }}" class="flex flex-col items-center text-center w-32 group hover:opacity-80 transition">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium"><i class="fas fa-check"></i></div>
                <p class="mt-2 text-xs font-medium text-green-500">Informasi Akun</p>
            </a>
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

        <form action="{{ route('admin.asesor.update.step2', $asesor->id_asesor) }}" method="POST" class="space-y-6">
          @csrf
          @method('PATCH')
          
          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Data Pribadi</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">No Registrasi <span class="text-red-500">*</span></label>
              <input type="text" name="nomor_regis" required 
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" 
                     value="{{ old('nomor_regis', $asesor->nomor_regis) }}">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
              <input type="text" name="nama_lengkap" required 
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" 
                     value="{{ old('nama_lengkap', $asesor->nama_lengkap) }}">
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
              <input type="text" name="nik" required 
                     class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" 
                     value="{{ old('nik', $asesor->nik) }}">
            </div>
          </div>

          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4 pt-4">Informasi Detail</h3>
          
          {{-- Searchable Dropdown Skema (Alpine JS Logic - Adapted for Edit) --}}
          @php 
            // Pastikan format array skema ids valid
            $currentSkemas = old('skema_ids', isset($selectedSkemas) ? $selectedSkemas : []);
            // Konversi ke array integer agar cocok dengan x-model
            $currentSkemas = array_map('intval', $currentSkemas);
          @endphp
          
          <div class="mb-6" x-data="{ 
                  open: false, 
                  search: '', 
                  selected: @json($currentSkemas), 
                  allSkemas: {{ $skemas->map(fn($s)=>['id'=>$s->id_skema,'nama'=>$s->nama_skema])->toJson() }}, 
                  get filtered() { 
                      return this.search === '' 
                          ? this.allSkemas 
                          : this.allSkemas.filter(i => i.nama.toLowerCase().includes(this.search.toLowerCase())) 
                  } 
              }">
              
              <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Sertifikasi (Skema) <span class="text-red-500">*</span></label>
              
              <button type="button" @click="open=!open" @click.away="open=false" 
                      class="w-full p-3 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center outline-none focus:ring-2 focus:ring-blue-500">
                <span x-text="selected.length > 0 ? selected.length + ' Skema Dipilih' : 'Pilih Skema...'" 
                      :class="selected.length > 0 ? 'text-gray-900' : 'text-gray-400'"></span>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="{'rotate-180':open}"></i>
              </button>
              
              <div x-show="open" x-transition x-cloak 
                   class="absolute z-20 w-full max-w-4xl mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-hidden flex flex-col"
                   style="width: inherit;">
                   
                <div class="p-2 border-b bg-gray-50 sticky top-0">
                    <input type="text" x-model="search" placeholder="Cari..." 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="overflow-y-auto flex-1 p-2 space-y-1">
                  <template x-for="skema in filtered" :key="skema.id">
                    <label class="flex items-start p-2 hover:bg-blue-50 rounded cursor-pointer transition-colors">
                      <input type="checkbox" name="skema_ids[]" :value="skema.id" x-model="selected" 
                             class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700" x-text="skema.nama"></span>
                    </label>
                  </template>
                  <div x-show="filtered.length === 0" class="p-4 text-center text-sm text-gray-500">Tidak ditemukan.</div>
                </div>
              </div>
          </div>
          {{-- End Searchable Dropdown --}}
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
               <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
               <input type="text" name="tempat_lahir" placeholder="Kota Kelahiran" required 
                      class="w-full p-3 border border-gray-300 rounded-lg outline-none" 
                      value="{{ old('tempat_lahir', $asesor->tempat_lahir) }}">
            </div>
            <div>
               <!-- PERBAIKAN: Single Date Input -->
               <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
               <input type="date" name="tanggal_lahir" required 
                      class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                      value="{{ old('tanggal_lahir', $asesor->tanggal_lahir) }}">
            </div>

            <div>
               <label class="block text-sm font-medium text-gray-700 mb-2">JK & Kebangsaan <span class="text-red-500">*</span></label>
               <div class="flex space-x-2">
                   <select name="jenis_kelamin" required 
                           class="w-1/2 p-3 border border-gray-300 rounded-lg outline-none bg-white focus:ring-2 focus:ring-blue-500">
                     <option value="">Pilih JK</option>
                     <option value="Laki-laki" {{ old('jenis_kelamin', $asesor->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                     <option value="Perempuan" {{ old('jenis_kelamin', $asesor->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                   </select>
                   <select name="kebangsaan" required 
                           class="w-1/2 p-3 border border-gray-300 rounded-lg outline-none bg-white focus:ring-2 focus:ring-blue-500">
                     <option value="Indonesia" {{ old('kebangsaan', $asesor->kebangsaan) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                     <option value="WNA" {{ old('kebangsaan', $asesor->kebangsaan) == 'WNA' ? 'selected' : '' }}>WNA</option>
                   </select>
               </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                <textarea name="alamat_rumah" required rows="3" 
                          class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('alamat_rumah', $asesor->alamat_rumah) }}</textarea>
            </div>
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Kota/Kab</label>
                <input type="text" name="kabupaten_kota" required 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('kabupaten_kota', $asesor->kabupaten_kota) }}">
            </div>
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Provinsi</label>
                <input type="text" name="provinsi" required 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('provinsi', $asesor->provinsi) }}">
            </div>
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Kode POS</label>
                <input type="text" name="kode_pos" 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('kode_pos', $asesor->kode_pos) }}">
            </div>
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">No HP</label>
                <input type="text" name="nomor_hp" required 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('nomor_hp', $asesor->nomor_hp) }}">
            </div>
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Pekerjaan</label>
                <input type="text" name="pekerjaan" required 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('pekerjaan', $asesor->pekerjaan) }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm mb-1 font-medium text-gray-700">NPWP</label>
                <input type="text" name="NPWP" required 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('NPWP', $asesor->NPWP) }}">
            </div>
          </div>

          <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4 pt-4">Informasi Bank</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Nama Bank</label>
                <input type="text" name="nama_bank" required 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('nama_bank', $asesor->nama_bank) }}">
            </div>
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">No Rekening</label>
                <input type="text" name="norek" required 
                       class="w-full p-3 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('norek', $asesor->norek) }}">
            </div>
          </div>

          <!-- Tombol Navigasi Bawah -->
          <div class="flex items-center justify-between pt-6">
            <a href="{{ route('admin.edit_asesor1', $asesor->id_asesor) }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md transition border border-gray-300 flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition flex items-center">
                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>