<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umpan Balik Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
         <!-- Sidebar Kiri -->
        <x-sidebar2></x-sidebar2>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-10">Umpan Balik dan Catatan Asesmen</h1>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">
                    <div class="col-span-1 font-medium text-gray-800">TUK</div>
                    <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Sewaktu
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Tempat Kerja
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Mandiri
                        </label>
                    </div>
                    
                    <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                    <div class="col-span-3 text-gray-800">: </div>

                    <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                    <div class="col-span-3 text-gray-800">: </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">
                                    Komponen
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">
                                    Ya
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">
                                    Tidak
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider w-40">
                                    Catatan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">1</span>
                                        <p class="text-sm text-gray-700">Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>
                            
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">2</span>
                                        <p class="text-sm text-gray-700">Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">3</span>
                                        <p class="text-sm text-gray-700">Master Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">4</span>
                                        <p class="text-sm text-gray-700">Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">5</span>
                                        <p class="text-sm text-gray-700">Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">6</span>
                                        <p class="text-sm text-gray-700">Master Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">7</span>
                                        <p class="text-sm text-gray-700">Master Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">8</span>
                                        <p class="text-sm text-gray-700">Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <span class="font-semibold text-gray-800 mr-3">9</span>
                                        <p class="text-sm text-gray-700">Master Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded-lg border-2 border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex justify-center">
                                        <input type="checkbox" class="w-5 h-5 text-red-600 rounded-lg border-2 border-red-500 focus:ring-red-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                        Tambahkan Pesan...
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <label for="catatan" class="text-sm font-medium text-gray-700">Catatan/komentar lainnya (apabila ada):</label>
                    <textarea id="catatan" rows="4" class="mt-2 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>

                </div>

                <div class="flex justify-between items-center mt-12">
                    <button class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Sebelumnya
                    </button>
                    <button class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                        Kirim
                    </button>
                </div>

            </div>
        </main>

    </div>

</body>
</html>