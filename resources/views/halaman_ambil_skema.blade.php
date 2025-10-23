<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh Navbar LSP</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100"> <nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
  
        <div class="flex-shrink-0 flex items-center">
          <a href="#">
            <img class="h-10 w-auto" src="https://seeklogo.com/images/P/politeknik-negeri-semarang-polines-logo-6933080838-seeklogo.com.png" alt="LSP Polines Logo">
          </a>
        </div>
  
        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
          
          <a href="#" 
             class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
            Home
          </a>
          <a href="#" 
             class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
            Skema
          </a>
          <a href="#" 
             class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
            Jadwal Asesmen
          </a>
          
          <a href="#" 
             class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
            Sertifikasi
          </a>
  
          <a href="#" 
             class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
            Info
          </a>
        </div>
  
        <div class="hidden sm:ml-6 sm:flex sm:items-center">
          <div class="flex items-center space-x-3 cursor-pointer">
            
            <img class="h-10 w-10 rounded-full object-cover" 
                 src="https://via.placeholder.com/150" 
                 alt="Foto Profil">
            
            <span class="text-gray-800 font-medium text-sm">
              Wiwokdetok
            </span>
            
            <svg class="h-4 w-4 text-gray-600" xmlns="http://www.worg/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
          </div>
        </div>
  
        <div class="-mr-2 flex items-center sm:hidden">
          <button typef="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
            <span class="sr-only">Buka menu</span>
            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
          </button>
        </div>
  
      </div>
    </div>
  </nav>
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold">Konten Halaman</h1>
    <p class="mt-4">
      Navbar di atas sudah jadi.
    </p>
  </main>

</body>
</html>