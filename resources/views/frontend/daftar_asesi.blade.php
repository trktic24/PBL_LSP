<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Asesi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100">

    <div> <aside class="w-64 bg-[linear-gradient(135deg,#4F46E5,#0EA5E9)] text-white p-6 flex flex-col fixed h-screen top-0 left-0 overflow-y-auto">
            <a href="#" class="inline-flex items-center gap-2 text-sm text-black-300 hover:text-white mb-8">
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
                <p class="text-xs text-blue-300 mt-1 px-4">
                    Lorem ipsum dolor sit amet. You're the best person I ever met
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

        <main class="ml-64 flex-1 flex flex-col min-h-screen">

            <div class="flex-1 p-8">

                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <div class="grid grid-cols-[150px,1fr] gap-x-6 gap-y-2 text-sm">
                        <div class="font-semibold text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</div>
                        <div>:</div>
                        <div class="font-semibold text-gray-700">Judul</div>
                        <div>:</div>
                        <div class="font-semibold text-gray-700">Nomor</div>
                        <div>:</div>
                        <div class="font-semibold text-gray-700">Tanggal</div>
                        <div>:</div>
                        <div class="font-semibold text-gray-700">Waktu</div>
                        <div>:</div>
                        <div class="font-semibold text-gray-700">TUK</div>
                        <div>:</div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Asesi</h2>

                <div class="bg-white rounded-lg shadow-xl overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">No</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Nama Asesi</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Pra Asesmen</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Asesmen</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Semua</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Asesmen Mandiri</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Penyesuaian</th>
                                <th class="p-4 text-left text-sm font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">1</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Tatang Sitartang</td>

                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>

                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block">
                                </td>

                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>

                                <td class="p-4 align-top" rowspan="4">
                                    <div class="flex flex-col gap-2">
                                        <button class="bg-blue-800 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-900 w-32">
                                            Daftar Hadir
                                        </button>
                                        <button class="bg-blue-800 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-900 w-32">
                                            Laporan Asesmen
                                        </button>
                                        <button class="bg-blue-800 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-900 w-32">
                                            Tinjauan Asesmen
                                        </button>
                                        <button class="bg-blue-800 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-900 w-32">
                                            Berita Acara
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">2</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Jojon Sudarman</td>

                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>
                                <td class="p-4 text-sm text-yellow-600 font-medium">Dalam Proses</td>

                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block">
                                </td>

                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>
                                </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">3</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Abdul Sidarta M.</td>

                                <td class="p-4 text-sm text-green-600 font-medium">Sudah Diverifikasi</td>
                                <td class="p-4 text-sm text-green-600 font-medium">Sudah Diverifikasi</td>
                                <td class="p-4 text-sm text-green-600 font-medium">Sudah Diverifikasi</td>

                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block" checked>
                                </td>

                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>
                                </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-700">4</td>
                                <td class="p-4 text-sm text-gray-900 font-medium">Mustika Pujastuti</td>

                                <td class="p-4 text-sm text-red-600 font-medium">Belum Diverifikasi</td>
                                <td class="p-4 text-sm text-red-600 font-medium">Belum Diverifikasi</td>
                                <td class="p-4 text-sm text-red-600 font-medium">Belum Diverifikasi</td>

                                <td class="p-4">
                                    <input type="checkbox" class="h-5 w-5 rounded text-blue-600 mx-auto block">
                                </td>

                                <td class="p-4">
                                    <button class="bg-blue-600 text-white px-3 py-2 rounded-md text-xs font-medium hover:bg-blue-700">
                                        Lakukan Penyesuaian
                                    </button>
                                </td>
                                </tr>

                        </tbody>
                    </table>
                </div>

            </div> </main> </div> </body>
</html>