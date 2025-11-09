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
    /* Efek menonjol dan naik ke atas */
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
}

/* PERBAIKAN: Mengunci warna dan efek tombol aktif agar bertahan saat di-hover */
.active-category:hover {
    background-color: rgb(250 204 21) !important; /* Pertahankan warna kuning pekat */
    transform: translateY(-2px) !important; /* Pertahankan posisi naik */
}

.inactive-category {
    background-color: rgb(254 249 195); /* kuning pudar */
    color: #444;
    transition: all 0.2s ease-in-out;
}
</style>

{{-- ======================= FILTER KATEGORI (SCROLL HORIZONTAL) ======================= --}}
<section class="py-10 text-center">
    <div id="scrollContainer" class="overflow-x-auto whitespace-nowrap px-6 cursor-grab active:cursor-grabbing select-none">
        <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
        <div id="categoryButtons" class="inline-flex gap-4">
            {{-- Tombol 'Semua' sebagai default. Tombol lain diisi JS --}}
            <button data-category="Semua" class="btn btn-sm font-bold rounded-full px-6 border-none active-category">Semua</button>
        </div>
    </div>
</section>

{{-- ======================= CAROUSEL GRID SKEMA ======================= --}}
<section class="px-10 mb-16">
    <div id="gridCarousel" class="relative overflow-hidden rounded-3xl w-full">
        <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">
            {{-- Loading awal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full p-6" id="initial-loading">
                <p>Memuat data skema...</p> 
            </div>
        </div>
        <div id="carousel-nav" class="hidden">
            {{-- Navigasi slide --}}
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

// **DEKLARASI VARIABEL GLOBAL**
let categoryButtons; // Dideklarasikan dengan 'let' agar bisa diperbarui
const categoryButtonsContainer = document.getElementById('categoryButtons'); 
const apiSkemaUrl = '/api/skema'; 
const gridSlides = document.getElementById('gridSlides');
let carouselInterval;
let allSkemas = []; 

// ======================= LOGIKA API FETCH & FILTER ======================= 

// FUNGSI: MENGAMBIL KATEGORI UNIK DAN MERENDER TOMBOL
function renderCategoryButtons(skemas) {
    const uniqueCategories = [...new Set(skemas.map(skema => skema.category))].filter(Boolean); 
    
    // Bersihkan tombol yang ada, kecuali 'Semua'
    while (categoryButtonsContainer.children.length > 1) {
        categoryButtonsContainer.removeChild(categoryButtonsContainer.lastChild);
    }
    
    // Tambahkan tombol untuk setiap kategori unik
    uniqueCategories.forEach(category => {
        const button = document.createElement('button');
        button.dataset.category = category;
        button.textContent = category;
        button.classList.add('btn', 'btn-sm', 'font-bold', 'rounded-full', 'px-6', 'border-none', 'inactive-category', 'hover:bg-yellow-200');
        categoryButtonsContainer.appendChild(button);
        
        button.addEventListener('click', handleFilterClick);
    });
    
    // Update variabel global categoryButtons
    categoryButtons = document.querySelectorAll('#categoryButtons button'); 
    
    // Pastikan filter default 'Semua' tetap aktif (untuk kasus page load)
    if (categoryButtons.length > 0) {
        categoryButtons[0].classList.remove('inactive-category'); 
        categoryButtons[0].classList.add('active-category');
        categoryButtons[0].style.transform = 'translateY(-2px)';
        categoryButtons[0].style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06)';
    }
}


function createSkemaCard(skema) { 
    const category = skema.category || 'Lainnya'; 
    const namaSkema = skema.nama_skema || 'Nama Skema Tidak Ada'; 
    const idSkema = skema.id_skema; 
    
    // Formatting harga
    const hargaFormat = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(skema.harga || 0);

    return `
        <div class="transition hover:scale-105 skema-card" data-category="${category}">
            <a href="/detail-skema/${idSkema}"> 
                <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                    <img src="{{ asset('images') }}/${skema.gambar || 'default-skema.jpg'}" alt="" class="h-48 w-full object-cover">
                </div>
            </a>
            <div class="px-2">
                <h2 class="text-lg font-bold text-gray-800">${namaSkema}</h2>
                <p class="text-gray-600">${hargaFormat}</p>
            </div>
        </div>
    `;
}

// Fungsi utama untuk me-render card ke slides
function renderSlides(skemaArray) {
    gridSlides.innerHTML = '';
    if (carouselInterval) clearInterval(carouselInterval);

    const cardsPerSlide = 6;
    let slidesHtml = [];
    
    if (skemaArray.length === 0) {
        gridSlides.innerHTML = `<div class="p-6 md:col-span-3 text-center text-gray-500">Tidak ada skema yang ditemukan dalam kategori ini.</div>`;
        return;
    }

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

    gridSlides.innerHTML = slidesHtml.join('');

    const newSlides = document.querySelectorAll('#gridSlides > div');
    const newTotalSlides = newSlides.length;
    let newCurrentIndex = 0;
    
    function showSlide(index) { 
        if(newTotalSlides > 0) {
            gridSlides.style.transform = `translateX(-${index * 100}%)`; 
        }
    }
    
    if (newTotalSlides > 1) {
        showSlide(0); 
        
        carouselInterval = setInterval(() => { 
            newCurrentIndex = (newCurrentIndex + 1) % newTotalSlides; 
            showSlide(newCurrentIndex); 
        }, 5000);
    } else {
        showSlide(0); 
    }
}

// FUNGSI: Menangani klik filter (Logika efek visual dan filtering)
function handleFilterClick(event) {
    const btn = event.currentTarget;
    const category = btn.dataset.category;

    // Logika ubah warna tombol (RESET SEMUA)
    categoryButtons.forEach(b => {
        b.classList.remove('active-category'); 
        b.classList.add('inactive-category');
        // Reset efek naik (transform)
        b.style.transform = 'translateY(0)';
        b.style.boxShadow = 'none';
    });
    
    // Terapkan efek pada tombol yang aktif (SET AKTIF)
    btn.classList.remove('inactive-category');
    btn.classList.add('active-category');
    btn.style.transform = 'translateY(-2px)';
    btn.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06)';

    // LOGIKA FILTER
    let filteredSkemas;
    const lowerCaseCategory = category.toLowerCase();
    
    if (category === 'Semua') {
        filteredSkemas = allSkemas;
    } else {
        filteredSkemas = allSkemas.filter(skema => 
            (skema.category && skema.category.toLowerCase() === lowerCaseCategory)
        );
    }
    
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
        
        // 1. Render tombol kategori dinamis
        renderCategoryButtons(allSkemas);
        
        // 2. Render data default (Semua)
        renderSlides(allSkemas);

        // 3. Pasang event listener untuk tombol 'Semua' (yang dibuat di Blade)
        document.querySelector('[data-category="Semua"]').addEventListener('click', handleFilterClick);
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