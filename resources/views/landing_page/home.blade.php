@extends('layouts.app-profil')

@section('title', 'Beranda')
@section('description', 'Selamat datang di LSP Polines. Lembaga Sertifikasi Profesi yang terpercaya dan profesional.')
@section('keywords', 'LSP, Polines, Sertifikasi, Profesi, BNSP, Kompetensi')

@section('content')

{{-- ======================= HERO ======================= --}}
{{-- ======================= HERO ======================= --}}
<section class="relative min-h-screen flex items-center rounded-b-[3rem] overflow-hidden z-10 pb-20">
    {{-- Gambar Latar Belakang --}}
    <img src="{{ asset('images/Gedung Polines.jpg') }}"
        alt="Gedung Polines"
        class="absolute inset-0 w-full h-full object-cover">
    
    {{-- Overlay Gradasi Biru --}}
    <div class="absolute inset-0 bg-gradient-to-r from-[#96C9F4]/95 via-[#96C9F4]/60 to-transparent"></div>
    
    {{-- Gradasi Putih di Bawah --}}
    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-white/80 via-white/30 to-transparent"></div>
    
    {{-- Konten Utama (Header Text dan Tombol) --}}
    <div class="relative w-full pt-32 pb-12">
        
        <div class="container mx-auto px-8"> 
            
            <div class="text-black drop-shadow-lg max-w-xl">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 font-poppins">LSP POLINES</h1>
                <p class="text-lg md:text-xl mb-6 leading-relaxed font-inter">Tempat sertifikasi resmi Politeknik Negeri Semarang.</p>

                <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 mt-8">
                    {{-- Tombol Daftar --}}
                    <a href="{{ route('login') }}"
                           class="bg-yellow-400 text-black font-bold px-8 py-3 rounded-lg shadow-lg
                                  hover:bg-yellow-500 transition-all duration-300 ease-in-out
                                  transform hover:scale-105 font-poppins w-full sm:w-auto text-center">
                        Daftar
                    </a>
                    {{-- Tombol Eksplore --}}
                    <a href="#skema-sertifikasi"
                           class="text-black font-semibold text-lg flex items-center gap-2
                                  hover:gap-3 transition-all duration-300 ease-in-out group font-poppins">
                        Eksplore Skema
                        <span class="font-bold text-xl transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ======================= CSS UNTUK FILTER & CAROUSEL ======================= --}}
