<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Detail Skema - {{ $skema->nama_skema }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">

<x-navigasi></x-navigasi>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <header class="relative bg-blue-500 rounded-xl overflow-hidden">
        <img 
            src="" 
            alt="Kode Program" 
            class="absolute inset-0 w-full h-full object-cover opacity-20">
        
        <div class="relative z-10 p-12 md:p-16">
            
            <h1 class="text-4xl md:text-5xl font-bold text-white">
                {{ $skema->nama_skema }}
            </h1>
            
            <p class="mt-3 text-lg text-blue-100">
                lorem ipsum dolor sit amet, you're the best person I've ever met
            </p>
        </div>
    </header>


    <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-12">

        <div class="lg:col-span-2">
            
            <section>
                <h2 class="text-3xl font-bold text-gray-900">
                    Deskripsi
                </h2>
                
                <p class="mt-4 text-base text-gray-600 leading-relaxed">
                    {!! nl2br(e($skema->deskripsi_skema)) !!}
                </p>
            </section>

            <section class="mt-10">
                <h3 class="text-2xl font-bold text-gray-900">
                    Detail Sertifikasi
                </h3>
                
                <ul class="mt-4 list-disc list-inside space-y-2 text-gray-600">
                    @forelse ($skema->detailSertifikasi as $detail)
                        <li>{{ $detail->deskripsi_detail }}</li>
                    @empty
                        <li>Belum ada detail sertifikasi untuk skema ini.</li>
                    @endforelse
                </ul>
            </section>
        </div>

        <aside class="lg:col-span-1">
            <h2 class="text-3xl font-bold text-gray-900">
                SKKNI
            </h2>
            
            <div class="mt-4 space-y-4">
                @forelse ($skema->unitKompetensi as $unit)
                    <div class="bg-gray-100 border border-gray-200 rounded-lg p-4">
                        <p class="font-bold text-sm text-gray-800">{{ $unit->kode_unit }}</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $unit->judul_unit }}</p>
                    </div>
                @empty
                    <div class="bg-gray-100 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-700">Belum ada unit kompetensi (SKKNI) untuk skema ini.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 text-center">
                <a href="/tracker" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-8 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    Ambil Skema
                </a>
            </div>
        </aside>

    </div>
    
</main>
</body>
</html>