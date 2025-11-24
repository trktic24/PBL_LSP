<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asesi Bukti Kelengkapan | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    ::-webkit-scrollbar {
      width: 0;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <x-navbar />
  <main class="flex min-h-[calc(100vh-80px)]">
    <x-sidebar_profile_asesi />

    <section class="ml-[22%] flex-1 p-8 h-[calc(100vh-80px)] overflow-y-auto">
      <div class="bg-white p-10 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.08)]">
        <h2 class="text-2xl font-bold text-gray-700 mb-8 text-center">Bukti Kelengkapan</h2>

        <div class="space-y-6">
          @for($i = 0; $i < 3; $i++)
            <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden" x-data="{ open: true }">

            <div @click="open = !open" class="flex items-center justify-between px-6 py-4 bg-gray-50 cursor-pointer">
              <div>
                <h3 class="font-semibold text-gray-700">Sertifikasi Pelatihan Polines</h3>
                <p class="text-sm text-gray-500">Sertifikat Kompetensi Polines</p>
              </div>
              <div class="flex items-center space-x-3">
                <span class="text-sm text-red-500 font-semibold">1 berkas</span>
                <i class="fas text-gray-500 transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
              </div>
            </div>

            <div x-show="open" x-transition class="p-6 border-t border-gray-200">
              <div class="flex items-center space-x-4">
                <div class="w-14 h-14 flex items-center justify-center bg-blue-100 text-blue-600 font-semibold rounded-lg text-lg">
                  .png
                </div>
                <div class="flex-1">
                  <p class="font-medium text-gray-700 text-sm">Sertifikat Kompetensi Polines</g p>
                  <p class="text-gray-500 text-xs mt-1">Keterangan:</p>
                  <p class="text-gray-600 text-xs">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                </div>
                <div class="flex flex-col space-y-2"> <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow flex items-center justify-center">
                    <i class="fas fa-edit mr-1.5"></i> Edit
                  </button>
                  <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-xs font-medium shadow flex items-center justify-center">
                    <i class="fas fa-trash mr-1.5"></i> Delete
                  </button>
                </div>
              </div>
            </div>
        </div>
        @endfor
      </div>
      <div class="mt-10 bg-white rounded-2xl shadow-xl p-8 relative border border-gray-200">

        <div class="absolute top-6 right-6 flex space-x-2">
          <button class="bg-yellow-400 hover:bg-yellow-500 text-white font-medium px-3 py-1 rounded-full text-sm flex items-center shadow">
            <i class="fas fa-edit mr-1.5"></i> Edit
          </button>
          <button class="bg-red-500 hover:bg-red-600 text-white font-medium px-3 py-1 rounded-full text-sm flex items-center shadow">
            <i class="fas fa-trash mr-1.5"></i> Delete
          </button>
        </div>

        <h3 class="text-3xl font-bold text-gray-800 text-center mb-8">Tanda Tangan Pemohon</h3>

        <p class="font-semibold text-gray-800 mb-4">Saya yang bertanda tangan di bawah ini</p>

        <div class="space-y-2 text-sm text-gray-700 mb-6">
          <p><span class="font-bold w-36 inline-block">Nama</span> <span class="font-bold mr-2">:</span> Rafa Saputra</p>
          <p><span class="font-bold w-36 inline-block">Jabatan</span> <span class="font-bold mr-2">:</span> Wakil Presiden</p>
          <p><span class="font-bold w-36 inline-block">Perusahaan</span> <span class="font-bold mr-2">:</span> Roihan Company</p>
          <p><span class="font-bold w-36 inline-block">Alamat Perusahaan</span> <span class="font-bold mr-2">:</span> Jakarta, Mranggen</p>
        </div>

        <p class="mt-6 text-gray-700 text-sm mb-6 max-w-2xl">
          Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan
          sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
        </p>

        <div class="mt-6 border border-gray-300 rounded-lg w-full max-w-md h-40 flex items-center justify-center relative">
          <img src="{{ asset('images/ttd_sample.png') }}" alt="Tanda Tangan" class="object-contain h-24">
        </div>
        <p class="text-xs text-red-500 mt-2">*Tanda tangan di sini</p>
      </div>
</div>
</section>
</main>
</body>

</html>