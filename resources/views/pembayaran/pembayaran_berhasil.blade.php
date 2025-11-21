<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Dikonfirmasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        {{-- JANGAN LUPA PASANG ID ASESI BIAR SIDEBAR JALAN --}}
        <x-sidebar :idAsesi="$asesi->id_asesi"></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-2xl mx-auto text-center">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-20">Pembayaran</h1>

                <div class="mb-8">
                    <svg class="w-32 h-32 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-gray-900">
                    Pembayaran Anda Telah <br> Dikonfirmasi!
                </h2>

                <div class="flex justify-center items-center mt-20 space-x-4">
                    {{-- TOMBOL 1: KE DASHBOARD UTAMA --}}
                    <a href="/dashboard" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Halaman Utama
                    </a>

                    {{-- TOMBOL 2: LANJUT (BALIK KE TRACKER JADWAL SPESIFIK) --}}
                    {{-- Nanti di tracker otomatis tombol "Pra Asesmen" kebuka --}}
                    <a href="{{ url('/tracker/' . $id_jadwal) }}" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                        Selanjutnya
                    </a>
                </div>

            </div>
        </main>

    </div>

</body>
</html>