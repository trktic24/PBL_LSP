<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form | LSP Polines</title>

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
        .fade-enter {
            opacity: 0;
            transform: translateY(-10px);
        }
        .fade-enter-active {
            transition: all 0.3s ease;
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
<div class="h-screen overflow-y-auto">
    <!-- ================= NAVBAR ================= -->
    <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
        </div>

        <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
            <a href="{{ route('dashboard') }}" class="relative text-gray-600 hover:text-blue-600 h-full flex items-center transition">Dashboard</a>

            <div x-data="{ open: false }" class="relative h-full flex items-center">
                <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                    <span>Master</span>
                    <i class="fas fa-caret-down ml-2.5 text-sm"></i>
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
                <a href="{{ route('user_profile_settings') }}" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                    <i class="fas fa-user-circle mr-3 text-lg"></i> Profile Settings
                </a>
                <a href="{{ route('user_profile_form') }}" class="flex items-center p-3 text-sm font-semibold rounded-xl bg-blue-600 text-white shadow">
                    <i class="far fa-clipboard mr-3 text-lg"></i> Form
                </a>
                <a href="#" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
                    <i class="fas fa-history mr-3 text-lg"></i> Riwayat Aktivitas
                </a>
                <a href="{{ route('user_profile_bukti') }}" class="flex items-center p-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-100 transition">
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
            <div x-data="{ open: false, active: null }" class="bg-white p-10 rounded-xl shadow-xl">
                <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Formulir Pendaftaran Sertifikasi</h2>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <template x-for="form in ['FR.APL.01', 'FR.APL.02', 'FR.MAPA.01', 'FR.AK.01', 'FR.AK.02', 'FR.AK.03', 'FR.AK.04', 'FR.AK.05']" :key="form">
                        <button
                            @click="active = form"
                            :class="active === form ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600'"
                            class="py-4 rounded-xl font-semibold text-center shadow transition-all duration-300"
                            :style="active && active !== form ? 'opacity:0.5' : 'opacity:1'">
                            <i class="fas fa-file-alt mr-2"></i> <span x-text="form"></span>
                        </button>
                    </template>
                </div>

                <!-- DROPDOWN -->
                <div class="mt-8 text-center">
                    <button @click="open = !open"
                            class="text-blue-600 font-semibold text-sm hover:underline transition-all">
                        <span x-show="!open">View More <i class="fas fa-chevron-down"></i></span>
                        <span x-show="open">View Less <i class="fas fa-chevron-up"></i></span>
                    </button>
                </div>

                <div x-show="open" x-transition class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-6">
                    <template x-for="form in ['FR.AK.06', 'FR.IA.01', 'FR.IA.02', 'FR.IA.03', 'FR.IA.04', 'FR.IA.05', 'FR.IA.06', 'FR.IA.07', 'FR.IA.08', 'FR.IA.09', 'FR.IA.10', 'FR.IA.11']" :key="form">
                        <button
                            @click="active = form"
                            :class="active === form ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600'"
                            class="py-4 rounded-xl font-semibold text-center shadow transition-all duration-300"
                            :style="active && active !== form ? 'opacity:0.5' : 'opacity:1'">
                            <i class="fas fa-file-alt mr-2"></i> <span x-text="form"></span>
                        </button>
                    </template>
                </div>

                <!-- FORM BUTTON -->
                <div class="mt-10 text-center">
                    <button type="button"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg shadow-md font-semibold transition-all">
                        Buka Form Terpilih
                    </button>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
