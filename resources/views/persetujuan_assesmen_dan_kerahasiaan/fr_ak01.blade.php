<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        
        {{-- Asumsi komponen sidebar ada di sini --}}
        <x-sidebar2></x-sidebar2>

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-3xl mx-auto">
                
                <h1 class="text-4xl font-bold text-gray-900 mb-8">Persetujuan Asesmen dan Kerahasiaan</h1>

                <p class="text-gray-700 mb-8 text-sm">
                    Persetujuan Asesmen ini untuk menjamin bahwa Peserta telah diberi arahan secara rinci tentang perencanaan dan proses asesmen
                </p>

                <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-6 text-sm">
                    
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                        {{-- Logika untuk menampilkan centang TUK (asumsi data TUK ada di $data_asesmen['tuk']) --}}
                        @php
                            $tuk_selected = $data_asesmen['tuk'] ?? '';
                        @endphp
                        
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Sewaktu" @checked($tuk_selected === 'Sewaktu') class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Sewaktu
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Tempat Kerja" @checked($tuk_selected === 'Tempat Kerja') class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Tempat Kerja
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Mandiri" @checked($tuk_selected === 'Mandiri') class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Mandiri
                        </label>
                    </dd>
                    
                    {{-- START PERBAIKAN: Nama Asesor. Jika gagal, akan memunculkan error Laravel (bukan placeholder) --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    <dd class="col-span-3 text-gray-800">: **{{ $asesor->nama_lengkap ?? '[Nama Asesor Belum Tersedia]' }}**</dd>

                    {{-- START PERBAIKAN: Nama Asesi --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800">: **{{ $asesi->nama_lengkap ?? '[Nama Asesi Belum Tersedia]' }}**</dd>
                    
                    <dt class="col-span-1 font-medium text-gray-800">Bukti yang akan dikumpulkan</dt>
                    <dd class="col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                        
                        {{-- Logika untuk menampilkan centang Bukti (asumsi data bukti ada di $data_asesmen['bukti_dikumpulkan']) --}}
                        @php
                            $bukti_checked = $data_asesmen['bukti_dikumpulkan'] ?? [];
                        @endphp

                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Verifikasi Portofolio" @checked(in_array('Verifikasi Portofolio', $bukti_checked)) class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Verifikasi Portofolio
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Test Tulis" @checked(in_array('Hasil Test Tulis', $bukti_checked)) class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Test Tulis
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Tes Lisan" @checked(in_array('Hasil Tes Lisan', $bukti_checked)) class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Tes Lisan
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Hasil Wawancara" @checked(in_array('Hasil Wawancara', $bukti_checked)) class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Hasil Wawancara
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="checkbox" value="Observasi Langsung" @checked(in_array('Observasi Langsung', $bukti_checked)) class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 mr-2">
                            Observasi Langsung
                        </label>
                    </dd>

                </dl>

                <p class="mt-10 text-gray-700 text-sm leading-relaxed">
                    Bahwa saya sudah Mendapatkan Penjelasan Hak dan Prosedur Banding Oleh Asesor
                </p>
                <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                    Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
                </p>

                <div class="mt-6">
                    <div class="w-full h-48 bg-gray-50 border border-gray-300 rounded-lg shadow-inner">
                        {{-- Area untuk tanda tangan digital --}}
                        </div>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-red-600 text-xs italic">*Tanda Tangan di sini</p>
                        <button class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                            Hapus
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-12">
                    <button class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Sebelumnya
                    </button>
                    <button class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>

    </div>

</body>
</html>