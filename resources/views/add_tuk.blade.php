<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add TUK | LSP Polines</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Font Poppins -->
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

        <!-- NAVBAR -->
        <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
            <div class="flex items-center space-x-4">
                <a href="{{ url('dashboard') }}">
                    <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
                </a>
            </div>

            <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
                <a href="{{ url('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Dashboard</a>

                <div x-data="{ open: false }" class="relative h-full flex items-center">
                    <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition h-full relative">
                        <span>Master</span>
                        <i :class="open ? 'fas fa-caret-up ml-2.5 text-sm' : 'fas fa-caret-down ml-2.5 text-sm'"></i>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
                        x-transition>
                        <a href="{{ url('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
                        <a href="{{ url('master_asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
                        <a href="{{ url('master_asesi') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
                    </div>
                </div>

                <a href="{{ url('schedule_admin') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>

                <!-- TUK aktif -->
                <a href="{{ url('tuk_sewaktu') }}" class="text-blue-600 h-full flex items-center relative">
                    TUK
                    <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
                </a>
            </div>

            <div class="flex items-center space-x-6">
                <!-- Notifikasi -->
                <a href="{{ url('notifications') }}"
                    class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-md 
                    hover:shadow-inner transition-all">
                    <i class="fas fa-bell text-xl text-gray-600 relative top-[1px]"></i>
                    <span class="absolute top-[9px] right-[9px]">
                        <span class="relative flex w-2 h-2">
                            <span class="absolute inline-flex w-full h-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex w-2 h-2 rounded-full bg-red-500"></span>
                        </span>
                    </span>
                </a>

                <!-- Profil -->
                <a href="{{ url('profile_admin') }}"
                    class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-md 
            hover:shadow-inner transition-all">
                    <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
                    <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
                        <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
                    </div>
                </a>
            </div>
        </nav>

        <!-- FORM CARD -->
        <main class="flex-1 flex justify-center items-start pt-10 pb-12">
            <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

                <!-- HEADER seperti Account Settings -->
                <div class="flex items-center justify-between mb-10">
                    <a href="{{ url('tuk_sewaktu') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">ADD TUK</h1>
                    <div class="w-[80px]"></div>
                </div>

                <!-- FORM -->
                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_ruangan" name="nama_ruangan" required
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Masukkan Nama Ruangan" />
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        </div>

                        <div>
                            <label for="jam" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="jam" name="jam" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        </div>
                    </div>

                    <div>
                        <label for="nama_skema" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Skema <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_skema" name="nama_skema" required
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Masukkan Nama Skema" />
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="daftar_asesor" class="block text-sm font-medium text-gray-700 mb-2">
                                Daftar Asesor <span class="text-red-500">*</span>
                            </label>
                            <select id="daftar_asesor" name="daftar_asesor" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">Pilih asesor</option>
                                <option>Asesor 1</option>
                                <option>Asesor 2</option>
                            </select>
                        </div>

                        <div>
                            <label for="daftar_asesi" class="block text-sm font-medium text-gray-700 mb-2">
                                Daftar Asesi <span class="text-red-500">*</span>
                            </label>
                            <select id="daftar_asesi" name="daftar_asesi" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">Pilih asesi</option>
                                <option>Asesi 1</option>
                                <option>Asesi 2</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="slot" class="block text-sm font-medium text-gray-700 mb-2">
                            Slot <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="slot" name="slot" required min="1"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Masukkan jumlah slot" />
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                            Tambah
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>

</html>