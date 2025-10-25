<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="bg-base-100">

    {{-- Navbar --}}
    <div class="navbar bg-white shadow-sm px-8">
        <div class="flex-1">
            <a href="/" class="font-bold text-xl text-blue-700">
                <span class="text-yellow-500">LSP</span> POLINES
            </a>
        </div>
        <div class="flex-none hidden md:flex gap-6 text-gray-700 font-medium">
            <a href="/" class="text-blue-700 font-semibold border-b-2 border-blue-700">Home</a>
            <a href="#">Skema</a>
            <a href="#">Jadwal Asesmen</a>
            <a href="#">Sertifikasi</a>
            <a href="#">Info</a>
            <a href="#">Profil</a>
            <a href="#" class="btn btn-sm btn-outline btn-primary ml-4">Masuk</a>
        </div>
    </div>

    {{-- Hero Section --}}
    <section class="relative bg-blue-50">
        <img src="{{ asset('img/gedung-polines.jpg') }}" alt="Gedung Polines" class="w-full h-[450px] object-cover rounded-b-3xl">
        <div class="absolute top-1/3 left-16 text-white drop-shadow-md">
            <h1 class="text-4xl font-bold mb-2">LSP POLINES</h1>
            <p class="text-lg mb-6">Lorem ipsum dolor sit amet, you're the best person I've ever met! ✨</p>
            <div class="flex gap-3">
                <a href="#" class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300">Daftar</a>
                <a href="#" class="btn btn-outline text-white border-white hover:bg-white hover:text-blue-700">Eksplor Skema</a>
            </div>
        </div>
    </section>

    {{-- Filter Kategori --}}
    <section class="py-10 text-center">
        <div class="flex justify-center gap-4 flex-wrap">
            <button class="btn btn-sm bg-yellow-400 text-black border-none">Semua</button>
            <button class="btn btn-sm btn-outline">Software</button>
            <button class="btn btn-sm btn-outline">IoT</button>
            <button class="btn btn-sm btn-outline">Skema 3</button>
            <button class="btn btn-sm btn-outline">Skema 4</button>
            <button class="btn btn-sm btn-outline">Skema 5</button>
            <button class="btn btn-sm btn-outline">Skema 6</button>
        </div>
    </section>

    {{-- Kartu Skema Sertifikasi --}}
    <section class="px-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-12">
        @for ($i = 1; $i <= 6; $i++)
        <div class="card bg-white shadow-md hover:shadow-lg transition">
            <figure>
                <img src="{{ asset('img/skema' . $i . '.jpg') }}" alt="Skema {{ $i }}" class="h-48 w-full object-cover">
            </figure>
            <div class="card-body">
                <h2 class="card-title">Skema Software {{ $i }}</h2>
                <p>Rp. x.xxx.xxx</p>
                <div class="card-actions justify-end">
                    <a href="#" class="btn btn-sm btn-outline btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
        @endfor
    </section>

    {{-- Jadwal Sertifikasi --}}
    <section class="bg-gray-50 py-12 px-10 text-center">
        <h2 class="text-2xl font-bold mb-6">Jadwal yang Akan Datang</h2>
        <div class="flex flex-wrap justify-center gap-8">
            <div class="card bg-white shadow-md w-72">
                <div class="card-body">
                    <h3 class="font-semibold text-lg">Sertifikasi: Cyber Security</h3>
                    <p>TUK: Politeknik Negeri Semarang</p>
                    <p>Tanggal: 15 Desember 2025</p>
                    <a href="#" class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300 mt-3">Detail</a>
                </div>
            </div>
            <div class="card bg-white shadow-md w-72">
                <div class="card-body">
                    <h3 class="font-semibold text-lg">Sertifikasi: Network Engineering</h3>
                    <p>TUK: Politeknik Negeri Semarang</p>
                    <p>Tanggal: 15 Desember 2025</p>
                    <a href="#" class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300 mt-3">Detail</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-blue-700 text-white py-10 mt-10">
        <div class="text-center">
            <h3 class="text-xl font-semibold mb-2">Tingkatkan Kompetensi Profesional Anda</h3>
            <p class="max-w-xl mx-auto mb-4">
                LSP Polines berkomitmen meningkatkan tenaga kerja kompeten siap bersaing di dunia industri secara nasional maupun internasional.
            </p>
            <a href="#" class="btn bg-white text-blue-700 font-semibold border-none hover:bg-blue-100 mb-8">Hubungi Kami</a>

            <div class="text-sm text-gray-200 space-y-1">
                <p>Jl. Prof. Soedarto, SH, Tembalang, Semarang, Jawa Tengah</p>
                <p>Email: lsp@polines.ac.id | Telp: (024) 7465407 ext. 125</p>
                <p>© 2025 LSP POLINES - All rights reserved</p>
            </div>
        </div>
    </footer>
</div>
