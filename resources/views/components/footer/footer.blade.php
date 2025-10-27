<footer class="w-full text-white py-16 font-[Poppins] text-center"
        style="background: linear-gradient(to top,  #1F3A58 0%, #0081FE 100%);">
  {{-- Bagian teks utama --}}
  <div class="text-center px-6 sm:px-12">
    <div class="space-y-4 sm:space-y-5 max-w-3xl mx-auto">
      <p class="uppercase text-sm tracking-widest text-white/55">
        SERTIFIKASI PROFESI UNTUK KARIER ANDA
      </p>

      <h2 class="text-3xl sm:text-4xl font-semibold leading-snug">
        Tingkatkan Kompetensi Profesional Anda
      </h2>

      <p class="text-white/90 text-base leading-relaxed sm:leading-loose">
        LSP Polines berkomitmen menghasilkan tenaga kerja kompeten yang siap bersaing
        dan diakui secara nasional maupun internasional.
      </p>
    </div>

    {{-- Tombol --}}
    <div class="mt-8 sm:mt-10">
      <a href="#"
        class="inline-block bg-blue-500 text-white font-semibold px-10 py-3 rounded-full rounded-tr-none 
               border border-white shadow-md hover:shadow-lg transition-all duration-300 
               hover:bg-white hover:text-blue-600 hover:border-blue-600">
        Hubungi Kami
      </a>
    </div>

    {{-- Jarak besar antara tombol dan alamat --}}
    <div style="margin-top: 6.25rem;"></div>
  </div>

  {{-- Bagian alamat dan kontak --}}
  <div class="w-full px-6 sm:px-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-sm text-white/90 gap-14 max-w-6xl mx-auto">
      
      {{-- Kolom kiri (Alamat) --}}
      <div class="text-center md:text-left leading-relaxed">
        <p>Jl. Prof. Soedarto, SH,<br>Tembalang, Semarang, Jawa Tengah</p>
      </div>

      {{-- Kolom kanan (Kontak) --}}
      <div class="text-center md:text-right leading-relaxed">
        <p>(024) 7473417 ext.516<br>lsp@polines.ac.id</p>
      </div>
    </div>
  </div>

  {{-- Garis pemisah bawah --}}
  <div class="w-full px-6 sm:px-12 mt-4">
    <div class="border-t border-white/30 pt-6 relative flex items-center justify-center text-sm text-white/80 max-w-6xl mx-auto">
      
      {{-- Logo kiri bawah --}}
      <div class="absolute left-0 flex items-center gap-2 mt-3">
        <img src="{{ asset('images/Polines Onli.png') }}" alt="Logo LSP POLINES" class="w-10">
      </div>

      {{-- Icon sosial kanan bawah (circle border putih) --}}
      <div class="absolute right-0 bottom-0 flex gap-2 mt-3">
        <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full border border-white hover:bg-white/20 transition">
          <i class="fa-brands fa-linkedin-in fa-xs text-white"></i>
        </a>
        <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full border border-white hover:bg-white/20 transition">
          <i class="fa-brands fa-facebook-f fa-xs text-white"></i>
        </a>
        <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full border border-white hover:bg-white/20 transition">
          <i class="fa-brands fa-instagram fa-xs text-white"></i>
        </a>
        <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full border border-white hover:bg-white/20 transition">
          <i class="fa-brands fa-youtube fa-xs text-white"></i>
        </a>
      </div>

      {{-- Teks tengah bawah --}}
      <p class="text-xs text-white/70 text-center mt-3">
        © 2025 LSP POLINES. All rights reserved.
      </p>

    </div>
  </div>
</footer>