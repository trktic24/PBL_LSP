<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'LSP Polines' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100">

    <aside class="w-64 bg-[linear-gradient(135deg,#4F46E5,#0EA5E9)] text-white p-6 flex flex-col fixed h-screen top-0 left-0 overflow-y-auto">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-blue-100 hover:text-white mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>

        <div class="text-center">
            <img src="https://via.placeholder.com/100/000000/FFFFFF/?text=SKEMA"
                 alt="Ikon Skema"
                 class="w-24 h-24 rounded-full mx-auto border-4 border-blue-700 object-cover">
            <h2 class="text-xl font-semibold mt-4">Junior Web Developer</h2>
            <p class="text-xs text-blue-200 mt-1 px-4">
                Lorem ipsum dolor sit amet. You're the best person I ever met.
            </p>
        </div>

        <hr class="my-6 border-blue-700">

        <div class="text-center">
            <span class="uppercase text-blue-200 font-semibold">ASESOR:</span>
            <div class="mt-3">
                <img src="https://via.placeholder.com/50/CCCCCC/FFFFFF/?text=AJ"
                     alt="Foto Asesor"
                     class="w-12 h-12 rounded-full mx-auto block">
                <div class="mt-3 text-center">
                    <h3 class="font-medium">Ajeng Febria H.</h3>
                </div>
            </div>
        </div>

        <div class="mt-auto text-center">
            <span class="text-xs uppercase text-blue-100 font-semibold">DIMULAI PADA:</span>
            <p class="text-sm font-medium">2025-09-28 06:18:25</p>
        </div>
    </aside>

    <!-- Konten utama -->
    <main class="ml-64 flex-1 flex flex-col min-h-screen">
        <header class="bg-white shadow p-4">
            <h1 class="text-lg font-semibold text-gray-800">
                {{ $title ?? 'LSP Polines' }}
            </h1>
        </header>

        <section class="flex-1">
            {{ $slot }}
        </section>
    </main>

</body>
</html>
