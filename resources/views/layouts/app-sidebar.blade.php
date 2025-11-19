<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LSP Polines - @yield('title', 'Halaman Form')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body, input, textarea, select, button {
            font-family: 'Poppins', sans-serif;
        }
    </style>

</head>
<body x-data class="bg-gray-50 min-h-screen flex">
  <x-sidebar.sidebar />

  {{-- Konten utama --}}
  <main class="flex-1 py-6 transition-all duration-300">
      <button
          class="lg:hidden fixed top-4 left-4 bg-blue-600 text-white p-3 rounded-full shadow-lg z-40"
          @click="$store.sidebar.open = true"
      >
          ☰
      </button>

      @yield('content')
  </main>

</body>
</html>
