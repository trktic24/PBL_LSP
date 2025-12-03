<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Berita | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                    <a href="{{ route('master_berita') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">EDIT BERITA</h1>
                    <div class="w-[80px]"></div> 
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
                        <div class="font-bold mb-1"><i class="fas fa-exclamation-circle mr-2"></i> Terdapat Kesalahan:</div>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('update_berita', $berita->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}" 
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition shadow-sm" 
                                   required>
                        </div>

                        <div x-data="{ fileName: '' }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama <span class="text-gray-400 font-normal">(Biarkan kosong jika tidak diubah)</span></label>
                            
                            <label class="w-full flex items-center px-4 py-3 bg-white border rounded-lg cursor-pointer hover:bg-gray-50 transition border-gray-300">
                                <i class="fas fa-image text-blue-500 mr-3"></i>
                                <span x-text="fileName || 'Ganti gambar...'" class="text-sm text-gray-600 truncate"></span>
                                <input type="file" name="gambar" @change="fileName = $event.target.files[0]?.name" class="hidden" accept="image/*">
                            </label>

                            @if($berita->gambar)
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                                    <img src="{{ asset($berita->gambar) }}" alt="Preview" class="h-24 rounded-lg border border-gray-200 object-cover">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Berita / Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="isi" rows="10" 
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition shadow-sm resize-y" 
                                  required>{{ old('isi', $berita->isi) }}</textarea>
                    </div>

                    <div class="pt-6 border-t mt-6">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition flex justify-center items-center text-lg">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>