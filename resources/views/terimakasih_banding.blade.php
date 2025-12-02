<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Terimakasih</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <x-sidebar2></x-sidebar2>

    <!-- Konten utama -->
    <main class="flex-1 flex flex-col items-center justify-center p-10">
        <div class="bg-white rounded-2xl p-12 shadow-xl max-w-md w-full text-center">
            <h1 class="text-2xl font-semibold text-gray-800 mb-8">
                Terimakasih atas Banding anda
            </h1>

            <div class="flex h-28 w-28 items-center justify-center mx-auto rounded-full bg-green-500 mb-8">
                <span class="text-7xl font-bold text-white" style="font-family: Arial, sans-serif;">✔</span>
            </div>

            <p class="text-gray-600 text-sm mb-8">
                Banding Anda telah berhasil dikirim. Terima kasih atas partisipasi Anda!
            </p>

            <!-- Tombol aksi -->
            <div class="flex justify-between">
                <a href="/tracker" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold">
                    Kembali
                </a>
                <a href="{{ $id ? route('detail_banding', ['id' => $id]) : '#' }}" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold">
                    Lihat Detail
                </a>
            </div>
        </div>
    </main>

</body>
</html>