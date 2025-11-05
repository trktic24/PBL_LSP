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
        
        <x-sidebar></x-sidebar>
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