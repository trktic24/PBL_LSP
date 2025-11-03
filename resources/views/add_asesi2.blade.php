<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Asesi (Step 2) | LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-1 flex justify-center items-start pt-10 pb-12">
      <div class="w-full max-w-5xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <div class="flex items-center justify-center mb-10">
          <h1 class="text-3xl font-bold text-gray-900 text-center">ADD ASESI</h1>
        </div>

        <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium">1</div>
                <p class="mt-2 text-xs font-medium text-green-500">Informasi Akun</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div>
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">2</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Data Pribadi</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">3</div>
                <p class="mt-2 text-xs font-medium text-gray-500">Bukti Kelengkapan</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-gray-500 text-white text-xs font-medium">4</div>
                <p class="mt-2 text-xs font-medium text-gray-500">Tanda Tangan</p>
            </div>
        </div>

        <form action="#" method="POST" class="space-y-8">
          <h2 class="text-2xl font-semibold text-gray-900 mb-6">Data Sertifikasi</h2>
          
          <p class="text-sm text-gray-600">
            Pilih Tujuan Asesmen serta Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi yang anda ajukan.
          </p>

          <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6 space-y-3">
            <label class="text-sm font-medium text-gray-700">Skema Sertifikasi / Klaster Asesmen</label>
            <div class="flex items-center">
              <span class="w-20 text-sm text-gray-600">Judul</span>
              <span class="text-sm font-semibold text-gray-800">: Lorem Ipsum Dolor Sit Amet</span>
            </div>
            <div class="flex items-center">
              <span class="w-20 text-sm text-gray-600">Nomor</span>
              <span class="text-sm font-semibold text-gray-800">: SKM12XXXXX</span>
            </div>
          </div>

          <section>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Tujuan Asesmen</h3>
            <div class="flex items-center space-x-6">
              <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="tujuan[]" value="sertifikasi" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-sm text-gray-700">Sertifikasi</span>
              </label>
              <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="tujuan[]" value="sertifikasi_ulang" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-sm text-gray-700">Sertifikasi Ulang</span>
              </label>
            </div>
          </section>

          <section>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Unit Kompetensi</h3>
            <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-x-auto">
              <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                  <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Kode Unit</th>
                    <th class="px-6 py-3">Judul Unit</th>
                    <th class="px-6 py-3">Jenis Standard</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  @for ($i = 1; $i <= 7; $i++)
                  <tr>
                    <td class="px-6 py-4">{{ $i }}</td>
                    <td class="px-6 py-4 font-medium">J.XXXXXXXX.XXX.XX</td>
                    <td class="px-6 py-4">Lorem Ipsum Dolor Sit Amet</td>
                    <td class="px-6 py-4">SKKNI No xxx tahun 20xx</td>
                  </tr>
                  @endfor
                </tbody>
              </table>
            </div>
          </section>

          <div class="flex justify-between items-center pt-6 border-t mt-10">
            <a href="{{ route('add_asesi1') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg text-sm shadow-sm transition">
              Sebelumnya
            </a>
            <a href="{{ route('add_asesi3') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm shadow-md transition">
              Selanjutnya
            </a>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>