<x-app-layout>

    <div class="flex h-screen overflow-hidden">

        <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi" />

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">

                <h1 class="text-4xl font-bold text-gray-900 mb-10">Umpan Balik dan Catatan Asesmen</h1>

                <form action="{{ route('ak03.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-y-4 text-sm mb-8">

                        <div class="col-span-1 font-medium text-gray-800 pt-2">TUK</div>
                        <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">

                            {{--
                                1. Ambil nilai TUK dari Database.
                                Kita siapkan variabel PHP di dalam blade untuk menampung data dari DB.
                                Kita ubah ke huruf kecil (strtolower) agar perbandingannya akurat.
                            --}}
                            @php
                                // Coba ambil dari kolom 'jenis_tuk', jika tidak ada coba 'nama_tuk', jika tidak ada kosongkan.
                                $tukRaw = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? $sertifikasi->jadwal->jenisTuk->nama_tuk ?? '';
                                $tukDb = strtolower($tukRaw);
                            @endphp

                            {{-- 2. Opsi Radio: SEWAKTU --}}
                            {{-- Class 'opacity-75' dan 'cursor-not-allowed' membuat tampilan visual disabled --}}
                            <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                <input type="radio"
                                       name="tuk_display_only" {{-- Name sama agar terlihat satu grup --}}
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mr-2 cursor-not-allowed bg-gray-100"
                                       disabled {{-- KUNCI UTAMA: Agar tidak bisa diklik --}}
                                       {{-- LOGIKA OTOMATIS: Jika di DB isinya 'sewaktu', maka centang ini --}}
                                       {{ $tukDb == 'sewaktu' ? 'checked' : '' }}>
                                Sewaktu
                            </label>

                            {{-- 3. Opsi Radio: TEMPAT KERJA --}}
                            {{-- [PERBAIKAN DI SINI] Menggunakan str_contains() bawaan PHP --}}
                            <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                <input type="radio"
                                       name="tuk_display_only"
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mr-2 cursor-not-allowed bg-gray-100"
                                       disabled {{-- KUNCI UTAMA: Agar tidak bisa diklik --}}
                                       {{-- LOGIKA OTOMATIS: Cek apakah di DB mengandung kata 'tempat' menggunakan str_contains() --}}
                                       {{ str_contains($tukDb, 'tempat') ? 'checked' : '' }}>
                                Tempat Kerja
                            </label>
                        </div>


                        <div class="col-span-1 font-medium text-gray-800">Nama Asesor</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                        </div>

                        <div class="col-span-1 font-medium text-gray-800">Nama Asesi</div>
                        <div class="col-span-3 text-gray-800 font-semibold">
                            : {{ $asesi->nama_lengkap ?? Auth::user()->name }}
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-900 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Komponen</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Ya</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-20">Tidak</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider w-48">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($komponen as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start">
                                            <span class="font-semibold text-gray-800 mr-3">{{ $loop->iteration }}</span>
                                            <p class="text-sm text-gray-700">{{ $item->komponen }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-middle text-center">
                                        <input type="radio" name="jawaban[{{ $item->id_poin_ak03 }}][hasil]" value="ya" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                    </td>
                                    <td class="px-6 py-4 align-middle text-center">
                                        <input type="radio" name="jawaban[{{ $item->id_poin_ak03 }}][hasil]" value="tidak" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500" required>
                                    </td>
                                    <td class="px-6 py-4 align-middle">
                                        <input type="text" name="jawaban[{{ $item->id_poin_ak03 }}][catatan]" placeholder="Tambahkan Pesan..." class="w-full text-xs border-b border-gray-300 focus:border-blue-500 focus:outline-none py-1 px-2">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <label for="catatan_tambahan" class="text-sm font-medium text-gray-700">Catatan/komentar lainnya (apabila ada):</label>
                        <textarea id="catatan_tambahan" name="catatan_tambahan" rows="4" class="mt-2 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2 border"></textarea>
                    </div>

                    <div class="flex justify-between items-center mt-12">
                        <button type="button" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                            Sebelumnya
                        </button>
                        <button type="submit" class="px-8 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 shadow-md transition-colors">
                            Kirim
                        </button>
                    </div>

                </form>

            </div>
        </main>
    </div>

</x-app-layout>