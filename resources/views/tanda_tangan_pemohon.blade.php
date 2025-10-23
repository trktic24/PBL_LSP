<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tanda Tangan Pemohon</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
</head>
<body class="bg-gray-200">

<div class="flex min-h-screen">

  <!-- Sidebar kiri (sesuai gambar) -->
  <aside class="w-72 bg-gradient-to-b from-[#77B8FF] to-[#A4D3FF] flex flex-col items-center text-center py-6 px-5 relative">
    
    <!-- Tombol kembali -->
    <a href="/tracker" class="flex items-center text-sm font-medium text-gray-700 hover:text-black mb-8">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
      </svg>
      Kembali
    </a>

    <!-- Foto profil skema -->
    <img src="https://i.ibb.co/mDk6bHt/code-bg.jpg" alt="Profile" class="rounded-full w-28 h-28 mt-10 border-4 border-white shadow-lg object-cover">

    <!-- Nama skema -->
    <h2 class="text-lg font-bold text-gray-900 mt-4">Junior Web<br>Developer</h2>

    <!-- Deskripsi -->
    <p class="text-xs text-gray-700 italic mt-2 leading-snug">
      Lorem ipsum dolor sit amet<br>You're the best person I ever met
    </p>

    <!-- Info peserta -->
    <div class="mt-10 text-center w-full">
      <p class="text-xs font-semibold text-gray-700 mb-2">OLEH PESERTA:</p>
      <div class="flex flex-col items-center mb-5">
        <img src="https://i.ibb.co/CH0hpWm/user-avatar.png" alt="Peserta" class="w-10 h-10 rounded-full border border-gray-400 mb-2">
        <p class="text-sm font-semibold text-gray-900">Tatang Sidartang</p>
      </div>

      <p class="text-xs font-semibold text-gray-700 mb-1">DIMULAI PADA:</p>
      <p class="text-sm font-bold text-gray-900">2025-09-29 06:18:25</p>
    </div>
  </aside>

  <!-- Konten utama -->
  <main class="flex-1 bg-white rounded-l-3xl p-10 shadow-lg">
    <div class="flex justify-between items-center mb-8">
      <div></div>
      <img src="https://bnsp.go.id/assets/img/logo-bnsp.png" alt="BNSP Logo" class="w-28">
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tanda Tangan Pemohon</h1>

    <p class="text-gray-700 mb-4">Saya yang bertanda tangan di bawah ini:</p>

    <form class="space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <label class="block text-gray-700 font-medium">Nama</label>
          <input type="text" placeholder="Ketik nama..." class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-gray-600 placeholder-gray-400 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none">
        </div>
        <div>
          <label class="block text-gray-700 font-medium">Jabatan</label>
          <input type="text" placeholder="Ketik jabatan..." class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-gray-600 placeholder-gray-400 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none">
        </div>
        <div>
          <label class="block text-gray-700 font-medium">Perusahaan</label>
          <input type="text" placeholder="Ketik nama perusahaan..." class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-gray-600 placeholder-gray-400 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none">
        </div>
        <div>
          <label class="block text-gray-700 font-medium">Alamat Perusahaan</label>
          <input type="text" placeholder="Ketik alamat perusahaan..." class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-gray-600 placeholder-gray-400 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none">
        </div>
      </div>

      <p class="text-gray-700 text-sm leading-relaxed mt-6">
        Dengan ini saya menyatakan mengisi data dengan sebenarnya untuk dapat digunakan sebagai bukti pemenuhan syarat Sertifikasi Lorem Ipsum Dolor Sit Amet.
      </p>

      <!-- Area tanda tangan -->
      <div class="mt-6">
        <label class="block text-gray-700 font-medium mb-2">Tanda Tangan Pemohon:</label>
        <div class="border-2 border-gray-300 rounded-md relative bg-gray-50">
          <canvas id="signature-pad" class="w-full h-48"></canvas>
          <p class="absolute bottom-2 left-3 text-sm text-red-500 italic">*Tanda Tangan di sini</p>
        </div>

        <div class="flex justify-center mt-4 gap-6">
          <button type="button" id="clear" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded-md font-semibold transition">
            Hapus
          </button>
        </div>
      </div>

      <!-- Tombol navigasi -->
      <div class="flex justify-between mt-10">
        <a href="/bukti_pemohon" type="button" class="border-2 border-blue-400 text-blue-500 font-semibold px-8 py-2 rounded-full hover:bg-blue-50 transition">
          Sebelumnya
        </a>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-2 rounded-full transition">
          Selesai
        </button>
      </div>
    </form>
  </main>
</div>

<script>
  // Inisialisasi Signature Pad
  const canvas = document.getElementById('signature-pad');
  const signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255,255,255)'
  });

  // Menyesuaikan ukuran canvas agar proporsional
  function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext('2d').scale(ratio, ratio);
    signaturePad.clear();
  }

  window.addEventListener("resize", resizeCanvas);
  resizeCanvas();

  // Tombol hapus tanda tangan
  document.getElementById('clear').addEventListener('click', () => signaturePad.clear());
</script>

</body>
</html>
