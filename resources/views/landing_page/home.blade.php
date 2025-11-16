@extends('layouts.app-profil')

@section('content')

{{-- ======================= HERO ======================= --}}
<section class="relative h-[1000px] rounded-t-4xl overflow-hidden">
    <img src="{{ asset('images/Gedung Polines.jpg') }}"
         alt=""
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-[#96C9F4]/95 via-[#96C9F4]/60 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-white/80 via-white/30 to-transparent"></div>
    <div class="absolute top-1/3 left-16 text-black drop-shadow-lg max-w-xl">
        <h1 class="text-6xl font-bold mb-4">LSP POLINES</h1>
        <p class="text-xl mb-6 leading-relaxed">Tempat sertifikasi resmi Politeknik Negeri Semarang.</p>

        {{-- ======================= KODE TOMBOL YANG DITAMBAHKAN ======================= --}}
        <div class="flex items-center gap-6 mt-8">
            {{-- Tombol Daftar (Kuning) --}}
            <a href="{{ route('login') }}" {{-- DIUBAH: Mengarah ke route 'login' --}}
            class="bg-yellow-400 text-black font-bold px-8 py-3 rounded-lg shadow-lg
                    hover:bg-yellow-500 transition-all duration-300 ease-in-out
                    transform hover:scale-105">
                Daftar
            </a>

            {{-- Tombol Eksplore Skema (Link) --}}
            <a href="#skema-sertifikasi" {{-- DIUBAH: Mengarah ke ID 'skema-sertifikasi' di bawah --}}
               class="text-black font-semibold text-lg flex items-center gap-2
                      hover:gap-3 transition-all duration-300 ease-in-out group">
                Eksplore Skema
                <span class="font-bold text-xl transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>
        {{-- ===================== AKHIR KODE TOMBOL YANG DITAMBAHKAN ===================== --}}

    </div>
</section>

<style>
html {
    scroll-behavior: smooth;
}

#scrollContainer::-webkit-scrollbar { display: none; }
#scrollContainer { -ms-overflow-style: none; scrollbar-width: none; }

#categoryButtons {
    position: relative;
    z-index: 50; /* pastikan di atas elemen lain */
}

.active-category {
    background-color: rgb(250 204 21);
    color: black;
    transform: scale(1.2);
    transition: all 0.25s ease-in-out;
    box-shadow: 0 4px 12px rgba(250, 204, 21, 0.6);
}

.inactive-category {
    background-color: rgb(254 249 195);
    color: #444;
    transform: scale(1);
    transition: all 0.25s ease-in-out;
}

.inactive-category:hover {
    background-color: rgb(254 240 138);
}

.active-category {
    transform: scale(1.2) translateY(-3px);
}

/* Tambahkan jarak agar efek glow tidak tertutup */
#scrollContainer {
    margin-bottom: 2rem;
}
</style>

{{-- ======================= FILTER KATEGORI ======================= --}}
<section id="skema-sertifikasi" {{-- <== PASTIKAN ID INI TERPASANG --}}
            class="py-10 text-center relative z-[100] bg-white -mt-10">
        <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
        <div id="categoryButtons" class="inline-flex gap-4">
            @foreach($categories as $category)
                <button data-category="{{ $category }}"
                        class="btn btn-sm font-bold rounded-full px-6 border-none
                        {{ $loop->first ? 'active-category' : 'inactive-category hover:bg-yellow-200' }}">
                    {{ $category }}
                </button>
            @endforeach
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
</script>

{{-- ======================= CAROUSEL GRID SKEMA ======================= --}}
<section class="px-10 mb-16">
    <div id="gridCarousel" class="relative overflow-hidden rounded-3xl w-full">
        <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">
            @php
                $chunks = $skemas->chunk(6); // 6 kartu per slide
            @endphp

            @foreach($chunks as $chunk)
                <div class="flex-none w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6">
                    @foreach($chunk as $skema)
                        <div class="transition hover:scale-105 skema-card" data-category="{{ $skema->kategori }}">
                            <a href="{{ route('skema.detail', ['id' => $skema->id_skema]) }}">
                                <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                                    <img src="{{ $skema->gambar ? asset('images/' . $skema->gambar) : asset('images/default.jpg') }}"
                                         alt=""
                                         class="h-48 w-full object-cover">
                                </div>
                            </a>
                            <div class="px-2">
                                <h2 class="text-lg font-bold text-gray-800">{{ $skema->nama_skema }}</h2>
                                <p class="text-sm text-gray-500 mb-1">{{ $skema->kategori }}</p>
                                <p class="text-gray-800 font-semibold">Rp {{ number_format($skema->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('#categoryButtons button');
    const cards = document.querySelectorAll('.skema-card');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedCategory = button.dataset.category;

            // ubah tampilan tombol aktif
            buttons.forEach(btn => {
                btn.classList.remove('active-category');
                btn.classList.add('inactive-category');
            });
            button.classList.add('active-category');
            button.classList.remove('inactive-category');

            // tampilkan kartu sesuai kategori
            cards.forEach(card => {
                const cardCategory = card.dataset.category;
                card.style.display = (selectedCategory === 'Semua' || cardCategory === selectedCategory)
                    ? '' : 'none';
            });
        });
    });
});
</script>

<script>
const gridSlides = document.getElementById('gridSlides');
const slides = gridSlides.children;
const totalSlides = slides.length;
let currentIndex = 0;

function showSlide(index) {
    gridSlides.style.transform = `translateX(-${index * 100}%)`;
}

setInterval(() => {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
}, 5000);
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
