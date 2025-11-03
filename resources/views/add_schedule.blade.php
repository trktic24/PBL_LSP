<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Schedule | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
        .btn-tuk.active {
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px currentColor;
        }
        .btn-tambah {
            background-image: linear-gradient(to right, #2563EB, #1D4ED8);
            @apply shadow-md transition duration-200 ease-in-out;
        }
        .btn-tambah:hover {
            background-image: linear-gradient(to right, #1D4ED8, #1E3A8A);
            @apply shadow-lg;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
<div class="min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-1 flex justify-center items-start pt-10 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-6 sm:p-10">

            <div class="flex items-center justify-between mb-8 sm:mb-10">
                <a href="{{ route('add_schedule') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 text-center flex-1 -ml-12 sm:-ml-20">ADD SCHEDULE</h1>
                <div class="w-16"></div>
            </div>

            <form action="#" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Skema <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_skema" name="nama_skema" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Nama Skema">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Unit Skema <span class="text-red-500">*</span></label>
                        <input type="text" id="kode_skema" name="kode_skema" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Kode Unit Skema">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                    <input type="text" id="deskripsi" name="deskripsi" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan deskripsi skema">
                </div>

                <!-- ✅ Jenis TUK dynamic menggunakan Alpine.js -->
                <div x-data="{ jenisTuk: 'Sewaktu' }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tuk <span class="text-red-500">*</span></label>
                    <div class="flex space-x-4">
                        <button type="button"
                            @click="jenisTuk = 'Sewaktu'"
                            :class="jenisTuk === 'Sewaktu' ? 'bg-blue-600 text-white btn-tuk active' : 'bg-gray-200 text-gray-700'"
                            class="btn-tuk font-semibold py-2 px-4 rounded-lg text-sm transition">
                            Sewaktu
                        </button>

                        <button type="button"
                            @click="jenisTuk = 'Tempat Kerja'"
                            :class="jenisTuk === 'Tempat Kerja' ? 'bg-yellow-500 text-white btn-tuk active' : 'bg-gray-200 text-gray-700'"
                            class="btn-tuk font-semibold py-2 px-4 rounded-lg text-sm transition">
                            Tempat Kerja
                        </button>
                    </div>

                    <input type="hidden" name="jenis_tuk" :value="jenisTuk">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal <span class="text-red-500">*</span></label>
                        <input type="text" id="jadwal" name="jadwal" placeholder="DD/MM/YYYY" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam <span class="text-red-500">*</span></label>
                        <input type="text" id="jam" name="jam" placeholder="HH:MM" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Asesor <span class="text-red-500">*</span></label>
                        <input type="text" id="asesor" name="asesor" required placeholder="Search" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Asesi <span class="text-red-500">*</span></label>
                        <input type="text" id="asesi" name="asesi" required placeholder="Search" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="pt-4 flex justify-center">
                    <button type="submit" class="btn-tambah w-64 py-3 text-white font-semibold rounded-lg shadow-md">
                        Tambah
                    </button>
                </div>
            </form>

        </div>
    </main>
</div>
</body>
</html>