<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banding AK-04</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

@php
    $respon = $respon ?? null;
    $asesi = $asesi ?? null;
@endphp

<div class="flex min-h-screen">

    <x-sidebar2></x-sidebar2>

    <main class="flex-1 p-12 bg-white overflow-y-auto">

        <div class="max-w-4xl mx-auto">

            <h1 class="text-4xl font-bold text-gray-900 mb-10">
                Formulir Banding (AK-04)
            </h1>

            <!-- Form -->
            <form action="/umpan_balik/store" method="POST">

                @csrf

                <input type="hidden" name="id_respon_ak04" value="{{ $respon->id_respon_ak04 ?? '' }}">
                <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $asesi->id_data_sertifikasi_asesi ?? '' }}">

                <!-- Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">

                    <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                    <div class="col-span-3 text-gray-800">: {{ $asesi->asesi->nama_lengkap ?? '-' }}</div>

                </div>


                <!-- Tabel Pernyataan -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-bold">Pertanyaan</th>
                                <th class="px-6 py-3 text-center text-sm font-bold w-20">Ya</th>
                                <th class="px-6 py-3 text-center text-sm font-bold w-20">Tidak</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            <!-- 1. Penjelasan Banding -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">
                                        Apakah proses banding telah dijelaskan kepada Anda?
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="penjelasan_banding" value="1"
                                           class="w-5 h-5" required>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="penjelasan_banding" value="0"
                                           class="w-5 h-5" required>
                                </td>
                            </tr>

                            <!-- 2. Diskusi dengan Asesor -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">
                                        Apakah Anda telah mendiskusikan banding dengan asesor?
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="diskusi_dengan_asesor" value="1"
                                           class="w-5 h-5" required>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="diskusi_dengan_asesor" value="0"
                                           class="w-5 h-5" required>
                                </td>
                            </tr>

                            <!-- 3. Melibatkan Orang Lain -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">
                                        Apakah Anda melibatkan orang lain dalam proses banding ini?
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="melibatkan_orang_lain" value="1"
                                           class="w-5 h-5" required>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="melibatkan_orang_lain" value="0"
                                           class="w-5 h-5" required>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- Alasan Banding -->
                <div class="mt-6">
                    <label class="text-sm font-medium text-gray-700">Alasan Mengajukan Banding:</label>
                    <textarea name="alasan_banding" rows="4"
                              class="mt-2 w-full border-gray-300 rounded-lg text-sm"></textarea>
                </div>

                <div class="flex justify-between items-center mt-12">
                    <a href="/tracker"
                       class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full">
                        Sebelumnya
                    </a>

                    <button type="submit" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full">
                        Kirim
                    </button>
                </div>

            </form>

        </div>
    </main>

</div>

</body>
</html>