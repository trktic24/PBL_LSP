<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Skema | LSP Polines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        ::-webkit-scrollbar { display: none; }
        
        /* TomSelect Custom Styles */
        .ts-control {
            padding: 0.75rem !important;
            border-radius: 0.5rem !important;
            border-color: #d1d5db !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            min-height: 50px;
            background-color: #fff !important;
            display: flex;
            align-items: center;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px #3b82f6 !important;
            outline: none !important;
            z-index: 10;
        }
        .ts-dropdown {
            border-radius: 0.5rem;
            border-color: #d1d5db;
            margin-top: 4px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }
        .ts-dropdown .option { padding: 0.75rem 1rem; font-size: 1rem; }
        .ts-dropdown .option.active { background-color: #eff6ff; color: #1d4ed8; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar_admin/>
        <main class="flex-1 flex justify-center items-start pt-10 pb-12">
            <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">
                
                <div class="flex items-center justify-between mb-10">
                    <a href="{{ route('admin.master_skema') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">EDIT SKEMA</h1>
                    <div class="w-[80px]"></div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.update_skema', $skema->id_skema) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Skema <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_skema" value="{{ old('nomor_skema', $skema->nomor_skema) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Skema <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_skema" value="{{ old('nama_skema', $skema->nama_skema) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id" required 
                                    data-initial-value="{{ old('category_id', $skema->category_id) }}">
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="harga" value="{{ old('harga', $skema->harga) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Skema <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi_skema" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>{{ old('deskripsi_skema', $skema->deskripsi_skema) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div x-data="{ fileName: '' }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                File SKKNI (PDF) 
                                <span class="text-gray-400 font-normal ml-1 text-xs">(Biarkan kosong jika tidak diubah)</span>
                            </label>
                            
                            <label class="w-full flex items-center px-4 py-3 bg-white border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                          @error('SKKNI') border-red-500 ring-1 ring-red-500 @else border-gray-300 @enderror">
                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                <span x-text="fileName || 'Ganti File PDF...'" class="text-sm text-gray-600"></span>
                                
                                <input type="file" name="SKKNI" @change="fileName = $event.target.files[0]?.name" class="hidden" accept=".pdf">
                            </label>
                            
                            @error('SKKNI')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            {{-- PRATINJAU DENGAN FORMAT BARU --}}
                            @if($skema->SKKNI)
                                <div class="mt-2 text-sm text-gray-600">
                                    <p class="font-medium">File Saat Ini:</p>
                                    <a href="{{ asset($skema->SKKNI) }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ basename($skema->SKKNI) }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div x-data="{ fileName: '' }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Skema 
                                <span class="text-gray-400 font-normal ml-1 text-xs">(Biarkan kosong jika tidak diubah)</span>
                            </label>

                            <label class="w-full flex items-center px-4 py-3 bg-white border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                          @error('gambar') border-red-500 ring-1 ring-red-500 @else border-gray-300 @enderror">
                                <i class="fas fa-image text-blue-500 mr-3"></i>
                                <span x-text="fileName || 'Ganti Gambar...'" class="text-sm text-gray-600"></span>
                                
                                <input type="file" name="gambar" @change="fileName = $event.target.files[0]?.name" class="hidden" accept="image/*">
                            </label>

                            @error('gambar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            {{-- PRATINJAU DENGAN FORMAT BARU --}}
                            @if($skema->gambar)
                                <div class="mt-2 text-sm text-gray-600">
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Current Image</label>
                                    @php
                                        $previewImg = $skema->gambar ? asset('storage/' . $skema->gambar) : asset('images/default.jpg');
                                    @endphp
                                    <img src="{{ $previewImg }}" 
                                         alt="Current Image" 
                                         class="h-40 rounded-lg border border-gray-200 object-cover"
                                         onerror="this.onerror=null;this.src='{{ asset('images/default.jpg') }}';">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-6 border-t mt-6">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('category_id');
            
            // 1. Ambil nilai kategori yang seharusnya dipilih dari data-attribute
            const initialValue = selectElement.getAttribute('data-initial-value');

            // 2. Inisialisasi Tom Select
            const ts = new TomSelect(selectElement, { 
                create: false, 
                sortField: { field: 'text', direction: 'asc' },
            });

            // 3. Paksa Tom Select memilih nilai awal menggunakan setValue
            // Cek jika nilai tidak kosong atau null.
            if (initialValue && initialValue !== '') {
                ts.setValue(initialValue);
            }
        });
    </script>
</body>
</html>