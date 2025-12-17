@use('Carbon\Carbon')

{{-- =================================================== --}}
{{-- BAGIAN LOGIC PHP --}}
{{-- =================================================== --}}
@php
    // 1. Ambil data terakhir dari Collection $daftarIa02 yang dikirim Controller
    $ia02SingleRecord = isset($daftarIa02) ? $daftarIa02->last() : null;

    // 2. Cek Admin/Asesor
    $isAdmin = auth()->check() && auth()->user()->role && in_array(auth()->user()->role->id, [1, 3, 4]);

    // 3. Unit Kompetensi
    $daftarUnitKompetensi = $daftarUnitKompetensi ?? ($sertifikasi->jadwal->skema->unitKompetensi ?? collect());
@endphp
{{-- =================================================== --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.02 - Tugas Praktik Demonstrasi</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        @layer base {
            body {
                font-family: 'Poppins', sans-serif;
            }
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background-color: white !important;
            }

            .max-w-6xl {
                max-width: 100% !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        {{-- SIDEBAR --}}
        <div class="h-full overflow-hidden no-print">
            <x-sidebar2 :idAsesi="$sertifikasi->asesi->id_asesi ?? null" :sertifikasi="$sertifikasi ?? null" />
        </div>

        {{-- CONTENT --}}
        <main class="flex-1 p-8 md:p-12 bg-white overflow-y-auto">
            <div class="max-w-6xl mx-auto">

                {{-- JUDUL --}}
                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 text-center mb-4 tracking-wide">
                        FR.IA.02 - TUGAS PRAKTIK DEMONSTRASI
                    </h1>
                    <div class="w-full border-b-2 border-gray-300 mb-6"></div>
                </div>

                {{-- Notifikasi --}}
                @if (session('success'))
                    <div
                        class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 mb-6 rounded shadow-sm no-print">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="ml-2">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 mb-6 rounded shadow-sm no-print">
                        <strong class="font-bold">Error!</strong>
                        <span class="ml-2">{{ session('error') }}</span>
                    </div>
                @endif


                {{-- Informasi Asesmen (IDENTITAS) --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        I. Identitas Skema & Asesi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Skema Sertifikasi</label>
                                <p class="text-gray-900 font-semibold mt-1" id="skema_judul">
                                    {{ $sertifikasi->jadwal->skema->nama_skema ?? 'Tidak Ditemukan' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">No. Skema Sertifikasi</label>
                                <p class="text-gray-900 font-semibold mt-1" id="skema_nomor">
                                    {{ $sertifikasi->jadwal->skema->nomor_skema ?? 'Tidak Ditemukan' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Asesor</label>
                                <p class="text-gray-900 font-semibold mt-1" id="nama_asesor">
                                    {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Tidak Ditemukan' }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Asesi</label>
                                <p class="text-gray-900 font-semibold mt-1" id="nama_asesi">
                                    {{ $sertifikasi->asesi->nama_lengkap ?? 'Tidak Ditemukan' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Pelaksanaan</label>
                                <p class="text-gray-900 font-semibold mt-1" id="tanggal_pelaksanaan">
                                    {{ Carbon::parse($sertifikasi->jadwal->tanggal_assesmen ?? now())->isoFormat('D MMMM YYYY') }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">TUK</label>
                                <div class="flex flex-wrap gap-4 mt-2" id="tuk_container">
                                    @php
                                        $tuk = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? '';
                                    @endphp
                                    <label class="flex items-center text-gray-900 font-medium text-sm cursor-default">
                                        <input type="checkbox" disabled {{ $tuk == 'Sewaktu' ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                                        Sewaktu
                                    </label>
                                    <label class="flex items-center text-gray-900 font-medium text-sm cursor-default">
                                        <input type="checkbox" disabled {{ $tuk == 'Tempat Kerja' ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-gray-300 mr-2 opacity-100 cursor-default">
                                        Tempat Kerja
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PETUNJUK --}}
                <div class="bg-blue-50 border border-blue-300 p-6 rounded shadow-sm mb-6">
                    <h2 class="text-lg font-bold text-blue-800 mb-3">A. Petunjuk</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm">
                        <li>Baca instruksi kerja dengan cermat.</li>
                        <li>Tanyakan asesor jika ada hal yang kurang jelas.</li>
                        <li>Laksanakan sesuai SOP/WI (jika ada).</li>
                    </ul>
                </div>

                {{-- SKENARIO & DATA IA02 --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                            B. Unit Kompetensi & Skenario
                        </h2>

                        {{-- TABEL UNIT KOMPETENSI --}}
                        <div class="bg-gray-50 border-b border-gray-200 p-4 rounded-xl mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                            <div class="overflow-x-auto rounded-xl border border-gray-300 shadow-sm">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr
                                            class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            <th class="p-3 border-b border-gray-300 w-16 text-center">No</th>
                                            <th class="p-3 border-b border-gray-300 w-1/4">Kode Unit</th>
                                            <th class="p-3 border-b border-gray-300">Judul Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($daftarUnitKompetensi as $i => $unit)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="p-3 text-center font-medium text-gray-700">
                                                    {{ $loop->iteration }}</td>
                                                <td class="p-3 font-mono text-gray-700">{{ $unit->kode_unit ?? '-' }}
                                                </td>
                                                <td class="p-3 text-gray-700">{{ $unit->judul_unit ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="p-6 text-center text-gray-500 italic">Tidak
                                                    ada unit
                                                    kompetensi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- DATA SKENARIO IA02 (Diambil via JS) --}}
                        <div id="ia02DataArea" class="space-y-6">
                            <p class="text-gray-500 italic p-4 text-center border rounded-xl bg-gray-50">Memuat data
                                Skenario, Peralatan, dan Waktu...</p>
                        </div>

                        {{-- Form untuk Admin/Asesor --}}
                        @if ($isAdmin)
                            <div class="mt-8 p-6 border-2 border-dashed border-red-300 rounded-xl bg-red-50 no-print">
                                <h3 class="text-lg font-bold text-red-700 mb-4">Admin/Asesor Input Form (IA.02)</h3>
                                
                                <form action="{{ route('ia02.store', $sertifikasi->id_data_sertifikasi_asesi) }}"
                                    method="POST" class="space-y-4">
                                    @csrf

                                    {{-- Input Skenario --}}
                                    <div>
                                        <label class="font-bold text-sm text-gray-700">Instruksi / Skenario <span class="text-red-500">*</span></label>
                                        <textarea name="skenario" rows="6"
                                            class="mt-2 w-full border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan skenario tugas praktik baru..."></textarea>
                                    </div>

                                    {{-- Input Peralatan --}}
                                    <div>
                                        <label class="font-bold text-sm text-gray-700">Perlengkapan & Peralatan <span class="text-red-500">*</span></label>
                                        <textarea name="peralatan" rows="3"
                                            class="mt-2 w-full border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan peralatan yang dibutuhkan..."></textarea>
                                    </div>

                                    {{-- Input Waktu --}}
                                    <div>
                                        <label class="font-bold text-sm text-gray-700">Waktu (HH:mm) <span class="text-red-500">*</span></label>
                                        <input type="time" name="waktu"
                                            class="mt-2 border border-gray-300 rounded shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 w-full md:w-1/3">
                                    </div>

                                    <div class="flex justify-end pt-4">
                                        <button type="submit"
                                            class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                                            Tambah Instruksi Baru ✓
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- TOMBOL NAVIGASI SUDAH DIHAPUS SESUAI REQUEST --}}

            </div>
        </main>

    </div>
    
    {{-- JAVASCRIPT LOGIC --}}
    <script>
        const idSertifikasi = "{{ $sertifikasi->id_data_sertifikasi_asesi ?? 'null' }}";
        const apiUrl = `/api/v1/ia02/${idSertifikasi}/data`;

        document.addEventListener("DOMContentLoaded", async function() {
            const ia02Container = document.getElementById("ia02DataArea");

            if (idSertifikasi === "null" || !idSertifikasi) {
                ia02Container.innerHTML =
                    `<p class="text-red-600 p-4 text-center border rounded-xl bg-red-50">Gagal memuat data: ID Sertifikasi tidak ditemukan.</p>`;
                return;
            }

            try {
                const response = await fetch(apiUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (!response.ok) {
                    throw new Error(`[${response.status}] Gagal memuat data.`);
                }

                const resp = await response.json();

                if (!resp.success) {
                    throw new Error(resp.message || "Gagal memuat data IA02");
                }

                const listIa02 = resp.data.ia02 || [];

                if (listIa02.length === 0) {
                    ia02Container.innerHTML =
                        `<p class="text-gray-500 italic p-4 text-center border rounded-xl bg-gray-100">Belum ada data skenario IA02 yang diinput oleh asesor.</p>`;
                    return;
                }

                let html = ``;

                listIa02.forEach((row, i) => {
                    const waktuFormatted = row.waktu ? row.waktu.slice(0, 5) : '-';

                    html += `
                    <div class="border border-gray-300 rounded-xl p-6 bg-gray-100 shadow-inner mb-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-300 pb-2">
                            Instruksi / Skenario #${i + 1}
                        </h4>

                        <div class="mb-4">
                            <p class="font-semibold text-sm text-gray-700 mb-1">Instruksi / Skenario:</p>
                            <div class="p-3 bg-white border border-gray-300 rounded whitespace-pre-line text-gray-700 min-h-[50px] shadow-sm">
                                ${row.skenario ?? '-'}
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="font-semibold text-sm text-gray-700 mb-1">Perlengkapan & Peralatan:</p>
                            <div class="p-3 bg-white border border-gray-300 rounded whitespace-pre-line text-gray-700 min-h-[50px] shadow-sm">
                                ${row.peralatan ?? '-'}
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <p class="font-semibold text-sm text-gray-700">Waktu:</p>
                            <div class="py-1 px-3 bg-white border border-gray-300 rounded font-mono text-gray-700 text-base shadow-sm">
                                ${waktuFormatted} WIB
                            </div>
                        </div>
                    </div>`;
                });

                ia02Container.innerHTML = html;

            } catch (err) {
                console.error("LOAD IA02 ERROR:", err);
                ia02Container.innerHTML =
                    `<p class="text-red-600 p-4 text-center border rounded-xl bg-red-50">Gagal memuat data Skenario: ${err.message}</p>`;
            }
        });
    </script>

</body>

</html>