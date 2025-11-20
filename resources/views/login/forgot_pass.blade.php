<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password | Admin LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }

    /* ================================================
         PIXEL CONFIG
       ================================================ */
    .pixel1, .pixel2 {
      position: absolute;
      image-rendering: pixelated;
      object-fit: contain;
      opacity: 0.9;
      pointer-events: none;

      /* Default ukuran (bisa diganti per-element) */
      width: var(--px-size, 340px);
      height: var(--px-size, 300px);

      /* Default posisi jika tidak diset */
      top: var(--px-top, auto);
      left: var(--px-left, auto);
      right: var(--px-right, auto);
      bottom: var(--px-bottom, auto);
    }

    /* FORM */
    .form-container {
      /* Dihapus: border, box-shadow, height, width, padding */
      /* Mengikuti tampilan screenshot yang lebih minimalis */
      padding: 0;
      height: auto;
      width: auto;
      background: transparent; /* Membuat kontainer form transparan */
    }

    /* Gaya tambahan untuk menyesuaikan dengan screenshot */
    .login-box {
      background: white;
      border-radius: 10px; /* Lebih kecil dari 22px */
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
      padding: 40px; /* Padding yang lebih besar */
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .custom-input {
      /* Menyesuaikan input field agar lebih mirip screenshot */
      border: 1px solid #ccc;
      padding: 10px 15px;
      border-radius: 5px;
      width: 100%;
      box-sizing: border-box;
    }
  </style>
</head>

<body class="bg-white overflow-hidden">

  <main class="flex h-screen w-full">

            <div class="w-[57%] h-full relative bg-cover bg-center select-none"
         style="background-image: url('{{ asset('images/gedung_gkt.jpg') }}');">

      <div class="absolute inset-0 bg-black/45"></div>

            <div class="relative text-white px-10 mt-10">
        <h1 class="text-[36px] font-bold leading-tight drop-shadow-lg">
          Dont be Afraid if you Forgot<br>
          your Password!
        </h1>
      </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 w-[90%] flex items-center justify-center text-white text-1xl">
        <p class="leading-relaxed text-center font-light text-base">
          Pusat kendali operasional yang mengamankan integritas data asesi dan memastikan kelancaran alur uji kompetensi.
        </p>
      </div>

    </div>


            <div class="w-[43%] h-full bg-white flex flex-col items-center justify-center relative select-none">

            <img 
        src="{{ asset('images/pixel.png') }}" 
        class="pixel1"
        style="
          --px-size: 250px;        /* ubah ukuran sesuka kamu */
          --px-top: 0px;           /* posisi Y */
          --px-right: 0px;         /* posisi X */
        "
      >

            <div class="absolute top-4 right-4 flex items-center gap-2">
        <span class="text-sm font-semibold text-gray-700">LSP Polines</span>
        <img 
          src="{{ asset('images/Logo_LSP_No_BG.png') }}" 
          class="w-20 drop-shadow"
          style="width: 50px;"     >
      </div>

            <div class="form-container mx-auto">
        <div class="login-box">
          <h2 class="text-[24px] font-semibold mb-8 tracking-wide">Masukan Username Anda!</h2>

          <form action="{{ url('/forgot-password') }}" method="POST" class="space-y-6">
            @csrf

            <div class="flex flex-col w-full text-left">
              <label class="text-sm font-light text-gray-500 mb-1">Username</label>
              <input type="text" name="username" class="custom-input focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="">
            </div>

            <button type="submit" class="w-full bg-[#0D63F3] text-white py-3 rounded-md text-[16px] font-medium shadow-md hover:shadow-lg active:scale-[0.97] transition">
              Masuk ke Akun
            </button>
          </form>
          
          <div class="mt-4 text-sm">
            <a href="#" class="text-gray-600">Remember Your <span class="font-semibold">Password?</span></a>
          </div>

        </div>
      </div>

                  <img 
        src="{{ asset('images/pixel2.png') }}" 
        class="pixel2"
        style="
          --px-size: 250px;       /* ubah ukuran */
          --px-bottom: -50px;       /* posisi Y */
          --px-left: 0px;         /* posisi X */
        "
      >
      

    </div>

  </main>

  <script>
    // Skrip Slideshow dihapus karena tidak relevan/tidak terlihat di screenshot
    // Jika Anda ingin mengembalikan fungsionalitas slideshow/togglePass, Anda bisa menambahkannya kembali.
  </script>

</body>
</html>