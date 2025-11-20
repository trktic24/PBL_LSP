<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>LSP Polines - @yield('title', 'Asesmen')</title>

  <link rel="icon" type="image/png" href="{{ asset('images\Logo_LSP_No_BG.png') }}">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  @stack('css')

  <!-- Google Fonts Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
      body, input, textarea, select, button {
          font-family: 'Poppins', sans-serif;
      }
  </style>
</head>
<body class="bg-gray-50 flex min-h-screen">

  {{-- Sidebar --}}
  <x-sidebar.sidebar-asesi />

  {{-- Konten utama --}}
  <main class="flex-1 p-8 ml-80">
    @yield('content')
  </main>

  @stack('js')
</body>
</html>