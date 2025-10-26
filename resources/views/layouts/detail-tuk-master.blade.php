{{-- FILE INI HARUS DISIMPAN SEBAGAI resources/views/layouts/detail-tuk-master.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', 'LSP Polines')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Tautkan ke CSS Kustom di folder PUBLIC --}}
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    {{-- Script untuk Dropdown --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown');
            
            dropdowns.forEach(dropdown => {
                const content = dropdown.querySelector('.dropdown-content');

                dropdown.addEventListener('mouseenter', () => {
                    content.style.opacity = '1';
                    content.style.visibility = 'visible';
                    content.style.transform = 'translateY(0)';
                    content.style.pointerEvents = 'auto';
                });

                dropdown.addEventListener('mouseleave', () => {
                    content.removeAttribute('style');
                });
            });
        });
    </script>

</head>
<body>

    {{-- HEADER/NAVBAR: INI ADALAH TUK-NAVIGATION --}}
    @include('layouts.tuk-navigation') 

    {{-- KONTEN UTAMA --}}
    <main class="main-content">
        <div class="container">
            @yield('content') 
        </div>
    </main>

    {{-- CTA DAN FOOTER --}}
    @include('layouts.footer')

</body>
</html>