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

  <x-navbar.navbar-admin />
  <main class="p-8">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 max-w-6xl mx-auto">
      <div class="flex items-center justify-between mb-8">
        <a href="{{ url('dashboard') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
          <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <h2 class="text-3xl font-bold text-gray-900 text-center flex-1 tracking-tight">Notification</h2>
        <div class="w-[80px]"></div>
      </div>

      <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
          <button class="px-5 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 shadow-md hover:bg-blue-700 transition duration-150">
            Gmail
          </button>
          <button class="px-5 py-2 text-sm font-medium rounded-lg text-white bg-green-500 shadow-md hover:bg-green-600 transition duration-150">
            Whatsapp
          </button>
        </div>

        <button class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
          Mark All Read
        </button>
      </div>

      <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2 mt-2"> 
        @php
          $notifications = [
            ['title' => 'Penilaian Asesi Baru', 'message' => 'Ada 3 asesi baru yang perlu dinilai.', 'time' => '2 hours ago', 'is_read' => false],
            ['title' => 'Skema Baru Ditambahkan', 'message' => 'Skema "Teknisi Listrik" telah ditambahkan.', 'time' => '3 hours ago', 'is_read' => false],
            ['title' => 'Sertifikasi Selesai', 'message' => 'Proses sertifikasi Asesor A telah selesai.', 'time' => '1 day ago', 'is_read' => true],
            ['title' => 'Data Asesi Diperbarui', 'message' => 'Profil asesi Budi Santoso telah diperbarui.', 'time' => '1 day ago', 'is_read' => true],
            ['title' => 'Perubahan Jadwal Asesmen', 'message' => 'Jadwal asesmen untuk skema Multimedia digeser ke Jumat.', 'time' => '2 days ago', 'is_read' => false],
            ['title' => 'Verifikasi Data Asesor', 'message' => 'Asesor baru telah menunggu verifikasi admin.', 'time' => '2 days ago', 'is_read' => true],
            ['title' => 'Formulir Baru', 'message' => 'Formulir asesmen versi 2025 telah tersedia.', 'time' => '3 days ago', 'is_read' => true],
            ['title' => 'Data Backup', 'message' => 'Backup data server LSP berhasil disimpan.', 'time' => '3 days ago', 'is_read' => false],
            ['title' => 'Asesi Tidak Aktif', 'message' => '2 Asesi belum melakukan login selama 30 hari.', 'time' => '4 days ago', 'is_read' => true],
          ];
        @endphp

        @foreach ($notifications as $notification)
        <div class="p-5 bg-white rounded-xl shadow-md border border-gray-200 transition duration-300 hover:shadow-lg cursor-pointer hover:-translate-y-0.5 ease-in-out">
          <div class="flex items-center justify-between">
            <div class="flex space-x-4 w-full">
              <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-lg shadow-inner text-blue-500 text-xl">
                <i class="far fa-bell"></i>
              </div>
              <div class="flex-1">
                <p class="text-lg font-semibold text-gray-900">{{ $notification['title'] }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
              </div>
            </div>

            <div class="relative flex flex-col items-end justify-center min-w-[100px]">
              <p class="text-sm text-gray-500 text-center">{{ $notification['time'] }}</p>
              @if (!$notification['is_read'])
              <span class="absolute -top-6 -right-2 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </main>
</div>
</body>
</html>