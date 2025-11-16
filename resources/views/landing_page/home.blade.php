@extends('layouts.app-profil')

@section('content')

{{-- ======================= HERO ======================= --}}
<section class="relative h-[900px] rounded-t-4xl overflow-hidden z-10">
    {{-- Gambar Latar Belakang --}}
    <img src="{{ asset('images/Gedung Polines.jpg') }}"
        alt="Gedung Polines"
        class="w-full h-full object-cover">
    
    {{-- Overlay Gradasi Biru --}}
    <div class="absolute inset-0 bg-gradient-to-r from-[#96C9F4]/95 via-[#96C9F4]/60 to-transparent"></div>
    
    {{-- Gradasi Putih di Bawah --}}
    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-white/80 via-white/30 to-transparent"></div>
    
    {{-- Konten Utama (Header Text dan Tombol) --}}
    <div class="absolute top-1/3 inset-x-0">
        
        <div class="container mx-auto px-8"> 
            
            <div class="text-black drop-shadow-lg max-w-xl">
                <h1 class="text-6xl font-bold mb-4">LSP POLINES</h1>
                <p class="text-xl mb-6 leading-relaxed">Tempat sertifikasi resmi Politeknik Negeri Semarang.</p>

                <div class="flex items-center gap-6 mt-8">
                    {{-- Tombol Daftar --}}
                    <a href="{{ route('login') }}"
                        class="bg-yellow-400 text-black font-bold px-8 py-3 rounded-lg shadow-lg
                             hover:bg-yellow-500 transition-all duration-300 ease-in-out
                             transform hover:scale-105">
                        Daftar
                    </a>
                    {{-- Tombol Eksplore --}}
                    <a href="#skema-sertifikasi"
                        class="text-black font-semibold text-lg flex items-center gap-2
                             hover:gap-3 transition-all duration-300 ease-in-out group">
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
body {
    overflow-x: hidden; /* Tambahkan ini */
}

.carousel-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 100;
    background: white;
    color: black;
    padding: 0.5rem 0.75rem;
    border-radius: 9999px; /* Full rounded */
    transition: all 0.3s;
    cursor: pointer;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
}

.carousel-nav-btn:hover {
    background: rgb(55 65 81);
    color: white;
}

html { scroll-behavior: smooth; }
#scrollContainer::-webkit-scrollbar { display: none; }
#scrollContainer { -ms-overflow-style: none; scrollbar-width: none; margin-bottom: 2rem; }

.active-category {
    background-color: rgb(250 204 21) !important; /* Kuning 500 */
    color: black;
    transform: translateY(-3px) scale(1.05) !important;
    transition: all 0.25s ease-in-out;
    box-shadow: 0 6px 15px rgba(250, 204, 21, 0.5);
}

.inactive-category {
    background-color: rgb(254 249 195) !important; /* Kuning 100 */
    color: #444;
    transform: scale(1);
    transition: all 0.25s ease-in-out;
}

.inactive-category:hover {
    background-color: rgb(254 240 138); /* Kuning 200 */
}

/* FIX KRUSIAL: Pastikan teks kategori tidak kepotong */
.category-btn {
    white-space: nowrap !important; /* Mencegah wrap */
    height: auto !important;
    padding-top: 0.5rem !important;
    padding-bottom: 0.5rem !important;
    display: inline-flex !important; /* Ganti dari inline-block ke inline-flex */
    width: fit-content !important;
}

/* Sembunyikan slide tambahan saat filter aktif */
.skema-slide.hidden-slide {
    display: none !important;
}
</style>

{{-- ======================= FILTER KATEGORI ====================== --}}
<section id="skema-sertifikasi" 
             class="py-10 text-center relative z-20 bg-white -mt-10">
    <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
    
    <div class="relative w-full max-w-7xl mx-auto">
        <div id="scrollContainer" 
             class="flex flex-row gap-4 overflow-x-scroll whitespace-nowrap justify-start py-4 px-4">
            
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
                                    {{-- Menggunakan asset path relatif untuk gambar skema --}}
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
<script>
// ======================= LOGIKA FILTER DAN CAROUSEL (Final) =======================
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('#categoryButtons button');
    const cards = document.querySelectorAll('.skema-card');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const gridSlides = document.getElementById('gridSlides');
    const slides = document.querySelectorAll('.skema-slide');
    const totalSlides = slides.length;
    let currentIndex = 0;
    let isFilterActive = false;

    // Fungsi untuk memperbarui tampilan tombol
    function setActiveButton(selectedButton) {
        buttons.forEach(btn => {
            btn.classList.remove('active-category');
            btn.classList.add('inactive-category');
        });
        selectedButton.classList.add('active-category');
        selectedButton.classList.remove('inactive-category');
    }
    
    window.moveSlide = function(direction) {
        if (isFilterActive) return; // Jangan geser jika sedang mode filter

        let newIndex = currentIndex + direction;

        if (newIndex < 0) {
            newIndex = 0; // Batas kiri
        } else if (newIndex >= totalSlides) {
            newIndex = totalSlides - 1; // Batas kanan
        }

        showSlide(newIndex);
    }

    function showSlide(index) {
        if (isFilterActive) return;

        currentIndex = index;
        gridSlides.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Update status tombol navigasi
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        if (prevBtn && nextBtn) {
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex === totalSlides - 1;
        }
    }

    // Set default button (Semua)
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
                // Mode Filter: Sembunyikan navigasi, reset slide, dan sembunyikan semua slide di awal
                if (prevBtn && nextBtn) {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'none';
                }
                gridSlides.style.transform = `translateX(0%)`;
                slides.forEach(slide => slide.classList.add('hidden-slide'));
            } else {
                // Mode "Semua": Tampilkan navigasi, reset slide, dan tampilkan semua slide
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
                
                // Gunakan 'block' atau 'none' untuk kartu
                card.style.display = isVisible ? 'block' : 'none';
                
                if (isVisible && isFilterActive) {
                    visibleCardCount++;
                    // Jika filter aktif, pastikan slide induk kartu yang terlihat juga ditampilkan
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
});

