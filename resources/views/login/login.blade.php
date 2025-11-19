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
  </style>
</head>
<body class="bg-white overflow-hidden">

  <main class="flex h-screen w-full">

    <!-- LEFT PANEL -->
    <div class="w-[43%] h-full bg-white flex flex-col justify-center px-16 relative select-none">

      <!-- Pixel Decoration Top Left -->
      <div class="absolute top-6 left-6 opacity-80">
        <img src="{{ asset('images/pixel_top_left.png') }}" class="w-48">
      </div>

      <!-- Logo Row -->
      <div class="flex items-center gap-4 mb-10 -mt-6">
        <img src="{{ asset('images/logo_lsp.png') }}" class="w-16">
        <img src="{{ asset('images/logo_polines.png') }}" class="w-20">
      </div>

      <!-- Title -->
      <h2 class="text-[24px] font-semibold mb-8 tracking-wide">Masukan akun Anda!</h2>

      <!-- FORM -->
      <form action="#" method="POST" class="space-y-6 w-full">
        <!-- Username -->
        <div class="flex flex-col w-full">
          <label class="text-sm font-medium">Username</label>
          <input type="text" class="mt-1 px-4 py-2 border rounded-lg shadow-sm focus:ring-2 outline-none" placeholder="Masukkan username" />
        </div>

        <!-- Password -->
        <div class="flex flex-col w-full">
          <label class="text-sm font-medium">Password</label>
          <input id="password" type="password" class="mt-1 px-4 py-2 border rounded-lg shadow-sm focus:ring-2 outline-none" placeholder="Masukkan password" />
        </div>

        <!-- Show Password + Forgot -->
        <div class="flex justify-between items-center text-sm -mt-2">
          <label class="flex items-center gap-2">
            <input type="checkbox" onclick="togglePass()" class="w-4 h-4">
            Tampilkan Password
          </label>

          <a href="#" class="text-gray-600">Forgot your <span class="font-semibold">Password?</span></a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full bg-[#0D63F3] text-white py-2.5 rounded-full text-[15px] font-medium shadow-md hover:shadow-lg active:scale-[0.97] transition">Masuk ke Akun</button>
      </form>

      <!-- Pixel Decoration Bottom Left -->
      <div class="absolute bottom-6 left-6 opacity-80">
        <img src="{{ asset('images/pixel_bottom_left.png') }}" class="w-48">
      </div>

    </div>

    <!-- RIGHT PANEL -->
    <div class="w-[57%] h-full relative bg-cover bg-center select-none" style="background-image: url('{{ asset('images/gedung_gkt.jpg') }}');">

      <!-- Dark Overlay -->
      <div class="absolute inset-0 bg-black/45"></div>

      <!-- Welcome Text -->
      <div class="relative text-white px-14 mt-20">
        <h1 class="text-[40px] font-bold leading-tight drop-shadow-lg">
          Welcome to Admin LSP<br>
          Politeknik Negeri<br>
          Semarang!
        </h1>
      </div>

      <!-- Bottom Slider Text -->
      <div class="absolute bottom-14 left-0 w-full px-14 flex items-center gap-4 text-white text-[14px]">
        <span class="text-xl cursor-pointer">◀</span>
        <p class="leading-relaxed w-[75%]">Pusat kendali operasional yang mengamankan integritas data asesi dan memastikan kelancaran alur uji kompetensi.</p>
        <span class="text-xl cursor-pointer">▶</span>
      </div>

    </div>
  </main>

  <!-- Toggle Password JS -->
  <script>
    function togglePass() {
      let p = document.getElementById("password");
      p.type = p.type === "password" ? "text" : "password";
    }
  </script>

  <!-- Responsive -->
  <style>
    @media (max-width: 1024px) {
      main { flex-direction: column; }
      main > div { width: 100%; height: 50vh; }
    }
  </style>

</body>
</html>
