<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Skema | LSP Polines</title>

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

        <div class="flex items-center justify-between mb-10">
          <a href="{{ route('master_skema') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Back
          </a>
          <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">EDIT SKEMA</h1>
          <div class="w-[80px]"></div>
        </div>

        <form action="{{ route('update_skema', $skema->id_skema) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          
          <div>
            <label for="nama_skema" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Skema <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_skema" name="nama_skema" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   placeholder="Masukkan Nama Skema"
                   value="{{ old('nama_skema', $skema->nama_skema) }}">
          </div>

          <div>
            <label for="kode_unit" class="block text-sm font-medium text-gray-700 mb-2">
              Kode Unit Skema <span class="text-red-500">*</span>
            </label>
            <input type="text" id="kode_unit" name="kode_unit" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   placeholder="Masukkan Kode Unit Skema"
                   value="{{ old('kode_unit', $skema->kode_unit) }}">
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div x-data="{ fileName: '' }">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Gambar Skema <span class="text-gray-400">(Opsional)</span>
              </label>
              <label class="w-full flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500">
                <i class="fas fa-upload text-gray-500 mr-3"></i>
                <span x-text="fileName || 'Pilih gambar baru...'" class="text-sm text-gray-600 truncate"></span>
                <input type="file" name="gambar_skema" @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''" class="opacity-0 absolute w-0 h-0" />
              </label>
              @if($skema->gambar)
                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ Storage::url($skema->gambar) }}" target="_blank" class="text-blue-500 hover:underline">Lihat Gambar</a></p>
              @endif
            </div>

            <div x-data="{ fileName: '' }">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                File SKKNI <span class="text-gray-400">(PDF)</span>
              </label>
              <label class="w-full flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500">
                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                <span x-text="fileName || 'Pilih file PDF baru...'" class="text-sm text-gray-600 truncate"></span>
                <input type="file" name="file_skkni" @change="fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''" class="opacity-0 absolute w-0 h-0" accept=".pdf" />
              </label>
              @if($skema->SKKNI)
                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ Storage::url($skema->SKKNI) }}" target="_blank" class="text-blue-500 hover:underline">Lihat PDF</a></p>
              @endif
            </div>
          </div>

          <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
              Deskripsi <span class="text-red-500">*</span>
            </label>
            <textarea id="deskripsi" name="deskripsi" required
                      class="w-full p-3 border border-gray-300 rounded-lg h-28 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"
                      placeholder="Masukkan deskripsi skema">{{ old('deskripsi', $skema->deskripsi_skema) }}</textarea>
          </div>

          <div class="pt-4">
            <button type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
              Simpan Perubahan
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>