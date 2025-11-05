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
      <x-sidebar></x-sidebar>

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
