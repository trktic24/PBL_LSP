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
      <!-- Sidebar -->
      <aside
        class="w-80 flex-shrink-0 p-8"
        style="background: linear-gradient(180deg, #fdfde0 0%, #f0f8ff 100%)"
      >
        <!-- Tombol Kembali -->
        <a
          href="#"
          class="flex items-center text-sm font-medium text-gray-700 hover:text-black mb-8"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-4 h-4 mr-2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M15.75 19.5L8.25 12l7.5-7.5"
            />
          </svg>
          Kembali
        </a>

        <!-- Info Sertifikat -->
        <div class="text-center mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Skema Sertifikat</h2>

          <!-- FOTO DALAM LINGKARAN -->
          <div class="relative w-[150px] h-[150px] mx-auto mb-4">
            <img
              src="https://kuliahdimana.id/public/news/9fbe8834c4f9b277b304a595a367513d.jpg"
              alt="Foto Web Developer"
              class="rounded-full w-[150px] h-[150px] object-cover border-4 border-white shadow-lg"
            />
          </div>

          <h1 class="text-xl font-semibold text-gray-900">
            Junior Web Developer
          </h1>
          <p class="text-sm text-gray-500 mb-4">SKM12XXXXXX</p>

          <p class="text-xs text-gray-600 italic px-2">
            “Lorem ipsum dolor sit amet, you're the best person I've ever met.”
          </p>
        </div>

        <!-- Persyaratan -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">
            Persyaratan Utama
          </h3>
          <ul class="space-y-3">
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                class="w-5 h-5 text-blue-600 mr-2"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd"
                />
              </svg>
              <span class="text-gray-700">Data Sertifikasi</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                class="w-5 h-5 text-blue-600 mr-2"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd"
                />
              </svg>
              <span class="text-gray-700">Rincian Data Pemohon</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                class="w-5 h-5 text-blue-600 mr-2"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd"
                />
              </svg>
              <span class="text-gray-700">Bukti Kelengkapan Pemohon</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                class="w-5 h-5 text-blue-600 mr-2"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                  clip-rule="evenodd"
                />
              </svg>
              <span class="text-gray-700">Bukti Pembayaran</span>
            </li>
          </ul>
        </div>
      </aside>

      <!-- Main Content -->
      <main
        class="flex-1 p-12 bg-white overflow-y-auto flex items-center justify-center"
      >
        <div class="max-w-2xl mx-auto text-center">
          <!-- TUNGGU PEMBAYARAN -->
          <h1 class="text-4xl font-bold text-gray-900 mb-20">Pembayaran</h1>
          <div class="mb-8">
            <svg
              class="w-32 h-32 text-yellow-500 mx-auto animate-spin-slow"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              viewBox="0 0 24 24"
            >
              <circle cx="12" cy="12" r="10" stroke-opacity="0.3" />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 6v6l3 3"
              />
            </svg>
          </div>
          <h2 class="text-3xl font-bold text-gray-900">
            Pembayaran Anda Sedang <br />
            Diproses...
          </h2>
          <p class="text-gray-500 mt-4">Harap tunggu beberapa saat.</p>
        </div>
      </main>
    </div>

    <style>
      .animate-spin-slow {
        animation: spin 3s linear infinite;
      }
      @keyframes spin {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }
    </style>
  </body>
</html>
