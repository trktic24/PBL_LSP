<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Kategori | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 0;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">

        <x-navbar.navbar_admin/>
        
        <main class="flex-1 flex justify-center items-start pt-10 pb-12">
            <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

                <div class="flex items-center justify-between mb-10">
                    <a href="{{ route('master_category') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">EDIT KATEGORI</h1>
                    <div class="w-[80px]"></div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg">
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="list-disc pl-5 mt-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('update_category', $category->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama_kategori" 
                            name="nama_kategori" 
                            required
                            autofocus
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Contoh: Cloud Computing" 
                            value="{{ old('nama_kategori', $category->nama_kategori) }}" 
                        />
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            readonly 
                            disabled
                            class="w-full p-3 border border-gray-200 bg-gray-100 text-gray-500 rounded-lg focus:outline-none"
                            value="{{ $category->slug }}" 
                        />
                         <p class="mt-2 text-xs text-gray-500">
                            Slug tidak dapat diubah untuk menjaga integritas URL (SEO).
                        </p>
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