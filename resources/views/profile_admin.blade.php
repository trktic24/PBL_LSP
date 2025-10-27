<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin | LSP Polines</title>

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
        /* Hilangkan efek scrollbar biru */
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

<body class="bg-gray-100 text-gray-800">

<div class="h-screen overflow-y-auto">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
        </div>

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
                    <a href="{{ route('master_skema') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Skema</a>
                    <a href="{{ route('master_asesor') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesor</a>
                    <a href="{{ route('master_asesi') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">Asesi</a>
                </div>
            </div>

            <a href="{{ route('schedule_admin') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">Schedule</a>
            <a href="{{ route('tuk_sewaktu') }}" class="text-gray-600 hover:text-blue-600 transition h-full flex items-center">TUK</a>
        </div>

        <!-- PROFIL & NOTIF -->
        <div class="flex items-center space-x-6">
            <!-- Ikon Notifikasi -->
                <a href="{{ route('notifications') }}" 
                    class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)] 
                    hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
                    <i class="fas fa-bell text-xl text-gray-600"></i>

            <!-- Animasi Notifikasi Merah Berdenyut -->
                    <span class="absolute top-2 right-2">
                        <span class="relative flex size-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex size-3 rounded-full bg-red-500"></span>
                        </span>
                    </span>
                </a>
            <!-- Profil Pengguna -->
            <a href="{{ route('profile_admin') }}" 
                class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
                hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
                <span class="text-blue-600 font-semibold text-base mr-2">Admin LSP</span>
                <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
                </div>
            </a>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="p-8" x-data="{ editMode: false }">

        <!-- Header di luar kotak -->
        <div class="flex items-center justify-between mb-6 max-w-3xl mx-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <h2 class="text-3xl font-semibold text-gray-800 text-center flex-1">Account Settings</h2>
            <div class="w-[80px]"></div>
        </div>

        <!-- Kotak utama -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-10 max-w-3xl mx-auto">

            <!-- FOTO PROFIL -->
            <div class="relative flex justify-center mb-10">
                <!-- FOTO PROFIL -->
                <div class="relative">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Avatar" 
                        class="w-48 h-48 rounded-full object-cover shadow-md border-4 border-white">
                    <label for="avatar-upload" 
                        class="absolute bottom-2 right-3 bg-white w-10 h-10 rounded-full flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-100 transition">
                        <i class="fas fa-camera text-gray-600"></i>
                    </label>
                    <input id="avatar-upload" type="file" class="hidden">
                </div>
                <!-- DELETE BUTTON: pojok kanan bawah -->
                <button class="absolute bottom-2 right-0 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-sm font-medium shadow-sm flex items-center space-x-1.5 transition">
                    <i class="fas fa-trash-alt text-xs"></i>
                    <span>Delete Avatar</span>
                </button>
            </div>


            <!-- FORM PROFIL -->
            <form class="space-y-6">
                @php
                    $fields = [
                        'Nama Lengkap' => 'Admin Sertifikasi Polines',
                        'Username' => 'admin',
                        'Email' => 'admin@polines.ac.id',
                        'Unit/Skema' => 'Teknologi Rekayasa Komputer'
                    ];
                @endphp

                @foreach ($fields as $label => $value)
                <div class="flex items-center">
                    <label class="w-1/3 text-sm font-medium text-gray-700">{{ $label }}</label>
                    <input type="text" value="{{ $value }}" 
                           :readonly="!editMode"
                           :class="editMode 
                                ? 'flex-1 border border-blue-400 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none' 
                                : 'flex-1 border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-gray-600 cursor-not-allowed'"
                    >
                </div>
                @endforeach

                <div class="flex items-center">
                    <label class="w-1/3 text-sm font-medium text-gray-700">Password</label>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium shadow-md flex items-center space-x-2">
                        <i class="fas fa-key"></i>
                        <span>Change Password</span>
                    </button>
                </div>

                <!-- BUTTON EDIT / SAVE -->
                <div class="flex justify-end pt-6">
                    <button type="button"
                        @click="editMode = !editMode"
                        x-text="editMode ? 'Save Changes' : 'Edit Profile'"
                        :class="editMode 
                            ? 'bg-blue-500 text-white px-6 py-2 rounded-md font-medium shadow-md hover:bg-blue-600 transition' 
                            : 'border border-gray-300 text-gray-700 px-6 py-2 rounded-md font-medium hover:bg-blue-500 hover:text-white hover:border-blue-500 transition duration-200 shadow-sm'"
                    ></button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>
