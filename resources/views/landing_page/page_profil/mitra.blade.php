@extends('layouts.app-profil')

@section('title', 'Mitra Kami')

@section('content')
    <section id="mitra" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-4 text-center font-poppins">Mitra Kami</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                
                @forelse($mitras as $mitra)
                    <a href="{{ $mitra->url }}" target="_blank" class="block group">
                        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center h-full transition hover:-translate-y-1 hover:shadow-xl duration-300 cursor-pointer">
                            
                            <div class="mb-6">
                                @if($mitra->logo)
                                    <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->nama_mitra }}" class="rounded shadow-sm w-32 h-32 object-contain bg-white group-hover:opacity-90 transition">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($mitra->nama_mitra) }}&background=random&size=128" alt="Inisial" class="rounded-full shadow-sm w-32 h-32 object-cover">
                                @endif
                            </div>

                            <h5 class="text-xl font-bold text-center font-poppins mb-2 group-hover:text-blue-600 transition">
                                {{ $mitra->nama_mitra }}
                            </h5>
                            
                            <span class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-external-link-alt"></i> Kunjungi Website
                            </span>

                        </div>
                    </a>
                @empty
                    <div class="col-span-1 md:col-span-3 text-center py-10">
                        <div class="text-gray-400 text-xl">Belum ada mitra.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection