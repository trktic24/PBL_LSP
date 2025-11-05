<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pra - Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <x-sidebar2></x-sidebar2>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Pra - Asesmen</h1>
                <h2 class="text-2xl font-semibold text-gray-800 mb-1">Menggunakan Struktur Data</h2>
                <p class="text-sm text-gray-500 mb-10">Unit Kompetensi 1 dari 8</p>

                <div class="shadow border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">
                                    Dapatkah saya .....?
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">
                                    K
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">
                                    BK
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider w-32">
                                    Bukti
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex">
                                        <span class="font-bold text-gray-800 mr-4">1</span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Mengidentifikasi Konsep Data dan Struktur Data</p>
                                            <ul class="list-decimal list-outside pl-5 mt-2 text-xs text-gray-600 space-y-1">
                                                <li>Konsep data dan struktur data diidentifikasi sesuai dengan konteks</li>
                                                <li>Alternatif struktur data dibandingkan kekurangannya untuk konteks permasalahan yang diselesaikan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-center pt-1">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-center pt-1">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="text-xs font-medium text-blue-600 hover:text-blue-800 cursor-pointer">
                                        Pilih File
                                    </span>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex">
                                        <span class="font-bold text-gray-800 mr-4">2</span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Menerapkan struktur data dan akses terhadap struktur data tersebut</p>
                                            <ul class="list-decimal list-outside pl-5 mt-2 text-xs text-gray-600 space-y-1">
                                                <li>Struktur data diimplementasikan sesuai dengan bahasa pemrograman yang dipergunakan</li>
                                                <li>Akses terdapa data dinyatakan dalam algoritma yang efisein sesuai bahasa pemrograman yang akan dipakai.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-center pt-1">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-center pt-1">
                                        <input type="checkbox" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="text-xs font-medium text-blue-600 hover:text-blue-800 cursor-pointer">
                                        Pilih File
                                    </span>
                                </td>
                            </tr>
                            
                            </tbody>
                    </table>
                </div>


                <div class="flex justify-end items-center mt-12">
                    <a href="/praasesmen2" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                        Selanjutnya
                    </a>
                </div>

            </div>
        </main>

    </div>

</body>
</html>