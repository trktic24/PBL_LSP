<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notifications | LSP Polines</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 0;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="h-screen overflow-y-auto">

        <x-navbar.navbar />

        <main class="p-8">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('asesor.dashboard') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <h2 class="text-3xl font-bold text-gray-900 text-center flex-1 tracking-tight">Notifikasi Anda</h2>
                    <div class="w-[80px]"></div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex space-x-3">
                        {{-- Placeholder buttons if needed --}}
                    </div>

                    <button class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150 cursor-not-allowed opacity-50" title="Fitur ini belum tersedia">
                        Tandai Semua Dibaca
                    </button>
                </div>

                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2 mt-2">
                    @forelse ($notifications as $notification)
                    <a href="{{ $notification['link'] }}" class="block">
                        <div class="p-5 bg-white rounded-xl shadow-md border border-gray-200 transition duration-300 hover:shadow-lg cursor-pointer hover:-translate-y-0.5 ease-in-out {{ !$notification['is_read'] ? 'bg-blue-50 border-blue-200' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-4 w-full">
                                    <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-lg shadow-inner text-blue-500 text-xl {{ !$notification['is_read'] ? 'bg-blue-100' : '' }}">
                                        <i class="far fa-bell"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-lg font-semibold text-gray-900">{{ $notification['title'] }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                                    </div>
                                </div>

                                <div class="relative flex flex-col items-end justify-center min-w-[100px]">
                                    <p class="text-sm text-gray-500 text-center whitespace-nowrap">{{ $notification['time'] }}</p>
                                    @if (!$notification['is_read'])
                                    <span class="absolute -top-6 -right-2 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500">Tidak ada notifikasi.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>

            </div>
        </main>
    </div>
</body>

</html>