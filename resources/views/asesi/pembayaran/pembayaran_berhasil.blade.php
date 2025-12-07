<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Dikonfirmasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi ?? null" />

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col relative overflow-y-auto p-8 md:p-12">

            <div class="max-w-3xl mx-auto w-full flex-grow flex flex-col justify-center">

                {{-- KARTU SUKSES --}}
                <div
                    class="bg-white rounded-2xl shadow-xl border border-green-100 p-10 text-center transform transition-all duration-500 hover:scale-[1.01]">

                    {{-- Ikon Sukses dengan Animasi --}}
                    <div class="mb-8 flex justify-center">
                        <div
                            class="w-28 h-28 bg-green-100 rounded-full flex items-center justify-center animate-bounce">
                            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Judul --}}
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        Pembayaran Berhasil!
                    </h1>

                    <p class="text-gray-600 text-lg mb-8">
                        Terima kasih. Pembayaran Anda telah kami terima dan status pendaftaran Anda telah diperbarui.
                    </p>

                    {{-- Detail Transaksi --}}
                    <div class="bg-gray-50 rounded-lg p-6 max-w-md mx-auto border border-gray-200">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-500 text-sm">Order ID</span>
                            <span class="font-mono font-medium text-gray-800 text-sm">{{ $order_id ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-500 text-sm">Status</span>
                            <span
                                class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs font-bold uppercase">
                                {{ $status ?? 'Lunas' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Waktu Pembayaran</span>

                            {{-- Ganti now() dengan created_at dari database --}}
                            <span class="text-gray-800 text-sm">
                                {{ $pembayaran ? $pembayaran->updated_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>

        </main>

    </div>

</body>

</html>
