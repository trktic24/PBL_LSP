<x-sidebar-asesor>
    <x-slot:title>
        Form FR.IA.06 - ASESMEN MANDIRI DAN PELAPORAN HASIL ASESMEN
    </x-slot:title>

    <div class="p-8" x-data="{ showNotif: false }">

        <!-- ==================================== -->
        <!-- BAGIAN FORM (TAMPIL DEFAULT)      -->
        <!-- ==================================== -->
        <div x-show="!showNotif">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">FR.IA.06 Pertanyaan Tertulis Esai</h1>

            <!-- Box Info Atas -->
            <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm">
                    <span class="font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Junior Web Developer">

                    <span class="font-medium text-gray-700">Judul / Nomor</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Sertifikasi KKNI II / 0341XXXXXXX">
                </div>

                <hr class="my-4 border-gray-300">

                <div class="grid grid-cols-[150px,10px,1fr] gap-x-2 gap-y-3 text-sm items-center">
                    <span class="font-medium text-gray-700">TUK</span><span class="font-medium">:</span>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="sewaktu" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Sewaktu
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="tempat_kerja" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Tempat Kerja
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="tuk_type" value="mandiri" class="h-4 w-4 text-blue-600 focus:ring-blue-500"> Mandiri
                        </label>
                    </div>

                    <span class="font-medium text-gray-700">Nama Asesor</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Ajeng Febria H.">

                    <span class="font-medium text-gray-700">Nama Asesi</span><span class="font-medium">:</span>
                    <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="John Doe">

                    <span class="font-medium text-gray-700">Tanggal</span><span class="font-medium">:</span>
                    <input type="date" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="2025-09-28">

                    <span class="font-medium text-gray-700">Waktu</span><span class="font-medium">:</span>
                    <input type="time" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="06:18">
                </div>
                <p class="text-xs text-gray-500 mt-4">*Coret yang tidak perlu</p>
            </div>

            <!-- Box Tabel Pertanyaan -->
            <div class="mb-6">
                <div class="overflow-x-auto border border-gray-300 rounded-md">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-center font-medium w-8/12">Jawaban</th>
                                <th class="p-3 text-center font-medium w-2/12" colspan="2">Pencapaian</th>
                            </tr>
                            <tr>
                                <th class="p-3 text-center font-medium"></th>
                                <th class="p-3 text-center font-medium w-1/12">Ya</th>
                                <th class="p-3 text-center font-medium w-1/12">Tidak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Contoh loop untuk 5 pertanyaan -->
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td class="p-3 text-left">
                                        {{ $i }}. <textarea class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 resize-y" rows="3" placeholder="Tulis jawaban esai di sini..."></textarea>
                                    </td>
                                    <td class="p-3 text-center">
                                        <input type="radio" name="pencapaian_{{ $i }}" value="ya" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="p-3 text-center">
                                        <input type="radio" name="pencapaian_{{ $i }}" value="tidak" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Box Umpan Balik Asesi -->
            <div class="bg-gray-50 p-6 rounded-md shadow-sm mb-6 border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Umpan Balik dari Asesi:</h3>
                <div class="grid grid-cols-[1fr] gap-y-3 text-sm">
                    <label class="font-medium text-gray-700">Aspek pengetahuan seluruh unit kompetensi yang diujikan (tercapai/belum tercapai)*</label>
                    <textarea class="p-2 w-full border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 resize-y" rows="3" placeholder="Tulis umpan balik asesi di sini..."></textarea>
                </div>
            </div>

            <!--
              =======================================
              MODIFIKASI BAGIAN YANG ANEH (FIXED)
              =======================================
              - Menghapus <table>
              - Menggunakan CSS Grid untuk 2 kolom
            -->
            <div class="mt-8">
                <h3 class="font-semibold text-gray-700 mb-3">Pencatatan dan Validasi</h3>

                <!-- Menggunakan CSS Grid untuk 2 kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 border border-gray-200 rounded-md shadow-sm">

                    <!-- Kolom Asesi -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-800">Asesi</h4>
                        <!-- Menggunakan Grid untuk perataan label + input -->
                        <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-center">
                            <span class="font-medium text-gray-700">Nama</span>
                            <span class="font-medium">:</span>
                            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="John Doe">

                            <span class="font-medium text-gray-700">Tandatangan/Tanggal</span>
                            <span class="font-medium">:</span>
                            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="[Tanda Tangan] / 28-09-2025">
                        </div>
                    </div>

                    <!-- Kolom Asesor -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-800">Asesor</h4>
                        <!-- Menggunakan Grid untuk perataan label + input -->
                        <div class="grid grid-cols-[150px,10px,1fr] gap-y-2 text-sm items-center">
                            <span class="font-medium text-gray-700">Nama</span>
                            <span class="font-medium">:</span>
                            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="Ajeng Febria H.">

                            <span class="font-medium text-gray-700">No. Reg. MET.</span>
                            <span class="font-medium">:</span>
                            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="MET-12345">

                            <span class="font-medium text-gray-700">Tandatangan/Tanggal</span>
                            <span class="font-medium">:</span>
                            <input type="text" class="p-1 w-full border-b border-gray-300 focus:border-blue-500 outline-none bg-transparent" value="[Tanda Tangan] / 28-09-2025">
                        </div>
                    </div>
                </div>
            </div>
            <!-- =================================== -->
            <!-- AKHIR DARI MODIFIKASI           -->
            <!-- =================================== -->

            <!-- Tombol Selesai Form -->
            <div class="mt-8 text-right">
                <button type="button"
                        @click="showNotif = true"
                        class="bg-blue-600 text-white font-medium py-2 px-6 rounded-md hover:bg-blue-700">
                    Selesai
                </button>
            </div>
        </div>

        <!-- ==================================== -->
        <!-- BAGIAN NOTIFIKASI (TAMPIL SETELAH KLIK) -->
        <!-- ==================================== -->
        <div x-show="showNotif" style="display: none;" class="flex items-center justify-center min-h-[calc(100vh-10rem)]">
            <div class="bg-white p-12 rounded-lg shadow-xl text-center max-w-md mx-auto border border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    FR.IA.06 - Pertanyaan Tertulis Esai
                </h1>

                <svg class="w-24 h-24 text-green-500 mx-auto mb-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>

                <p class="text-xl font-medium text-gray-700">Form Berhasil Dikirim</p>

                <div class="mt-8">
                    <button type="button"
                            @click="showNotif = false"
                            class="bg-blue-600 text-white font-medium py-2 px-6 rounded-md hover:bg-blue-700">
                        Kembali Ke Form
                    </button>

                    <!-- Tombol ini bisa Anda arahkan ke dashboard -->
                    <a href="{{ route('home') }}" class="ml-4 bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-md hover:bg-gray-400">
                        Ke Dashboard
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-sidebar-asesor>