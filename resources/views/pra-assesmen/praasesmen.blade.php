<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $skema_title ?? 'Pra-Asesmen Mandiri' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        body {
            font-family: 'Poppins';
        }

        /* --- Custom Logic untuk Checkbox Tailwind Murni --- */
        .peer:checked~.check-icon {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- Panggil Component Sidebar --}}
        <x-sidebar2 backUrl="/tracker" :asesorNama="$asesor['nama']" :asesorNoReg="$asesor['no_reg']":idAsesi="$idAsesi" />

        <main class="flex-1 p-12 bg-white overflow-y-auto">
            <div class="max-w-4xl mx-auto">

                <h1 class="text-4xl font-bold text-gray-900 mb-2">Pra - Asesmen</h1>
                <h2 class="text-2xl font-semibold text-gray-800 mb-1">{{ $currentSkema['judul'] }}</h2>
                <p class="text-sm text-gray-500 mb-10">Unit Kompetensi {{ $currentSkema['unit'] }} dari
                    {{ $totalSkema }}</p>

                {{-- PANDUAN ASESMEN MANDIRI --}}
                <div class="mb-8 border border-orange-300 rounded-lg overflow-hidden shadow-md">
                    <div class="bg-orange-400 p-3 text-white font-bold text-sm uppercase">
                        PANDUAN ASESMEN MANDIRI
                    </div>
                    <div class="p-4 bg-white">
                        <p class="font-semibold text-gray-700 mb-2 text-sm">Instruksi:</p>
                        <ul class="list-disc ml-6 text-sm text-gray-600 space-y-1">
                            <li>Baca setiap pertanyaan di kolom sebelah kiri.</li>
                            <li>Beri tanda centang (<span class="text-blue-500">K</span>) pada kotak jika Anda yakin
                                dapat melakukan tugas yang dijelaskan.</li>
                            <li>Isi kolom di sebelah kanan dengan mendaftar bukti yang Anda miliki untuk menunjukkan
                                bahwa Anda melakukan tugas-tugas ini.</li>
                        </ul>
                    </div>
                    {{-- ACCORDION CONTAINER (Menggunakan data $skemaList dari Controller) --}}
                    <div id="accordion-container" class="space-y-4">
                        {{-- Loop utama menggunakan data dari Model --}}
                        @foreach ($skemaList as $skema)
                            @php
                                // Logika pembentukan HTML tabel (View Logic) tetap di Blade
                                $unitId = 'unit-' . $skema['unit'];
                                $tableHTML = '';

                                foreach ($skema['elements'] as $element) {
                                    $elementNo = $element['no'];
                                    $groupName = "el_{$skema['unit']}_{$elementNo}";
                                    $buktiOnClick = "showCustomMessage('Simulasi upload bukti untuk Elemen Kompetensi {$elementNo}. Anda telah menandai Kompeten (K).', 'Upload Bukti', 'info')";

                                    $kuksHTML = '';
                                    foreach ($element['kuks'] as $kukIndex => $kukText) {
                                        $kukNo = $kukIndex + 1;
                                        $kuksHTML .= "<p class='text-xs text-gray-600 pl-4'>({$elementNo}.{$kukNo}) {$kukText}</p>";
                                    }

                                    // Building the table row
                                    $tableHTML .= "
                                <tr class='hover:bg-gray-50 transition-colors'>
                                    <td class='px-6 py-4 align-top w-3/4 text-sm text-gray-700'>
                                        <div class='flex items-start'>
                                            <span class='font-bold text-gray-900 text-base mr-4'>{$elementNo}</span>
                                            <div class='space-y-2'>
                                                <p class='text-sm font-semibold text-gray-900 mb-2'>{$element['name']}</p>
                                                <div class='space-y-1'>
                                                    {$kuksHTML}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class='px-3 py-4 align-top border-l border-gray-200 text-center'>
                                        <label for='{$groupName}_k' class='flex justify-center cursor-pointer pt-1 relative'>
                                            {{-- INPUT K --}}
                                            <input type='checkbox'
                                                id='{$groupName}_k'
                                                data-group='{$groupName}'
                                                data-type='k'
                                                onchange='handleCheckboxToggle(this)'
                                                class='peer absolute opacity-0 w-6 h-6 z-10'
                                                title='Kompeten'
                                            >
                                            {{-- CUSTOM BOX & CENTANG (Tailwind Murni) --}}
                                            <div class='w-6 h-6 border border-gray-400 rounded-md transition-colors peer-checked:bg-blue-500 peer-checked:border-blue-500'></div>
                                            <svg class='check-icon hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 text-white pointer-events-none' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'>
                                                <polyline points='20 6 9 17 4 12'></polyline>
                                            </svg>
                                        </label>
                                    </td>
                                    <td class='px-3 py-4 align-top border-l border-gray-200 text-center'>
                                        <label for='{$groupName}_bk' class='flex justify-center cursor-pointer pt-1 relative'>
                                            {{-- INPUT BK --}}
                                            <input type='checkbox'
                                                id='{$groupName}_bk'
                                                data-group='{$groupName}'
                                                data-type='bk'
                                                onchange='handleCheckboxToggle(this)'
                                                class='peer absolute opacity-0 w-6 h-6 z-10'
                                                title='Belum Kompeten'
                                            >
                                            {{-- CUSTOM BOX & SILANG (Tailwind Murni - Menggunakan warna Merah untuk BK) --}}
                                            <div class='w-6 h-6 border border-gray-400 rounded-md transition-colors peer-checked:bg-red-500 peer-checked:border-red-500'></div>
                                            <svg class='check-icon hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 text-white pointer-events-none' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'>
                                                <line x1='18' y1='6' x2='6' y2='18'></line><line x1='6' y1='6' x2='18' y2='18'></line>
                                            </svg>
                                        </label>
                                    </td>
                                    <td class='px-6 py-4 align-top border-l border-gray-200 text-center'>
                                        <button id='bukti_btn_{$groupName}'
                                                onclick=\"{$buktiOnClick}\"
                                                disabled
                                                class='text-xs font-semibold py-1 px-2 mt-1 rounded-md bg-gray-200 border border-gray-300 text-gray-600 transition-all opacity-50 cursor-not-allowed active:scale-95'>
                                            File Bukti
                                        </button>
                                    </td>
                                </tr>
                            ";
                                }
                            @endphp

                            {{-- Accordion Item Wrapper --}}
                            <div class="rounded-xl overflow-hidden shadow-xl border border-blue-200">
                                {{-- Header (Tombol Dropdown) --}}
                                <div data-target="{{ $unitId }}"
                                    class="flex items-center justify-between p-4 md:p-6 text-gray-800 transition-colors duration-300 bg-blue-100 hover:bg-blue-200 cursor-pointer">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-lg font-extrabold text-blue-700">{{ $skema['unit'] }}.</span>
                                        <div>
                                            <p class="font-semibold text-base md:text-lg">{{ $skema['judul'] }}</p>
                                            <p class="text-xs text-gray-600">{{ $skema['kode'] }}</p>
                                        </div>
                                    </div>
                                    <svg data-icon="{{ $unitId }}"
                                        class="w-6 h-6 transform transition-transform text-blue-700" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                {{-- Body (Konten Tabel K/BK) --}}
                                <div id="{{ $unitId }}" class="hidden bg-white px-4 pt-0 pb-4">
                                    <div class="p-2">
                                        <h3 class="text-xl font-bold text-gray-700 mb-2">Tabel Asesmen Mandiri</h3>
                                        <p class="text-sm text-gray-500 mb-4">Unit: {{ $skema['judul'] }}
                                            ({{ $skema['kode'] }})
                                        </p>

                                        {{-- K/BK Table --}}
                                        <div
                                            class="shadow-xl border border-gray-200 rounded-xl overflow-hidden mb-8 mt-4">
                                            <table class="min-w-full bg-white">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-sm font-bold text-gray-800 w-3/4">
                                                            Dapatkah saya ....?</th>
                                                        <th scope="col"
                                                            class="px-3 py-3 text-center text-sm font-bold text-gray-800 w-12 border-l border-gray-200">
                                                            K</th>
                                                        <th scope="col"
                                                            class="px-3 py-3 text-center text-sm font-bold text-gray-800 w-12 border-l border-gray-200">
                                                            BK</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-center text-sm font-bold text-gray-800 w-1/4 border-l border-gray-200">
                                                            Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    {!! $tableHTML !!} {{-- Render tabel baris yang telah dibuat --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-12 mb-12">
                        <button
                            onclick="showCustomMessage('Formulir Asesmen Mandiri telah disubmit. (Simulasi)', 'Berhasil Disubmit', 'success')"
                            class="bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg shadow-xl hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-4 focus:ring-indigo-300">
                            Simpan dan Lanjutkan Tanda Tangan
                        </button>
                    </div>

                </div>

        </main>
        {{-- ========================================================================================= --}}
        {{-- CUSTOM MODAL (Client-side UI Logic) --}}
        {{-- ========================================================================================= --}}
        <div id="custom-modal"
            class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4"
            onclick="this.classList.add('hidden')">
            <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all duration-300"
                onclick="event.stopPropagation()">
                <div id="modal-header" class="px-6 py-4 rounded-t-xl text-white font-bold text-lg"></div>
                <div class="p-6">
                    <p id="modal-message" class="text-gray-700 mb-4"></p>
                    <div class="flex justify-end">
                        <button id="modal-close"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================================= --}}
        {{-- JAVASCRIPT LOGIC (Hanya untuk interaksi UI) --}}
        {{-- ========================================================================================= --}}
        <script>
            /**
             * Menampilkan pesan kustom (Modal).
             */
            function showCustomMessage(message, title = 'Informasi', type = 'info') {
                const modal = document.getElementById('custom-modal');
                const header = document.getElementById('modal-header');
                const messageEl = document.getElementById('modal-message');
                const closeBtn = document.getElementById('modal-close');

                messageEl.textContent = message;
                header.textContent = title;

                // Set styling based on type
                header.className = 'px-6 py-4 rounded-t-xl text-white font-bold text-lg';
                closeBtn.className = 'px-4 py-2 rounded-lg transition-colors';

                if (type === 'success') {
                    header.classList.add('bg-blue-500');
                    closeBtn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
                } else if (type === 'error') {
                    header.classList.add('bg-red-500');
                    closeBtn.classList.add('bg-red-600', 'hover:bg-red-700', 'text-white');
                } else { // info/default
                    header.classList.add('bg-blue-500');
                    closeBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white');
                }

                modal.classList.remove('hidden');
                closeBtn.onclick = () => modal.classList.add('hidden');
            }

            /**
             * Mengatur logika eksklusif K/BK dan mengaktifkan tombol Bukti.
             */
            function handleCheckboxToggle(currentCheckbox) {
                const isChecked = currentCheckbox.checked;
                const isK = currentCheckbox.getAttribute('data-type') === 'k';
                const groupName = currentCheckbox.getAttribute('data-group');

                const kCheckbox = document.getElementById(`${groupName}_k`);
                const bkCheckbox = document.getElementById(`${groupName}_bk`);
                const buktiButton = document.getElementById(`bukti_btn_${groupName}`);

                // 1. Logika Eksklusif (Hanya bisa pilih K atau BK)
                if (isChecked) {
                    const otherCheckbox = isK ? bkCheckbox : kCheckbox;
                    if (otherCheckbox) {
                        otherCheckbox.checked = false;
                    }
                }

                // 2. Logika Tombol Bukti (Aktif hanya jika K dicentang)
                if (buktiButton) {
                    const isKChecked = kCheckbox.checked;

                    if (isKChecked) {
                        // Aktifkan tombol (Warna Biru)
                        buktiButton.disabled = false;
                        buktiButton.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-200', 'text-gray-600');
                        buktiButton.classList.add('bg-blue-200', 'hover:bg-blue-300', 'text-blue-800');
                    } else {
                        // Non-aktifkan tombol (Warna Abu-abu)
                        buktiButton.disabled = true;
                        buktiButton.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-200', 'text-gray-600');
                        buktiButton.classList.remove('bg-blue-200', 'hover:bg-blue-300', 'text-blue-800');
                    }
                }
            }

            /**
             * Tambahkan Event Listener untuk Toggle Accordion.
             */
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-target]').forEach(header => {
                    header.addEventListener('click', (e) => {
                        const targetId = header.getAttribute('data-target');
                        const targetBody = document.getElementById(targetId);
                        const targetIcon = header.querySelector(`[data-icon="${targetId}"]`);

                        // Logic: Tutup semua yang terbuka, lalu buka target
                        document.querySelectorAll('#accordion-container > div > div:nth-child(2)')
                            .forEach(body => {
                                if (body.id !== targetId && !body.classList.contains('hidden')) {
                                    body.classList.add('hidden');
                                    const closedIcon = document.querySelector(
                                        `[data-icon="${body.id}"]`);
                                    if (closedIcon) closedIcon.classList.remove('rotate-180');
                                }
                            });

                        // Toggle visibility of the current target body and rotate icon
                        targetBody.classList.toggle('hidden');
                        targetIcon.classList.toggle('rotate-180');

                        // Scroll ke header yang diklik
                        header.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    });
                });
            });
        </script>
</body>

</html>
