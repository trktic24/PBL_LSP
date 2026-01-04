<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal dan TUK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        input[type="checkbox"] {
            accent-color: #3b82f6;
            width: 1.2em;
            height: 1.2em;
        }

        /* Style disabled checkbox */
        input[type="checkbox"]:disabled {
            opacity: 1;
            cursor: default;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- SIDEBAR --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar2 :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi" />
        </div>

        {{-- 2. HEADER MOBILE (Component Baru) --}}
        @php
            // Logika gambar skema dari referensi (Standardized)
            $gambarSkema = null;
            if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) {
                $gambarSkema = asset('storage/' . $sertifikasi->jadwal->skema->gambar);
            }
        @endphp

        <x-mobile_header :title="'Jadwal dan TUK'" :code="$sertifikasi->jadwal->skema->kode_unit ?? ($sertifikasi->jadwal->skema->nomor_skema ?? '-')" :name="$asesi->nama_lengkap ?? Auth::user()->name" :image="$gambarSkema" :sertifikasi="$sertifikasi" />
        {{-- MAIN CONTENT --}}
        {{-- Simpan ID Sertifikasi --}}
        <main class="flex-1 px-6 pt-20 pb-12 md:p-12 overflow-y-auto" data-sertifikasi-id="{{ $id_sertifikasi }}">
            <div class="max-w-4xl mx-auto">

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Tempat Uji Kompetensi</h1>
                    <div class="w-full h-0.5 bg-gray-400 mt-4"></div>
                </div>

                {{-- CARD 1: INFORMASI TUK --}}
                <div class="bg-white rounded-xl border border-gray-400 p-6 mb-6 relative">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi TUK</h2>
                    <div class="w-full h-0.5 bg-gray-300 mb-6"></div>

                    <div class="space-y-4 text-sm font-medium text-gray-800">
                        {{-- Baris TUK (Checkbox) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 items-center">
                            <div class="font-bold text-base">TUK</div>
                            <div class="col-span-2 flex flex-wrap gap-4">
                                <label class="flex items-center space-x-2 cursor-default">
                                    <input type="checkbox" id="tuk_Sewaktu" disabled class="rounded border-gray-400">
                                    <span>Sewaktu</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-default">
                                    <input type="checkbox" id="tuk_Tempat Kerja" disabled
                                        class="rounded border-gray-400">
                                    <span>Tempat Kerja</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-default">
                                    <input type="checkbox" id="tuk_Mandiri" disabled class="rounded border-gray-400">
                                    <span>Mandiri</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <div class="font-bold text-base">Lokasi</div>
                            <div class="col-span-2 flex"><span class="mr-2">:</span> <span
                                    id="text_lokasi">Memuat...</span></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <div class="font-bold text-base">Alamat</div>
                            <div class="col-span-2 flex"><span class="mr-2">:</span> <span
                                    id="text_alamat">Memuat...</span></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <div class="font-bold text-base">Kontak TUK</div>
                            <div class="col-span-2 flex"><span class="mr-2">:</span> <span
                                    id="text_kontak">Memuat...</span></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <div class="font-bold text-base">Tanggal Pelaksanaan</div>
                            <div class="col-span-2 flex"><span class="mr-2">:</span> <span
                                    id="text_tanggal">Memuat...</span></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <div class="font-bold text-base">Waktu Mulai</div>
                            <div class="col-span-2 flex"><span class="mr-2">:</span> <span
                                    id="text_waktu">Memuat...</span></div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: PETA LOKASI --}}
                <div class="bg-white rounded-xl border border-gray-400 p-6 mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                        </svg>
                        <h2 class="text-lg font-bold text-gray-800">Alamat lokasi</h2>
                    </div>

                    <div class="w-full h-[450px] bg-gray-200 rounded-lg overflow-hidden shadow-inner">
                        <iframe id="iframe_maps" src="" width="100%" height="100%" style="border:0;"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-800 p-4 mb-6 text-sm font-medium">
                    <span class="font-bold">Perhatian:</span> Harap hadir <span class="font-bold">30 menit</span>
                    sebelum jadwal dimulai dan wajib membawa <span class="font-bold">Kartu Identitas (KTP/KTM)</span>
                    asli.
                </div>

                {{-- CARD 3: FOTO GEDUNG --}}
                <div class="bg-white rounded-xl border border-gray-400 p-6 mb-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Gambar Lokasi TUK</h2>
                    <div
                        class="w-full h-[500px] bg-gray-200 rounded-lg overflow-hidden shadow-inner flex items-center justify-center relative">
                        <img id="img_gedung" src="" class="w-full h-full object-cover hidden"
                            alt="Foto Gedung TUK">
                        <span id="placeholder_gedung" class="text-gray-500 font-bold text-xl">MEMUAT FOTO...</span>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainElement = document.querySelector('main');
            const idSertifikasi = mainElement.dataset.sertifikasiId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const nextUrl =
                "{{ route('asesi.kerahasiaan.fr_ak01', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}";

            if (!idSertifikasi) {
                alert("ID Sertifikasi tidak ditemukan");
                return;
            }

            // 1. FETCH DATA (Gua benerin tanda kutipnya pake backtick ` )
        // Pastikan url-nya bener (/api/v1/... atau /api/...)
        fetch(`/api/v1/jadwal-tuk/${idSertifikasi}`)
            .then(res => res.json())
            .then(resp => {
                if (resp.success) {
                    const data = resp.data;

                    // A. Isi Checkbox TUK (Pake backtick)
                    const tukEl = document.getElementById(`tuk_${data.jenis_tuk}`);
                    if (tukEl) tukEl.checked = true;

                    // B. Isi Teks Informasi
                    document.getElementById('text_lokasi').innerText = data.lokasi;
                    document.getElementById('text_alamat').innerText = data.alamat;
                    document.getElementById('text_kontak').innerText = data.kontak;

                    // C. Format Tanggal (Pake kode asli lu)
                    const date = new Date(data.tanggal);
                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    document.getElementById('text_tanggal').innerText = date.toLocaleDateString('id-ID',
                        options);

                    // D. Format Waktu (INI PAKE KODE ASLI LU YANG BENER)
                    const waktuObj = new Date(data.waktu);
                    const jamWib = waktuObj.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false // Format 24 jam
                    });
                    document.getElementById('text_waktu').innerText = jamWib + ' WIB';

                    // E. Isi Maps
                    if (data.link_gmap) {
                        document.getElementById('iframe_maps').src = data.link_gmap;
                    }

                    // F. Isi Foto Gedung (Pake backtick buat path)
                    if (data.foto_gedung) {
                        const imgEl = document.getElementById('img_gedung');
                        imgEl.src = `/storage/${data.foto_gedung}`;
                        imgEl.classList.remove('hidden');
                        document.getElementById('placeholder_gedung').classList.add('hidden');
                    } else {
                        document.getElementById('placeholder_gedung').innerText = "FOTO TIDAK TERSEDIA";
                    }
                }
            })
            .catch(err => console.error(err));

        // 2. TOMBOL NAVIGASI
        document.getElementById('btn_selanjutnya').addEventListener('click', () => {
            // Konfirmasi (Pake backtick)
            fetch(`/api/v1/jadwal-tuk/konfirmasi/${idSertifikasi}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect ke Tracker
                            window.location.href = nextUrl;
                        }
                    });
            });

            // Tombol Sebelumnya
            document.getElementById('btn_sebelumnya').addEventListener('click', () => {
                window.history.back();
            });
        });
    </script>

</body>

</html>
