<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Asesi (Step 4) | LSP Polines</title>

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
      <div class="w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">

        <div class="flex items-center justify-center mb-10">
          <h1 class="text-3xl font-bold text-gray-900 text-center">ADD ASESI</h1>
        </div>

        <div class="flex items-start w-full max-w-3xl mx-auto mb-12">
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">1</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Informasi Akun</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div>
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">2</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Data Pribadi</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-blue-600 text-white text-xs font-medium">3</div>
                <p class="mt-2 text-xs font-medium text-blue-600">Bukti Kelengkapan</p>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4 mt-2.5"></div> 
            <div class="flex flex-col items-center text-center w-32">
                <div class="rounded-full h-5 w-5 flex items-center justify-center bg-green-500 text-white text-xs font-medium">4</div>
                <p class="mt-2 text-xs font-medium text-green-500">Tanda Tangan</p>
            </div>
        </div>

        <form action="{{ route('asesi.store') }}" method="POST">
          @csrf
          <h2 class="text-2xl font-semibold text-gray-900 mb-6">Tanda Tangan Pemohon</h2>

          <div class="space-y-4 text-gray-800">
            <p class="text-sm">Saya yang bertanda tangan di bawah ini:</p>
            
            <div class="pl-4 space-y-2 text-sm">
                <div class="flex">
                    <span class="w-36">Nama</span>
                    <span>:</span>
                    <span class="font-medium ml-2">[Nama Asesi dari Step 1]</span>
                </div>
                <div class="flex">
                    <span class="w-36">Jabatan</span>
                    <span>:</span>
                    <span class="font-medium ml-2">[Jabatan dari Step 1]</span>
                </div>
                <div class="flex">
                    <span class="w-36">Perusahaan</span>
                    <span>:</span>
                    <span class="font-medium ml-2">[Perusahaan dari Step 1]</span>
                </div>
                <div class="flex">
                    <span class="w-36">Alamat Perusahaan</span>
                    <span>:</span>
                    <span class="font-medium ml-2">[Alamat dari Step 1]</span>
                </div>
            </div>

            <p class="text-sm pt-4 !mt-6">
                Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
            </p>

            <div class="pt-4">
                <div class="border border-gray-300 rounded-lg bg-white w-full h-56">
                    </div>
                <div class="flex justify-between items-start mt-1">
                    <label class="text-xs text-red-500">*Tanda Tangan di sini</label>
                    <button type="button" class="px-4 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg text-xs shadow-sm transition">
                      Hapus
                    </button>
                </div>
            </div>
          </div>

          <div class="flex justify-between items-center pt-6 border-t mt-10">
            <a href="{{ route('add_asesi3') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg text-sm shadow-sm transition">
              Sebelumnya
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm shadow-md transition">
              Tambah
            </button>
          </div>
        </form>

      </div>
    </main>
  </div>
</body>
</html>