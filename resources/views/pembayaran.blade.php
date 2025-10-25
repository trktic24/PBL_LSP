<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
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
                        <span class="text-gray-700">Data Sertifikasi</span>
                    </li>
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
            <div class="max-w-xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Pembayaran</h1>

                <h2 class="text-xl font-semibold text-gray-800">Pilih Metode Pembayaran</h2>
                <hr class="my-4 border-gray-300">

                <div class="mb-8">
                    <p class="text-sm text-gray-600 mb-2">Rincian biaya sertifikasi</p>
                    <p class="text-lg font-semibold text-blue-600 mb-2">Biaya</p>
                    <p class="text-5xl font-bold text-gray-900 mb-4">Rp X.XXX.XXX</p>
                    
                    <hr class="my-6 border-gray-300">
                    
                    <p class="text-lg font-semibold text-gray-800">Total Biaya : X.XXX.XXX</p>
                    
                    <hr class="my-6 border-gray-300">
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Metode Pembayaran</h3>
                    <label for="transfer-bank" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input id="transfer-bank" name="metode_pembayaran" type="radio" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-3 text-sm font-medium text-gray-700">
                            Transfer Bank ( No rekening: 123456789 - Bank BCA )
                        </span>
                    </label>
                </div>

                <div class="flex justify-end items-center mt-12">
                    <button class="px-10 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>

    </div>

</body>
</html>