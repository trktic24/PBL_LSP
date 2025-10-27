@extends('layouts.app-profil')
@section('content')

    <section class="relative h-[1000px] rounded-t-4xl overflow-hidden">
        <img src="{{ asset('images/Gedung Polines.jpg') }}"
            alt="Gedung Polines"
            class="w-full h-full object-cover">

        <!-- Gradasi biru ke transparan (lebih pekat di kiri) -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/95 via-blue-500/60 to-transparent"></div>

        <!-- Gradasi putih di bawah (lebih halus dan tidak terlalu putih) -->
        <div class="absolute bottom-0 left-0 w-full h-64 bg-gradient-to-t from-white/95 via-white/50 to-transparent"></div>

        <!-- Teks diatas gradasi -->
        <div class="absolute top-1/3 left-16 text-black drop-shadow-lg max-w-xl">
            <h1 class="text-6xl font-bold mb-4">LSP POLINES</h1>
            <p class="text-xl mb-6 leading-relaxed">Lorem ipsum dolor sit amet, you're the best person I've ever met!</p>
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
            <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
            <div class="inline-flex gap-4">
                <button class="btn btn-sm font-bold bg-yellow-400 text-black border-none rounded-full px-6">Semua</button>
                <button class="btn btn-sm font-bold bg-yellow-100 text-gray-700 border-none rounded-full px-6 hover:bg-yellow-200">Software</button>
                <button class="btn btn-sm font-bold bg-yellow-100 text-gray-700 border-none rounded-full px-6 hover:bg-yellow-200">IoT</button>
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
    <section class="px-10 mb-16">
        @php
            $slide1Images = [
                'skema1.jpg',
                'skema2.jpg',
                'skema3.jpg',
                'skema4.jpg',
                'skema5.jpg',
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full shrink-0 p-6">
                    @foreach ($slide1Images as $index => $file)
                        <div class="transition hover:scale-105">
                            <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                                <img src="{{ asset('images/' . $file) }}" alt="Skema {{ $index + 1 }}" class="h-48 w-full object-cover">
                            </div>
                            <div class="px-2">
                                <h2 class="text-lg font-bold text-gray-800">Skema {{ $index + 1 }} A</h2>
                                <p class="text-gray-600">Rp. x.xxx.xxx</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Slide 2  --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full shrink-0 p-6">
                    @foreach ($slide2Images as $index => $file)
                        <div class="transition hover:scale-105">
                            <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                                <img src="{{ asset('images/' . $file) }}" alt="Skema {{ $index + 7 }}" class="h-48 w-full object-cover">
                            </div>
                            <div class="px-2">
                                <h2 class="text-lg font-bold text-gray-800">Skema {{ $index + 7 }} B</h2>
                                <p class="text-gray-600">Rp. x.xxx.xxx</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
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

        // Auto slide setiap 5 detik
        setInterval(() => {
            currentIndex = (currentIndex + 1) % totalSlides;
            showSlide(currentIndex);
        }, 5000);
    </script>

    {{-- Jadwal Sertifikasi --}}
    <section class="bg-gray-50 py-12 px-10 text-center">
        <h2 class="text-3xl font-bold mb-8">Jadwal yang Akan Datang</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-6xl mx-auto">
            <div class="card bg-white shadow-lg rounded-2xl">
                <div class="card-body flex flex-col p-8">
                    <p class="text-base mb-1 font-bold text-left">Sertifikasi:</p>
                    <p class="text-base mb-3 text-left">Network Engineering</p>
                    <p class="text-base mb-1 font-bold text-left">TUK:</p>
                    <p class="text-base mb-3 text-left">Politeknik Negeri Semarang</p>
                    <p class="text-base mb-1 font-bold text-left">Tanggal:</p>
                    <p class="text-base mb-6 text-left">15 Desember 2025</p>
                    <a href="#" class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300 px-8 py-3 rounded-full text-base">Detail</a>
                </div>
            </div>

            <div class="card bg-white shadow-lg rounded-2xl">
                <div class="card-body flex flex-col p-8">
                    <p class="text-base mb-1 font-bold text-left">Sertifikasi:</p>
                    <p class="text-base mb-3 text-left">Network Engineering</p>
                    <p class="text-base mb-1 font-bold text-left">TUK:</p>
                    <p class="text-base mb-3 text-left">Politeknik Negeri Semarang</p>
                    <p class="text-base mb-1 font-bold text-left">Tanggal:</p>
                    <p class="text-base mb-6 text-left">15 Desember 2025</p>
                    <a href="#" class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300 px-8 py-3 rounded-full text-base">Detail</a>
                </div>
            </div>
        </div>
    </section>
@endsection
