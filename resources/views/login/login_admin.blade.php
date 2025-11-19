<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Admin LSP Polines</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }

    .pixel-block {
      position: absolute;
      width: 300px;
      height: 260px;
      pointer-events: none;
      opacity: 0.75;
      --size: 6px;
      --step: 14px;
      background-image:
        linear-gradient(90deg, #1a1a1a 0, #1a1a1a var(--size), transparent var(--size)),
        linear-gradient(#1a1a1a 0, #1a1a1a var(--size), transparent var(--size));
      background-size: var(--step) var(--step);
    }

    .pixel-top {
      top: 26px;
      right: 26px;
      mask-image: radial-gradient(circle at 100% 0%, rgba(0,0,0,1) 38%, rgba(0,0,0,0) 75%);
    }

    .pixel-bottom {
      bottom: 0;
      left: 0;
      width: 300px;
      height: 260px;
      mask-image: radial-gradient(circle at 0% 100%, rgba(0,0,0,1) 48%, rgba(0,0,0,0) 80%);
    }

    .form-container {
      border: 2px solid #0D63F3;
      border-radius: 22px;
      padding: 50px;
      width: 520px;
      background: white;
      box-shadow: -14px 0px 34px rgba(0,0,0,0.13);
    }

    /* Fade animation */
    .fade {
      transition: opacity 0.6s ease;
    }
  </style>
</head>

<body class="bg-white overflow-hidden">

  <main class="flex h-screen w-full">

    <!-- LEFT SIDEBAR -->
    <div class="w-[43%] h-full bg-white flex flex-col justify-center px-20 relative select-none">

      <div class="pixel-block pixel-top scale-[1.3]"></div>

      <div class="absolute top-6 left-10">
        <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" class="w-24 drop-shadow">
      </div>

      <div class="form-container mx-auto -mt-10">
        <h2 class="text-[22px] font-semibold mb-6 tracking-wide">Masukan akun Anda!</h2>

        <form action="{{ url('/login') }}" method="POST" class="space-y-6">
          @csrf

          <div class="flex flex-col w-full">
            <label class="text-sm font-medium">Username</label>
            <input type="text" name="username" class="mt-1 px-4 py-2 border rounded-lg shadow-sm focus:ring-2 outline-none" placeholder="Masukkan username">
          </div>

          <div class="flex flex-col w-full">
            <label class="text-sm font-medium">Password</label>
            <input id="password" type="password" name="password" class="mt-1 px-4 py-2 border rounded-lg shadow-sm focus:ring-2 outline-none" placeholder="Masukkan password">
          </div>

          <div class="flex justify-between items-center text-sm -mt-2">
            <label class="flex items-center gap-2">
              <input type="checkbox" onclick="togglePass()" class="w-4 h-4">
              Tampilkan Password
            </label>

            <a href="#" class="text-gray-600">Forgot your <span class="font-semibold">Password?</span></a>
          </div>

          <button type="submit" class="w-full bg-[#0D63F3] text-white py-2.5 rounded-full text-[15px] font-medium shadow-md hover:shadow-lg active:scale-[0.97] transition">
            Masuk ke Akun
          </button>
        </form>
      </div>

      <div class="pixel-block pixel-bottom"></div>

    </div>

    <!-- RIGHT PANEL -->
    <div class="w-[57%] h-full relative bg-cover bg-center select-none"
         style="background-image: url('{{ asset('images/gedung_gkt.jpg') }}');">

      <div class="absolute inset-0 bg-black/45"></div>

      <div class="relative text-white px-14 mt-20">
        <h1 class="text-[70px] font-bold leading-tight drop-shadow-lg">
          Welcome to Admin LSP<br>
          Politeknik Negeri<br>
          Semarang!
        </h1>
      </div>

      <!-- SLIDESHOW TEXT -->
      <div class="absolute bottom-16 left-1/2 -translate-x-1/2 w-[80%] flex items-center justify-center gap-6 text-white text-2xl">

        <!-- Tombol kiri -->
        <span class="text-3xl cursor-pointer select-none" id="prevBtn">◀</span>

        <!-- Tempat Teks -->
        <p id="slideText" class="leading-relaxed text-center fade opacity-100">
          Pusat kendali operasional yang mengamankan integritas data asesi dan memastikan kelancaran alur uji kompetensi.
        </p>

        <!-- Tombol kanan -->
        <span class="text-3xl cursor-pointer select-none" id="nextBtn">▶</span>

      </div>

    </div>

  </main>

  <script>
    function togglePass() {
      let p = document.getElementById("password");
      p.type = p.type === "password" ? "text" : "password";
    }

    /* -----------------------------
       SLIDESHOW TEKS OTOMATIS + MANUAL
    ----------------------------- */

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

    // Auto slide setiap 5 detik
    setInterval(() => {
      index = (index + 1) % texts.length;
      updateText();
    }, 5000);

  </script>

</body>
</html>
