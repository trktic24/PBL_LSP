<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
<div class="bg-base-100 min-h-screen">

    {{-- Hero Section --}}
    <section class="relative h-[450px] rounded-t-4xl overflow-hidden mt-20">
        <img src="{{ asset('images/Gedung Polines.jpg') }}" 
            alt="Gedung Polines" 
            class="w-full h-full object-cover">

        <!-- Gradasi putih di bawah -->
         <div class="absolute bottom-0 left-0 w-full h-80 bg-gradient-to-t from-white/90 via-white/70 to-transparent"></div>
         
         <!-- Gradasi biru ke transparan -->
         <div class="absolute inset-0 bg-gradient-to-r from-blue-500/80 via-blue-300/40 to-transparent"></div> 
        
         <!-- Teks diatas gradasi -->
         <div class="absolute top-1/3 left-16 text-black drop-shadow-md max-w-xl">
            <h1 class="text-4xl font-bold font-serif mb-2">LSP POLINES</h1>
            <p class="text-lg font-serif mb-6 leading-relaxed">Lorem ipsum dolor sit amet, you're the best person I've ever met!</p>
            <div class="flex gap-3">
                <a href="#" class="btn bg-yellow-400 text-black font-semibold font-serif border-none hover:bg-yellow-300">Daftar</a>
                <a href="#" class="btn btn-outline text-black font-serif border-white hover:bg-white hover:text-blue-700">Eksplore Skema</a>
            </div>
        </div>
    </section>

    <style>
        #scrollContainer::-webkit-scrollbar {
            display: none;
        }
        #scrollContainer {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- Filter Kategori --}}
    <section class="py-10 text-center">
        <div id="scrollContainer" class="overflow-x-auto whitespace-nowrap px-6 cursor-grab active:cursor-grabbing select-none">
            <div class="inline-flex gap-4">
                <button class="btn btn-sm bg-yellow-400 text-black border-none">Semua</button>
                <button class="btn btn-sm btn-outline">Software</button>
                <button class="btn btn-sm btn-outline">IoT</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
            </div>
        </div>
    </section>

    <script>
        const scrollContainer = document.getElementById("scrollContainer");
        let isDown = false;
        let startX;
        let scrollLeft;

        scrollContainer.addEventListener("mousedown", (e) => {
            isDown = true;
            scrollContainer.classList.add("active");
            startX = e.pageX - scrollContainer.offsetLeft;
            scrollLeft = scrollContainer.scrollLeft;
        });

        scrollContainer.addEventListener("mouseleave", () => {
            isDown = false;
        });

        scrollContainer.addEventListener("mouseup", () => {
            isDown = false;
        });

        scrollContainer.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - scrollContainer.offsetLeft;
            const walk = (x - startX) * 2; // kecepatan geser
            scrollContainer.scrollLeft = scrollLeft - walk;
        });
    </script>

    {{-- Carousel Skema Sertifikasi --}}
    <section class="px-10 mb-16">
        <div id="indicators-carousel" class="relative w-full rounded-3xl overflow-hidden" data-carousel="slide">
            <div class="relative h-80 md:h-[450px] overflow-hidden rounded-3xl">
                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                    <img src="{{ asset('images/skema1.jpg') }}" class="absolute w-full h-full object-cover" alt="Skema 1">
                </div>
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="{{ asset('images/skema2.jpg') }}" class="absolute w-full h-full object-cover" alt="Skema 2">
                </div>
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="{{ asset('images/skema3.jpg') }}" class="absolute w-full h-full object-cover" alt="Skema 3">
                </div>
            </div>

            <!-- Tombol navigasi -->
            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer" data-carousel-prev>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/40 hover:bg-white/70">
                    <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 6 10" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 1 1 5l4 4"/>
                    </svg>
                </span>
            </button>
            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer" data-carousel-next>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/40 hover:bg-white/70">
                    <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 6 10" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m1 9 4-4-4-4"/>
                    </svg>
                </span>
            </button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function (){
            const carousel = document.querySelector('[data-carousel="slide"]');
            const items = carousel.querySelectorAll('[data-carousel-item]');
            let index = 0;
            const total = items.length;
            const interval = 4000;

        function showNext() {
            items[index].classList.add('hidden');
            index = (index + 1) % total;
            items[index].classList.remove('hidden');
        }

        setInterval(showNext, interval);
        });
    </script>


    <!-- 
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
-->

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
