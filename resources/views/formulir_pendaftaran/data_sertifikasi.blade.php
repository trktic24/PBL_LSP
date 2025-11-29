@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sertifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Meta CSRF Token wajib ada untuk request Ajax --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Custom scrollbar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-50 md:bg-gray-100 font-sans">

    {{-- WRAPPER UTAMA --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- ====================================================================== --}}
        {{-- 1. SIDEBAR (Desktop Only - TIDAK DIUBAH) --}}
        {{-- ====================================================================== --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi" backUrl="/" />
        </div>

        {{-- ====================================================================== --}}
        {{-- 2. HEADER MOBILE (UPDATED: Z-Index & Padding) --}}
        {{-- ====================================================================== --}}
        @php
            // Logic Gambar biar rapi saat dipassing ke component
            $gambarSkema =
                $sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar
                    ? asset('images/' . $sertifikasi->jadwal->skema->gambar)
                    : null;
        @endphp

        <x-mobile_header :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'" :code="$sertifikasi->jadwal->skema->kode_unit ?? ($sertifikasi->jadwal->skema->nomor_skema ?? '-')" :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'" :image="$gambarSkema" backUrl="/" />

        {{-- Foto Profil Floating --}}
        <div class="absolute left-1/2 transform -translate-x-1/2 -bottom-10 z-30">
            <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                @if ($sertifikasi && $sertifikasi->jadwal && $sertifikasi->jadwal->skema)
                    <img src="{{ asset('images/' . ($sertifikasi->jadwal->skema->gambar ?? 'default_skema.jpg')) }}"
                        alt="Logo Skema" class="w-full h-full object-cover"
                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($sertifikasi->jadwal->skema->nama_skema) }}&background=0D8ABC&color=fff'">
                @else
                    <img src="https://ui-avatars.com/api/?name=Skema&background=0D8ABC&color=fff" alt="Logo"
                        class="w-full h-full object-cover">
                @endif
            </div>
        </div>
    </div>

    {{-- ====================================================================== --}}
    {{-- 3. MAIN CONTENT --}}
    {{-- ====================================================================== --}}
    {{-- z-0 biar dia posisinya di bawah header --}}
    <main class="flex-1 w-full relative md:p-12 md:overflow-y-auto bg-gray-50 md:bg-white z-0"
        data-sertifikasi-id="{{ $id_sertifikasi_untuk_js }}">

        {{-- CONTAINER RESPONSIF --}}
        <div class="max-w-4xl mx-auto mt-16 md:mt-0 p-6 md:p-0 transition-all">

            {{-- A. STEPPER --}}
            <div class="hidden md:flex items-center justify-center mb-12">
                {{-- Desktop Stepper --}}
                <div class="flex flex-col items-center">
                    <div
                        class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        1</div>
                </div>
                <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                <div class="flex flex-col items-center">
                    <div
                        class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                        2</div>
                </div>
                <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                <div class="flex flex-col items-center">
                    <div
                        class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                        3</div>
                </div>
            </div>

            <div class="flex items-center justify-center mb-8 md:hidden">
                {{-- Mobile Stepper (Kuning) --}}
                <div class="flex flex-col items-center">
                    <div
                        class="w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md">
                        1</div>
                </div>
                <div class="w-16 h-1.5 bg-[#FFF4C3] mx-1"></div>
                <div class="flex flex-col items-center">
                    <div
                        class="w-12 h-12 bg-[#FFF4C3] rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">
                        2</div>
                </div>
                <div class="w-16 h-1.5 bg-[#FFF4C3] mx-1"></div>
                <div class="flex flex-col items-center">
                    <div
                        class="w-12 h-12 bg-[#FFF4C3] rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">
                        3</div>
                </div>
            </div>

            {{-- JUDUL HALAMAN --}}
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 text-center md:text-left">Data Sertifikasi</h1>
            <p class="text-gray-600 mb-8 text-sm md:text-base text-center md:text-left">
                Pilih Tujuan Asesmen serta Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi yang anda
                ajukan.
            </p>

            {{-- B. INFO BOX --}}
            <div class="bg-[#F9F6E6] border-[#EAE5C8] md:bg-amber-50 md:border-amber-200 border rounded-lg p-6 mb-8">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Skema Sertifikasi / Klaster Asesmen</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4">
                    <div class="flex md:contents">
                        <div class="w-24 md:w-auto text-sm font-medium text-gray-600">Judul</div>
                        <div class="md:col-span-2 text-sm text-gray-900 font-medium md:font-normal" id="skema-judul">:
                            ...Memuat data...</div>
                    </div>
                    <div class="flex md:contents">
                        <div class="w-24 md:w-auto text-sm font-medium text-gray-600">Nomor</div>
                        <div class="md:col-span-2 text-sm text-gray-900 font-mono" id="skema-nomor">: ...Memuat data...
                        </div>
                    </div>
                </div>
            </div>

            {{-- C. FORM TUJUAN ASESMEN --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Tujuan Asesmen</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach (['sertifikasi' => 'Sertifikasi', 'PKT' => 'Pengakuan Kompetensi Terkini (PKT)', 'RPL' => 'Rekognisi Pembelajaran Lampau (RPL)', 'lainnya' => 'Lainnya'] as $val => $label)
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="tujuan_asesmen" value="{{ $val }}"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- D. TABEL UNIT KOMPETENSI (UPDATED: NO SCROLL) --}}
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                {{-- Hapus 'overflow-x-auto' agar tabel tidak discroll, tapi teks turun ke bawah --}}
                <div class="border border-gray-200 rounded-lg shadow-sm">
                    <table class="w-full table-fixed md:table-auto divide-y divide-gray-200">
                        {{-- Header Hitam di Mobile, Abu di Desktop --}}
                        <thead class="bg-black md:bg-gray-50 text-white md:text-gray-500">
                            <tr>
                                {{-- Lebar kolom disesuaikan untuk mobile --}}
                                <th
                                    class="px-2 md:px-6 py-3 text-center text-[10px] md:text-xs font-bold uppercase tracking-wider w-8 md:w-16">
                                    No</th>
                                <th
                                    class="px-2 md:px-6 py-3 text-left text-[10px] md:text-xs font-bold uppercase tracking-wider w-20 md:w-32">
                                    Kode</th>
                                <th
                                    class="px-2 md:px-6 py-3 text-left text-[10px] md:text-xs font-bold uppercase tracking-wider">
                                    Judul Unit</th>
                                {{-- Kolom jenis standar bisa disembunyikan di HP kalau terlalu sempit, tapi di sini kita keep --}}
                                <th
                                    class="px-2 md:px-6 py-3 text-center text-[10px] md:text-xs font-bold uppercase tracking-wider w-16 md:w-32">
                                    Jenis</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="unit-kompetensi-body">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex justify-end items-center pb-10 md:pb-0">
                <button type="button" id="btn-selanjutnya"
                    data-next-url="{{ route('bukti.pemohon', ['id_sertifikasi' => $id_sertifikasi_untuk_js]) }}"
                    class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed w-full md:w-auto">
                    Selanjutnya
                </button>
            </div>

        </div>
    </main>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainElement = document.querySelector('main[data-sertifikasi-id]');
            let sertifikasiId = null;
            const elJudulSkema = document.getElementById('skema-judul');
            const elNomorSkema = document.getElementById('skema-nomor');
            const tujuanCheckboxes = document.querySelectorAll('.tujuan-checkbox');
            const btnSelanjutnya = document.getElementById('btn-selanjutnya');

            if (mainElement && mainElement.dataset.sertifikasiId) {
                sertifikasiId = mainElement.dataset.sertifikasiId;
            } else {
                alert('Terjadi error: ID Sertifikasi tidak ditemukan.');
                return;
            }

            fetch(`/api/v1/data-sertifikasi/detail/${sertifikasiId}`)
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data) {
                        const data = response.data;
                        if (data.jadwal && data.jadwal.skema) {
                            elJudulSkema.textContent = ': ' + (data.jadwal.skema.nama_skema || '-');
                            elNomorSkema.textContent = ': ' + (data.jadwal.skema.nomor_skema || '-');
                            renderTabelUnit(data.jadwal.skema.unit_kompetensi);
                        } else {
                            elJudulSkema.textContent = ': Data tidak ditemukan';
                            elNomorSkema.textContent = ': -';
                        }
                        if (data.tujuan_asesmen) {
                            tujuanCheckboxes.forEach(cb => {
                                if (cb.value === data.tujuan_asesmen) cb.checked = true;
                            });
                        }
                    }
                })
                .catch(err => {
                    console.error('Gagal load data:', err);
                    elJudulSkema.textContent = ': Gagal memuat';
                });

            tujuanCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', (e) => {
                    const clicked = e.target;
                    tujuanCheckboxes.forEach(cb => {
                        if (cb !== clicked) cb.checked = false;
                    });
                });
            });

            btnSelanjutnya.addEventListener('click', async function() {
                const selected = Array.from(tujuanCheckboxes).find(cb => cb.checked);
                if (!selected) {
                    alert('Mohon pilih salah satu Tujuan Asesmen sebelum melanjutkan.');
                    return;
                }
                const originalText = this.textContent;
                this.textContent = 'Menyimpan...';
                this.disabled = true;

                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                    const response = await fetch('/api/v1/data-sertifikasi', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            id_data_sertifikasi_asesi: sertifikasiId,
                            tujuan_asesmen: selected.value
                        })
                    });
                    const result = await response.json();
                    if (response.ok && result.success) {
                        window.location.href = this.dataset.nextUrl;
                    } else {
                        throw new Error(result.message || 'Gagal menyimpan data.');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan: ' + error.message);
                    this.textContent = originalText;
                    this.disabled = false;
                }
            });

            // UPDATED: Render Table Unit agar responsive tanpa scroll
            function renderTabelUnit(units) {
                const tbody = document.getElementById('unit-kompetensi-body');
                tbody.innerHTML = '';
                if (units && units.length > 0) {
                    units.forEach((unit, index) => {
                        const row = `
                            <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} border-b border-gray-100">
                                <td class="px-2 md:px-6 py-3 text-center text-xs md:text-sm text-gray-900 font-medium align-top">${index + 1}</td>
                                <td class="px-2 md:px-6 py-3 text-xs md:text-sm text-gray-700 font-mono align-top break-words">${unit.kode_unit || '-'}</td>
                                <td class="px-2 md:px-6 py-3 text-xs md:text-sm text-gray-700 align-top whitespace-normal leading-snug">${unit.judul_unit || '-'}</td>
                                <td class="px-2 md:px-6 py-3 text-center text-xs md:text-sm text-gray-700 align-top">${unit.jenis_standar || '-'}</td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                } else {
                    tbody.innerHTML =
                        `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada unit kompetensi terdaftar.</td></tr>`;
                }
            }
        });
    </script>

</body>

</html>
