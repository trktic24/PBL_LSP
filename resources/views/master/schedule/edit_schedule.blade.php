<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Schedule | LSP Polines</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome CDN for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <!-- Alpine.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
        .btn-tuk.active {
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px currentColor;
        }
        .btn-tambah {
            background-image: linear-gradient(to right, #2563EB, #1D4ED8);
        }
        .btn-tambah:hover {
            background-image: linear-gradient(to right, #1D4ED8, #1E3A8A);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">

        <x-navbar />

        <main class="flex-1 flex justify-center items-start pt-10 pb-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-6 sm:p-10">

                <div class="grid grid-cols-3 items-center mb-8 sm:mb-10">
                    <a href="{{ route('master_schedule') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium justify-self-start">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
<<<<<<< HEAD
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 text-center">EDIT SCHEDULE</h1>
=======
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 text-center flex-1 -ml-12 sm:-ml-20">EDIT SCHEDULE</h1>
>>>>>>> de4ee13127cf7a9342a8b438429ab652ca9296c7
                    <div class="w-16"></div>
                </div>

                <form action="#" method="POST" class="space-y-6">
                    
                    <!-- Nama Skema & Kode Unit Skema -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Skema <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_skema" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Nama Skema">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Unit Skema <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_skema" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan Kode Unit Skema">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <input type="text" name="deskripsi" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan deskripsi skema">
                    </div>

                    <!-- ✅ Jenis TUK (Sudah bisa dipilih) -->
                    <div x-data="{ jenisTuk: 'Sewaktu' }">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis TUK <span class="text-red-500">*</span></label>

                        <div class="flex space-x-4">
                            <!-- Sewaktu -->
                            <button type="button"
                                @click="jenisTuk = 'Sewaktu'"
                                :class="jenisTuk === 'Sewaktu' ? 'bg-blue-600 text-white btn-tuk active' : 'bg-gray-200 text-gray-700'"
                                class="btn-tuk font-semibold py-2 px-4 rounded-lg text-sm transition">
                                Sewaktu
                            </button>

                            <!-- Tempat Kerja -->
                            <button type="button"
                                @click="jenisTuk = 'Tempat Kerja'"
                                :class="jenisTuk === 'Tempat Kerja' ? 'bg-yellow-500 text-white btn-tuk active' : 'bg-gray-200 text-gray-700'"
                                class="btn-tuk font-semibold py-2 px-4 rounded-lg text-sm transition">
                                Tempat Kerja
                            </button>
                        </div>

                        <!-- Hidden field untuk dikirim ke backend -->
                        <input type="hidden" name="jenis_tuk" :value="jenisTuk">
                    </div>

                    <!-- Jadwal & Jam -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal <span class="text-red-500">*</span></label>
                            <input type="text" name="jadwal" placeholder="DD/MM/YYYY" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam <span class="text-red-500">*</span></label>
                            <input type="text" name="jam" placeholder="HH:MM" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Asesor & Asesi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Asesor <span class="text-red-500">*</span></label>
                            <input type="text" name="asesor" required placeholder="Search" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Asesi <span class="text-red-500">*</span></label>
                            <input type="text" name="asesi" required placeholder="Search" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-4 flex justify-center">
                        <button type="submit" class="btn-tambah w-64 py-3 text-white font-semibold rounded-lg shadow-md">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</body>
</html>