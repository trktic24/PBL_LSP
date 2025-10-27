<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Diproses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        <x-sidebar></x-sidebar>
        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-2xl mx-auto text-center pt-12">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-20">Pembayaran Anda Sedang Diproses</h1>

                <div class="mb-8">
                    <svg class="w-32 h-32 text-gray-800 mx-auto" fill="none" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Harap Tunggu Beberapa Saat...
                </h2>
                
                <p class="text-base text-gray-600 mb-2">
                    Anda bisa menunggu disini atau kembali ke halaman utama
                </p>
                
                <p class="text-sm text-gray-500">
                    Proses paling lambat 1 x 24 jam
                </p>

            </div>
        </main>

    </div>

</body>
</html>