<style>
/* CSS Anda untuk kategori dan filter sudah benar, saya salin dari file Anda */
body {
    overflow-x: hidden; 
}
.carousel-nav-btn {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 100;
    background: white; color: black; padding: 0.5rem 0.75rem; border-radius: 9999px;
    transition: all 0.3s; cursor: pointer; border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
}
.carousel-nav-btn:hover { background: rgb(55 65 81); color: white; }
.category-nav-btn {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 30;
    background: white; color: black; padding: 0.5rem 0.6rem; border-radius: 9999px;
    transition: all 0.2s; cursor: pointer; border: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    font-size: 1.25rem; line-height: 1; height: 40px; width: 40px;
    display: flex; align-items: center; justify-content: center;
}
.category-nav-btn:hover { background: #FFD700; color: black; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); }
html { scroll-behavior: smooth; }
#scrollContainer::-webkit-scrollbar { display: none; }
#scrollContainer { -ms-overflow-style: none; scrollbar-width: none; margin-bottom: 2rem; }
.active-category {
    background-color: rgb(250 204 21) !important; color: black;
    transform: translateY(-3px) scale(1.05) !important; transition: all 0.25s ease-in-out;
    box-shadow: 0 6px 15px rgba(250, 204, 21, 0.5);
}
.inactive-category {
    background-color: rgb(254 249 195) !important; color: #444;
    transform: scale(1); transition: all 0.25s ease-in-out;
}
.inactive-category:hover { background-color: rgb(254 240 138); }
.category-btn {
    white-space: nowrap !important; height: auto !important;
    padding-top: 0.5rem !important; padding-bottom: 0.5rem !important;
    display: inline-flex !important; width: fit-content !important;
}
.skema-slide.hidden-slide { display: none !important; }
</style>

{{-- ======================= FILTER KATEGORI ====================== --}}
<section id="skema-sertifikasi" 
             class="py-10 text-center relative z-20 bg-white -mt-10">
    <p class="font-bold text-2xl mb-6 font-poppins">Skema Sertifikasi</p>
    
    <div class="relative w-full max-w-7xl mx-auto px-4"> 
        
        <button id="prevCategoryBtn" 
                class="category-nav-btn -left-0 disabled:opacity-30 disabled:cursor-not-allowed hidden md:flex" 
                onclick="scrollCategory(-1)">&#10094;</button>
        
        <div id="scrollContainer" 
             class="flex flex-row gap-4 overflow-x-scroll whitespace-nowrap justify-start py-4">
            
            <div id="categoryButtons" class="inline-flex gap-4 w-fit px-4"> 
                @foreach($categories as $category)
                    <button data-category="{{ $category }}"
                           class="category-btn text-sm font-bold rounded-full px-4 py-2 border-none 
                           {{ $loop->first ? 'active-category' : 'inactive-category' }}
                           "> 
                        {{ $category }}
                    </button>
                @endforeach
            </div>

        </div>
        
        <button id="nextCategoryBtn" 
                class="category-nav-btn -right-0 disabled:opacity-30 disabled:cursor-not-allowed hidden md:flex" 
                onclick="scrollCategory(1)">&#10095;</button>

    </div>
</section>

{{-- ======================= CAROUSEL GRID SKEMA ======================= --}}
<section class="px-4 md:px-10 mb-16 relative min-h-[700px]">
    <div class="relative w-full max-w-7xl mx-auto">
        {{-- Tombol Navigasi Carousel --}}
        <button id="prevBtn" 
                class="carousel-nav-btn -left-4 md:-left-8 disabled:opacity-30 disabled:cursor-not-allowed" 
                onclick="moveSlide(-1)">&#10094;</button>
        
        <button id="nextBtn" 
                class="carousel-nav-btn -right-4 md:-right-8 disabled:opacity-30 disabled:cursor-not-allowed" 
                onclick="moveSlide(1)">&#10095;</button>

        <div id="gridCarousel" class="relative overflow-hidden rounded-3xl w-full">
        
        <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">

            @php
                $chunks = $skemas->chunk(6); // 6 kartu per slide
            @endphp

            @forelse($chunks as $index => $chunk)
                <div class="flex-none w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-6 px-4 skema-slide" data-slide-index="{{ $index }}">
                    @foreach($chunk as $skema)
                        @php
                            $categoryName = $skema->category?->nama_kategori ?? 'Tidak Terkategori';
                        @endphp
                        
                        <div class="transition hover:scale-105 skema-card" data-category="{{ $categoryName }}">
                            <a href="{{ route('skema.detail', ['id' => $skema->id_skema]) }}">
                                <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                                    {{-- Path gambar skema Anda sudah benar --}}
                                    <img src="{{ asset('images/skema/' . ($skema->gambar ?? 'default.jpg')) }}"
                                        alt="Gambar Skema"
                                        class="w-full h-48 object-cover">
                                </div>
                            </a>
                            <div class="px-2">
                                <h2 class="text-lg font-bold text-gray-800">{{ $skema->nama_skema }}</h2>
                                <p class="text-sm text-gray-500 mb-1">{{ $categoryName }}</p>
                                <p class="text-gray-800 font-semibold">Rp {{ number_format($skema->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="text-center text-gray-500 py-10 w-full">
                    <p>Tidak ada skema sertifikasi yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
        
        {{-- Area untuk pesan jika filter tidak menemukan hasil --}}
        <div id="noResultsMessage" class="hidden text-center text-gray-500 py-10">
            <p>Tidak ada skema yang ditemukan untuk kategori ini.</p>
        </div>

    </div>
</section>

{{-- ======================= SCRIPT JAVASCRIPT CAROUSEL DAN FILTER ======================= --}}
{{-- Kode script JavaScript Anda sudah benar, saya salin dari file Anda --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('#categoryButtons button');
    const cards = document.querySelectorAll('.skema-card');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const gridSlides = document.getElementById('gridSlides');
    const slides = document.querySelectorAll('.skema-slide');
    const totalSlides = slides.length;
    let currentIndex = 0;
    let isFilterActive = false;
    
    function setActiveButton(selectedButton) {
        buttons.forEach(btn => {
            btn.classList.remove('active-category');
            btn.classList.add('inactive-category');
        });
        selectedButton.classList.add('active-category');
        selectedButton.classList.remove('inactive-category');
    }
    
    window.moveSlide = function(direction) {
        if (isFilterActive) return; 
        let newIndex = currentIndex + direction;
        if (newIndex < 0) { newIndex = 0; }
        else if (newIndex >= totalSlides) { newIndex = totalSlides - 1; }
        showSlide(newIndex);
    }

    function showSlide(index) {
        if (isFilterActive) return;
        currentIndex = index;
        gridSlides.style.transform = `translateX(-${currentIndex * 100}%)`;
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        if (prevBtn && nextBtn) {
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex === totalSlides - 1;
        }
    }
    
    const defaultButton = document.querySelector('button[data-category="Semua"]');
    if (defaultButton) {
        setActiveButton(defaultButton);
        showSlide(0);
    }

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedCategory = button.dataset.category;
            let visibleCardCount = 0;
            setActiveButton(button);
            isFilterActive = (selectedCategory !== 'Semua');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            if (isFilterActive) {
                if (prevBtn && nextBtn) {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'none';
                }
                gridSlides.style.transform = `translateX(0%)`;
                slides.forEach(slide => slide.classList.add('hidden-slide'));
            } else {
                if (prevBtn && nextBtn) {
                    prevBtn.style.display = 'block';
                    nextBtn.style.display = 'block';
                }
                slides.forEach(slide => slide.classList.remove('hidden-slide'));
                showSlide(0); 
            }

            cards.forEach(card => {
                const cardCategory = card.dataset.category;
                const isVisible = (selectedCategory === 'Semua' || cardCategory === selectedCategory);
                card.style.display = isVisible ? 'block' : 'none';
                if (isVisible && isFilterActive) {
                    visibleCardCount++;
                    card.closest('.skema-slide').classList.remove('hidden-slide');
                }
            });
            if (visibleCardCount === 0 && isFilterActive) {
                noResultsMessage.classList.remove('hidden');
            } else {
                noResultsMessage.classList.add('hidden');
            }
        });
    });
    
    const categoryScrollContainer = document.getElementById('scrollContainer');
    const prevCategoryBtn = document.getElementById('prevCategoryBtn');
    const nextCategoryBtn = document.getElementById('nextCategoryBtn');
    const scrollAmount = 200; 

    window.scrollCategory = function(direction) {
        categoryScrollContainer.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
        setTimeout(updateCategoryNavButtons, 350);
    }

    function updateCategoryNavButtons() {
        if (!categoryScrollContainer) return;
        const { scrollLeft, scrollWidth, clientWidth } = categoryScrollContainer;
        prevCategoryBtn.disabled = scrollLeft === 0;
        nextCategoryBtn.disabled = (scrollLeft + clientWidth) >= scrollWidth;
    }
    categoryScrollContainer.addEventListener('scroll', updateCategoryNavButtons);
    updateCategoryNavButtons();
});

