<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respon Asesmen IA01</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <x-sidebar></x-sidebar>

        <main class="flex-1 p-10 overflow-y-auto">
            <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-xl">
                <ol class="relative">

                    @php
                        function renderCheckmark() {
                            return '<div class="absolute -top-1 -left-1.5 z-10 bg-green-500 rounded-full p-0.5 border-2 border-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3 h-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>';
                        }
                    @endphp

                    @foreach($respon as $item)
                    <li class="relative flex items-start pb-10">
                        <div class="absolute left-6 top-6 -bottom-10 w-0.5 bg-gray-200"></div>
                        <div class="relative flex-shrink-0 mr-6">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M12 12h8.25m-8.25 0H3.75" />
                                </svg>
                            </div>
                            {!! renderCheckmark() !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Kriteria: {{ $item->kriteria->nama_kriteria ?? '-' }}</h3>
                            <p class="text-sm text-gray-500">Respon Asesi: {{ $item->respon_asesi_apl02 ?? 'Belum diisi' }}</p>
                            <p class="text-sm text-gray-500">Pencapaian IA01: {{ $item->pencapaian_ia01 === 1 ? 'Ya/K' : ($item->pencapaian_ia01 === 0 ? 'Tidak/BK' : '-') }}</p>
                            <p class="text-sm text-gray-500">Penilaian Lanjut: {{ $item->penilaian_lanjut_ia01 ?? '-' }}</p>

                            @if($item->bukti_asesi_apl02)
                                <a href="{{ asset('storage/'.$item->bukti_asesi_apl02) }}" target="_blank" class="mt-2 inline-block px-4 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-md hover:bg-blue-600">
                                    Unduh Bukti
                                </a>
                            @endif
                        </div>
                    </li>
                    @endforeach

                </ol>
            </div>
        </main>
    </div>
</body>
</html>