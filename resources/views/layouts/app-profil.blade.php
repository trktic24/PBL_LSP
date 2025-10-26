<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP Polines - @yield('title', 'Selamat Datang')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white">
    <x-navbar.navbar-fixx />
    <main class="pt-20 bg-gray-50 min-h-screen">
        @yield('content')
    </main>
</body>

</html>
