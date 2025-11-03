<div class="border border-gray-900 shadow-md">
    <table class="w-full">
        
        {{-- 
          Tag <thead> dan styling seragamnya (bg-black) kita definisikan DI SINI.
        --}}
        <thead class="bg-black text-white">
            {{-- 
              Ini adalah "lubang" untuk ISI header. 
              Kamu bisa masukkan 1, 2, atau 3 baris <tr> di sini.
            --}}
            {{ $thead ?? ''}}
        </thead>

        {{-- 
          Ini adalah "lubang" untuk ISI body. 
          Kamu bisa masukkan <tr> dengan rowspan atau apapun di sini.
        --}}
        <tbody>
            {{ $slot }}
        </tbody>

    </table>
</div>