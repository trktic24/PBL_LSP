<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <aside class="w-80 flex-shrink-0 p-8" style="background: linear-gradient(180deg, #FDFDE0 0%, #F0F8FF 100%);">
            
            <a href="#" class="flex items-center text-sm font-medium text-gray-700 hover:text-black mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali
            </a>

            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Skema Sertifikat</h2>
                
                <img src="https://plus.unsplash.com/premium_photo-1661763171882-685b838f50b7?w=128&h=128&fit=crop&q=80" 
                     alt="Junior Web Developer" 
                     class="w-28 h-28 rounded-full mx-auto mb-4 border-4 border-white shadow-lg object-cover">
                
                <h1 class="text-xl font-semibold text-gray-900">Junior Web Developer</h1>
                <p class="text-sm text-gray-500 mb-4">SKM12XXXXXX</p>
                
                <p class="text-xs text-gray-600 italic px-2">
                    "Lorem ipsum dolor sit amet, you're the best person I've ever met"
                </p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Persyaratan Utama</h3>
                <ul class="space-y-3">
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Rincian Data Pemohon Sertifikasi</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Data Sertifikasi</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Bukti Kelengkapan Pemohon</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700">Bukti Pembayaran</span>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Persetujuan Asesmen dan Kerahasiaan</h1>

                <p class="text-gray-700 mb-8 text-sm">
                    Persetujuan Asesmen ini untuk menjamin bahwa Peserta telah diberi arahan secara rinci tentang perencanaan dan proses asesmen
                </p>

                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                    
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
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
                    </dd>
                    
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    <dd class="col-span-3 text-gray-800">: </dd>

                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800">: </dd>
                    
                    <dt class="col-span-1 font-medium text-gray-800">Bukti yang akan dikumpulkan</dt>
                    <dd class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Verifikasi Portofolio
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Test Tulis
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Tes Lisan
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Wawancara
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Observasi Langsung
                        </label>
                    </dd>

                </dl>

                <p class="mt-10 text-gray-700 text-sm leading-relaxed">
                    Bahwa saya sudah Mendapatkan Penjelasan Hak dan Prosedur Banding Oleh Asesor
                </p>
                <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                    Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
                </p>

                <div class="mt-6">
                    <div class="w-full h-48 bg-gray-50 border border-gray-300 rounded-lg shadow-inner">
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
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>

    </div>

</body>
</html>