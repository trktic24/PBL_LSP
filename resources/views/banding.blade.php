<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banding Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <!-- Catatan: x-sidebar adalah custom component, diasumsikan berfungsi -->
        <x-sidebar></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <!-- START: FORM WRAPPER -->
                <!-- action disesuaikan dengan route('banding.store') yang sudah Anda buat (POST /banding) -->
                <form method="POST" action="{{ route('banding.store') }}">
                    @csrf <!-- WAJIB untuk form POST di Laravel -->

                    <!-- Data Hidden (Diambil dari Controller) -->
                    <input type="hidden" name="nama_asesor" value="{{ $dataAsesmen->nama_asesor ?? '' }}">
                    <input type="hidden" name="nama_asesi" value="{{ $dataAsesmen->nama_asesi ?? '' }}">
                    <input type="hidden" name="skema_sertifikasi" value="{{ $dataAsesmen->skema_sertifikasi ?? '' }}">
                    <input type="hidden" name="no_skema_sertifikasi" value="{{ $dataAsesmen->no_skema_sertifikasi ?? '' }}">
                    <input type="hidden" name="tanggal_asesmen" value="{{ $dataAsesmen->tanggal_asesmen ?? '' }}">
                    <input type="hidden" name="tanggal_pengajuan_banding" value="{{ date('Y-m-d') }}">


                    <h1 class="text-4xl font-bold text-gray-900 mb-10">Banding Asesmen</h1>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">
                        <div class="col-span-1 font-medium text-gray-800">TUK</div>
                        <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                            <!-- Field TUK: Karena ini checkbox yang tidak divalidasi di Controller,
                                 kita abaikan name, tetapi jika ini data penting, 
                                 Anda harus menggunakan radio button atau hidden input/field name yang jelas. 
                                 Untuk saat ini, kita biarkan saja. -->
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" name="tuk_sewaktu" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                                Sewaktu
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" name="tuk_tempatkerja" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                                Tempat Kerja
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" name="tuk_mandiri" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                                Mandiri
                            </label>
                        </div>
                        
                        <!-- Menampilkan data dari Controller (dataAsesmen) -->
                        <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800">: {{ $dataAsesmen->nama_asesor ?? '' }}</div>

                        <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800">: {{ $dataAsesmen->nama_asesi ?? '' }}</div>
                    </div>

                    <div class="text-sm text-gray-700 mb-6">Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :</div>
                    
                    <div class="shadow border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-900 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Komponen</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Ya</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Tidak</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Pertanyaan 1: Harus menggunakan RADIO BUTTON dengan NAME yang sama agar hanya satu pilihan yang terpilih -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">Apakah Proses Banding telah dijelaskan kepada Anda?</td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_1" value="Ya" required class="w-5 h-5 text-blue-600 rounded-full border-2 border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_1" value="Tidak" required class="w-5 h-5 text-red-600 rounded-full border-2 border-red-500 focus:ring-red-500">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Pertanyaan 2 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_2" value="Ya" required class="w-5 h-5 text-blue-600 rounded-full border-2 border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_2" value="Tidak" required class="w-5 h-5 text-red-600 rounded-full border-2 border-red-500 focus:ring-red-500">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Pertanyaan 3 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda mau melibatkan 'orang lain' membantu Anda dalam Proses Banding?</td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_3" value="Ya" required class="w-5 h-5 text-blue-600 rounded-full border-2 border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <div class="flex justify-center">
                                            <input type="radio" name="ya_tidak_3" value="Tidak" required class="w-5 h-5 text-red-600 rounded-full border-2 border-red-500 focus:ring-red-500">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 text-sm text-gray-700 space-y-2">
                        <p>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional berikut:</p>
                        <!-- Menampilkan data dari Controller (dataAsesmen) -->
                        <p>Skema Sertifikasi: <span class="font-medium text-gray-900">{{ $dataAsesmen->skema_sertifikasi ?? '' }}</span></p>
                        <p>No. Skema Sertifikasi: <span class="font-medium text-gray-900">{{ $dataAsesmen->no_skema_sertifikasi ?? '' }}</span></p>
                    </div>

                    <div class="mt-6">
                        <label for="alasan" class="text-sm font-medium text-gray-700">Banding ini diajukan atas alasan sebagai berikut:</label>
                        <!-- WAJIB: Tambahkan name="alasan_banding" -->
                        <textarea id="alasan" name="alasan_banding" rows="4" placeholder="Berikan Keterangan Disini" required class="mt-2 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                    </div>

                    <div class="mt-6">
                        <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                            Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
                        </p>
                        <div class="w-full h-32 bg-gray-50 border border-gray-300 rounded-lg shadow-inner mt-2">
                            <!-- Area Tanda Tangan akan ditambahkan di sini (biasanya canvas) -->
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-red-600 text-xs italic">*Tanda Tangan di sini</p>
                            <button type="button" class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                                Hapus
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-12">
                        <button type="button" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                            Sebelumnya
                        </button>
                        <!-- Tombol Kirim harus bertipe submit agar mengirim form -->
                        <button type="submit" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                            Kirim
                        </button>
                    </div>
                    
                </form>
                <!-- END: FORM WRAPPER -->

            </div>
        </main>

    </div>

</body>
</html>