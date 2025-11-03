<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Admin LSP Polines</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-br from-[#e6ebf1] to-[#dce4f7]">

  <!-- HEADER -->
  <header class="fixed top-0 left-0 w-full bg-white shadow-md z-10 flex justify-center items-center h-20">
    <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16">
  </header>

  <!-- MAIN CONTENT -->
  <main class="flex flex-1 mt-20">
    
    <!-- LEFT: Login Form -->
    <div class="w-1/2 flex justify-center items-center bg-gradient-to-br from-[#f5f8ff] to-[#eaf0ff] px-10">
      <div class="w-full max-w-md">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Kamu Admin LSP Polines?</h2>
        <p class="text-sm text-gray-500 mb-8 text-center">Masukkan username dan password untuk mengakses akunmu.</p>

        <form action="{{ url('/login') }}" method="POST" class="space-y-6">
          @csrf

          <!-- Username -->
          <div>
            <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
            <input id="username" type="text" name="username" placeholder="Username"
                   class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            
           @error('username')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
            <input id="password" type="password" name="password" placeholder="Password"
                   class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Forgot -->
          <div class="text-sm text-gray-600 text-right">
            <a href="#" class="text-blue-600 font-semibold hover:underline">Forgot your password?</a>
          </div>

          <!-- Button -->
          <button type="submit"
                  class="w-full py-3 font-bold text-gray-800 rounded-xl transition
                         bg-gradient-to-r from-[#b4e1ff] to-[#d7f89c]
                         shadow-[inset_2px_2px_5px_rgba(255,255,255,0.6),inset_-3px_-3px_6px_rgba(0,0,0,0.15),0_4px_10px_rgba(0,0,0,0.1)]
                         hover:from-[#a6d5ff] hover:to-[#c9f588]">
            LOGIN
          </button>

          @if(session('error'))
            <p class="text-red-500 text-sm text-center mt-2">{{ session('error') }}</p>
          @endif
        </form>
      </div>
    </div>

    <!-- RIGHT: Image -->
    <div class="w-1/2 bg-cover bg-center"
         style="background-image: url('{{ asset('images/gedung_gkt.jpg') }}');">
    </div>
  </main>

  <!-- RESPONSIVE STACK -->
  <style>
    @media (max-width: 1024px) {
      main { flex-direction: column; }
      main > div { width: 100%; height: 50vh; }
    }
  </style>

</body>
</html>
