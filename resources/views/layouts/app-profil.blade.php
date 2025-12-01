<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP Polines - @yield('title', 'Selamat Datang')</title>
    <meta name="description" content="@yield('description', 'LSP - Lembaga Sertifikasi Profesi')">
    <meta name="keywords" content="@yield('keywords', 'LSP, Sertifikasi, Profesi, BNSP')">
    <meta name="author" content="{{ config('app.name') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="LSP Polines - @yield('title', 'Selamat Datang')">
    <meta property="og:description" content="@yield('description', 'LSP - Lembaga Sertifikasi Profesi')">
    <meta property="og:image" content="@yield('og:image', asset('images/logo-lsp.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="LSP Polines - @yield('title', 'Selamat Datang')">
    <meta property="twitter:description" content="@yield('description', 'LSP - Lembaga Sertifikasi Profesi')">
    <meta property="twitter:image" content="@yield('og:image', asset('images/logo-lsp.png'))">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('styles')
</head>

<body class="antialiased bg-white font-inter">

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
