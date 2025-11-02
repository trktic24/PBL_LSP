<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form IA.06 - Pertanyaan Tertulis Esai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white"> <div class="flex min-h-screen">

    <aside class="w-64 bg-[linear-gradient(135deg,#4F46E5,#0EA5E9)] text-white p-6 flex flex-col fixed h-screen top-0 left-0 overflow-y-auto">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-black-300 hover:text-white mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>

        <div class="text-center">
            <img src="https://via.placeholder.com/100/000000/FFFFFF/?text=SKEMA"
                 alt="Ikon Skema"
                 class="w-24 h-24 rounded-full mx-auto border-4 border-blue-700 object-cover">
            <h2 class="text-xl font-semibold mt-4">Junior Web Developer</h2>
            <p class="text-xs text-blue-300 mt-1 px-4">
                Lorem ipsum dolor sit amet. You're the best person I ever met
            </p>
        </div>

        <hr class="my-6 border-blue-700">

        <div class="text-center">
            <span class="uppercase text-blue-200 font-semibold">ASESOR:</span>
            <div class="mt-3">
                <img src="https://via.placeholder.com/50/CCCCCC/FFFFFF/?text=AJ"
                     alt="Foto Asesor"
                     class="w-12 h-12 rounded-full mx-auto block">
                <div class="mt-3 text-center">
                    <h3 class="font-medium">Ajeng Febria H.</h3>
                </div>
            </div>
        </div>

        <div class="mt-auto text-center">
            <span class="text-xs uppercase text-blue-100 font-semibold">DIMULAI PADA:</span>
            <p class="text-sm font-medium">2025-09-28 06:18:25</p>
        </div>
    </aside>

    <main class="ml-64 flex-1 flex flex-col min-h-screen" x-data="{ showNotif: false }">

        <!-- Form -->
        <div class="flex-1 p-8" x-show="!showNotif">
          <h1 class="text-2xl font-bold text-gray-800 mb-6">Pertanyaan Tertulis Esai</h1>

          <div class="grid grid-cols-2 gap-x-8 gap-y-4 mb-6 text-sm">
            <div class="flex items-center gap-4">
              <span class="font-medium text-gray-700">TUK:</span>
              <div class="flex gap-4">
                <label class="flex items-center gap-1">
                  <input type="radio" name="tuk" class="h-4 w-4 text-blue-600"> Sewaktu
                </label>
                <label class="flex items-center gap-1">
                  <input type="radio" name="tuk" class="h-4 w-4 text-blue-600"> Tempat Kerja
                </label>
                <label class="flex items-center gap-1">
                  <input type="radio" name="tuk" class="h-4 w-4 text-blue-600"> Mandiri
                </label>
              </div>
            </div>

            <div></div>

            <div class="flex items-center">
              <label for="nama_asesor" class="w-28 font-medium text-gray-700 flex-shrink-0">Nama Asesor</label>
              <span>:</span>
              <input type="text" id="nama_asesor" class="ml-2 p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none">
            </div>

            <div class="flex items-center">
              <label for="nama_peserta" class="w-28 font-medium text-gray-700 flex-shrink-0">Nama Peserta</label>
              <span>:</span>
              <input type="text" id="nama_peserta" class="ml-2 p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none">
            </div>
          </div>

          <div class="bg-blue-600 text-white p-4 rounded-md mb-6 text-sm">
            <div class="grid grid-cols-[100px,1fr] gap-2">
              <strong>Skema</strong><span>: Sertifikasi KKNI II</span>
              <strong>Judul</strong><span>: Lembaga Jasa 23x 5s fasil</span>
              <strong>Kode</strong><span>: 0341XXXXXXX</span>
            </div>
          </div>

          <div>
            <label for="jawaban_esai" class="block text-sm font-medium text-gray-700 mb-2">
              Jawaban diisikan pada tempat di bawah ini:
            </label>
            <textarea id="jawaban_esai" rows="8" class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
          </div>

          <div class="mt-6 text-sm text-gray-600">
            <h3 class="font-bold mb-2">Catatan:</h3>
            <ul class="list-disc list-inside space-y-1">
              <li>Kerahasiaan bagian dokumen ini harus dijaga/dipelihara...</li>
              <li>Bila dokumen ini lebih dari satu halaman, semua halaman...</li>
              <li>Bila dokumen ini diubah dan direvisi, edisi sebelumnya...</li>
            </ul>
          </div>

          <div class="mt-8">
            <h3 class="font-semibold text-gray-700 mb-3">Pencatatan dan Validasi</h3>
            <div class="overflow-x-auto border border-gray-300 rounded-md">
              <table class="w-full text-sm">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="p-3 text-left font-medium">Nama</th>
                    <th class="p-3 text-left font-medium">Jabatan</th>
                    <th class="p-3 text-left font-medium">Tandatangan dan Tgl</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="p-3">Denny Herlis Mukoyed</td>
                    <td class="p-3">Penyetuju</td>
                    <td class="p-3 text-gray-500 italic">19-03-2025</td>
                  </tr>
                  <tr>
                    <td class="p-3">Admin LSP</td>
                    <td class="p-3">Asesor</td>
                    <td class="p-3 text-gray-500 italic">19-03-2025</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="mt-8 text-right">
            <button type="button"
                    @click="showNotif = true"
                    class="bg-blue-600 text-white font-medium py-2 px-6 rounded-md hover:bg-blue-700">
              Selesai
            </button>
          </div>
        </div>

        <!-- Notifikasi -->
        <div class="flex-1 p-8 flex items-center justify-center h-full" x-show="showNotif">
          <div class="bg-white p-12 rounded-lg shadow-xl text-center max-w-md mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
              Rencana Aktivitas dan Proses Asesmen
            </h1>

            <svg class="w-24 h-24 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <p class="text-xl font-medium text-gray-700">Form Berhasil Dikirim</p>

            <div class="mt-8">
              <button type="button"
                      @click="showNotif = false"
                      class="bg-blue-600 text-white font-medium py-2 px-6 rounded-md hover:bg-blue-700">
                Kembali
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
  </html>