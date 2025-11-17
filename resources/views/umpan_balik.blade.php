<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umpan Balik Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

@php
    $feedback = $feedback ?? null;
    $asesi = $asesi ?? null;
    $asesor = $asesor ?? null;
@endphp

<div class="flex min-h-screen">

    <x-sidebar2></x-sidebar2>

    <main class="flex-1 p-12 bg-white overflow-y-auto">

        <div class="max-w-4xl mx-auto">

            <h1 class="text-4xl font-bold text-gray-900 mb-10">
                Umpan Balik dan Catatan Asesmen
            </h1>

            <!-- Form -->
            <form action="/umpan_balik/{{ $feedback?->id ?? 0 }}/store" method="POST">

                @csrf

                <input type="hidden" name="id_umpan_balik" value="{{ $feedback->id ?? '' }}">

                <!-- Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">

                    <div class="col-span-1 font-medium text-gray-800">TUK</div>
                    <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                        @foreach (['Sewaktu','Tempat Kerja','Mandiri'] as $t)
                        <label class="flex items-center text-gray-700">
                            <input type="radio" name="tuk" value="{{ $t }}"
                                   class="w-4 h-4 mr-2">
                            {{ $t }}
                        </label>
                        @endforeach
                    </div>

                    <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                    <div class="col-span-3 text-gray-800">: {{ $asesor->nama_asesor ?? '' }}</div>

                    <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                    <div class="col-span-3 text-gray-800">: {{ $asesi->nama_asesi ?? '' }}</div>

                </div>

                <!-- Tabel Pernyataan -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-bold">Komponen</th>
                                <th class="px-6 py-3 text-center text-sm font-bold w-20">Ya</th>
                                <th class="px-6 py-3 text-center text-sm font-bold w-20">Tidak</th>
                                <th class="px-6 py-3 text-left text-sm font-bold w-40">Catatan</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @php 
                            $list = [
                                "Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi",
                                "Saya diberikan kesempatan mempelajari standar kompetensi dan menilai diri sendiri",
                                "Master Asesor menggali seluruh bukti pendukung yang saya miliki",
                                "Saya diberi kesempatan penuh mendemonstrasikan kompetensi",
                                "Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen",
                                "Master Asesor memberikan umpan balik yang mendukung",
                                "Kami mempelajari seluruh dokumen asesmen bersama",
                                "Saya mendapatkan jaminan kerahasiaan hasil asesmen",
                                "Master Asesor menggunakan komunikasi yang efektif",
                            ];
                            @endphp

                            @foreach ($list as $i => $text)
                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">{{ $i+1 }}</span>
                                        <p class="text-sm text-gray-700">{{ $text }}</p>
                                    </div>

                                    <input type="hidden" name="items[{{ $i }}][nomor]" value="{{ $i+1 }}">
                                    <input type="hidden" name="items[{{ $i }}][pernyataan]" value="{{ $text }}">
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="items[{{ $i }}][jawaban]" value="1"
                                           class="w-5 h-5 border-blue-500">
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="items[{{ $i }}][jawaban]" value="0"
                                           class="w-5 h-5 border-red-500">
                                </td>

                                <td class="px-6 py-4">
                                    <textarea name="items[{{ $i }}][catatan]" 
                                        class="w-full text-sm border-gray-300 rounded-lg"></textarea>
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- Catatan Tambahan -->
                <div class="mt-6">
                    <label class="text-sm font-medium text-gray-700">Catatan/komentar lainnya:</label>
                    <textarea name="catatan_tambahan" rows="4"
                              class="mt-2 w-full border-gray-300 rounded-lg text-sm"></textarea>
                </div>

                <div class="flex justify-between items-center mt-12">
                    <a href="/"
                       class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full">
                        Sebelumnya
                    </a>

                    <button class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full">
                        Kirim
                    </button>
                </div>

            </form>

        </div>
    </main>

</div>

</body>
</html>