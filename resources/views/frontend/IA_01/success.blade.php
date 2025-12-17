@extends('layouts.app-sidebar')

@section('title', 'Berhasil Dikirim - BNSP')

@section('content')

<div class="flex flex-col items-center justify-center min-h-[80vh] text-center animate-fade-in-up px-4">

    {{-- Logo --}}
    <div class="mb-6">
        <img src="{{ asset('images/bnsp_logo.png') }}" alt="BNSP" class="h-12 object-contain">
    </div>

    {{-- Judul --}}
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 uppercase tracking-wide">Cek Observasi</h1>
        <h2 class="text-2xl md:text-3xl font-medium text-gray-900">Demonstrasi / Praktik</h2>
    </div>

    {{-- Centang --}}
    <div class="mb-8 relative">
        <div class="absolute inset-0 bg-green-100 rounded-full animate-ping opacity-60"></div>

        <div class="relative w-20 h-20 md:w-24 md:h-24 bg-green-500 rounded-full flex items-center justify-center shadow-xl">
            <svg class="checkmark w-12 h-12 md:w-14 md:h-14 text-white"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none"
                      d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
    </div>

    {{-- Pesan --}}
    <div class="mb-10">
        <h3 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-1">Form Berhasil Dikirim!</h3>
        <p class="text-base md:text-lg text-gray-500">Data asesmen telah tersimpan di sistem.</p>
    </div>

    {{-- Tombol --}}
    <a href="{{ route('asesi.profile.tracker') }}"
       class="px-10 md:px-12 py-3 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-full shadow-lg transition hover:-translate-y-1 hover:shadow-xl focus:ring-2 focus:ring-blue-300">
        Selesai
    </a>

</div>

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}
.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: transparent;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}
.checkmark__check {
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    stroke: #fff;
    stroke-width: 4;
    stroke-linecap: round;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.5s forwards;
}
@keyframes stroke { to { stroke-dashoffset: 0; } }
</style>

@endsection
