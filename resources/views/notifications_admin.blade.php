<x-app-layout>

    <!-- Pemuatan Font Awesome untuk ikon -->
    <!-- Catatan: Pastikan Anda memuat Font Awesome di file layout utama Anda (app.blade.php) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJc5f8t6Mv2Xb2zSA+sJ/z1K+X3TqQ+zU0c7zR2t4Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="px-4 py-8 md:px-6 lg:px-8 bg-gray-50 min-h-screen">
        <!-- Header (Sesuai Layout Dashboard Anda, disesuaikan agar cocok dengan halaman notifikasi) -->
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900 flex items-center space-x-4">
                <a href="#" class="text-gray-500 hover:text-gray-700">
                    <!-- Ikon kembali, mengikuti tata letak gambar -->
                    <i class="fas fa-arrow-left"></i>
                </a>
                <span>Notification</span>
            </h2>
        </header>

        <!-- Konten Notifikasi -->
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <!-- Tombol Filter/Kategori Notifikasi -->
                <div class="flex space-x-3">
                    <!-- Ditambahkan kelas untuk transisi dan efek saat diklik -->
                    <button class="px-5 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 shadow-md shadow-blue-500/50 hover:bg-blue-700 transition duration-150 active:scale-95">
                        Gmail
                    </button>
                    <button class="px-5 py-2 text-sm font-medium rounded-lg text-white bg-green-500 shadow-md shadow-green-500/50 hover:bg-green-600 transition duration-150 active:scale-95">
                        Whatsapp
                    </button>
                </div>

                <!-- Tombol Mark All Read -->
                <!-- Ditambahkan kelas untuk transisi dan efek saat diklik -->
                <button class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150 active:scale-95">
                    Mark All Read
                </button>
            </div>

            <!-- Daftar Notifikasi -->
            <div class="space-y-4">
                @php
                    // Contoh data notifikasi (Anda harus mengganti ini dengan data dari database/model)
                    $notifications = [
                        ['title' => 'Judul Notif Pertama', 'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'time' => '2 hours ago', 'is_read' => false],
                        ['title' => 'Judul Notif Kedua', 'message' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'time' => '3 hours ago', 'is_read' => false],
                        ['title' => 'Judul Notif Ketiga', 'message' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', 'time' => '1 day ago', 'is_read' => true],
                        ['title' => 'Judul Notif Keempat', 'message' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.', 'time' => '1 day ago', 'is_read' => true],
                    ];
                @endphp

                @foreach ($notifications as $notification)
                    <!-- Efek Hover Card: terangkat sedikit (hover:-translate-y-0.5) dan bayangan membesar (hover:shadow-2xl) -->
                    <div class="p-4 bg-white rounded-xl shadow-lg border border-gray-200 transition duration-300 hover:shadow-2xl cursor-pointer hover:-translate-y-0.5 ease-in-out
                        @if (!$notification['is_read'])
                            border-l-4 border-blue-500
                        @endif">
                        <div class="flex items-start justify-between">
                            <!-- Bagian Kiri: Ikon dan Konten -->
                            <div class="flex space-x-4">
                                <!-- Ikon Kalender -->
                                <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-lg shadow-inner text-blue-600 text-xl">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <!-- Judul dan Deskripsi -->
                                <div>
                                    <p class="text-lg font-semibold text-gray-900 @if(!$notification['is_read']) font-bold @endif">{{ $notification['title'] }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                                </div>
                            </div>

                            <!-- Bagian Kanan: Waktu dan Indikator Baru -->
                            <div class="flex flex-col items-end space-y-1">
                                <p class="text-sm text-gray-500 whitespace-nowrap">{{ $notification['time'] }}</p>
                                @if (!$notification['is_read'])
                                    <!-- Indikator Biru (Notifikasi Belum Dibaca) - Ditambahkan animasi pulse -->
                                    <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-0.5 animate-pulse"></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
