<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Admin LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    /* PIXEL CONFIG */
    .pixel1,
    .pixel2 {
      position: absolute;
      image-rendering: pixelated;
      object-fit: contain;
      opacity: 0.9;
      pointer-events: none;
      width: var(--px-size, 340px);
      height: var(--px-size, 300px);
      top: var(--px-top, auto);
      left: var(--px-left, auto);
      right: var(--px-right, auto);
      bottom: var(--px-bottom, auto);
    }

    /* FORM */
    .form-container {
      border: 2px solid #edededff;
      border-radius: 22px;
      padding: 40px 30px;
      width: 100%;
      max-width: 500px;
      background: white;
    }

    .fade {
      transition: opacity 0.6s ease;
    }

    /* ANIMASI LOGIN */
    /* Gambar masuk dari Kiri (seolah bekas posisi Forgot Pass) */
    @keyframes imageEnterFromLeft {
        0% { transform: translateX(-100%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }

    /* Form masuk dari Kanan */
    @keyframes formEnterFromRight {
        0% { transform: translateX(100%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }

    .animate-image-entry {
        animation: imageEnterFromLeft 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    .animate-form-entry {
        animation: formEnterFromRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
  </style>
</head>

<body class="bg-white overflow-hidden">

  <main class="flex h-screen w-full">

    <div class="w-full lg:w-1/2 h-full bg-white flex flex-col justify-center px-10 lg:px-20 relative select-none z-10 overflow-hidden">

      <img src="{{ asset('images/pixel.png') }}" class="pixel1"
        style="--px-size: 250px; --px-top: -50px; --px-right: -13px;">

      <div class="absolute top-4 left-6">
        <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" class="w-24 drop-shadow">
      </div>

      <div class="form-container mx-auto shadow-2xl relative z-20">
        <h2 class="text-[24px] font-semibold mb-6 tracking-wide text-gray-800">Masukan akun Anda!</h2>

        @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 text-xs rounded-lg">
          {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('login_admin.post') }}" method="POST" class="space-y-5">
          @csrf

          <div class="flex flex-col w-full">
            <label class="text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
              class="px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('email') border-red-500 @enderror"
              placeholder="Masukkan email" required autofocus>

            @error('email')
            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
          </div>

          <div class="flex flex-col w-full">
            <label class="text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password"
              class="px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror"
              placeholder="Masukkan password" required>

            @error('password')
            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
          </div>

          <div class="flex justify-end items-center text-sm ">
            <a href="{{ route('forgot_pass') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors mb-6">
              Lupa Password?
            </a>
          </div>

          <button type="submit" class="w-full bg-[#0D63F3] text-white py-3 rounded-xl text-[15px] font-semibold shadow-lg hover:bg-blue-700 hover:shadow-xl active:scale-[0.98] transition-all duration-200">
            Masuk ke Akun
          </button>
        </form>
      </div>

      <img src="{{ asset('images/pixel2.png') }}" class="pixel2"
        style="--px-size: 250px; --px-bottom: -50px; --px-left: -15px;">

    </div>

    <div class="hidden lg:block w-1/2 h-full relative bg-cover bg-center select-none rounded-bl-[4%] rounded-tl-[4%] shadow-[-10px_0_30px_rgba(0,0,0,0.4)] z-20 overflow-hidden"
      style="background-image: url('{{ asset('images/gedung_gkt.jpg') }}');">

      <div class="absolute inset-0 bg-black/50 backdrop-blur-[1px]"></div>

      <div class="relative text-white px-10 mt-8 z-10">
        <h1 class="text-[42px] font-bold leading-tight drop-shadow-2xl">
          Welcome to Admin LSP<br>
          Politeknik Negeri Semarang!
        </h1>
      </div>

      <div class="absolute bottom-20 left-1/2 -translate-x-1/2 w-[95%] flex items-center justify-between text-white z-10">

        <button id="prevBtn" class="group p-2 rounded-full hover:bg-white/10 transition-all duration-800 focus:outline-none active:scale-90">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="w-8 h-8 text-white/70 group-hover:text-white transition-colors">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
          </svg>
        </button>

        <p id="slideText" class="leading-relaxed text-center text-lg font-light fade opacity-100 h-20 flex items-center justify-center w-[75%] select-none">
          Pusat kendali operasional yang mengamankan integritas data asesi dan memastikan kelancaran alur uji kompetensi.
        </p>

        <button id="nextBtn" class="group p-2 rounded-full hover:bg-white/10 transition-all duration-800 focus:outline-none active:scale-90">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="w-8 h-8 text-white/70 group-hover:text-white transition-colors">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
          </svg>
        </button>

      </div>

    </div>

  </main>

  <script>
    /* [PERBAIKAN] Fungsi togglePass dihapus karena checkbox sudah tidak ada */

    /* Script agar Enter di Username pindah ke Password */
    document.addEventListener('DOMContentLoaded', function() {
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");

      emailInput.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
          event.preventDefault();
          passwordInput.focus();
        }
      });
    });

    /* SLIDESHOW TEKS */
    const texts = [
      "Pusat kendali operasional yang mengamankan integritas data asesi dan memastikan kelancaran alur uji kompetensi.",
      "Pengelola administrasi yang menjamin ketepatan data dan memenuhi standar BNSP dalam setiap pelaksanaan uji sertifikasi.",
      "Fasilitator utama yang mengorganisir komunikasi antara asesi dan asesor, serta mengelola alur kerja sertifikasi secara efisien."
    ];

    let index = 0;
    const textElement = document.getElementById("slideText");

    function updateText() {
      textElement.style.opacity = 0;
      setTimeout(() => {
        textElement.textContent = texts[index];
        textElement.style.opacity = 1;
      }, 300);
    }

    document.getElementById("nextBtn").onclick = () => {
      index = (index + 1) % texts.length;
      updateText();
    };

    document.getElementById("prevBtn").onclick = () => {
      index = (index - 1 + texts.length) % texts.length;
      updateText();
    };

    let slideInterval = setInterval(() => {
      index = (index + 1) % texts.length;
      updateText();
    }, 7000);
  </script>

</body>

</html>