const scrollContainer = document.getElementById("scrollContainer");
if (scrollContainer) { 
    let isDown = false, startX, scrollLeft;
    scrollContainer.addEventListener("mousedown", e => {
        isDown = true; startX = e.pageX - scrollContainer.offsetLeft;
        scrollLeft = scrollContainer.scrollLeft; scrollContainer.style.cursor = 'grabbing'; 
    });
    scrollContainer.addEventListener("mouseleave", () => { isDown = false; scrollContainer.style.cursor = 'grab'; });
    scrollContainer.addEventListener("mouseup", () => { isDown = false; scrollContainer.style.cursor = 'grab'; });
    scrollContainer.addEventListener("mousemove", e => {
        if(!isDown) return; e.preventDefault();
        const x = e.pageX - scrollContainer.offsetLeft; const walk = (x - startX) * 2;
        scrollContainer.scrollLeft = scrollLeft - walk;
    });
    scrollContainer.style.cursor = 'grab'; 
}
</script>


{{-- ======================= JADWAL SERTIFIKASI (VERSI SLIDER) ======================= --}}
{{-- 
  INI ADALAH BAGIAN YANG DIPERBAIKI. 
  Kode ini menggantikan grid lama Anda dengan slider 3-kartu yang benar.
--}}
<section class="bg-gray-50 py-12 px-10 text-center relative">
    <h2 class="text-3xl font-bold mb-8 font-poppins">Jadwal yang Akan Datang</h2>

    @php
        // Membagi data jadwal menjadi kelompok-kelompok (3 jadwal per kelompok/slide)
        $jadwalChunks = $jadwals->chunk(3);
    @endphp

    {{-- Container slider dibuat lebar penuh (px-10 dari section) agar kartu sama lebarnya --}}
    <div class="max-w-7xl mx-auto relative">
        
        <div class="overflow-hidden relative rounded-2xl">
            <div class="flex transition-transform duration-500 ease-in-out" id="jadwal-track">

                @forelse($jadwalChunks as $chunk)
                    {{-- Setiap Slide (berisi 3 kartu) --}}
                    <div class="flex-none w-full grid grid-cols-1 md:grid-cols-3 gap-6 p-2">
                        
                        @foreach($chunk as $jadwal)
                            {{-- KARTU JADWAL (Ukuran Dikecilkan) --}}
                            <div class="card bg-white shadow-lg rounded-2xl">
                                <div class="card-body flex flex-col p-6 text-left">
                                    
                                    <p class="text-sm mb-1 font-bold">Sertifikasi:</p>
                                    <p class="text-base font-semibold mb-3 h-12 line-clamp-2">{{ $jadwal->skema?->nama_skema ?? 'Skema tidak ditemukan' }}</p>

                                    <p class="text-sm mb-1 font-bold">TUK:</p>
                                    {{-- PERBAIKAN: Menggunakan 'nama_lokasi' --}}
                                    <p class="text-base mb-3">{{ $jadwal->masterTuk?->nama_lokasi ?? 'TUK tidak spesifik' }}</p>

                                    <p class="text-sm mb-1 font-bold">Tanggal:</p>
                                    <p class="text-base mb-6">{{ $jadwal->tanggal_pelaksanaan ? $jadwal->tanggal_pelaksanaan->format('d F Y') : 'TBA' }}</p>

                                    {{-- PERBAIKAN: Tombol besar, di tengah, dan route 'jadwal.detail' --}}
                                    <a href="{{ route('jadwal.detail', ['id' => $jadwal->id_jadwal]) }}" 
                                       class="btn bg-yellow-400 text-black font-semibold border-none 
                                              hover:bg-yellow-300 px-8 py-3 rounded-full text-base mt-auto w-full text-center">Detail</a>
                                </div>
                            </div>
                        @endforeach

                        {{-- Placeholder untuk slide yang tidak penuh --}}
                        @if($loop->last && $chunk->count() < 3)
                            @for ($i = 0; $i < (3 - $chunk->count()); $i++)
                                <div class="hidden md:block"></div> 
                            @endfor
                        @endif

                    </div>
                @empty
                    <div class="w-full text-center text-gray-500 py-10">
                        <p>Belum ada jadwal yang akan datang saat ini.</p>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- Tombol Navigasi Slider --}}
        @if($jadwalChunks->count() > 1)
            <button id="jadwal-prev" 
                    class="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2 
                           bg-white rounded-full shadow-md w-10 h-10 flex items-center justify-center 
                           text-gray-700 hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed z-10">
                &larr;
            </button>
            <button id="jadwal-next" 
                    class="absolute top-1/2 right-0 translate-x-1/2 -translate-y-1/2 
                           bg-white rounded-full shadow-md w-10 h-10 flex items-center justify-center 
                           text-gray-700 hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed z-10">
                &rarr;
            </button>
        @endif

    </div>
