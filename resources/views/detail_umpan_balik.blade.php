<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Umpan Balik</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <x-sidebar2></x-sidebar2>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">

        <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-xl">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Detail Umpan Balik</h1>

            <!-- Informasi Asesi -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Asesi</h2>
                <p class="text-gray-700">
                    Nama: {{ $respon->dataSertifikasiAsesi->asesi->nama_lengkap ?? '-' }}
                </p>
                <p class="text-gray-700">
                    ID Data Sertifikasi: {{ $respon->dataSertifikasiAsesi->id_data_sertifikasi_asesi ?? '-' }}
                </p>
            </div>

            <!-- Tabel Detail Jawaban -->
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left">Pernyataan</th>
                            <th class="px-6 py-3 text-center w-24">Jawaban</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                        <!-- 1 -->
                        <tr>
                            <td class="px-6 py-4">
                                Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->penjelasan_proses_asesmen ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 2 -->
                        <tr>
                            <td class="px-6 py-4">
                                Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->memahami_standar_kompetensi ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 3 -->
                        <tr>
                            <td class="px-6 py-4">
                                Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metode, instrumen, dan sumber asesmen, serta jadwal asesmen.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->diskusi_metode_dengan_asesor ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 4 -->
                        <tr>
                            <td class="px-6 py-4">
                                Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->menggali_bukti_pendukung ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 5 -->
                        <tr>
                            <td class="px-6 py-4">
                                Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->kesempatan_demos_kompetensi ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 6 -->
                        <tr>
                            <td class="px-6 py-4">
                                Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->penjelasan_keputusan_asesmen ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 7 -->
                        <tr>
                            <td class="px-6 py-4">
                                Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->umpan_balik_setelah_asesmen ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 8 -->
                        <tr>
                            <td class="px-6 py-4">
                                Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->mempelajari_dokumen_asesmen ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 9 -->
                        <tr>
                            <td class="px-6 py-4">
                                Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->jaminan_kerahasiaan ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- 10 -->
                        <tr>
                            <td class="px-6 py-4">
                                Asesor menggunakan keterampilan komunikasi efektif selama asesmen.
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->komunikasi_efektif_asesor ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>

                        <!-- Catatan -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 font-semibold">
                                Catatan / Komentar Tambahan
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $respon->catatan ?? '-' }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('terimakasih', ['id' => $respon->dataSertifikasiAsesi->id_data_sertifikasi_asesi]) }}"
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold">
                   Kembali
                </a>

            </div>

        </div>

    </main>

</body>
</html>