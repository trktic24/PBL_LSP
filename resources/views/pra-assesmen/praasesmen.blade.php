<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $skema_title ?? 'Pra-Asesmen Mandiri' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        @layer base {

            /* --- PERBAIKAN: Font yang di-set kini Poppins, diikuti fallback sans-serif --- */
            body {
                font-family: 'Poppins', sans-serif;
            }
        }

        /* Custom Logic untuk Checkbox Tailwind Murni */
        .peer:checked~.check-icon {
            display: block;

            /* Custom style untuk radio K/BK */
            .radio-k:checked {
                background-color: #10B981;
                /* Green-500 */
            }

            .radio-bk:checked {
                background-color: #EF4444;
                /* Red-500 */
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    {{-- Mengatur agar sidebar tetap di tempatnya menggunakan kelas Tailwind sticky, top-0, dan h-screen --}}
    <div class="flex min-h-screen">

        {{-- ================================================= --}}
        {{-- BAGIAN 1: SIDEBAR (Komponen X-Sidebar2 Asli Anda) --}}
        <div class="sticky top-0 h-screen">
            {{-- Pastikan data seperti $asesor dan $idAsesi dikirim dari controller --}}
            <x-sidebar2 backUrl="/tracker" :asesorNama="$asesor['nama']" :asesorNoReg="$asesor['no_reg']" :idAsesi="$idAsesi" />
        </div>


        {{-- ================================================= --}}
        {{-- BAGIAN 2: KONTEN UTAMA APL-02 --}}
        <main class="flex-1 p-8 md:p-12 bg-white overflow-y-auto">
            <div class="max-w-6xl mx-auto">

                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Pra - Asesmen Mandiri (APL-02)</h1>
                <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-1">Skema:
                    {{ $currentSkema['judul'] ?? 'N/A' }}</h2>

                @php
                    // PERBAIKAN: Melakukan pengecekan tipe dan isset sebelum memanggil count()
                    $unit_kompetensi_count =
                        (isset($currentSkema['unit']) && is_array($currentSkema['unit'])) ||
                        $currentSkema['unit'] instanceof \Countable
                            ? count($currentSkema['unit'])
                            : 0;
                @endphp

                @if ($error)
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md mt-6"
                        role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ $error }}</span>
                    </div>
                    {{-- MENGGUNAKAN VARIABEL $unit_kompetensi_count yang sudah diamankan --}}
                @elseif ($unit_kompetensi_count === 0)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative shadow-md mt-6"
                        role="alert">
                        <strong class="font-bold">Perhatian!</strong>
                        <span class="block sm:inline">Tidak ada Unit Kompetensi yang terdaftar untuk skema ini.</span>
                    </div>
                @else
                    <p class="text-gray-600 mb-8 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                        Anda diminta untuk melakukan asesmen mandiri. Tandai **(K)** jika Anda Kompeten
                        dan **wajib** mengunggah bukti pendukung. Tandai **(BK)** jika Belum Kompeten.
                    </p>

                    {{-- Kontainer Accordion Unit Kompetensi --}}
                    <div id="accordion-container" class="space-y-4">
                        <form id="apl02-form" method="POST" action="/asesi/respon/apl02/{{ $idDataSertifikasi }}"
                            enctype="multipart/form-data">
                            {{-- CSRF dan Data Sertifikasi --}}
                            @csrf
                            <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $idDataSertifikasi }}">

                            @foreach ($currentSkema['unit'] as $unit)
                                @php
                                    $unitId = 'unit-kompetensi-' . $unit->id_unit_kompetensi;
                                @endphp
                                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                                    {{-- Accordion Header: Unit Kompetensi --}}
                                    <div class="accordion-header p-5 cursor-pointer flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition duration-150"
                                        data-target="{{ $unitId }}">
                                        <div class="flex flex-col">
                                            <p class="text-xs font-medium text-blue-600">UNIT KOMPETENSI:
                                                {{ $unit->kode_unit }}</p>
                                            <h3 class="text-lg font-bold text-gray-800">{{ $unit->judul_unit }}</h3>
                                        </div>
                                        {{-- Icon untuk indikasi buka/tutup --}}
                                        <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-300"
                                            data-icon="{{ $unitId }}" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>

                                    {{-- Accordion Body: Tabel KUK dan Pilihan Asesi --}}
                                    <div id="{{ $unitId }}"
                                        class="accordion-body hidden border-t border-gray-200">
                                        <div class="p-5">
                                            <p class="text-lg font-bold text-blue-700 mb-4">Dapatkah saya: (Tugas
                                                Mandiri)</p>
                                        </div>

                                        {{-- Tabel Utama Unit --}}
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full bg-white border-collapse border-b border-gray-300">
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
                                                            class="p-3 border-b border-t border-gray-300 w-1/4 text-center">
                                                            Bukti (Wajib K)</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    @foreach ($unit->elemen as $elemen)
                                                        @php
                                                            $elemenNum = $loop->iteration;
                                                        @endphp
                                                        {{-- Baris Elemen --}}
                                                        <tr class="bg-gray-50">
                                                            <td
                                                                class="p-3 font-semibold text-gray-700 border-r border-gray-200 align-top">
                                                                {{ $elemenNum }}
                                                            </td>
                                                            <td class="p-3 font-semibold text-gray-700 align-top"
                                                                colspan="4">
                                                                {{ $elemen->elemen }}
                                                            </td>
                                                        </tr>

                                                        {{-- Baris Kriteria Unjuk Kerja (KUK) --}}
                                                        @foreach ($elemen->kriteriaUnjukKerja as $kuk)
                                                            @php
                                                                // ID unik untuk setiap KUK
                                                                $kukId = 'kuk-' . $kuk->id_kriteria;
                                                                $radioName = 'respon[' . $kuk->id_kriteria . ']';
                                                                $fileId = 'bukti-' . $kuk->id_kriteria;
                                                            @endphp
                                                            <tr class="hover:bg-white transition duration-100">
                                                                <td
                                                                    class="p-3 text-sm text-gray-600 border-r border-gray-200 align-top">
                                                                    {{ $elemenNum }}.{{ $loop->iteration }}
                                                                </td>
                                                                <td class="p-3 text-sm text-gray-700 align-top">
                                                                    ({{ $elemenNum }}.{{ $loop->iteration }})
                                                                    {{ $kuk->kriteria_unjuk_kerja }}
                                                                </td>

                                                                {{-- Opsi K --}}
                                                                <td class="p-3 text-center align-top">
                                                                    <input type="radio" id="{{ $kukId }}-k"
                                                                        name="{{ $radioName }}" value="1"
                                                                        class="w-5 h-5 radio-k text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 rounded-full cursor-pointer"
                                                                        data-file-target="{{ $fileId }}"
                                                                        onclick="toggleFileRequired(this, true)">
                                                                </td>

                                                                {{-- Opsi BK (Default Checked) --}}
                                                                <td class="p-3 text-center align-top">
                                                                    <input type="radio" id="{{ $kukId }}-bk"
                                                                        name="{{ $radioName }}" value="0"
                                                                        class="w-5 h-5 radio-bk text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 rounded-full cursor-pointer"
                                                                        onclick="toggleFileRequired(this, false)"
                                                                        checked>
                                                                </td>

                                                                {{-- Input Bukti --}}
                                                                <td class="p-2 text-center align-top">
                                                                    <input type="file"
                                                                        name="bukti[{{ $kuk->id_kriteria }}]"
                                                                        id="{{ $fileId }}"
                                                                        class="w-full text-xs text-gray-500 file:mr-4 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150"
                                                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                                    <small id="{{ $fileId }}-required-msg"
                                                                        class="text-red-500 text-xs mt-1 hidden font-medium">Wajib
                                                                        diisi!</small>
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

                            {{-- Tombol Submit --}}
                            <div class="mt-8 pt-6 border-t border-gray-300 flex justify-end">
                                <button type="submit"
                                    class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300">
                                    Simpan Asesmen Mandiri (APL-02)
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Modal Placeholder for Success/Error Messages (Custom Alert) --}}
            <div id="message-modal"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm">
                    <h3 id="modal-title" class="text-xl font-bold mb-4 text-gray-800">Pesan</h3>
                    <p id="modal-body" class="text-gray-600 mb-6"></p>
                    <button id="modal-close-button"
                        class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Tutup</button>
                </div>
            </div>

        </main>
    </div>

    <script>
        // --- Custom Alert Function ---
        function showCustomAlert(title, message, isError = false) {
            const modal = document.getElementById('message-modal');
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-body').textContent = message;

            const titleElement = document.getElementById('modal-title');
            if (isError) {
                titleElement.classList.remove('text-gray-800');
                titleElement.classList.add('text-red-600');
            } else {
                titleElement.classList.remove('text-red-600');
                titleElement.classList.add('text-gray-800');
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.getElementById('modal-close-button').addEventListener('click', () => {
            document.getElementById('message-modal').classList.remove('flex');
            document.getElementById('message-modal').classList.add('hidden');
        });


        // --- Logic Accordion ---
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.accordion-header').forEach(header => {
                header.addEventListener('click', (e) => {
                    const targetId = header.getAttribute('data-target');
                    const targetBody = document.getElementById(targetId);
                    const targetIcon = header.querySelector(`[data-icon="${targetId}"]`);

                    // Logic: Tutup semua accordion body kecuali yang diklik (jika sedang terbuka)
                    document.querySelectorAll('#accordion-container > div > div.accordion-body')
                        .forEach(body => {
                            const closedId = body.id;
                            const closedIcon = document.querySelector(
                                `[data-icon="${closedId}"]`);

                            // Hanya tutup jika yang lain yang sedang terbuka
                            if (body.id !== targetId && !body.classList.contains('hidden')) {
                                body.classList.add('hidden');
                                if (closedIcon) closedIcon.classList.remove('rotate-180');
                            }
                        });

                    // Toggle visibility of the current target body and rotate icon
                    targetBody.classList.toggle('hidden');
                    targetIcon.classList.toggle('rotate-180');

                    // Scroll ke header yang diklik
                    header.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });
        });

        // --- Logic K/BK dan Bukti (File) ---
        function toggleFileRequired(radio, isRequired) {
            // Mengambil ID KUK dari nama radio: respon[123] -> 123
            const kukIdMatch = radio.name.match(/\[(\d+)\]/);
            if (!kukIdMatch) return;
            const kukId = kukIdMatch[1];
            const fileInput = document.getElementById(`bukti-${kukId}`);
            const requiredMsg = document.getElementById(`bukti-${kukId}-required-msg`);

            // Atur file input required hanya jika K dipilih
            if (isRequired) {
                fileInput.required = true;
                requiredMsg.classList.remove('hidden');
            } else {
                fileInput.required = false;
                requiredMsg.classList.add('hidden');
                // Opsional: reset input file jika pindah ke BK, agar tidak ada file yang terkirim
                fileInput.value = '';
            }
        }

        // Inisialisasi: Pastikan semua BK (default) tidak required
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[type="radio"][value="0"]').forEach(radioBk => {
                // Jalankan pengecekan awal saat DOMContentLoaded untuk data yang sudah tersimpan
                if (radioBk.checked) {
                    toggleFileRequired(radioBk, false);
                }
            });
        });

        // --- Logic Form Submission dengan AJAX (untuk handling upload) ---
        document.getElementById('apl02-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            // 1. Validasi manual untuk K yang belum ada bukti
            let isValid = true;
            document.querySelectorAll('input[type="radio"][value="1"]:checked').forEach(radioK => {
                const kukIdMatch = radioK.name.match(/\[(\d+)\]/);
                if (!kukIdMatch) return;
                const kukId = kukIdMatch[1];
                const fileInput = document.getElementById(`bukti-${kukId}`);
                const requiredMsg = document.getElementById(`bukti-${kukId}-required-msg`);

                // Cek hanya jika K dipilih dan input file diperlukan/required (seharusnya sama)
                if (fileInput.required && fileInput.files.length === 0) {
                    isValid = false;
                    requiredMsg.classList.remove('hidden');
                    // Scroll ke elemen yang bermasalah
                    fileInput.closest('tr').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    requiredMsg.classList.add('hidden');
                }
            });

            if (!isValid) {
                showCustomAlert('Validasi Gagal',
                    'Harap unggah bukti pendukung untuk semua Kriteria Unjuk Kerja yang Anda nyatakan Kompeten (K).',
                    true);
                return;
            }

            // 2. Submission data via Fetch API
            const form = e.target;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');

            // Tampilkan loading state
            submitButton.textContent = 'Menyimpan...';
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                // Pastikan action URL sesuai dengan route Anda (misalnya: /asesi/respon/apl02/{id})
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                });

                // Coba parse response, jika gagal (bukan JSON), kita tangani sebagai error
                let result;
                try {
                    result = await response.json();
                } catch (e) {
                    // Jika response bukan JSON (misal: redirect HTML atau error page)
                    result = {
                        message: 'Respons server tidak valid (bukan JSON). Cek log server.'
                    };
                }


                if (response.ok) {
                    showCustomAlert('Berhasil!', result.message || 'Respon APL-02 berhasil disimpan.', false);
                } else {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                    if (result && result.message) {
                        errorMessage = result.message;
                    } else if (result && result.errors) {
                        errorMessage += "\n" + Object.values(result.errors).flat().join('\n');
                    }
                    showCustomAlert('Gagal Menyimpan', errorMessage, true);
                }

            } catch (error) {
                console.error('Network or Parse Error:', error);
                showCustomAlert('Kesalahan Jaringan', 'Tidak dapat terhubung ke server. Coba lagi.', true);
            } finally {
                // Kembalikan state tombol
                submitButton.textContent = 'Simpan Asesmen Mandiri (APL-02)';
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>
</body>

</html>
