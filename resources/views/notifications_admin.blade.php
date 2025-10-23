<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | LSP Polines</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Dashboard</a>

                <div x-data="{ open: false }" class="relative h-full flex items-center">
                    <button @click="open = !open" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                        <span>Master</span>
                        <i class="fas fa-caret-down ml-2.5 text-sm"></i>
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
                    <i class="fas fa-bell text-xl text-blue-600"></i>
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
            <!-- HEADER -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-900 flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-blue-600 transition">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <span>Notifikasi</span>
                </h2>
            </div>

            <!-- FILTER -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex space-x-3">
                    <button class="px-5 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 shadow-md shadow-blue-500/50 hover:bg-blue-700 transition duration-150 active:scale-95">
                        Gmail
                    </button>
                    <button class="px-5 py-2 text-sm font-medium rounded-lg text-white bg-green-500 shadow-md shadow-green-500/50 hover:bg-green-600 transition duration-150 active:scale-95">
                        Whatsapp
                    </button>
                </div>

                <button class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150 active:scale-95">
                    Mark All Read
                </button>
            </div>

            <!-- DAFTAR NOTIFIKASI -->
            <div class="space-y-4 max-w-5xl mx-auto">
                @php
                    $notifications = [
                        ['title' => 'Penilaian Asesi Baru', 'message' => 'Ada 3 asesi baru yang perlu dinilai.', 'time' => '2 hours ago', 'is_read' => false],
                        ['title' => 'Skema Baru Ditambahkan', 'message' => 'Skema "Teknisi Listrik" telah ditambahkan.', 'time' => '3 hours ago', 'is_read' => false],
                        ['title' => 'Sertifikasi Selesai', 'message' => 'Proses sertifikasi Asesor A telah selesai.', 'time' => '1 day ago', 'is_read' => true],
                        ['title' => 'Data Asesi Diperbarui', 'message' => 'Profil asesi Budi Santoso telah diperbarui.', 'time' => '1 day ago', 'is_read' => true],
                    ];
                @endphp

                @foreach ($notifications as $notification)
                    <div class="p-4 bg-white rounded-xl shadow-lg border border-gray-200 transition duration-300 hover:shadow-2xl cursor-pointer hover:-translate-y-0.5 ease-in-out
                        @if (!$notification['is_read'])
                            border-l-4 border-blue-500
                        @endif">
                        <div class="flex items-start justify-between">
                            <div class="flex space-x-4">
                                <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-lg shadow-inner text-blue-600 text-xl">
                                    <i class="far fa-bell"></i>
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">{{ $notification['title'] }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col items-end space-y-1">
                                <p class="text-sm text-gray-500 whitespace-nowrap">{{ $notification['time'] }}</p>
                                @if (!$notification['is_read'])
                                    <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-0.5 animate-pulse"></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>

</body>
</html>
