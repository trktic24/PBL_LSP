<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('FR_IA_07')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
</head>

</head>
<body class="bg-gray-50 flex">

  {{-- Sidebar --}}
  <x-sidebar.sidebar />

  {{-- Konten utama --}}
  <main class="flex-1 p-8">
    @yield('content')
  </main>

</body>
</html>