@extends('layouts.app-profil')

@section('title', 'Mitra Kami')

@section('content')
    <section id="mitra" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-4 text-center font-poppins">Mitra Kami</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                
                @forelse($mitras as $mitra)
                
                    <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center justify-center h-full transition hover:-translate-y-1 hover:shadow-xl duration-300">
                        
                        <div class="mb-6">
                            @if($mitra->logo)
                                <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->nama_mitra }}" class="rounded shadow-sm w-32 h-32 object-contain bg-white">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($mitra->nama_mitra) }}&background=random&size=128" alt="Inisial" class="rounded-full shadow-sm w-32 h-32 object-cover">
                            @endif
                        </div>

                        <h5 class="text-xl font-bold text-center font-poppins mb-2">
                            {{ $mitra->nama_mitra }}
                        </h5>
                        
                        <p class="text-center text-gray-600 text-sm mb-1 px-2">
                            {{ $mitra->alamat }}
                        </p>
                        
                        <p class="text-center text-blue-500 text-xs font-semibold">
                            {{ $mitra->no_telp }}
                        </p>

                    </div>
                    @empty
                    <div class="col-span-1 md:col-span-3 text-center py-10">
                        <div class="text-gray-400 text-xl">Belum ada mitra.</div>
                    </div>
                @endforelse
                </div>
        </div>
    </section>
@endsection