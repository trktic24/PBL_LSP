<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule | LSP Polines</title>

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
        ::-webkit-scrollbar {
            width: 0;
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
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">
                    Dashboard
                </a>

                <!-- Dropdown Master -->
                <div x-data="{ open: false }" class="relative h-full flex items-center">
                    <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                        <span>Master</span>
                        <i class="fas fa-caret-down ml-2.5 text-sm"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
                        x-transition>
                        <a href="{{ route('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
                    </div>
                </div>

                <!-- Schedule Aktif (dengan garis biru di bawahnya) -->
                <a href="{{ route('schedule_admin') }}" class="relative text-blue-600 h-full flex items-center">
                    Schedule
                    <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
                </a>

                <a href="{{ route('tuk_sewaktu') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">TUK</a>
            </div>

            <!-- PROFIL & NOTIF -->
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
                    class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
                    hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
                    <span class="text-gray-800 font-semibold text-base mr-2">Admin LSP</span>
                    <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
                        <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
                    </div>
                </a>
            </div>
        </nav>

        <!-- KONTEN SCHEDULE -->
        <main class="p-8">

            <p class="text-sm text-gray-500 mb-1">Hi, Admin LSP</p>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Schedule</h2>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Kalender -->
                <div class="bg-white rounded-xl shadow-lg p-4 w-full lg:w-2/3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">October 15 - 21, 2025</h3>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded-lg text-sm">Today</button>
                            <button class="px-3 py-1 border rounded-lg text-sm bg-blue-500 text-white">Week</button>
                            <button class="px-3 py-1 border rounded-lg text-sm">Month</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center text-sm font-medium">
                        <div class="p-2 bg-blue-50 rounded-lg border border-blue-300 text-blue-600 font-semibold relative">
                            6 <br>
                            <span class="absolute bottom-1 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">Cybersecurity</span>
                        </div>
                        <div class="p-2">7</div>
                        <div class="p-2">8</div>
                        <div class="p-2">9</div>
                        <div class="p-2">10</div>
                        <div class="p-2">11</div>
                        <div class="p-2">12</div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white rounded-xl shadow-lg p-4 w-full lg:w-1/3">
                    <h3 class="font-semibold text-lg mb-3">Status</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Confirm</li>
                        <li class="flex items-center"><span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span> Pending</li>
                        <li class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-400 mr-2"></span> Reschedule</li>
                        <li class="flex items-center"><span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span> Cancel</li>
                    </ul>
                </div>
            </div>

            <!-- Tabel Schedule -->
            <div class="bg-white mt-8 p-4 rounded-xl shadow-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-600 uppercase text-xs">
                            <th class="px-4 py-3 text-left">Id</th>
                            <th class="px-4 py-3 text-left">Tanggal Pelaksanaan</th>
                            <th class="px-4 py-3 text-left">Kode Unit Skema</th>
                            <th class="px-4 py-3 text-left">Nama Skema</th>
                            <th class="px-4 py-3 text-left">Jenis TUK</th>
                            <th class="px-4 py-3 text-left">Gelombang</th>
                            <th class="px-4 py-3 text-left">Daftar Asesor</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2">1</td>
                            <td class="px-4 py-2">15/12/2025</td>
                            <td class="px-4 py-2">2609051226</td>
                            <td class="px-4 py-2 font-medium">Cybersecurity</td>
                            <td class="px-4 py-2">Mandiri, Sewaktu, dan Tempat Kerja</td>
                            <td class="px-4 py-2">1</td>
                            <td class="px-4 py-2">Rohian Enrico<br>Rafa Saputra<br>Zulfikar Pujianga</td>
                            <td class="px-4 py-2"><button class="px-3 py-1 bg-blue-500 text-white text-xs rounded-lg">Detail</button></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">2</td>
                            <td class="px-4 py-2">15/12/2025</td>
                            <td class="px-4 py-2">2609051226</td>
                            <td class="px-4 py-2 font-medium">Computer Vision</td>
                            <td class="px-4 py-2">Mandiri & Tempat Kerja</td>
                            <td class="px-4 py-2">2</td>
                            <td class="px-4 py-2">Rohian Enrico<br>Rafa Saputra<br>Zulfikar Pujianga</td>
                            <td class="px-4 py-2"><button class="px-3 py-1 bg-blue-500 text-white text-xs rounded-lg">Detail</button></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">3</td>
                            <td class="px-4 py-2">15/12/2025</td>
                            <td class="px-4 py-2">2609051226</td>
                            <td class="px-4 py-2 font-medium">MySQL Database</td>
                            <td class="px-4 py-2">Sewaktu & Tempat Kerja</td>
                            <td class="px-4 py-2">3</td>
                            <td class="px-4 py-2">Rohian Enrico<br>Rafa Saputra<br>Zulfikar Pujianga</td>
                            <td class="px-4 py-2"><button class="px-3 py-1 bg-blue-500 text-white text-xs rounded-lg">Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
