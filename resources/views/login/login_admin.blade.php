<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Admin LSP Polines</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }

    /* PIXEL CONFIG */
    .pixel1, .pixel2 {
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
      border: 2px solid #0D63F3;
      border-radius: 22px;
      padding: 40px 30px; 
      width: 100%;
      max-width: 450px; 
      background: white;
    }

    .fade { transition: opacity 0.6s ease; }
  </style>
</head>

<body class="bg-white overflow-hidden">

  <main class="flex h-screen w-full">

    <div class="w-full lg:w-[43%] h-full bg-white flex flex-col justify-center px-10 lg:px-20 relative select-none z-10">

      <img src="{{ asset('images/pixel.png') }}" class="pixel1"
        style="--px-size: 250px; --px-top: -50px; --px-right: -15px;">

      <div class="absolute top-4 left-6">
        <img src="{{ asset('images/Logo_LSP_No_BG.png') }}" class="w-20 drop-shadow">
      </div>

      <div class="form-container mx-auto shadow-2xl relative z-20">
        <h2 class="text-[22px] font-semibold mb-6 tracking-wide text-gray-800">Masukan akun Anda!</h2>

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 text-xs rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login_admin.post') }}" method="POST" class="space-y-5">
          @csrf

          <div class="flex flex-col w-full">
            <label class="text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" 
                   class="px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('username') border-red-500 @enderror" 
                   placeholder="Masukkan username" required autofocus>
            
            @error('username')
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

          <div class="flex justify-end items-center text-sm">
            <a href="{{ route('forgot_pass') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
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

    <div class="hidden lg:block w-[57%] h-full relative bg-cover bg-center select-none"
         style="background-image: url('{{ asset('images/gedung_gkt.jpg') }}');">

      <div class="absolute inset-0 bg-black/50 backdrop-blur-[1px]"></div>

      <div class="relative text-white px-10 mt-10 z-10">
        <h1 class="text-[42px] font-bold leading-tight drop-shadow-2xl">
          Welcome to Admin LSP<br>
          Politeknik Negeri<br>
          Semarang!
        </h1>
      </div>

      <div class="absolute bottom-20 left-1/2 -translate-x-1/2 w-[80%] flex items-center justify-center gap-6 text-white z-10">
        <button class="text-3xl hover:text-gray-300 transition transform hover:scale-110 focus:outline-none" id="prevBtn">◀</button>

        <p id="slideText" class="leading-relaxed text-center text-lg font-light fade opacity-100 h-20 flex items-center justify-center">
          Pusat kendali operasional yang mengamankan integritas data asesi dan memastikan kelancaran alur uji kompetensi.
        </p>

        <button class="text-3xl hover:text-gray-300 transition transform hover:scale-110 focus:outline-none" id="nextBtn">▶</button>
      </div>

    </div>

  </main>

  <script>
    /* [PERBAIKAN] Fungsi togglePass dihapus karena checkbox sudah tidak ada */

    /* Script agar Enter di Username pindah ke Password */
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.getElementById("username");
        const passwordInput = document.getElementById("password");

        usernameInput.addEventListener("keydown", function(event) {
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
    }, 5000);
  </script>

</body>
</html>