<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unggah Bukti Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <x-sidebar></x-sidebar>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-2xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Pembayaran</h1>

                <h2 class="text-2xl font-semibold text-gray-800">Unggah Bukti Pembayaran</h2>
                <hr class="my-6 border-gray-300">

                <div class="bg-gray-100 rounded-2xl p-8 shadow-inner border border-gray-200">
                    <h3 class="text-lg font-semibold text-center text-gray-800 mb-6">Unggah Bukti Pembayaran</h3>

                    <label for="file-upload" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <span class="px-4 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                Pilih file
                            </span>
                            <p class="mt-2 text-sm text-gray-500">
                                <span class="font-semibold">Drag & drop file here</span> or click to upload
                            </p>
                        </div>
                        <input id="file-upload" type="file" class="hidden" />
                    </label>

                    <button class="mt-6 w-full px-6 py-3 bg-blue-500 text-white text-base font-semibold rounded-xl hover:bg-blue-600 shadow-lg">
                        Kirim Bukti Pembayaran
                    </button>
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