</section>

{{-- ======================= SCRIPT UNTUK SLIDER JADWAL ======================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const jadwalTrack = document.getElementById('jadwal-track');
    const jadwalPrevBtn = document.getElementById('jadwal-prev');
    const jadwalNextBtn = document.getElementById('jadwal-next');

    // Cek jika elemen slider jadwal ada
    if (jadwalTrack && jadwalPrevBtn && jadwalNextBtn) {
        const jadwalSlides = Array.from(jadwalTrack.children);
        const totalJadwalSlides = jadwalSlides.length;
        let jadwalCurrentIndex = 0;

        function showJadwalSlide(index) {
            if (!jadwalTrack) return; 
            jadwalTrack.style.transform = `translateX(-${index * 100}%)`;
            jadwalPrevBtn.disabled = (index === 0);
            jadwalNextBtn.disabled = (index === totalJadwalSlides - 1);
        }

        jadwalPrevBtn.addEventListener('click', () => {
            if (jadwalCurrentIndex > 0) {
                jadwalCurrentIndex--;
                showJadwalSlide(jadwalCurrentIndex);
            }
        });

        jadwalNextBtn.addEventListener('click', () => {
            if (jadwalCurrentIndex < totalJadwalSlides - 1) {
                jadwalCurrentIndex++;
                showJadwalSlide(jadwalCurrentIndex);
            }
        });

        showJadwalSlide(0);
    }
});
</script>


