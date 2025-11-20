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

    .pixel {
      position: absolute;
      image-rendering: pixelated;
      opacity: .9;
      pointer-events: none;
      width: var(--px-size, 260px);
      height: auto;
    }
  </style>
</head>

<body class="bg-white overflow-hidden">

<main class="flex h-screen w-full">

  <!-- LEFT SECTION -->
  <section class="relative w-1/2 h-full bg-cover bg-center select-none"
    style="background-image: url('{{ asset('images/gedung_gkt.jpg') }}');">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative text-white px-10 mt-12 drop-shadow">
      <h1 class="text-[34px] font-bold leading-tight">
        Dont be Afraid if you Forgot<br>
        your Password!
      </h1>
    </div>

    <p class="absolute bottom-10 left-1/2 -translate-x-1/2 w-[80%] 
        text-center text-white font-light text-base leading-relaxed">
      Pusat kendali operasional yang mengamankan integritas data asesi 
      dan memastikan kelancaran alur uji kompetensi.
    </p>
  </section>


  <!-- RIGHT SECTION -->
  <section class="relative w-1/2 h-full flex items-center justify-center select-none">

    <!-- Pixel Top -->
    <img src="{{ asset('images/pixel.png') }}" 
      class="pixel" 
      style="--px-size:230px; top:0; right:0;">

    <!-- Logo -->
    <div class="absolute top-4 right-4 flex items-center gap-2">
      <span class="text-sm font-semibold text-gray-700">LSP Polines</span>
      <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" class="w-12 drop-shadow">
    </div>

    <!-- FORM -->
    <div class="bg-white px-10 py-12 rounded-xl shadow-lg w-[380px] text-center">
      
      <h2 class="text-[22px] font-semibold mb-8">Masukan Username Anda!</h2>

      <form action="{{ route('forgot_pass.send') }}" method="POST" class="space-y-6">
        @csrf

        <div class="text-left">
          <label class="text-sm text-gray-600 mb-1 block">Username</label>
          <input 
            type="text" 
            name="username" 
            class="w-full border border-gray-300 px-4 py-2 rounded-md
                   focus:ring-2 focus:ring-blue-500 outline-none" />
        </div>

        <button type="submit" 
          class="w-full bg-[#0D63F3] text-white py-3 rounded-md text-[16px] font-medium 
                 shadow-md hover:shadow-lg active:scale-95 transition">
          Masuk ke Akun
        </button>
      </form>

      <p class="mt-4 text-sm">
        <a href="#" class="text-gray-600">
          Remember Your <span class="font-semibold">Password?</span>
        </a>
      </p>
    </div>

    <!-- Pixel Bottom -->
    <img src="{{ asset('images/pixel2.png') }}" 
      class="pixel" 
      style="--px-size:230px; bottom:-40px; left:0;">
  </section>

</main>

</body>
</html>
