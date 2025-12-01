<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('description', 'LSP - Lembaga Sertifikasi Profesi')">
    <meta name="keywords" content="@yield('keywords', 'LSP, Sertifikasi, Profesi, BNSP')">
    <meta name="author" content="{{ config('app.name') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Laravel'))">
    <meta property="og:description" content="@yield('description', 'LSP - Lembaga Sertifikasi Profesi')">
    <meta property="og:image" content="@yield('og:image', asset('images/logo-lsp.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Laravel'))">
    <meta property="twitter:description" content="@yield('description', 'LSP - Lembaga Sertifikasi Profesi')">
    <meta property="twitter:image" content="@yield('og:image', asset('images/logo-lsp.png'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins text-gray-900 antialiased bg-gray-100">
    <main class="min-h-screen flex items-center justify-center p-8">
        {{ $slot }}
    </main>
</body>
</html>
