<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Status Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body class="bg-gray-100">
    <div class="flex min-h-screen">
      <x-sidebar></x-sidebar>
      <!-- Main Content -->
       <main class="flex-1 p-12 bg-white overflow-y-auto">
        <div class="max-w-3xl mx-auto text-center">
          <div class="flex items-center justify-center mb-16">
            <div class="flex flex-col items-center">
              <div
                class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg"
              >
                1
              </div>
            </div>
            <div class="w-24 h-1 bg-yellow-400 mx-1"></div>
            <div class="flex flex-col items-center">
              <div
                class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg"
              >
                2
              </div>
            </div>
            <div class="w-24 h-1 bg-yellow-400 mx-1"></div>
            <div class="flex flex-col items-center">
              <div
                class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg"
              >
                3
              </div>
            </div>
          </div>

      <main
        class="flex-1 p-12 bg-white overflow-y-auto flex items-center justify-center"
      >
        <div class="max-w-2xl mx-auto text-center">
          <!-- TUNGGU PEMBAYARAN -->
          <h1 class="text-4xl font-bold text-gray-900 mb-20">
            Dokumen Anda Sedang Diproses
          </h1>

          <div class="mb-16 flex flex-col items-center justify-center relative">

  <!-- Icon Dokumen dengan Jam (yang bergerak) -->
  <div class="mb-16 flex flex-col items-center justify-center relative">
  
  <!-- Ikon dokumen + jam (bergerak) -->
  <div class="animate-bounce-doc">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="1.5"
      class="w-32 h-32 text-yellow-500"
    >
      <!-- Dokumen -->
      <path
        d="M7 2a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8l-6-6H7z"
        stroke-linejoin="round"
      />
      <path d="M13 2v6h6" stroke-linecap="round" stroke-linejoin="round" />

      <!-- Jam kecil -->
      <circle cx="16" cy="16" r="3" stroke-width="1.5" />
      <path d="M16 14v2l1 1" stroke-linecap="round" />
    </svg>
  </div>
</div>

  
  <br>
          <h2 class="text-3xl font-bold text-gray-900">
            Harap Tunggu Beberapa Saat...
          </h2>
          
        </div>
      </main>
    </div>

    <style>
@keyframes bounce-doc {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-25px);
  }
}

.animate-bounce-doc {
  animation: bounce-doc 1.2s ease-in-out infinite;
}

</style>

  </body>
</html>
