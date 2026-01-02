<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pra-Asesmen Mandiri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- FONT & CUSTOM STYLE DARI KODE TEMANMU --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        @layer base {
            body {
                font-family: 'Poppins', sans-serif;
            }
        }

        /* Custom transitions for accordion */
        .transition-max-height {
            transition: max-height 0.3s ease-in-out;
        }

        .hidden-custom {
            display: none;
        }

        /* Custom style untuk radio K/BK agar mirip dengan desain temanmu */
        .radio-k:checked {
            background-color: #10B981;
            /* Green-500 */
            border-color: #10B981;
        }

        .radio-bk:checked {
            background-color: #EF4444;
            /* Red-500 */
            border-color: #EF4444;
        }
    </style>
</head>

<body class="bg-gray-100">

    {{-- LAYOUT UTAMA: SIDEBAR & KONTEN --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- SIDEBAR (Menggunakan Komponen Sidebar Kita) --}}
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

        <x-mobile_header :title="'Pra-Asesmen Mandiri'" :code="$sertifikasi->jadwal->skema->kode_unit ?? ($sertifikasi->jadwal->skema->nomor_skema ?? '-')" :name="$asesi->nama_lengkap ?? Auth::user()->name" :image="$gambarSkema" :sertifikasi="$sertifikasi" />

        {{-- MAIN CONTENT --}}
        <main class="flex-1 px-6 pt-20 pb-12 md:p-12 bg-white overflow-y-auto">
            <div class="max-w-6xl mx-auto">

                {{-- JUDUL HALAMAN & SKEMA --}}
                {{-- JUDUL HALAMAN & SKEMA (MODIFIED STYLE) --}}
                <div class="mb-8">
                    {{-- 1. Judul Utama (Persis Screenshot: Tengah, Tebal, Gelap) --}}
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 text-center mb-4 tracking-wide">
                        Pra - Asesmen Mandiri
                    </h1>

                    {{-- 2. Garis Pembatas (Divider Tebal Abu-abu) --}}
                    <div class="w-full border-b-2 border-gray-300 mb-6"></div>

                    {{-- 3. Detail Skema (Dibuat Center agar seimbang dengan Header) --}}
                    <div class="text-center">
                        <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-1">
                            Skema: {{ $skema->nama_skema ?? 'N/A' }}
                        </h2>
                        <p
                            class="text-sm text-gray-500 mt-1 bg-gray-100 inline-block px-3 py-1 rounded-full border border-gray-200">
                            Nomor Skema: <span
                                class="font-mono font-medium text-gray-700">{{ $skema->nomor_skema ?? ($skema->kode_unit ?? '-') }}</span>
                        </p>
                    </div>
                </div>

                @php
                    // Hitung total unit kompetensi
                    $totalUnits = 0;
                    foreach ($skema->kelompokPekerjaan as $kelompok) {
                        $totalUnits += $kelompok->unitKompetensi->count();
                    }
                @endphp

                {{-- LOGIK CEK UNIT KOSONG (Dari kode kita sebelumnya) --}}
                @if ($totalUnits == 0)
                    {{-- TAMPILAN JIKA TIDAK ADA UNIT (PLACEHOLDER) --}}
                    <div
                        class="flex flex-col items-center justify-center py-16 px-4 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                        <div class="bg-gray-100 p-6 rounded-full mb-6">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Unit Kompetensi Belum Tersedia</h3>
                        <p class="text-gray-600 max-w-lg mb-8 leading-relaxed">
                            Mohon maaf, saat ini data Unit Kompetensi untuk skema ini belum ditambahkan.
                        </p>
                        <a href="{{ $backUrl ?? ($sertifikasi ? '/tracker/' . $sertifikasi->id_jadwal : '/dashboard') }}"
                            class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition transform hover:-translate-y-0.5">
                            Kembali ke Tracker
                        </a>
                    </div>
                @else
                    {{-- TAMPILAN FORMULIR APL-02 (Style dimiripkan dengan temanmu) --}}

                    {{-- INFO BOX --}}
                    <p class="text-gray-600 mb-8 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                        Anda diminta untuk melakukan asesmen mandiri. Tandai <strong>(K)</strong> jika Anda Kompeten dan
                        <strong>wajib</strong> mengunggah bukti pendukung. Tandai <strong>(BK)</strong> jika Belum
                        Kompeten.
                    </p>

                    {{-- FORM AJAX KITA --}}
                    <form id="apl02-form" method="POST"
                        action="{{ route('api.v1.apl02.store', ['id_sertifikasi' => $idDataSertifikasi]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $idDataSertifikasi }}">

                        <div id="accordion-container" class="space-y-6">

                            {{-- LOOP KELOMPOK PEKERJAAN --}}
                            @foreach ($skema->kelompokPekerjaan as $kelompok)
                                {{-- LOOP UNIT KOMPETENSI --}}
                                @foreach ($kelompok->unitKompetensi as $unit)
                                    @php
                                        $unitId = 'unit-' . $unit->id_unit_kompetensi;
                                    @endphp

                                    {{-- CONTAINER UNIT (Accordion Style Temanmu) --}}
                                    <div
                                        class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">

                                        {{-- ACCORDION HEADER --}}
                                        <div class="accordion-header p-5 cursor-pointer flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition duration-150"
                                            data-target="{{ $unitId }}">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-xs font-medium text-blue-600 uppercase tracking-wide">UNIT
                                                    KOMPETENSI: {{ $unit->kode_unit }}</span>
                                                <h3 class="text-lg font-bold text-gray-800 mt-1">
                                                    {{ $unit->judul_unit }}
                                                </h3>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300 icon-chevron"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>

                                        {{-- ACCORDION BODY --}}
                                        <div id="{{ $unitId }}"
                                            class="accordion-body hidden border-t border-gray-200">

                                            {{-- SUB-HEADER --}}
                                            <div class="p-5 pb-2">
                                                <p class="text-lg font-bold text-blue-700 mb-2">Dapatkah saya: (Tugas
                                                    Mandiri)</p>
                                            </div>

                                            {{-- TABEL KUK (Style Temanmu) --}}
                                            <div class="overflow-x-auto">
                                                <table
                                                    class="min-w-full bg-white border-collapse border-b border-gray-300">
                                                    <thead>
                                                        <tr
                                                            class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                            <th class="p-3 border-b border-t border-gray-300 w-1/12">No.
                                                            </th>
                                                            <th class="p-3 border-b border-t border-gray-300 w-1/2">
                                                                Elemen / Kriteria Unjuk Kerja</th>
                                                            <th
                                                                class="p-3 border-b border-t border-gray-300 w-1/12 text-center">
                                                                K</th>
                                                            <th
                                                                class="p-3 border-b border-t border-gray-300 w-1/12 text-center">
                                                                BK</th>
                                                            <th
                                                                class="p-3 border-b border-t border-gray-300 w-1/4 text-left pl-6">
                                                                Bukti (Wajib K)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">

                                                        {{-- LOOP ELEMEN --}}
                                                        @foreach ($unit->elemen as $elemen)
                                                            @php $elemenNum = $loop->iteration; @endphp
                                                            <tr class="bg-gray-50/80">
                                                                <td
                                                                    class="p-3 font-bold text-gray-700 border-r border-gray-200 align-top">
                                                                    {{ $elemenNum }}</td>
                                                                <td class="p-3 font-bold text-gray-700 align-top"
                                                                    colspan="4">
                                                                    Elemen: {{ $elemen->elemen }}
                                                                </td>
                                                            </tr>

                                                            {{-- LOOP KRITERIA (KUK) --}}
                                                            @foreach ($elemen->kriteria as $kuk)
                                                                @php
                                                                    $kukId = 'kuk-' . $kuk->id_kriteria;
                                                                    $radioName = 'respon[' . $kuk->id_kriteria . '][k]';
                                                                    $fileId = 'bukti-' . $kuk->id_kriteria;

                                                                    // LOGIC KITA: Ambil History Jawaban
                                                                    $saved =
                                                                        $existingResponses[$kuk->id_kriteria] ?? null;
                                                                    $isKompeten =
                                                                        $saved && $saved->respon_asesi_apl02 == 1;
                                                                    $isBelum =
                                                                        $saved && $saved->respon_asesi_apl02 == 0;
                                                                    $filePath = $saved
                                                                        ? $saved->bukti_asesi_apl02
                                                                        : null;
                                                                @endphp

                                                                <tr class="hover:bg-blue-50/30 transition duration-100">
                                                                    <td
                                                                        class="p-3 text-sm text-gray-600 border-r border-gray-200 align-top">
                                                                        {{ $elemenNum }}.{{ $loop->iteration }}
                                                                    </td>
                                                                    <td class="p-3 text-sm text-gray-700 align-top">
                                                                        <span
                                                                            class="font-mono text-xs text-gray-500 mr-1">({{ $kuk->no_kriteria }})</span>
                                                                        {{ $kuk->kriteria }}
                                                                    </td>

                                                                    {{-- RADIO K (Value 1) - Style Temanmu --}}
                                                                    <td class="p-3 text-center align-top">
                                                                        <input type="radio"
                                                                            id="{{ $kukId }}-k"
                                                                            name="{{ $radioName }}" value="1"
                                                                            class="w-5 h-5 radio-k text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 rounded-full cursor-pointer"
                                                                            onchange="toggleBukti({{ $kuk->id_kriteria }}, true)"
                                                                            {{ $isKompeten ? 'checked' : '' }}
                                                                            required>
                                                                    </td>

                                                                    {{-- RADIO BK (Value 0) - Style Temanmu --}}
                                                                    <td class="p-3 text-center align-top">
                                                                        <input type="radio"
                                                                            id="{{ $kukId }}-bk"
                                                                            name="{{ $radioName }}" value="0"
                                                                            class="w-5 h-5 radio-bk text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 rounded-full cursor-pointer"
                                                                            onchange="toggleBukti({{ $kuk->id_kriteria }}, false)"
                                                                            {{ $isBelum ? 'checked' : '' }}>
                                                                    </td>

                                                                    {{-- INPUT FILE (Logic Kita + Style Temanmu) --}}
                                                                    <td class="p-3 align-top pl-6">
                                                                        <div id="bukti-wrapper-{{ $kuk->id_kriteria }}"
                                                                            class="{{ $isKompeten ? '' : 'hidden-custom' }}">

                                                                            <input type="file"
                                                                                name="respon[{{ $kuk->id_kriteria }}][bukti]"
                                                                                id="file-input-{{ $kuk->id_kriteria }}"
                                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                                class="block w-full text-xs text-slate-500
                                                                                      file:mr-4 file:py-1.5 file:px-3
                                                                                      file:rounded-full file:border-0
                                                                                      file:text-xs file:font-semibold
                                                                                      file:bg-blue-50 file:text-blue-700
                                                                                      hover:file:bg-blue-100 transition duration-150">

                                                                            {{-- Link File Tersimpan (Logic Kita) --}}
                                                                            @if ($filePath)
                                                                                <div
                                                                                    class="mt-2 flex items-center text-xs text-green-600 bg-green-50 p-1.5 rounded-md inline-flex">
                                                                                    <svg class="w-4 h-4 mr-1"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M5 13l4 4L19 7"></path>
                                                                                    </svg>
                                                                                    <span class="mr-1">File
                                                                                        tersimpan.</span>
                                                                                    <a href="{{ asset($filePath) }}"
                                                                                        target="_blank"
                                                                                        class="underline text-blue-600 font-semibold hover:text-blue-800">Lihat</a>
                                                                                </div>
                                                                            @endif

                                                                            <p class="text-red-500 text-xs mt-1 hidden font-medium"
                                                                                id="error-msg-{{ $kuk->id_kriteria }}">
                                                                                Wajib diisi!</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div> {{-- End accordion-container --}}

                        {{-- TOMBOL SIMPAN (Style Temanmu) --}}
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end items-center pb-8">

                            {{-- 2. TOMBOL SIMPAN (Lebar Tetap w-48, Teks "Simpan") --}}
                            <button type="submit" id="btn-submit"
                                class="w-48 py-3 bg-blue-500 text-white font-bold rounded-full shadow-lg hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed text-center">
                                Simpan
                            </button>

                        </div>

                    </form>
                @endif

            </div>
        </main>
    </div>

    {{-- MODAL CUSTOM ALERT (Style Temanmu) --}}
    <div id="message-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm transform transition-all scale-95"
            id="modal-content">
            <h3 id="modal-title" class="text-xl font-bold mb-4 text-gray-800">Pesan</h3>
            <p id="modal-body" class="text-gray-600 mb-6 leading-relaxed"></p>
            <button id="modal-close-button"
                class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">Selanjutnya</button>
        </div>
    </div>

    {{-- JAVASCRIPT (MENGGUNAKAN LOGIC KITA YANG SUDAH STABIL) --}}
    <script>
        // Definisikan URL Redirect (Sesuaikan route ini jika mau ke Tracker atau Jadwal TUK)
        // Kalau mau ke Tracker, ganti route-nya jadi 'asesi.tracker'
        const nextUrl = "{{ route('asesi.tracker', ['jadwal_id' => $sertifikasi->id_jadwal ?? '']) }}";

        // --- BAGIAN 1: LOGIC ACCORDION / DROPDOWN (YANG GAK BOLEH HILANG) ---
        // Ini logic dropdown kamu yang lama, Kak Gem taruh paling atas biar aman.
        document.addEventListener('DOMContentLoaded', function() {

            // A. Logic Accordion
            document.querySelectorAll('.accordion-header').forEach(header => {
                header.addEventListener('click', () => {
                    const targetId = header.dataset.target;
                    const content = document.getElementById(targetId);
                    const icon = header.querySelector('.icon-chevron');

                    // Logic: Tutup yang lain saat satu dibuka
                    document.querySelectorAll('.accordion-body').forEach(body => {
                        if (body.id !== targetId && !body.classList.contains('hidden')) {
                            body.classList.add('hidden');
                            const otherIcon = document.querySelector(
                                `[data-target="${body.id}"] .icon-chevron`);
                            if (otherIcon) otherIcon.classList.remove('rotate-180');
                        }
                    });

                    content.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');

                    // Scroll ke header
                    if (!content.classList.contains('hidden')) {
                        header.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // --- BAGIAN 2: LOGIC SUBMIT DENGAN POP-UP SWEETALERT ---
            const form = document.getElementById('apl02-form');

            if (form) {
                const btnSubmit = document.getElementById('btn-submit');

                form.addEventListener('submit', async (e) => {
                    e.preventDefault(); // Stop submit bawaan browser

                    // A. Validasi Manual (Cek File Kosong pada Pilihan K)
                    let isValid = true;
                    let firstErrorElement = null;
                    const checkedK = form.querySelectorAll('input[type="radio"][value="1"]:checked');

                    checkedK.forEach(radio => {
                        const matches = radio.name.match(/\[(\d+)\]/);
                        if (matches) {
                            const idKriteria = matches[1];
                            const fileInput = document.getElementById(
                                `file-input-${idKriteria}`);
                            const wrapper = document.getElementById(
                                `bukti-wrapper-${idKriteria}`);
                            const errorMsg = document.getElementById(`error-msg-${idKriteria}`);
                            const hasSavedFile = wrapper.querySelector('a[href*="storage"]');

                            if (fileInput.files.length === 0 && !hasSavedFile) {
                                isValid = false;
                                errorMsg.classList.remove('hidden');
                                if (!firstErrorElement) firstErrorElement = wrapper;
                            } else {
                                errorMsg.classList.add('hidden');
                            }
                        }
                    });

                    if (!isValid) {
                        // Pakai SweetAlert untuk Error Validasi
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data Belum Lengkap',
                            text: 'Harap unggah bukti pendukung untuk semua Kriteria yang Anda nyatakan Kompeten (K).',
                            confirmButtonColor: '#d33'
                        });

                        if (firstErrorElement) {
                            firstErrorElement.closest('tr').scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                        return; // Stop di sini kalau tidak valid
                    }

                    // B. POP UP KONFIRMASI (INI YANG BARU)
                    const confirmResult = await Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Pastikan seluruh bukti dan jawaban sudah sesuai. Data tidak dapat diubah setelah disimpan!",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563EB', // Biru
                        cancelButtonColor: '#d33', // Merah
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal, Cek Lagi'
                    });

                    // Kalau pilih batal, berhenti
                    if (!confirmResult.isConfirmed) return;

                    // C. Proses Submit AJAX
                    btnSubmit.disabled = true;
                    const originalBtnText = btnSubmit.innerText;
                    btnSubmit.innerText = 'Menyimpan...'; // Loading text

                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        // Parsing JSON
                        let result;
                        try {
                            result = await response.json();
                        } catch (err) {
                            throw new Error("Terjadi kesalahan server (Response bukan JSON).");
                        }

                        if (response.ok && result.success) {
                            // D. POP UP SUKSES
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: result.message || 'Asesmen Mandiri berhasil disimpan.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Redirect ke Next URL (Tracker)
                            // Menggunakan logic redirect dari JSON respon controller kamu (jika ada id_jadwal)
                            // Atau fallback ke variable nextUrl di atas
                            if (result.id_jadwal) {
                                // Construct URL Tracker manual karena di JS susah pakai route() dinamis
                                // Asumsi base URL tracker adalah /asesi/tracker
                                window.location.href = "{{ url('/asesi/tracker') }}/" + result
                                    .id_jadwal;
                            } else {
                                window.location.href = nextUrl;
                            }

                        } else {
                            throw new Error(result.message || 'Gagal menyimpan data.');
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: error.message,
                            confirmButtonColor: '#d33'
                        });
                    } finally {
                        // Balikin tombol
                        btnSubmit.disabled = false;
                        btnSubmit.innerText = originalBtnText;
                    }
                });
            }
        });

        // --- BAGIAN 3: GLOBAL FUNCTION (BUKTI SHOW/HIDE) ---
        // Ini ditaruh di luar DOMContentLoaded biar bisa dipanggil onclick di HTML
        window.toggleBukti = function(idKriteria, isKompeten) {
            const wrapper = document.getElementById(`bukti-wrapper-${idKriteria}`);
            const errorMsg = document.getElementById(`error-msg-${idKriteria}`);

            if (isKompeten) {
                wrapper.classList.remove('hidden-custom');
            } else {
                wrapper.classList.add('hidden-custom');
                if (errorMsg) errorMsg.classList.add('hidden');
            }
        };
    </script>
</body>

</html>
