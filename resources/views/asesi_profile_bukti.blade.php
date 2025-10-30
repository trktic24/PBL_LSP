<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kelengkapan | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
        .custom-input {
            @apply px-4 py-2 border border-gray-300 rounded-lg w-full bg-gray-100 text-gray-600 text-sm;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="h-screen overflow-y-auto">
        <!-- ================= NAVBAR (TIDAK DIUBAH) ================= -->
        <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
            </div>

            <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
                <a href="{{ route('dashboard') }}" class="relative text-gray-600 hover:text-blue-600 h-full flex items-center transition">Dashboard</a>

                <div x-data="{ open: false }" class="relative h-full flex items-center">
                    <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                        <span>Master</span>
                        <i :class="open ? 'fas fa-caret-up ml-2.5 text-sm' : 'fas fa-caret-down ml-2.5 text-sm'"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95">
                        <a href="{{ route('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
                        <a href="{{ route('master_asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
                        <a href="{{ route('master_asesi') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
                    </div>
                </div>

                <a href="{{ route('schedule_admin') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>
                <a href="{{ route('tuk_sewaktu') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">TUK</a>
            </div>

            <div class="flex items-center space-x-6">
                <a href="{{ route('notifications') }}"
                    class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)]
                    hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
                    <i class="fas fa-bell text-xl text-gray-600"></i>
                    <span class="absolute top-2 right-2">
                        <span class="relative flex size-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex size-3 rounded-full bg-red-500"></span>
                        </span>
                    </span>
                </a>

                <a href="{{ route('profile_admin') }}"
                class="flex items-center space-x-3 bg-white border border-blue-400 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)]
                hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
                    <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
                    <div class="h-10 w-10 rounded-full border-2 border-blue-500 overflow-hidden shadow-inner">
                        <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
                    </div>
                </a>
            </div>
        </nav>

        <!-- =================== MAIN CONTENT =================== -->
        <main class="flex min-h-[calc(100vh-80px)] bg-gray-50">
            <!-- SIDEBAR (TIDAK DIUBAH) -->
            <aside class="w-1/4 p-6 bg-white shadow-lg border-r border-gray-200">
                <div class="p-6 bg-blue-50 rounded-xl mb-6 shadow">
                    <h3 class="text-xl font-bold text-blue-900 mb-4">Biodata</h3>
                    <div class="flex flex-col items-center">
                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-md mb-3">
                            <img src="{{ asset('images/profile.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
                        </div>
                        <p class="text-lg font-bold text-gray-900">{{ $user->nama_lengkap ?? 'Roihan Enrico' }}</p>
                        <p class="text-sm text-gray-600">{{ $user->pekerjaan ?? 'Data Scientist' }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="asesi_profile_settings" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                        <i class="fas fa-asesi-circle mr-3 text-lg"></i> Profile Settings
                    </a>
                    <a href="asesi_profile_form" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                        <i class="far fa-clipboard mr-3 text-lg"></i> Form
                    </a>
                    <a href="#" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                        <i class="fas fa-history mr-3 text-lg"></i> Lacak Aktivitas
                    </a>
                    <a href="asesi_profile_bukti" class="flex items-center p-3 text-sm font-semibold rounded-xl bg-blue-600 text-white shadow">
                        <i class="fas fa-check-square mr-3 text-lg"></i> Bukti Kelengkapan
                    </a>
                </div>

                <div class="flex space-x-2 mt-6">
                    <button class="flex-1 py-2 rounded-xl text-sm font-semibold text-white bg-blue-500 shadow hover:bg-blue-600 transition">Asesi</button>
                    <button class="flex-1 py-2 rounded-xl text-sm font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition">Asesor</button>
                </div>
            </aside>

            <!-- =================== CONTENT =================== -->
            <section class="flex-1 p-10">
                <div class="bg-white p-10 rounded-xl shadow-xl">
                    <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">Bukti Kelengkapan</h2>

                    <!-- ====== DAFTAR SERTIFIKAT ====== -->
                    <div class="space-y-5">
                        @for($i = 0; $i < 3; $i++)
                        <div class="border border-gray-200 rounded-xl shadow-sm">
                            <div class="flex items-center justify-between px-6 py-4 bg-gray-100 rounded-t-xl">
                                <h3 class="font-semibold text-gray-700">Sertifikasi Pelatihan Polines</h3>
                                <span class="text-sm text-red-500 font-semibold">1 berkas</span>
                            </div>
                            <div class="p-6 space-y-3">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-14 flex items-center justify-center bg-blue-100 text-blue-600 font-semibold rounded-lg text-lg">
                                        .png
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-700 text-sm">Sertifikat Kompetensi Polines</p>
                                        <p class="text-gray-500 text-xs">Keterangan:</p>
                                        <p class="text-gray-600 text-xs">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg text-sm font-medium shadow">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-medium shadow">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <!-- ====== TANDA TANGAN PEMOHON ====== -->
                    <div class="mt-10 border-t pt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Tanda Tangan Pemohon</h3>
                            <div class="flex space-x-2">
                                <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg shadow text-sm font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow text-sm font-semibold">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </div>
                        </div>

                        <div class="text-sm leading-relaxed text-gray-700 space-y-2">
                            <p><span class="font-semibold">Nama</span> : Roihan Saputra</p>
                            <p><span class="font-semibold">Jabatan</span> : Wakil Presiden</p>
                            <p><span class="font-semibold">Perusahaan</span> : Roihan Company</p>
                            <p><span class="font-semibold">Alamat Perusahaan</span> : Jakarta, Mranggen</p>
                        </div>

                        <p class="mt-5 text-gray-700 text-sm">
                            Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan
                            sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
                        </p>

                        <div class="mt-6 border border-gray-300 rounded-lg w-full max-w-md h-40 flex items-center justify-center relative">
                            <img src="{{ asset('images/ttd_sample.png') }}" alt="Tanda Tangan" class="object-contain h-24">
                        </div>
                        <p class="text-xs text-red-500 mt-2">*Tanda Tangan di sini</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
