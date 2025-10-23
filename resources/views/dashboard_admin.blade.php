<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | LSP Polines</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Hilangkan efek scrollbar biru */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background-color: transparent;
        }
        ::-webkit-scrollbar-track {
            background-color: transparent;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="h-screen overflow-y-auto">

        <!-- NAVBAR -->
        <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
            <!-- LOGO -->
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
            </div>

            <!-- MENU TENGAH -->
            <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
                <!-- Dashboard aktif -->
                <a href="#" class="relative text-blue-600 h-full flex items-center">
                    Dashboard
                    <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
                </a>

                <!-- Dropdown Master -->
                <div x-data="{ open: false }" class="relative h-full flex items-center">
                    <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                        <span>Master</span>
                        <i class="fas fa-caret-down ml-2.5 text-sm"></i> <!-- jarak diperlebar -->
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
                        x-transition>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
                    </div>
                </div>

                <a href="#" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">TUK</a>
            </div>

            <!-- PROFIL & NOTIF -->
            <div class="flex items-center space-x-6">
                <!-- Ikon Notifikasi -->
                <a href="{{ route('notifications') }}" 
                    class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)] 
                    hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
                    <i class="fas fa-bell text-xl text-gray-600"></i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border border-white"></span>
                </a>


                <!-- Profil Pengguna -->
                <div class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
                    hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
                    <span class="text-gray-800 font-semibold text-base mr-2">Roihan Enrico</span>
                    <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
                        <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </nav>

        <!-- KONTEN UTAMA -->
        <main class="p-6">
            <p class="text-sm text-gray-500 mb-1">Hi, Roihan’s</p>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h2>

            <!-- SEARCH & FILTER -->
            <div class="flex items-start justify-between mb-8">
                <div class="relative w-1/4">
                    <input type="text" placeholder="Search"
                        class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                </div>
                <div class="flex space-x-1 p-1 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <button class="px-4 py-2 text-gray-800 font-semibold rounded-xl text-sm transition-all"
                            style="background: linear-gradient(to right, #b4e1ff, #d7f89c); box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                            Today
                    </button>
                    <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Year</button>
                    <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Week</button>
                    <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm">Month</button>
                </div>
            </div>

            <!-- KARTU STATISTIK -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Kartu 1 -->
                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-blue-600/30 min-h-[200px]">
                    <!-- ICON KIRI -->
                    <div class="flex justify-center items-center w-1/3">
                        <i class="far fa-calendar-alt text-8xl text-blue-600/80"></i>
                    </div>
                    <!-- TEKS KANAN -->
                    <div class="relative flex-1 h-full flex items-center justify-center">
                        <p class="absolute top-4 text-sm text-gray-500">Asesmen yang sedang berlangsung</p>
                        <p class="text-5xl font-bold text-gray-900">33</p>
                    </div>
                </div>

                <!-- Kartu 2 -->
                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-green-600/30 min-h-[200px]">
                    <div class="flex justify-center items-center w-1/3">
                        <i class="far fa-calendar-check text-8xl text-green-500"></i>
                    </div>
                    <div class="relative flex-1 h-full flex items-center justify-center">
                        <p class="absolute top-4 text-sm text-gray-500">Asesmen yang selesai</p>
                        <p class="text-5xl font-bold text-gray-900">3</p>
                    </div>
                </div>

                <!-- Kartu 3 -->
                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-yellow-600/30 min-h-[200px]">
                    <div class="flex justify-center items-center w-1/3">
                        <i class="fas fa-book-reader text-8xl text-yellow-400"></i>
                    </div>
                    <div class="relative flex-1 h-full flex items-center justify-center">
                        <p class="absolute top-4 text-sm text-gray-500">Jumlah Asesi</p>
                        <p class="text-5xl font-bold text-gray-900">34,567</p>
                    </div>
                </div>

                <!-- Kartu 4 -->
                <div class="bg-white p-6 rounded-xl shadow-lg flex items-center border-b-4 border-red-600/30 min-h-[200px]">
                    <div class="flex justify-center items-center w-1/3">
                        <i class="fas fa-chalkboard-teacher text-8xl text-red-500"></i>
                    </div>
                    <div class="relative flex-1 h-full flex items-center justify-center">
                        <p class="absolute top-4 text-sm text-gray-500">Jumlah Asesor</p>
                        <p class="text-5xl font-bold text-gray-900">90</p>
                    </div>
                </div>
            </section>


            <!-- STATISTIK GRAFIK -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-4 rounded-xl shadow-lg">
                    <h3 class="text-md font-semibold mb-2">Statistik Skema</h3>
                    <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x256/f87171/ffffff?text=Statistik+Skema"
                            alt="Line Chart" class="object-cover w-full h-full">
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-lg">
                    <h3 class="text-md font-semibold mb-2">Statistik Asesi yang Mengikuti Skema</h3>
                    <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x256/3b82f6/ffffff?text=Statistik+Asesi"
                            alt="Bar Chart" class="object-cover w-full h-full">
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-lg">
                    <h3 class="text-md font-semibold mb-2">Progress Skema</h3>
                    <div class="h-64 flex items-center justify-center border border-gray-200 rounded-lg overflow-hidden">
                        <img src="https://via.placeholder.com/400x256/10b981/ffffff?text=Progress+Skema"
                            alt="Doughnut Chart" class="object-cover w-full h-full">
                    </div>
                </div>
            </section>

            <!-- TABEL -->
            <section class="bg-white p-4 rounded-xl shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Course Name</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 mr-3 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-microchip text-blue-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">Data Scientist</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">—</td>
                            <td class="px-6 py-4 text-gray-600">201939</td>
                            <td class="px-6 py-4 text-gray-600">3</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 mr-3 bg-red-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-fingerprint text-red-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">Blockchain Architect</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">name</td>
                            <td class="px-6 py-4 text-gray-600">id</td>
                            <td class="px-6 py-4 text-gray-600">amount</td>
                            <td class="px-6 py-4 text-gray-600">status</td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>
</html>
