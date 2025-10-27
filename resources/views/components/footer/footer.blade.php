<footer 
  x-data="footerData()" 
  class="w-full text-white py-16 font-[Poppins] text-center"
  style="background: linear-gradient(to top, #1F3A58 0%, #0081FE 100%);"
>
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
      <button 
        @click="showModal = true"
        class="inline-block bg-blue-500 text-white font-semibold px-10 py-3 rounded-full rounded-tr-none 
               border border-white shadow-md hover:shadow-lg transition-all duration-300 
               hover:bg-white hover:text-blue-600 hover:border-blue-600">
        Hubungi Kami
      </button>
    </div>

    {{-- Jarak besar antara tombol dan alamat --}}
    <div class="mt-24"></div>
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

  {{-- Garis pemisah --}}
  <div class="w-full px-6 sm:px-12 mt-8">
    <div class="border-t border-white/30 pt-3 max-w-6xl mx-auto"></div>

    {{-- Footer bawah --}}
    <div class="relative max-w-6xl mx-auto mt-3 flex items-center justify-between gap-3">
      {{-- Logo kiri --}}
      <div class="flex items-center gap-2">
        <img src="{{ asset('images/Polines Onli.png') }}" alt="Logo LSP POLINES" class="w-10">
      </div>

      {{-- Copyright --}}
      <p class="absolute left-1/2 transform -translate-x-1/2 text-xs text-white/70">
        © <span x-text="year"></span> LSP POLINES. All rights reserved.
      </p>

      {{-- Icon sosial kanan --}}
      <div class="flex gap-2">
        <template x-for="icon in socialLinks" :key="icon.name">
          <a 
            :href="icon.link" 
            target="_blank"
            class="w-8 h-8 flex items-center justify-center rounded-full border border-white hover:bg-white/20 transition"
          >
            <i :class="`fab fa-${icon.name} text-white text-sm`"></i>
          </a>
        </template>
      </div>
    </div>
  </div>

  {{-- MODAL Hubungi Kami --}}
  <div 
    x-show="showModal"
    x-transition.opacity
    x-cloak
    @click.self="showModal = false"
    class="fixed inset-0 z-50 flex justify-center items-center bg-black/40 backdrop-blur-sm"
  >
    <div 
      x-transition.scale.origin.center
      class="relative bg-white rounded-2xl shadow-lg w-full max-w-xs mx-auto overflow-hidden font-[Poppins]"
    >
      {{-- Modal header --}}
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 tracking-wide">
          Hubungi Kami
        </h3>
        <button 
          @click="showModal = false"
          class="text-gray-400 hover:bg-gray-200 hover:text-gray-800 rounded-lg text-xs h-7 w-7 flex justify-center items-center transition"
        >
          <svg class="w-2.5 h-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
      </div>

      {{-- Modal body --}}
      <div class="p-5 space-y-3">
        {{-- WhatsApp --}}
        <a href="https://wa.me/6281234567890" target="_blank"
           class="flex items-center justify-between w-full border border-gray-200 rounded-lg px-4 py-3 
                  transition-all duration-300 hover:shadow-md hover:border-blue-500/70 hover:ring-1 hover:ring-blue-400/40">
          <div class="flex items-center gap-3">
            <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-5 h-5">
            <span class="font-medium text-gray-800 text-sm">WhatsApp</span>
          </div>
        </a>

        {{-- Gmail --}}
        <a href="mailto:lsp@polines.ac.id"
           class="flex items-center justify-between w-full border border-gray-200 rounded-lg px-4 py-3 
                  transition-all duration-300 hover:shadow-md hover:border-blue-500/70 hover:ring-1 hover:ring-blue-400/40">
          <div class="flex items-center gap-3">
            <img src="https://cdn-icons-png.flaticon.com/512/5968/5968534.png" alt="Gmail" class="w-5 h-5">
            <span class="font-medium text-gray-800 text-sm">Gmail</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</footer>

{{-- FontAwesome --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

{{-- Alpine.js --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
  function footerData() {
    return {
      year: new Date().getFullYear(),
      showModal: false,
      socialLinks: [
        { name: 'linkedin-in', link: 'https://linkedin.com' },
        { name: 'facebook-f', link: 'https://facebook.com' },
        { name: 'instagram', link: 'https://instagram.com' },
        { name: 'youtube', link: 'https://youtube.com' },
      ]
    }
  }
</script>
