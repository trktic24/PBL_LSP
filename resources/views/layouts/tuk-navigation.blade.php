<header class="header">
    <div class="logo">
        <img src="{{ asset('img/logo-lsp-polines.png') }}" alt="Logo LSP Polines">
    </div>
    <nav class="nav-menu">
        <ul>
            <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
            <li><a href="{{ url('/alur-sertifikasi') }}" class="nav-link">Jadwal Asesmen</a></li>
            <li><a href="#" class="nav-link">Sertifikasi</a></li>
            
            {{-- DROPDOWN MENU: INFO --}}
            <li class="dropdown">
                <a href="#" class="nav-link">Info <span class="arrow-down"></span></a>
                <ul class="dropdown-content">
                    <li><a href="{{ url('/alur-sertifikasi') }}" class="dropdown-link">Alur Proses</a></li> 
                    <li><a href="#" class="dropdown-link">Daftar Asesor</a></li>
                    <li><a href="{{ url('/info-tuk') }}" class="dropdown-link">TUK</a></li>
                </ul>
            </li>
            
            {{-- DROPDOWN MENU: PROFIL --}}
            <li class="dropdown">
                <a href="#" class="nav-link">Profil <span class="arrow-down"></span></a>
                <ul class="dropdown-content">
                    <li><a href="{{ url('/visimisi') }}" class="dropdown-link">Visi & Misi</a></li>
                    <li><a href="{{ url('/struktur') }}" class="dropdown-link">Struktur</a></li>
                    <li><a href="{{ url('/mitra') }}" class="dropdown-link">Mitra</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    {{-- Tombol Masuk/User Profile (Melindungi dari Error Auth::user()::name) --}}
    @guest
        <a href="{{ route('login') }}" class="btn btn-masuk">Masuk</a>
    @endguest
    @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-masuk">{{ Auth::user()->name }}</a>
    @endauth
</header>