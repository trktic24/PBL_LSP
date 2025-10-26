<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
<div class="bg-base-100 min-h-screen">

    {{-- Hero Section --}}
    <section class="relative h-[900px] rounded-t-4xl overflow-hidden mt-20">
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
    <section class="py-10 text-center font-serif">
        <div id="scrollContainer" class="overflow-x-auto whitespace-nowrap px-6 cursor-grab active:cursor-grabbing select-none">
            <div class="inline-flex gap-4">
                <button class="btn btn-sm bg-yellow-400 text-black border-none">Semua</button>
                <button class="btn btn-sm btn-outline">Software</button>
                <button class="btn btn-sm btn-outline">IoT</button>
                <button class="btn btn-sm btn-outline">Skema 3</button>
                <button class="btn btn-sm btn-outline">Skema 4</button>
                <button class="btn btn-sm btn-outline">Skema 5</button>
                <button class="btn btn-sm btn-outline">Skema 6</button>
                <button class="btn btn-sm btn-outline">Skema 7</button>
                <button class="btn btn-sm btn-outline">Skema 8</button>
                <button class="btn btn-sm btn-outline">Skema 9</button>
                <button class="btn btn-sm btn-outline">Skema 10</button>
                <button class="btn btn-sm btn-outline">Skema 11</button>
                <button class="btn btn-sm btn-outline">Skema 12</button>
                <button class="btn btn-sm btn-outline">Skema 13</button>
                <button class="btn btn-sm btn-outline">Skema 14</button>
                <button class="btn btn-sm btn-outline">Skema 15</button>
                <button class="btn btn-sm btn-outline">Skema 16</button>
                <button class="btn btn-sm btn-outline">Skema 17</button>
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

    {{-- Carousel Grid Skema --}}
    <section class="px-10 mb-16 font-serif">
        @php 
            $slide1Images = [
                'skema1.jpg',
                'skema2.jpg',
                'skema3.jpg',
                'skema4.jpg',
                'skema 5.jpg',
                'skema6.jpg',
                ];

            $slide2Images = [
                'skema7.jpg',
                'skema8.jpg',
                'skema9.jpg',
                'skema10.jpg',
                'skema11.jpg',
                'skema12.jpg',
                ];
        @endphp

        <div id="gridCarousel" class="relative overflow-hidden rounded-3xl">
            <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">
                {{-- Slide 1 --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full shrink-0">
                    @foreach ($slide1Images as $index => $file)
                        <div class="card bg-white shadow-md hover:shadow-lg transition">
                            <figure>
                                <img src="{{ asset('images/' . $file) }}" alt="Skema {{ $index + 1 }}" class="h-48 w-full object-cover rounded-lg">
                            </figure>
                            <div class="card-body">
                                <h2 class="card-title">Skema {{ $index + 1 }} A</h2>
                                <p>Rp. x.xxx.xxx</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Slide 2  --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full shrink-0">
                    @foreach ($slide2Images as $index => $file)
                        <div class="card bg-white shadow-md hover:shadow-lg transition">
                            <figure>
                                <img src="{{ asset('images/' . $file) }}" alt="Skema {{ $index + 7 }}" class="h-48 w-full object-cover rounded-lg">
                            </figure>
                            <div class="card-body">
                                <h2 class="card-title">Skema {{ $index + 7 }} B</h2>
                                <p>Rp. x.xxx.xxx</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol navigasi manual -->
            <button id="prevBtn" class="absolute top-1/2 left-2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-2 shadow-md">
                ‹
            </button>
            <button id="nextBtn" class="absolute top-1/2 right-2 -translate-y-1/2 bg-white/70 hover:bg-white rounded-full p-2 shadow-md">
                ›
            </button>
        </div>
    </section>

    <script>
        const gridSlides = document.getElementById('gridSlides');
        const slides = document.querySelectorAll('#gridSlides > div');
        const totalSlides = slides.length;
        let currentIndex = 0;

        function showSlide(index) {
            gridSlides.style.transform = `translateX(-${index * 100}%)`;
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalSlides;
            showSlide(currentIndex);
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            showSlide(currentIndex);
        });

        // Auto slide setiap 5 detik
        setInterval(() => {
            currentIndex = (currentIndex + 1) % totalSlides;
            showSlide(currentIndex);
        }, 5000);
    </script>

    {{-- Jadwal Sertifikasi --}}
    <section class="bg-gray-50 py-12 px-10 text-center">
        <h2 class="text-2xl font-bold font-serif mb-6">Jadwal yang Akan Datang</h2>
        <div class="flex flex-wrap justify-center gap-6">
            <div class="card bg-white shadow-md rounded-lg">
                <div class="card-body flex flex-col p-6">
                    <p class="text-sm mb-1 font-serif font-bold text-left">Sertifikasi:</p>
                    <p class="text-sm mb-1 font-serif text-left">Network Engineering</p>
                    <p class="text-sm mb-1 font-serif font-bold text-left">TUK:</p>
                    <p class="text-sm mb-1 font-serif text-left">Politeknik Negeri Semarang</p>
                    <p class="text-sm mb-1 font-serif font-bold text-left">Tanggal:</p>
                    <p class="text-sm mb-4 font-serif text-left">15 Desember 2025</p>
                    <a href="#" class="btn bg-yellow-400 text-black font-semibold font-serif border-none hover:bg-yellow-300 px-6 py-2 rounded">Detail</a>
                </div>
            </div>

            <div class="card bg-white shadow-md rounded-lg">
                <div class="card-body flex left flex-col p-6">
                    <p class="text-sm mb-1 font-serif font-bold text-left">Sertifikasi:</p>
                    <p class="text-sm mb-1 font-serif text-left">Network Engineering</p>
                    <p class="text-sm mb-1 font-serif font-bold text-left">TUK:</p>
                    <p class="text-sm mb-1 font-serif text-left">Politeknik Negeri Semarang</p>
                    <p class="text-sm mb-1 font-serif font-bold text-left">Tanggal:</p>
                    <p class="text-sm mb-4 font-serif text-left">15 Desember 2025</p>
                    <a href="#" class="btn bg-yellow-400 text-black font-semibold font-serif border-none hover:bg-yellow-300 px-6 py-2 rounded">Detail</a>
                </div>
            </div>
        </div>
    </section>
</div>
