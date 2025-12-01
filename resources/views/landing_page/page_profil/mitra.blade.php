@extends('layouts.app-profil')

@section('title', 'Mitra Kami')
@section('description', 'Daftar mitra kerja sama LSP Polines.')

@section('content')
    <section id="mitra" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-4 text-center font-poppins">Mitra Kami</h2>
            <p class="text-center text-gray-600 text-xl -mt-4 mb-12">Lorem ipsum dolor sit amet</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center h-full">
                    <img src="https://dicoding-web-img.sgp1.cdn.digitaloceanspaces.com/original/commons/dicoding-logo-full.png" alt="Dicoding Logo" class="max-w-[150px] mb-6">
                    <h5 class="text-xl font-bold text-center font-poppins">Dicoding</h5>
                </div>
                
                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center h-full">
                    <div class="w-full h-24 bg-gray-200 rounded flex items-center justify-center text-2xl text-gray-400 mb-4">
                        Logo
                    </div>
                    <h5 class="text-xl font-bold text-center font-poppins">Lorem Ipsum dolor</h5>
                    <p class="text-center text-gray-600">sit amet</p>
                </div>
                
                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center h-full">
                    <div class="w-full h-24 bg-gray-200 rounded flex items-center justify-center text-2xl text-gray-400 mb-4">
                        Logo
                    </div>
                    <h5 class="text-xl font-bold text-center font-poppins">Lorem Ipsum dolor</h5>
                    <p class="text-center text-gray-600">sit amet</p>
                </div>
                
            </div>
        </div>
    </section>
@endsection