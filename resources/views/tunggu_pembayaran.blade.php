<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembayaran | Simulasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      /* animasi rotasi jam */
      @keyframes spin {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }
      .animate-spin-slow {
        animation: spin 3s linear infinite;
      }

      /* animasi fade */
      .fade-in {
        animation: fadeIn 0.8s ease-in forwards;
      }
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: scale(0.95);
        }
        to {
          opacity: 1;
          transform: scale(1);
        }
      }
    </style>
  </head>

  <body class="bg-[#d4cbcb] flex items-center justify-center min-h-screen">
    <div
      id="card"
      class="flex bg-white rounded-2xl shadow-lg overflow-hidden w-[950px] h-[550px] transition-all duration-500"
    >
      <!-- Sidebar -->
      <div
        class="w-1/3 bg-gradient-to-b from-[#ffefba] to-[#a1c4fd] p-6 flex flex-col justify-start"
      >
        <!-- Header Skema Sertifikat -->
        <div class="flex items-center gap-2 mb-4">
          <!-- Panah kembali -->
          <a
            href="#"
            class="text-gray-700 hover:text-gray-900 transition"
            title="Kembali ke Tracker"
          >
            ←
          </a>
          <h2 class="font-semibold text-left text-gray-700">
            Skema Sertifikat
          </h2>
        </div>

        <!-- Isi Sidebar -->
        <img
          src="https://kuliahdimana.id/public/news/9fbe8834c4f9b277b304a595a367513d.jpg"
          class="rounded-full mx-auto mb-4 w-[150px] h-[150px]"
          alt="Foto Web Developer"
        />
        <h3 class="font-semibold text-lg text-gray-800 text-center">
          Junior Web Developer
        </h3>
        <p class="text-sm text-gray-600 mb-6 text-center">SMK123XXXXXX</p>
        <div class="text-left text-sm text-gray-700 space-y-2">
          <p><strong>Persyaratan Utama</strong></p>
          <ul class="list-disc ml-4">
            <li>Daftar Online</li>
            <li>Konfirmasi dan Notifikasi Sertifikat</li>
            <li>Submit Pembayaran</li>
          </ul>
        </div>
      </div>

      <!-- Main Content -->
      <div
        id="content"
        class="w-2/3 flex flex-col items-center justify-center text-center p-10 transition-all duration-500"
      >
        <!-- Loading State -->
        <div id="loadingState" class="fade-in">
          <h1 class="text-2xl font-semibold mb-6">
            Pembayaran Anda Sedang Diproses
          </h1>
          <div class="text-6xl mb-4 animate-spin-slow">🕒</div>
          <p class="text-gray-600 text-lg font-medium mb-2">
            Harap Tunggu Beberapa Saat...
          </p>
          <p class="text-gray-500 text-sm">Proses paling lambat ± 24 jam.</p>
        </div>

        <!-- Success State -->
        <div id="successState" class="hidden fade-in">
          <h1 class="text-2xl font-semibold mb-6">Pembayaran</h1>
          <div class="text-6xl text-green-500 mb-4">✅</div>
          <p class="text-lg font-medium text-gray-700 mb-8">
            Pembayaran Anda Telah Dikonfirmasi!
          </p>

          <div class="flex gap-4 justify-center">
            <button
              class="px-6 py-2 rounded-lg border border-gray-400 text-gray-700 hover:bg-gray-100 transition"
            >
              Halaman Utama
            </button>
            <button
              class="px-6 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition"
            >
              Selanjutnya
            </button>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Simulasi loading -> success
      setTimeout(() => {
        document.getElementById("loadingState").classList.add("hidden");
        document.getElementById("successState").classList.remove("hidden");
        document.getElementById("successState").classList.add("fade-in");
      }, 4000); // 4 detik
    </script>
  </body>
</html>
