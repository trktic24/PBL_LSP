<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Skema | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .ts-control { padding: 0.75rem 1rem; border-radius: 0.5rem; border-color: #D1D5DB; }
        .ts-control:focus-within { border-color: #2563EB; box-shadow: 0 0 0 2px #BFDBFE; }
        .ts-dropdown .option.active { background-color: #EFF6FF; color: #1D4ED8; }
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
                    <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">ADD SKEMA</h1>
                    <div class="w-[80px]"></div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('add_skema.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Skema <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_skema" value="{{ old('nomor_skema') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: SKM/001/2025" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Skema <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_skema" value="{{ old('nama_skema') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: Network Administrator" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select id="categorie_id" name="categorie_id" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                            <input type="number" name="harga" value="{{ old('harga') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Cth: 500000">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Skema <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi_skema" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required placeholder="Jelaskan secara singkat tentang skema ini...">{{ old('deskripsi_skema') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div x-data="{ fileName: '' }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File SKKNI (PDF, Max 5MB)</label>
                            <label class="w-full flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                <span x-text="fileName || 'Upload PDF...'" class="text-sm text-gray-600"></span>
                                <input type="file" name="SKKNI" @change="fileName = $event.target.files[0]?.name" class="hidden" accept=".pdf">
                            </label>
                        </div>
                        <div x-data="{ fileName: '' }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Skema (JPG/PNG)</label>
                            <label class="w-full flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <i class="fas fa-image text-blue-500 mr-3"></i>
                                <span x-text="fileName || 'Upload Gambar...'" class="text-sm text-gray-600"></span>
                                <input type="file" name="gambar" @change="fileName = $event.target.files[0]?.name" class="hidden" accept="image/*">
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 border-t mt-6">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                            Simpan & Lanjut
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#categorie_id', { create: false, sortField: { field: 'text', direction: 'asc' } });
    </script>
</body>
</html>