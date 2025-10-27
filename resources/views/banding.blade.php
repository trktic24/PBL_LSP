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
        
        <x-sidebar></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-10">Banding Asesmen</h1>

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
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Proses Banding telah dijelaskan kepada Anda?</td>
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
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
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
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">Apakah Anda mau melibatkan 'orang lain' membantu Anda dalam Proses Banding?</td>
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
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 text-sm text-gray-700 space-y-2">
                    <p>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi Okupasi Nasional berikut:</p>
                    <p>Skema Sertifikasi: <span class="font-medium text-gray-900"></span></p>
                    <p>No. Skema Sertifikasi: <span class="font-medium text-gray-900"></span></p>
                </div>

                <div class="mt-6">
                    <label for="alasan" class="text-sm font-medium text-gray-700">Banding ini diajukan atas alasan sebagai berikut:</label>
                    <textarea id="alasan" rows="4" placeholder="Berikan Keterangan Disini" class="mt-2 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                </div>

                <div class="mt-6">
                    <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                        Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.
                    </p>
                    <div class="w-full h-32 bg-gray-50 border border-gray-300 rounded-lg shadow-inner mt-2">
                        </div>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-red-600 text-xs italic">*Tanda Tangan di sini</p>
                        <button class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                            Hapus
                        </button>
                    </div>
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