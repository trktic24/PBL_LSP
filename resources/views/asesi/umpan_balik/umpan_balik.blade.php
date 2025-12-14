@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umpan Balik & Catatan Asesmen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- FONT POPPINS --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- SWEETALERT CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Terapkan Poppins ke seluruh body */
        body { font-family: 'Poppins', sans-serif; }
        
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 md:bg-gray-100 font-sans">

    {{-- WRAPPER UTAMA --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- 1. SIDEBAR (Desktop Only - Menggunakan x-sidebar2 sesuai kode asli Anda) --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar2 :idAsesi="$asesi->id_asesi ?? null" :sertifikasi="$sertifikasi" />
        </div>

        {{-- 2. HEADER MOBILE (Component Baru) --}}
        @php
            // Logika gambar skema dari referensi
            $gambarSkema = null;
            if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) {
                $gambar = $sertifikasi->jadwal->skema->gambar;
                if (str_starts_with($gambar, 'images/')) {
                    $gambarSkema = asset($gambar);
                } elseif (file_exists(public_path('images/skema/foto_skema/' . $gambar))) {
                    $gambarSkema = asset('images/skema/foto_skema/' . $gambar);
                } else {
                    $gambarSkema = asset('images/skema/' . $gambar);
                }
            }
        @endphp

        <x-mobile_header
            :title="'Umpan Balik Asesmen'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-'"
            :name="$asesi->nama_lengkap ?? Auth::user()->name"
            :image="$gambarSkema"
            :sertifikasi="$sertifikasi"
        />

        {{-- 3. MAIN CONTENT --}}
        <main class="flex-1 w-full relative md:p-12 md:overflow-y-auto bg-gray-50 md:bg-white z-0">
            
            {{-- CONTAINER RESPONSIF (Card Style yang Anda inginkan) --}}
            <div class="max-w-4xl mx-auto mt-16 md:mt-0 p-6 md:p-0 transition-all bg-white rounded-t-[40px] md:rounded-none md:bg-transparent min-h-screen md:min-h-0 shadow-2xl md:shadow-none">

                {{-- JUDUL HALAMAN --}}
                <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2 md:mb-4 pt-4 md:pt-0 text-center md:text-left">
                    Umpan Balik & Catatan
                </h1>
                <p class="text-gray-600 mb-8 text-sm md:text-base text-center md:text-left">
                    Silakan isi form umpan balik dan catatan asesmen di bawah ini.
                </p>

                {{-- FORM START --}}
                <form id="form-umpan-balik" action="{{ route('asesi.ak03.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
                    @csrf

                    {{-- INFO BOX (Styled agar mirip referensi) --}}
                    <div class="bg-[#F9F6E6] border-[#EAE5C8] md:bg-amber-50 md:border-amber-200 border rounded-lg p-6 mb-8 text-sm">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-y-3">
                            {{-- TUK --}}
                            <div class="col-span-1 font-semibold text-gray-800">TUK</div>
                            <div class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center">
                                @php
                                    $tukRaw = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? $sertifikasi->jadwal->jenisTuk->nama_tuk ?? '';
                                    $tukDb = strtolower($tukRaw);
                                @endphp

                                <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                    <input type="radio" name="tuk_display_only" class="w-4 h-4 text-blue-600 border-gray-300 mr-2 bg-gray-100" disabled {{ $tukDb == 'sewaktu' ? 'checked' : '' }}>
                                    Sewaktu
                                </label>

                                <label class="flex items-center text-gray-700 cursor-not-allowed opacity-75">
                                    <input type="radio" name="tuk_display_only" class="w-4 h-4 text-blue-600 border-gray-300 mr-2 bg-gray-100" disabled {{ str_contains($tukDb, 'tempat') ? 'checked' : '' }}>
                                    Tempat Kerja
                                </label>
                            </div>

                            {{-- Asesor --}}
                            <div class="col-span-1 font-semibold text-gray-800">Nama Asesor</div>
                            <div class="col-span-3 text-gray-800">
                                : {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}
                            </div>

                            {{-- Asesi --}}
                            <div class="col-span-1 font-semibold text-gray-800">Nama Asesi</div>
                            <div class="col-span-3 text-gray-800">
                                : {{ $asesi->nama_lengkap ?? Auth::user()->name }}
                            </div>
                        </div>
                    </div>

                    {{-- TABEL INPUT --}}
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Pertanyaan</h3>
                        <div class="border border-gray-200 rounded-lg shadow-sm overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-black text-white">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs md:text-sm font-bold uppercase tracking-wider">Komponen</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs md:text-sm font-bold uppercase tracking-wider w-16">Ya</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs md:text-sm font-bold uppercase tracking-wider w-16">Tidak</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs md:text-sm font-bold uppercase tracking-wider w-48">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($komponen as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <div class="flex items-start">
                                                <span class="font-semibold text-gray-800 mr-3">{{ $loop->iteration }}.</span>
                                                <p class="text-sm text-gray-700 leading-snug">{{ $item->komponen }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 align-top text-center pt-5">
                                            <input type="radio" name="jawaban[{{ $item->id_poin_ak03 }}][hasil]" value="ya" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer" required>
                                        </td>
                                        <td class="px-4 py-4 align-top text-center pt-5">
                                            <input type="radio" name="jawaban[{{ $item->id_poin_ak03 }}][hasil]" value="tidak" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer" required>
                                        </td>
                                        <td class="px-4 py-4 align-top pt-4">
                                            <input type="text" name="jawaban[{{ $item->id_poin_ak03 }}][catatan]" placeholder="Tulis catatan..." class="w-full text-sm border-b border-gray-300 focus:border-blue-500 focus:outline-none py-1 px-1 bg-transparent">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- CATATAN TAMBAHAN --}}
                    <div class="mb-8">
                        <label for="catatan_tambahan" class="text-sm font-bold text-gray-900">Catatan/Komentar Lainnya (Apabila ada):</label>
                        <textarea id="catatan_tambahan" name="catatan_tambahan" rows="4" class="mt-2 w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-3 bg-gray-50"></textarea>
                    </div>

                    {{-- TOMBOL NAVIGASI --}}
                    <div class="flex justify-end items-center pb-20 md:pb-0 gap-4 mt-8 border-t border-gray-100 pt-6">
                        <button type="button" id="btn-submit" class="w-full md:w-64 text-center px-8 py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-md transition-all shadow-blue-200 flex items-center justify-center gap-2">
                            <span>Kirim Umpan Balik</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                </form>
                {{-- FORM END --}}

            </div>
        </main>
    </div>

    {{-- SCRIPT JS (SweetAlert & Logic) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnSubmit = document.getElementById('btn-submit');
            const form = document.getElementById('form-umpan-balik');

            btnSubmit.addEventListener('click', function (e) {
                e.preventDefault(); 

                // Cek validasi HTML5
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Pengiriman',
                    text: "Apakah Anda yakin seluruh data sudah benar? Formulir yang telah dikirim tidak dapat diubah kembali.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563EB', // Blue-600 match tailwind
                    cancelButtonColor: '#9CA3AF', // Gray-400
                    confirmButtonText: 'Ya, Kirim Data',
                    cancelButtonText: 'Periksa Lagi',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl', // Agar rounded match tema
                        confirmButton: 'rounded-full px-6',
                        cancelButton: 'rounded-full px-6'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading state
                        btnSubmit.disabled = true;
                        btnSubmit.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengirim...
                        `;
                        
                        form.submit();
                    }
                });
            });
        });
    </script>

</body>
</html>