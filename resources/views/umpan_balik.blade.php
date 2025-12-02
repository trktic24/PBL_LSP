<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umpan Balik</title>
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
                Formulir Umpan Balik
            </h1>

            <!-- Form -->
            <form action="/umpan_balik/store" method="POST">

                @csrf

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

                        <!-- 1. Penjelasan proses asesmen -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="penjelasan_proses_asesmen" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="penjelasan_proses_asesmen" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 2. Memahami standar kompetensi -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="memahami_standar_kompetensi" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="memahami_standar_kompetensi" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 3. Diskusi metode dengan asesor -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metode, instrumen, dan sumber asesmen, serta jadwal asesmen.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="diskusi_metode_dengan_asesor" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="diskusi_metode_dengan_asesor" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 4. Menggali bukti pendukung -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="menggali_bukti_pendukung" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="menggali_bukti_pendukung" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 5. Kesempatan demonstrasi kompetensi -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="kesempatan_demos_kompetensi" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="kesempatan_demos_kompetensi" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 6. Penjelasan keputusan asesmen -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="penjelasan_keputusan_asesmen" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="penjelasan_keputusan_asesmen" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 7. Umpan balik setelah asesmen -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="umpan_balik_setelah_asesmen" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="umpan_balik_setelah_asesmen" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 8. Mempelajari dokumen asesmen -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="mempelajari_dokumen_asesmen" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="mempelajari_dokumen_asesmen" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 9. Jaminan kerahasiaan -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="jaminan_kerahasiaan" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="jaminan_kerahasiaan" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                        <!-- 10. Komunikasi efektif asesor -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    Asesor menggunakan keterampilan komunikasi efektif selama asesmen.
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="komunikasi_efektif_asesor" value="1" class="w-5 h-5" required>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input type="radio" name="komunikasi_efektif_asesor" value="0" class="w-5 h-5" required>
                            </td>
                        </tr>

                    </tbody>

                    </table>
                </div>

                <!-- Alasan Banding -->
                <div class="mt-6">
                    <label class="text-sm font-medium text-gray-700">Catatan/komentar (apabila ada):</label>
                    <textarea name="catatan" rows="4"
                              class="border-2 border-gray-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-300 rounded-md p-2 w-full"></textarea>
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