{{-- ======================= BERITA TERBARU (VERSI SLIDER) ======================= --}}
<section id="berita-terbaru" class="bg-white py-12 px-10 text-center relative">
    <h2 class="text-3xl font-bold mb-8 font-poppins">Berita Terbaru</h2>

    @php
        // Membagi data berita menjadi kelompok-kelompok (3 berita per slide)
        $beritaChunks = $beritas->chunk(3);
    @endphp

    {{-- Container slider (Sama lebarnya dengan Skema dan Jadwal) --}}
    <div class="max-w-7xl mx-auto relative">
        
        <div class="overflow-hidden relative rounded-2xl">
            <div class="flex transition-transform duration-500 ease-in-out" id="berita-track">

                @forelse($beritaChunks as $chunk)
                    {{-- Setiap Slide (berisi 3 kartu) --}}
                    <div class="flex-none w-full grid grid-cols-1 md:grid-cols-3 gap-6 p-2">
                        
                        @foreach($chunk as $berita)
                            {{-- KARTU BERITA (Dibuat h-full agar sama tinggi) --}}
                            <div class="card bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.03] text-left h-full flex flex-col">
                                
                                <a href="{{ route('berita.detail', ['id' => $berita->id]) }}" class="flex flex-col h-full"> 
                                    
                                    <img src="{{ asset('storage/berita/' . $berita->gambar) }}" 
                                         alt="{{ $berita->judul }}" 
                                         class="w-full h-48 object-cover">
                                    
                                    {{-- Diberi flex-grow agar 'Baca Selengkapnya' rata bawah --}}
                                    <div class="p-6 flex flex-col flex-grow">
                                        <p class="text-sm text-gray-500 mb-2">
                                            {{ $berita->created_at->format('d F Y') }}
                                        </p>
                                        
                                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2" style="min-height: 3.5rem;">
                                            {{ $berita->judul }}
                                        </h3>
                                        
                                        {{-- 'mt-auto' mendorong tombol ke bawah --}}
                                        <span class="font-semibold text-blue-700 hover:underline flex items-center gap-1 group mt-auto">
                                            Baca Selengkapnya
                                            <span class="transition-transform duration-200 group-hover:translate-x-1">&rarr;</span>
                                        </span>
                                    </div>

                                </a>
                            </div>
                        @endforeach

                        {{-- Placeholder untuk slide yang tidak penuh --}}
                        @if($loop->last && $chunk->count() < 3)
                            @for ($i = 0; $i < (3 - $chunk->count()); $i++)
                                <div class="hidden md:block"></div> 
                            @endfor
                        @endif

                    </div>
                @empty
                    <div class="w-full text-center text-gray-500 py-10">
                        <p>Belum ada berita yang dipublikasikan.</p>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- Tombol Navigasi Slider --}}
        @if($beritaChunks->count() > 1)
            <button id="berita-prev" 
                    class="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2 
                           bg-white rounded-full shadow-md w-10 h-10 flex items-center justify-center 
                           text-gray-700 hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed z-10">
                &larr;
            </button>
            <button id="berita-next" 
                    class="absolute top-1/2 right-0 translate-x-1/2 -translate-y-1/2 
                           bg-white rounded-full shadow-md w-10 h-10 flex items-center justify-center 
                           text-gray-700 hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed z-10">
                &rarr;
            </button>
        @endif

    </div>
</section>

{{-- ======================= SCRIPT UNTUK SLIDER BERITA ======================= --}}
{{-- (Script ini terpisah dari slider Jadwal, menggunakan ID unik) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const beritaTrack = document.getElementById('berita-track');
    const beritaPrevBtn = document.getElementById('berita-prev');
    const beritaNextBtn = document.getElementById('berita-next');

    // Cek jika elemen slider berita ada di halaman ini
    if (beritaTrack && beritaPrevBtn && beritaNextBtn) {
        const beritaSlides = Array.from(beritaTrack.children);
        const totalBeritaSlides = beritaSlides.length;
        let beritaCurrentIndex = 0;

        function showBeritaSlide(index) {
            if (!beritaTrack) return; 
            beritaTrack.style.transform = `translateX(-${index * 100}%)`;
            beritaPrevBtn.disabled = (index === 0);
            beritaNextBtn.disabled = (index === totalBeritaSlides - 1);
        }

        beritaPrevBtn.addEventListener('click', () => {
            if (beritaCurrentIndex > 0) {
                beritaCurrentIndex--;
                showBeritaSlide(beritaCurrentIndex);
            }
        });

        beritaNextBtn.addEventListener('click', () => {
            if (beritaCurrentIndex < totalBeritaSlides - 1) {
                beritaCurrentIndex++;
                showBeritaSlide(beritaCurrentIndex);
            }
        });

        // Tampilkan slide pertama saat load
        showBeritaSlide(0);
    }
});
</script>

@endsection