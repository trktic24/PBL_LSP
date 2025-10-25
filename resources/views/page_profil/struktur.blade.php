@extends('layouts.app-profil')

@section('title', 'Struktur Organisasi')

@section('content')
<section id="struktur" class="py-20 bg-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-24">Struktur Organisasi</h2>

        <div class="relative w-full max-w-6xl mx-auto" style="height: 500px;">

            <div class="absolute top-0 left-1/2 -translate-x-1/2 z-20">
                <div class="bg-blue-600 text-white font-semibold py-4 px-10 rounded-lg shadow-md">
                    JABATAN
                </div>
            </div>

            <div class="absolute w-0.5 h-10 bg-black left-1/2 -translate-x-1/2 z-10 top-14"></div>

            <div class="absolute h-0.5 bg-black z-10 top-24" style="left: 16.66%; right: 16.66%;"></div>

            <div class="absolute w-full flex justify-between z-20 top-24">

                <div class="relative w-1/3 flex justify-center">
                    <div class="absolute top-0 w-0.5 h-8 bg-black"></div>
                    <div class="bg-white border-2 border-blue-600 text-gray-800 font-semibold py-4 px-8 rounded-lg shadow-md mt-8">
                        JABATAN
                    </div>
                    <div class="absolute top-full w-0.5 h-10 bg-black"></div>
                </div>

                <div class="relative w-1/3 flex justify-center">
                    
                    <div class="bg-white border-2 border-blue-600 text-gray-800 font-semibold py-4 px-8 rounded-lg shadow-md mt-8">
                        JABATAN
                    </div>
                    <div class="absolute top-full w-0.5 h-10 bg-black"></div>
                </div>

                <div class="relative w-1/3 flex justify-center">
                    <div class="absolute top-0 w-0.5 h-8 bg-black"></div>
                    <div class="bg-white border-2 border-blue-600 text-gray-800 font-semibold py-4 px-8 rounded-lg shadow-md mt-8">
                        JABATAN
                    </div>
                    <div class="absolute top-full w-0.5 h-10 bg-black"></div>
                </div>
            </div>

            <div class="absolute h-0.5 bg-black z-10 top-56" style="left: 12.5%; right: 12.5%;"></div>

            <div class="absolute w-full flex justify-between z-20 top-56">
                
                <div class="relative w-1/4 flex justify-center">
                    <div class="absolute top-0 w-0.5 h-8 bg-black"></div>
                    <div class="bg-white border-2 border-gray-400 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow-md mt-8">
                        JABATAN
                    </div>
                </div>

                <div class="relative w-1/4 flex justify-center">
                    <div class="absolute top-0 w-0.5 h-8 bg-black"></div>
                    <div class="bg-white border-2 border-gray-400 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow-md mt-8">
                        JABATAN
                    </div>
                </div>

                <div class="relative w-1/4 flex justify-center">
                    <div class="absolute top-0 w-0.5 h-8 bg-black"></div>
                    <div class="bg-white border-2 border-gray-400 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow-md mt-8">
                        JABATAN
                    </div>
                </div>

                <div class="relative w-1/4 flex justify-center">
                    <div class="absolute top-0 w-0.5 h-8 bg-black"></div>
                    <div class="bg-white border-2 border-gray-400 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow-md mt-8">
                        JABATAN
                    </div>
                </div>
            </div>

        </div> </div>
</section>
@endsection