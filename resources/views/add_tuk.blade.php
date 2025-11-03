<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add TUK | LSP Polines</title>

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

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />
    <main class="flex-1 flex justify-center items-start pt-10 pb-12">
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <div class="flex items-center justify-between mb-10">
          <a href="{{ route('tuk_sewaktu') }}" class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Back
          </a>
          <h1 class="text-3xl font-bold text-gray-900 text-center flex-1">ADD TUK</h1>
          <div class="w-[80px]"></div>
        </div>

        <form action="#" method="POST" class="space-y-6">
          <div>
            <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Ruangan <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_ruangan" name="nama_ruangan" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Masukkan Nama Ruangan" />
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal <span class="text-red-500">*</span>
              </label>
              <input type="date" id="tanggal" name="tanggal" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>

            <div>
              <label for="jam" class="block text-sm font-medium text-gray-700 mb-2">
                Jam <span class="text-red-500">*</span>
              </label>
              <input type="time" id="jam" name="jam" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
          </div>

          <div>
            <label for="nama_skema" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Skema <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_skema" name="nama_skema" required
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Masukkan Nama Skema" />
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label for="daftar_asesor" class="block text-sm font-medium text-gray-700 mb-2">
                Daftar Asesor <span class="text-red-500">*</span>
              </label>
              <select id="daftar_asesor" name="daftar_asesor" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Pilih asesor</option>
                <option>Asesor 1</option>
                <option>Asesor 2</option>
              </select>
            </div>

            <div>
              <label for="daftar_asesi" class="block text-sm font-medium text-gray-700 mb-2">
                Daftar Asesi <span class="text-red-500">*</span>
              </label>
              <select id="daftar_asesi" name="daftar_asesi" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">Pilih asesi</option>
                <option>Asesi 1</option>
                <option>Asesi 2</option>
              </select>
            </div>
          </div>

          <div>
            <label for="slot" class="block text-sm font-medium text-gray-700 mb-2">
              Slot <span class="text-red-500">*</span>
            </label>
            <input type="number" id="slot" name="slot" required min="1"
              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Masukkan jumlah slot" />
          </div>

          <div class="pt-4">
            <button type="submit"
              class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
              Tambah
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>