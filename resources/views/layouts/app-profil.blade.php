<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP Polines - @yield('title', 'Selamat Datang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white">
    @include('components.navbar.navbar-fixx') 
    <main class="pt-20 bg-gray-50 min-h-screen">
        @yield('content')
    </main>
    @include('components.footer.footer') 
</body>

</html>
