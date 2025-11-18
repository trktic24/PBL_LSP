<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sertifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">
        {{-- 1. Sidebar Component (Statis) --}}
        <x-sidebar.sidebar :idAsesi="1"></x-sidebar.sidebar>

        {{-- Memberi margin kiri untuk sidebar --}}
        <main class="flex-1 p-12 bg-white overflow-y-auto ml-64">

            {{-- Container utama untuk konten --}}
            <div class="max-w-4xl mx-auto">

                {{-- 2. Progress Bar --}}
                <div class="flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                            2</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                            3</div>
                    </div>
                </div>

                {{-- 3. Header --}}
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Data Sertifikasi</h1>
                <p class="text-gray-600 mb-8">
                    Pilih Tujuan Asesmen serta Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi yang anda
                    ajukan.
                </p>

                {{-- 4. Info Box Skema (Statis) --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Skema Sertifikasi / Klaster Asesmen</h3>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Judul</label>
                        <span class="text-gray-900 font-medium" id="judul-skema">
                            : Junior Web Developer (Statis)</span>
                    </div>
                    <div class="flex">
                        <label class="w-48 text-gray-600">Nomor</label>
                        <span class="text-gray-900 font-medium" id="nomor-skema">
                            : SKM.JWD.2024.01 (Statis)</span>
                    </div>
                </div>


                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Tujuan Asesmen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:auto-rows-fr">

                        {{-- 1. Sertifikasi --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="radio" name="tujuan_asesmen" value="Sertifikasi"
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio">
                            <span class="ml-3 text-sm font-medium text-gray-700">Sertifikasi</span>
                        </label>

                        {{-- 2. Sertifikasi Ulang --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="radio" name="tujuan_asesmen" value="Sertifikasi Ulang"
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio">
                            <span class="ml-3 text-sm font-medium text-gray-700">Sertifikasi Ulang</span>
                        </label>

                        {{-- 3. PKT --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="radio" name="tujuan_asesmen" value="PKT"
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio">
                            <span class="ml-3 text-sm font-medium text-gray-700">Pengakuan Kompetensi Terkini
                                (PKT)</span>
                        </label>

                        {{-- 4. RPL --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="radio" name="tujuan_asesmen" value="RPL"
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio">
                            <span class="ml-3 text-sm font-medium text-gray-700">Rekognisi Pembelajaran Lampau</span>
                        </label>

                        {{-- 5. Lainnya --}}
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full">
                            <input type="radio" name="tujuan_asesmen" value="Lainnya"
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 tujuan-radio"
                                id="radio-lainnya">
                            <span class="ml-3 text-sm font-medium text-gray-700">Lainnya</span>
                        </label>
                    </div>

                    {{--
                        PERBAIKAN: 
                        Blok "Input Keterangan 'Lainnya'" 
                        dan tombol-tombolnya telah DIHAPUS.
                    --}}

                </div>

                {{-- 5. Tabel Unit Kompetensi --}}
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Kode Unit</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Judul Unit</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Jenis Standard</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Loop data (placeholder) --}}
                                @for ($i = 1; $i <= 7; $i++)
                                    <tr class="{{ $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $i }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">J.XXXXXXXX.XXX.XX
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Lorem ipsum Dolor
                                            Sit Amet</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">SKKNI No xxx tahun
                                            20xx</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 6. Tombol Footer (Statis) --}}
                <div class="flex justify-end items-center">
                    <a href="#"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md">
                        Selanjutnya
                    </a>
                </div>

            </div>
            
        </main>
    </div>

    {{--
        PERBAIKAN: 
        Script JavaScript disederhanakan dan 
        semua logika untuk "Keterangan Lainnya" telah DIHAPUS.
    --}}
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Variabel yang tidak diperlukan lagi telah dihapus.
            // Anda bisa tambahkan logika simpan otomatis di sini jika perlu,
            // misalnya saat salah satu radio button diklik.
        });
    </Fscript>

</body>
</html>