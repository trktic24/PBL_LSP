@extends('layouts.app-profil')

@section('title', 'Visi & Misi')

@section('content')
    <section id="visi-misi" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <img src="{{ asset('images/Logo LSP No BG.png') }}" alt="LSP POLINES" class="mb-3 mx-auto" style="height: 200px;">
                <p class="font-bold text-3xl">Lembaga Sertifikasi Profesi Politeknik Negeri Semarang</p>
            </div>

            {{-- 🟦 PERBAIKAN: Menggunakan satu Grid Container 🟦 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 justify-items-center">
        
                {{-- Kartu Visi --}}
                <div class="bg-white shadow-lg rounded-lg p-10 text-center h-full w-full">
                    <h3 class="text-2xl font-bold mb-3">Visi</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, you're the best person i've ever met</p>
                </div>
        
                {{-- Kartu Misi --}}
                <div class="bg-white shadow-lg rounded-lg p-10 text-center h-full w-full">
                    <h3 class="text-2xl font-bold mb-3">Misi</h3>
                    <p class="text-gray-600">Lorem ipsum dolor sit amet, you're the best person i've ever met</p>
                </div>
        
                {{-- Kartu Tujuan (Sekarang membentang 2 kolom) --}}
                <div class="bg-white shadow-lg rounded-lg p-12 lg:col-span-2 w-full">
                    <h3 class="text-2xl font-bold mb-6 text-center">Tujuan</h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                        <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                        <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                        <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                        <li>Lorem ipsum dolor sit amet, you're the best person i've ever met</li>
                    </ol>
                </div>
        
            </div>
            {{-- 🟦 BATAS AKHIR KODE BARU 🟦 --}}
            
        </div>
    </section>
@endsection