// ======================= SCROLL DRAG KATEGORI =======================
const scrollContainer = document.getElementById("scrollContainer");
if (scrollContainer) { 
    let isDown = false, startX, scrollLeft;
    scrollContainer.addEventListener("mousedown", e => {
        isDown = true;
        startX = e.pageX - scrollContainer.offsetLeft;
        scrollLeft = scrollContainer.scrollLeft;
        scrollContainer.style.cursor = 'grabbing'; 
    });
    scrollContainer.addEventListener("mouseleave", () => { isDown = false; scrollContainer.style.cursor = 'grab'; });
    scrollContainer.addEventListener("mouseup", () => { isDown = false; scrollContainer.style.cursor = 'grab'; });
    scrollContainer.addEventListener("mousemove", e => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - scrollContainer.offsetLeft;
        const walk = (x - startX) * 2;
        scrollContainer.scrollLeft = scrollLeft - walk;
    });
    scrollContainer.style.cursor = 'grab'; 
}
</script>

{{-- ======================= JADWAL SERTIFIKASI ======================= --}}
<section class="bg-gray-50 py-12 px-10 text-center">
    <h2 class="text-3xl font-bold mb-8">Jadwal yang Akan Datang</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-6xl mx-auto">
        @forelse ($jadwals as $jadwal)
            <div class="card bg-white shadow-lg rounded-2xl">
                <div class="card-body flex flex-col p-8">
                    <p class="text-base mb-1 font-bold text-left">Sertifikasi:</p>
                    {{-- Mengambil nama skema dari relasi 'skema' --}}
                    <p class="text-base mb-3 text-left">{{ $jadwal->skema?->nama_skema ?? 'Skema tidak ditemukan' }}</p>

                    <p class="text-base mb-1 font-bold text-left">TUK:</p>
                    {{-- Mengambil nama TUK dari relasi 'masterTuk' --}}
                    <p class="text-base mb-3 text-left">{{ $jadwal->masterTuk?->nama_tuk ?? 'TUK tidak spesifik' }}</p>

                    <p class="text-base mb-1 font-bold text-left">Tanggal:</p>
                    {{-- Menggunakan kolom 'tanggal_pelaksanaan' dan memformatnya --}}
                    <p class="text-base mb-6 text-left">{{ $jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d F Y') : 'Tanggal belum diatur' }}</p>

                    {{-- Menggunakan primary key 'id_jadwal' untuk detail jadwal --}}
                    <a href="{{ route('detail_jadwal', $jadwal->id_jadwal) }}" 
                       class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300 px-8 py-3 rounded-full text-base">Detail</a>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 text-center text-gray-500">
                <p>Belum ada jadwal yang akan datang saat ini.</p>
            </div>
        @endforelse
    </div>
</section>

{{-- ======================= BERITA TERBARU ======================= --}}
<section id="berita-terbaru" class="bg-white py-12 px-10 text-center">
    <h2 class="text-3xl font-bold mb-8">Berita Terbaru</h2>
    
    {{-- Grid untuk Kartu Berita --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
        
        @forelse ($beritas as $berita)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.03] text-left">
                
                <a href="{{ route('berita.detail', ['id' => $berita->id]) }}"> 
                    
                    {{-- Gambar Berita (Menggunakan asset('storage/berita/...') sebagai path gambar yang lebih umum) --}}
                    <img src="{{ asset('storage/berita/' . $berita->gambar) }}" 
                        alt="{{ $berita->judul }}" 
                        class="w-full h-48 object-cover">
                    
                    <div class="p-6">
                        {{-- Tanggal Publish --}}
                        <p class="text-sm text-gray-500 mb-2">
                            {{ $berita->created_at->format('d F Y') }}
                        </p>
                        
                        {{-- Judul Berita --}}
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2" style="min-height: 3.5rem;">
                            {{ $berita->judul }}
                        </h3>
                        
                        {{-- Link Baca Selengkapnya --}}
                        <span class="font-semibold text-blue-700 hover:underline flex items-center gap-1 group">
                            Baca Selengkapnya
                            <span class="transition-transform duration-200 group-hover:translate-x-1">&rarr;</span>
                        </span>
                    </div>

                </a>
            </div>
        @empty
            {{-- Tampilan jika tidak ada berita --}}
            <div class="md:col-span-2 lg:col-span-3 text-center text-gray-500 py-16">
                <p class="text-xl">Belum ada berita yang dipublikasikan.</p>
            </div>
        @endforelse

    </div>
</section>

@endsection