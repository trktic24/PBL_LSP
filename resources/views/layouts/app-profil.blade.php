<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP Polines - @yield('title', 'Selamat Datang')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('styles')
</head>

<body class="antialiased bg-white">

    <x-navbar.navbar-fixx/>
    <main class="bg-white min-h-screen">
        @yield('content')
    </main>

    {{-- KARENA ANDA SUDAH MENGHILANGKAN FOOTER SECARA KONTINU,
         JIKA ADA FOOTER GLOBAL, HARUS DITAMBAHKAN DI SINI MENGGUNAKAN @yield. --}}
    {{-- Contoh jika Anda ingin memiliki footer global yang bisa dihilangkan: --}}
    {{-- @yield('global-footer') --}}
    <x-footer.footer/>
</body>
</html>
