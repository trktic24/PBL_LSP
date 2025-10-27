<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Jadwal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <!-- HEADER -->
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            
            <!-- Logo dan Navigasi -->
            <div class="flex items-center space-x-6">
                <!-- Logo -->
                <div class="w-10 h-10 flex items-center justify-center">
                    <!-- <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" alt="Logo" class="w-10 h-10 object-cover rounded-md">  -->
                    <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" width="100">
                </div>
 

                <!-- Navigasi -->
                <nav class="absolute left-1/2 transform -translate-x-1/2 flex space-x-6 text-gray-700 font-medium">
                    <a href="{{ route('home') }}" class="text-blue-600 font-semibold underline">Home</a>
                    <a href="{{ route('jadwal') }}" class="hover:text-blue-600">Jadwal</a>
                    <a href="{{ route('laporan') }}" class="hover:text-blue-600">Laporan</a>
                    <a href="{{ route('profil') }}" class="hover:text-blue-600">Profil</a>
                </nav>
            </div>

            <!-- Profil User -->
            <div class="relative">
                <div class="flex items-center space-x-3 cursor-pointer" id="userMenuButton">
                    <span class="text-gray-800 font-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                    <a href="{{ route('profil') }}">
                        <img src="{{ Auth::user()->photo_url ?? asset('images/default-profile.png') }}" 
                             alt="Foto Profil" 
                             class="w-10 h-10 rounded-full border-2 border-blue-500 object-cover">
                    </a>
                    <!-- Tombol Dropdown -->
                    <button id="dropdownToggle" class="focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border border-gray-200">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN TABEL -->
    <main class="max-w-7xl mx-auto mt-8 px-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Halaman Belum Tersedia</h1>
    </main>
</body>
</html>