@extends('layouts.app-profil')

@section('content')

{{-- ======================= HERO ======================= --}}
<section class="relative h-[1000px] rounded-t-4xl overflow-hidden">
    <img src="{{ asset('images/Gedung Polines.jpg') }}"
        alt="Gedung Polines"
        class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-[#96C9F4]/95 via-[#96C9F4]/60 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-white/80 via-white/30 to-transparent"></div>
    <div class="absolute top-1/3 left-16 text-black drop-shadow-lg max-w-xl">
        <h1 class="text-6xl font-bold mb-4">LSP POLINES</h1>
        <p class="text-xl mb-6 leading-relaxed">Tempat sertifikasi resmi Politeknik Negeri Semarang.</p>

        <div class="flex items-center gap-6 mt-8">
            <a href="{{ route('login') }}"
            class="bg-yellow-400 text-black font-bold px-8 py-3 rounded-lg shadow-lg
                    hover:bg-yellow-500 transition-all duration-300 ease-in-out
                    transform hover:scale-105">
                Daftar
            </a>
            <a href="#skema-sertifikasi"
               class="text-black font-semibold text-lg flex items-center gap-2
                       hover:gap-3 transition-all duration-300 ease-in-out group">
                Eksplore Skema
                <span class="font-bold text-xl transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>
    </div>
</section>

<style>
/* ======================= FIX CSS UNTUK FORMAT KATEGORI ======================= */
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
    white-space: nowrap !important; /* Mencegah teks turun baris */
    height: auto !important;
    padding-top: 0.5rem !important;
    padding-bottom: 0.5rem !important;
}

/* Sembunyikan slide tambahan saat filter aktif */
.skema-slide.hidden-slide {
    display: none !important;
}
</style>

{{-- ======================= FILTER KATEGORI (FIXED TAMPILAN) ====================== --}}
<section id="skema-sertifikasi" 
             class="py-10 text-center relative z-[100] bg-white -mt-10">
    <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
    
    {{-- FIX: Menggunakan container lebar penuh dan menghilangkan max-w-4xl --}}
    <div class="relative w-full px-4 mx-auto">
        {{-- FIX: Menggunakan justify-start agar item berada di kiri (walaupun di scroll) --}}
        <div id="scrollContainer" 
             class="flex flex-row gap-4 overflow-x-scroll whitespace-nowrap justify-start pb-4">
            
            {{-- FIX: Menggunakan w-fit dan mx-auto agar tombol rata tengah jika kurang dari lebar layar --}}
            <div id="categoryButtons" class="inline-flex gap-4 w-fit mx-auto"> 
                @foreach($categories as $category)
                    <button data-category="{{ $category }}"
                            class="category-btn btn btn-sm font-bold rounded-full px-4 border-none
                            {{ $loop->first ? 'active-category' : 'inactive-category' }}">
                        {{ $category }} 
                    </button>
                @endforeach
            </div>

        </div>
    </div>
</section>

{{-- ======================= CAROUSEL GRID SKEMA ======================= --}}
<section class="px-10 mb-16">
    <div id="gridCarousel" class="relative overflow-hidden rounded-3xl w-full">
        
        <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">
            @php
                $chunks = $skemas->chunk(6); // 6 kartu per slide
            @endphp

            @forelse($chunks as $index => $chunk)
                {{-- Setiap chunk (kelompok 6 kartu) menjadi 1 slide --}}
                <div class="flex-none w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6 skema-slide" data-slide-index="{{ $index }}">
                    @foreach($chunk as $skema)
                        @php
                            // FIX RELASI KRUSIAL: Menggunakan Nullsafe Operator (?->) pada relasi 'category'
                            $categoryName = $skema->category?->nama_kategori ?? 'Tidak Terkategori';
                        @endphp
                        
                        <div class="transition hover:scale-105 skema-card" data-category="{{ $categoryName }}">
                            <a href="{{ route('skema.detail', ['id' => $skema->id_skema]) }}">
                                <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                                    <img src="{{ $skema->gambar ? asset('images/' . $skema->gambar) : asset('images/default.jpg') }}"
                                         alt="Gambar Skema"
                                         class="h-48 w-full object-cover">
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
    
    // Set default button (Semua)
    const defaultButton = document.querySelector('button[data-category="Semua"]');
    if (defaultButton) {
        setActiveButton(defaultButton);
    }

    // Fungsi untuk menggeser carousel (tanpa filter)
    function showSlide(index) {
        if (!isFilterActive) {
            currentIndex = index;
            gridSlides.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
    }
    
    // Autoscroll
    let carouselInterval = setInterval(() => {
        if (!isFilterActive) {
            currentIndex = (currentIndex + 1) % totalSlides;
            showSlide(currentIndex);
        }
    }, 5000);

    // Fungsi utama filter
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedCategory = button.dataset.category;
            let visibleCardCount = 0;

            setActiveButton(button);
            
            // Tentukan apakah filter 'Semua' sedang aktif
            isFilterActive = (selectedCategory !== 'Semua');
            
            if (isFilterActive) {
                // Hentikan autoscroll dan reset slide ke 0
                clearInterval(carouselInterval);
                gridSlides.style.transform = `translateX(0%)`;
                
                // Sembunyikan semua slide di awal
                slides.forEach(slide => slide.classList.add('hidden-slide'));

            } else {
                // Filter "Semua" aktif: Tampilkan semua slide dan aktifkan autoscroll
                slides.forEach(slide => slide.classList.remove('hidden-slide'));
                currentIndex = 0;
                showSlide(0); 
                carouselInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    showSlide(currentIndex);
                }, 5000);
            }

            // Tampilkan kartu sesuai kategori
            cards.forEach(card => {
                const cardCategory = card.dataset.category;
                
                const isVisible = (selectedCategory === 'Semua' || cardCategory === selectedCategory);
                
                // Gunakan 'block' atau 'none' untuk kartu
                card.style.display = isVisible ? 'block' : 'none';
                
                if (isVisible) {
                    visibleCardCount++;
                    // Jika filter aktif, pastikan slide induk kartu yang terlihat juga ditampilkan
                    if(isFilterActive) {
                        card.closest('.skema-slide').classList.remove('hidden-slide');
                    }
                }
            });

            // Tampilkan/Sembunyikan pesan "Tidak ada hasil"
            if (visibleCardCount === 0) {
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
                    <p class="text-base mb-3 text-left">{{ $jadwal->nama_skema }}</p>

                    <p class="text-base mb-1 font-bold text-left">TUK:</p>
                    <p class="text-base mb-3 text-left">{{ $jadwal->tuk }}</p>

                    <p class="text-base mb-1 font-bold text-left">Tanggal:</p>
                    <p class="text-base mb-6 text-left">{{ $jadwal->tanggal ? $jadwal->tanggal->format('d F Y') : 'Tanggal belum diatur' }}</p>

                    <a href="{{ route('jadwal.detail', ['id' => $jadwal->id]) }}" class="btn bg-yellow-400 text-black font-semibold border-none hover:bg-yellow-300 px-8 py-3 rounded-full text-base">Detail</a>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 text-center text-gray-500">
                <p>Belum ada jadwal yang akan datang saat ini.</p>
            </div>
        @endforelse
    </div>
</section>

@endsection