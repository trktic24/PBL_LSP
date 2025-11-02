<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Gagal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <x-sidebar></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-10 text-center">Verifikasi</h1>

                <hr class="border-gray-900 mb-16">

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Anda Belum Memenuhi <br> Persyaratan Sertifikasi
                    </h2>
                    <p class="text-base text-gray-600 max-w-lg mx-auto mb-10">
                        berdasarkan penilaian diri anda, beberapa unit kompetensi belum terpenuhi dan belum dapat melanjutkan ke tahap pemilihan Tempat Uji Kompetensi (TUK) sampai semua persyaratan terpenuhi
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Hasil Penilaian Diri</h3>
                    
                    <div class="shadow border border-gray-300 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase">Unit Kompetensi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">Kompeten</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">Belum Kompeten</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">Belum Kompeten</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-16">
                    <button class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Sebelumnya
                    </button>
                    <button class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                        Kembali
                    </button>
                </div>

            </div>
        </main>

    </div>

</body>
</html>