<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>LSP Polines - @yield('title', 'Selamat Datang')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

    <main>
        @yield('content')
    </main>

    <section class="cta-section py-5">
        <div class="container text-center">
            <h6 class="text-uppercase">SERTIFIKASI PROFESI UNTUK KARIER ANDA</h6>
            <h2 class="display-5 fw-bold my-3">Tingkatkan Kompetensi Profesional Anda</h2>
            <p class="lead mb-4" style="opacity: 0.9;">
                LSP Polines berkomitmen menghasilkan tenaga kerja kompeten yang<br>
                siap bersaing dan diakui secara nasional maupun internasional.
            </p>
            <a href="#" class="btn btn-light">Hubungi Kami</a>
        </div>
    </section>

    <footer class="footer pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 mb-4">
                    <img src="https://lsp.polines.ac.id/wp-content/uploads/2022/02/cropped-logo-lsp-polines-web-3.png" alt="LSP POLINES Logo Putih">
                    <p class="mt-2">Jl. Prof. Soedarto, SH.<br>Tembalang, Semarang, Jawa Tengah.</p>
                </div>
                
                <div class="col-lg-3 col-md-1 mb-4">
                </div>
                
                <div class="col-lg-4 col-md-5 mb-4 text-md-end">
                    <p>(024) 7473417 ext.256</p>
                    <p>lsp@polines.ac.id</p>
                </div>
            </div>
            
            <div class="row copyright align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 small">&copy; 2025 LSP POLINES. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end social-icons">
                    <a href="#" class="me-3"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="me-3"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>