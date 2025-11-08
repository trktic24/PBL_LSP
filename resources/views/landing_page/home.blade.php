@extends('layouts.app-profil')

@section('content')

{{-- ======================= HERO ======================= --}}
<section class="relative h-[1000px] rounded-t-4xl overflow-hidden">
    <img src="{{ asset('images/Gedung Polines.jpg') }}"
         alt="Gedung Polines"
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-[#96C9F4]/95 via-[#96C9F4]/60 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-64 bg-gradient-to-t from-white/95 via-white/50 to-transparent"></div>
    <div class="absolute top-1/3 left-16 text-black drop-shadow-lg max-w-xl">
        <h1 class="text-6xl font-bold mb-4">LSP POLINES</h1>
        <p class="text-xl mb-6 leading-relaxed">Tempat sertifikasi resmi Politeknik Negeri Semarang.</p>
    </div>
</section>

<style>
/* CSS untuk menyembunyikan scrollbar bawaan browser */
#scrollContainer::-webkit-scrollbar { display: none; }
#scrollContainer { -ms-overflow-style: none; scrollbar-width: none; }

.active-category {
    background-color: rgb(250 204 21); /* kuning pekat */
    color: black;
}
.inactive-category {
    background-color: rgb(254 249 195); /* kuning pudar */
    color: #444;
}
</style>

{{-- ======================= FILTER KATEGORI (SCROLL HORIZONTAL) ======================= --}}
<section class="py-10 text-center">
    <div id="scrollContainer" class="overflow-x-auto whitespace-nowrap px-6 cursor-grab active:cursor-grabbing select-none">
        <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
        <div id="categoryButtons" class="inline-flex gap-4">
            {{-- Tombol Kategori Disesuaikan Berdasarkan Data API yang Terisi --}}
            <button data-category="Semua" class="btn btn-sm font-bold rounded-full px-6 border-none active-category">Semua</button>
            <button data-category="Bisnis & Manajemen" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Bisnis & Manajemen</button>
            <button data-category="Jaringan & Keamanan" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Jaringan & Keamanan</button>
            <button data-category="Administrasi" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Administrasi</button>
            <button data-category="Konstruksi" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Konstruksi</button>
            <button data-category="Teknologi Informasi" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Teknologi Informasi</button>
            {{-- Anda bisa menambahkan tombol lain jika data back-end sudah diisi --}}
        </div>
    </div>
</section>

<script>
// ======================= SCROLL DRAG KATEGORI =======================
const scrollContainer = document.getElementById("scrollContainer");
let isDown = false, startX, scrollLeft;
scrollContainer.addEventListener("mousedown", e => {
    isDown = true; 
    startX = e.pageX - scrollContainer.offsetLeft;
    scrollLeft = scrollContainer.scrollLeft;
});
scrollContainer.addEventListener("mouseleave", () => isDown = false);
scrollContainer.addEventListener("mouseup", () => isDown = false);
scrollContainer.addEventListener("mousemove", e => {
    if(!isDown) return; 
    e.preventDefault();
    const x = e.pageX - scrollContainer.offsetLeft;
    const walk = (x - startX) * 2;
    scrollContainer.scrollLeft = scrollLeft - walk;
});
// Variabel Global untuk kategori button
const categoryButtons = document.querySelectorAll('#categoryButtons button'); 
</script>

{{-- ======================= CAROUSEL GRID SKEMA ======================= --}}
<section class="px-10 mb-16">
    <div id="gridCarousel" class="relative overflow-hidden rounded-3xl w-full">
        <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">
            {{-- Loading awal, akan diisi ulang oleh JavaScript --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full p-6" id="initial-loading">
                <p>Memuat data skema...</p> 
            </div>
        </div>
        <div id="carousel-nav" class="hidden">
            {{-- Navigasi slide (dibuat jika ada lebih dari 1 slide) --}}
        </div>
    </div>
</section>

<script>
// ======================= LOGIKA API FETCH & FILTER ======================= 
const apiSkemaUrl = '/api/skema'; 
const gridSlides = document.getElementById('gridSlides');
let carouselInterval; // Variabel untuk menyimpan interval carousel
let allSkemas = []; // Menyimpan semua data skema yang dimuat dari API

function createSkemaCard(skema) { 
    const category = skema.kategori || 'Lainnya'; 
    const namaSkema = skema.nama_skema || 'Nama Skema Tidak Ada';
    const idSkema = skema.id_skema;
    
    return `
        <div class="transition hover:scale-105 skema-card" data-category="${category}">
            <a href="/detail-skema/${idSkema}"> 
                <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                    <img src="{{ asset('images') }}/${skema.gambar || 'default-skema.jpg'}" alt="" class="h-48 w-full object-cover">
                </div>
            </a>
            <div class="px-2">
                <h2 class="text-lg font-bold text-gray-800">${namaSkema}</h2>
                <p class="text-gray-600">Rp. x.xxx.xxx</p>
            </div>
        </div>
    `;
}

// Fungsi utama untuk me-render card ke slides
function renderSlides(skemaArray) {
    // 1. Bersihkan slides
    gridSlides.innerHTML = '';
    
    // 2. Bersihkan interval carousel lama
    if (carouselInterval) clearInterval(carouselInterval);

    // 3. Konfigurasi slide
    const cardsPerSlide = 6;
    let slidesHtml = [];
    
    for (let i = 0; i < skemaArray.length; i += cardsPerSlide) {
        const slideSkemas = skemaArray.slice(i, i + cardsPerSlide);
        let slideContent = '';
        
        slideSkemas.forEach(skema => {
            slideContent += createSkemaCard(skema);
        });
        
        slidesHtml.push(`
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full p-6">
                ${slideContent}
            </div>
        `);
    }

    if (slidesHtml.length === 0) {
        gridSlides.innerHTML = `<div class="p-6 md:col-span-3 text-center text-gray-500">Tidak ada skema yang ditemukan dalam kategori ini.</div>`;
        return;
    }

    // 4. Masukkan konten ke DOM
    gridSlides.innerHTML = slidesHtml.join('');

    // 5. Inisialisasi Ulang Carousel
    const newSlides = document.querySelectorAll('#gridSlides > div');
    const newTotalSlides = newSlides.length;
    let newCurrentIndex = 0;
    
    function showSlide(index) { 
        if(newTotalSlides > 0) {
            gridSlides.style.transform = `translateX(-${index * 100}%)`; 
        }
    }
    
    if (newTotalSlides > 1) {
        // Atur posisi awal ke slide pertama
        showSlide(0); 
        
        carouselInterval = setInterval(() => { 
            newCurrentIndex = (newCurrentIndex + 1) % newTotalSlides; 
            showSlide(newCurrentIndex); 
        }, 5000);
    } else {
        // Jika hanya 1 slide, pastikan tidak ada translasi yang salah
        showSlide(0); 
    }
}

// Fungsi untuk menangani klik filter
function handleFilterClick(event) {
    const btn = event.currentTarget;
    const category = btn.dataset.category;

    // Logika ubah warna tombol
    categoryButtons.forEach(b => b.classList.remove('active-category', 'inactive-category'));
    categoryButtons.forEach(b => b.classList.add('inactive-category'));
    btn.classList.remove('inactive-category');
    btn.classList.add('active-category');

    // LOGIKA PENTING: Filter data berdasarkan kategori
    let filteredSkemas;
    const lowerCaseCategory = category.toLowerCase();
    if (category === 'Semua') {
        filteredSkemas = allSkemas;
    } else {
        // Filter array data, pastikan 'category' sesuai dengan key di JSON API
        filteredSkemas = allSkemas.filter(skema => (skema.kategori || '').toLowerCase() === lowerCaseCategory
        );
    }
    
    // Render ulang slides dengan data yang sudah disaring
    renderSlides(filteredSkemas);
}

// Ambil data dari API
fetch(apiSkemaUrl)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        allSkemas = data.data; // Simpan semua data skema
        
        // 1. Render data default (Semua)
        renderSlides(allSkemas);

        // 2. Pasang event listener pada tombol kategori (setelah data siap)
        categoryButtons.forEach(btn => {
            btn.addEventListener('click', handleFilterClick);
        });
    })
    .catch(error => {
        // Tampilkan error jika fetch gagal
        gridSlides.innerHTML = `<div class="p-6 md:col-span-3 text-center text-red-600">
            Gagal memuat data skema. Silakan coba lagi nanti. (${error.message})
        </div>`;
    });
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