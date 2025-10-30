<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 0;
        }
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
            <!-- SIDEBAR -->
            <aside class="w-1/4 p-6 bg-white shadow-lg border-r border-gray-200">
                <div class="p-6 bg-blue-50 rounded-xl mb-6 shadow">
                    <h3 class="text-xl font-bold text-blue-900 mb-4">Biodata</h3>
                    <div class="flex flex-col items-center">
                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-md mb-3">
                            <img src="{{ asset('images/profile.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
                        </div>
                        <p class="text-lg font-bold text-gray-900">{{ $user->nama_lengkap ?? 'Nama User' }}</p>
                        <p class="text-sm text-gray-600">{{ $user->pekerjaan ?? 'Belum diatur' }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('asesi_profile_settings') }}" class="flex items-center p-3 text-sm font-semibold rounded-xl bg-blue-600 text-white shadow">
                        <i class="fas fa-user-circle mr-3 text-lg"></i> Profile Settings
                    </a>
                    <a href="{{ route('asesi_profile_form') }}" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                        <i class="far fa-clipboard mr-3 text-lg"></i> Form
                    </a>
                    <a href="#" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                        <i class="fas fa-history mr-3 text-lg"></i> Riwayat Aktivitas
                    </a>
                    <a href="{{ route('asesi_profile_settings') }}" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                        <i class="fas fa-check-square mr-3 text-lg"></i> Bukti Kelengkapan
                    </a>
                </div>

                <div class="flex space-x-2 mt-6">
                    <button class="flex-1 py-2 rounded-xl text-sm font-semibold text-white bg-blue-500 shadow hover:bg-blue-600 transition">Asesi</button>
                    <button class="flex-1 py-2 rounded-xl text-sm font-semibold text-gray-700 bg-gray-200 hover:bg-gray-300 transition">Asesor</button>
                </div>
            </aside>

            <!-- CONTENT -->
            <section class="flex-1 p-10">
                <div class="bg-white p-10 rounded-xl shadow-xl">
                    <div class="flex flex-col items-center mb-10">
                        <h2 class="text-3xl font-bold text-gray-800 mb-5">Profile Settings</h2>
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 shadow-md">
                            <img src="{{ asset('images/profile.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
                        </div>
                        <p class="text-xl font-bold text-gray-900 mt-3">{{ $user->nama_lengkap ?? 'Nama User' }}</p>
                        <p class="text-sm text-gray-600">{{ $user->pekerjaan ?? 'Belum diatur' }}</p>
                    </div>

                    <!-- FORM -->
                    <form class="space-y-6">
                        <h4 class="text-md font-bold text-gray-800">Informasi Pribadi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" value="{{ $user->nama_lengkap ?? '' }}" class="custom-input" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Keluarga</label>
                                <input type="text" value="{{ $user->nik ?? '' }}" class="custom-input" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                <input type="text" value="{{ $user->nomor_telepon ?? '' }}" class="custom-input" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                <input type="date" value="{{ $user->tanggal_lahir ?? '' }}" class="custom-input" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kualifikasi Pendidikan</label>
                                <input type="text" value="{{ $user->pendidikan ?? '' }}" class="custom-input" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <input type="text" value="{{ $user->jenis_kelamin ?? '' }}" class="custom-input" readonly>
                            </div>
                        </div>

                        <h4 class="text-md font-bold text-gray-800 pt-4">Alamat Lengkap</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota / Kabupaten</label>
                                <input type="text" value="{{ $user->kota ?? '' }}" class="custom-input" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                <input type="text" value="{{ $user->provinsi ?? '' }}" class="custom-input" readonly>
                            </div>
                        </div>

                        <h4 class="text-md font-bold text-gray-800 pt-4">Akun</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" value="{{ $user->email ?? '' }}" class="custom-input" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" value="********" class="custom-input" readonly>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
