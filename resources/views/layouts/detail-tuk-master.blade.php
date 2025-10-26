<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Ambil nilai dari @section('title') di view anak --}}
    <title>@yield('title', 'LSP Polines')</title>
    
    {{-- Font Google Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    {{-- Font Awesome (untuk ikon) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Tautkan ke CSS Kustom di folder PUBLIC --}}
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    {{-- Script untuk Dropdown (Menggunakan DOMContentLoaded untuk inisialisasi) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown');
            
            // Loop melalui setiap dropdown
            dropdowns.forEach(dropdown => {
                const content = dropdown.querySelector('.dropdown-content');

                // Event saat mouse masuk (menampilkan dropdown)
                dropdown.addEventListener('mouseenter', () => {
                    content.style.opacity = '1';
                    content.style.visibility = 'visible';
                    content.style.transform = 'translateY(0)';
                    content.style.pointerEvents = 'auto';
                });

                // Event saat mouse keluar (menyembunyikan dropdown)
                dropdown.addEventListener('mouseleave', () => {
                    // Cukup hapus styling inline agar CSS hover di file style.css yang mengendalikan transisi
                    content.removeAttribute('style');
                });
            });
        });
    </script>

</head>
<body>

    {{-- HEADER/NAVBAR --}}
    @include('layouts.navigation')

    {{-- KONTEN UTAMA --}}
    <main class="main-content">
        <div class="container">
            {{-- Tempat konten view anak akan disuntikkan --}}
            @yield('content') 
        </div>
    </main>

    {{-- CTA DAN FOOTER --}}
    @include('layouts.footer')

</body>
</html>