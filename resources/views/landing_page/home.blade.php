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

{{-- ======================= FILTER KATEGORI ======================= --}}
<section class="py-10 text-center">
    <div id="scrollContainer" class="overflow-x-auto whitespace-nowrap px-6 cursor-grab active:cursor-grabbing select-none">
        <p class="font-bold text-2xl mb-6">Skema Sertifikasi</p>
        <div id="categoryButtons" class="inline-flex gap-4">
            <button data-category="Semua" class="btn btn-sm font-bold rounded-full px-6 border-none active-category">Semua</button>
            <button data-category="Software" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Software</button>
            <button data-category="Hardware" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Hardware</button>
            <button data-category="Jaringan" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">Jaringan</button>
            <button data-category="AI & Network" class="btn btn-sm font-bold rounded-full px-6 border-none inactive-category hover:bg-yellow-200">AI & Network</button>
        </div>
    </div>
</section>

<script>
const scrollContainer = document.getElementById("scrollContainer");
let isDown = false, startX, scrollLeft;
scrollContainer.addEventListener("mousedown", e => { 
    isDown = true; startX = e.pageX - scrollContainer.offsetLeft; 
    scrollLeft = scrollContainer.scrollLeft; 
});
scrollContainer.addEventListener("mouseleave", () => isDown = false);
scrollContainer.addEventListener("mouseup", () => isDown = false);
scrollContainer.addEventListener("mousemove", e => { 
    if(!isDown) return; e.preventDefault(); 
    const x = e.pageX - scrollContainer.offsetLeft; 
    const walk = (x - startX) * 2; 
    scrollContainer.scrollLeft = scrollLeft - walk; 
});
</script>

{{-- ======================= CAROUSEL GRID SKEMA ======================= --}}
<section class="px-10 mb-16">
    @php
        $skemas = $skemas ?? [];
        $slide1 = array_slice($skemas, 0, 6);
        $slide2 = array_slice($skemas, 6, 6);
    @endphp
    <div id="gridCarousel" class="relative overflow-hidden rounded-3xl w-full">
        <div class="flex transition-transform duration-700 ease-in-out" id="gridSlides">
            {{-- Slide 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full p-6">
                @foreach ($slide1 as $i => $file)
                <div class="transition hover:scale-105 skema-card" data-category="{{ $file['kategori'] }}">
                    <a href="{{ route('detail_skema', ['id' => $file['id']]) }}">
                        <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                            <img src="{{ asset('images/' . $file['gambar']) }}" alt="{{ $file['nama'] }}" class="h-48 w-full object-cover">
                        </div>
                    </a>
                    <div class="px-2">
                        <h2 class="text-lg font-bold text-gray-800">{{ $file['nama'] }}</h2>
                        <p class="text-gray-600">Rp. x.xxx.xxx</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Slide 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 flex-none w-full p-6">
                @foreach ($slide2 as $file)
                <div class="transition hover:scale-105 skema-card" data-category="{{ $file['kategori'] }}">
                    <a href="{{ route('detail_skema', ['id' => $file['id']]) }}">
                        <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-lg mb-3">
                            <img src="{{ asset('images/' . $file['gambar']) }}" alt="{{ $file['nama'] }}" class="h-48 w-full object-cover">
                        </div>
                    </a>
                    <div class="px-2">
                        <h2 class="text-lg font-bold text-gray-800">{{ $file['nama'] }}</h2>
                        <p class="text-gray-600">Rp. x.xxx.xxx</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
const gridSlides = document.getElementById('gridSlides');
const slides = document.querySelectorAll('#gridSlides > div');
const totalSlides = slides.length;
let currentIndex = 0;
function showSlide(index) { gridSlides.style.transform = `translateX(-${index * 100}%)`; }
setInterval(() => { currentIndex = (currentIndex + 1) % totalSlides; showSlide(currentIndex); }, 5000);

// ===== FILTER KATEGORI =====
const categoryButtons = document.querySelectorAll('#categoryButtons button');
const cards = document.querySelectorAll('.skema-card');

categoryButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        // ubah warna tombol aktif
        categoryButtons.forEach(b => b.classList.remove('active-category'));
        categoryButtons.forEach(b => b.classList.add('inactive-category'));
        btn.classList.remove('inactive-category');
        btn.classList.add('active-category');

        const category = btn.dataset.category;
        cards.forEach(card => {
            const match = (category === 'Semua') || (card.dataset.category === category);
            card.style.display = match ? 'block' : 'none';
        });
    });
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
