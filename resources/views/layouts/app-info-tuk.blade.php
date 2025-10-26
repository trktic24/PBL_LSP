<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP Polines - @yield('title', 'Beranda')</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f7f9; }
        .header { background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .nav-link { padding: 1rem 1.5rem; display: block; text-decoration: none; color: #333; font-weight: 500; }
        .hero { background-color: #0d47a1; color: white; padding: 4rem 0; text-align: center; }
        .container { max-width: 1200px; margin: 0 auto; padding: 1rem; }
        .card { background-color: white; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-top: 2rem; }
        .footer { background-color: #0d47a1; color: white; padding: 3rem 0; }
        .table-responsive { overflow-x: auto; }
        .tuk-table { width: 100%; border-collapse: collapse; }
        .tuk-table th, .tuk-table td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        .tuk-table th { background-color: #e3f2fd; color: #0d47a1; font-weight: bold; }
        .detail-button { background-color: #ffc107; color: #333; border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

    <header class="header">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center;">
                <img src="logo_lsp_polines.png" alt="LSP Polines Logo" style="height: 40px; margin-right: 10px;">
                <span style="font-weight: bold; color: #0d47a1;">LSP POLINES</span>
            </div>

            <nav style="display: flex;">
                <a href="#" class="nav-link">Home</a>
                <a href="#" class="nav-link">Jadwal Asesmen</a>
                <a href="#" class="nav-link">Sertifikasi</a>
                <div class="nav-link dropdown" style="position: relative;">
                    Info <span style="font-size: 0.7em;">▼</span>
                    </div>
                <div class="nav-link dropdown" style="position: relative;">
                    Profil <span style="font-size: 0.7em;">▼</span>
                    </div>
                <a href="#" class="nav-link">Masuk</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container" style="text-align: center;">
            <h2>SERTIFIKASI PROFESI UNTUK KARIER ANDA</h2>
            <h1>Tingkatkan Kompetensi Profesional Anda</h1>
            <p>LSP Polines berkomitmen menghasilkan tenaga kerja kompeten yang siap bersaing dan diakui secara nasional maupun internasional.</p>
            <a href="#" style="display: inline-block; background-color: white; color: #0d47a1; padding: 10px 30px; border-radius: 20px; text-decoration: none; margin-top: 20px; font-weight: bold;">Hubungi Kami</a>
        </div>
        <div class="container" style="margin-top: 3rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
            <div style="text-align: left;">
                <p>Jl. Prof. Soedarto, SH.</p>
                <p>Tembalang, Semarang, Jawa Tengah.</p>
                <div style="display: flex; align-items: center; margin-top: 1rem;">
                    <img src="logo_lsp_polines.png" alt="LSP Polines Logo" style="height: 30px; margin-right: 10px; filter: brightness(0) invert(1);">
                    <span style="font-weight: bold;">LSP POLINES</span>
                </div>
            </div>
            <div style="text-align: right;">
                <p>(024) 7473417 ext.256</p>
                <p>lsp@polines.ac.id</p>
                <div style="margin-top: 1rem;">
                    <span style="margin-right: 10px;">&copy; 2025 LSP POLINES. All rights reserved.</span>
                    <a href="#" style="color: white; margin-left: 10px;">in</a>
                    <a href="#" style="color: white; margin-left: 10px;">f</a>
                    <a href="#" style="color: white; margin-left: 10px;">o</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>