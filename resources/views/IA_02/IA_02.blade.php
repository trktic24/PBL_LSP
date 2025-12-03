@use('Carbon\Carbon')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.02 - Tugas Praktik Demonstrasi</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen">
        <div class="h-full overflow-hidden">
            {{-- SIDEBAR --}}
            <x-sidebar2 :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi" />
        </div>

        {{-- CONTENT --}}
        <main class="flex-1 h-full overflow-y-auto" data-sertifikasi-id="{{ $sertifikasi->id_data_sertifikasi_asesi }}">
            <div class="p-8 space-y-10">

                {{-- HEADER --}}
                <div class="p-6 bg-white border-t-4 border-gray-800 rounded-lg shadow-lg">
                    <h1 class="text-3xl font-extrabold text-gray-900">FR.IA.02 - TUGAS PRAKTIK DEMONSTRASI</h1>
                    <p class="text-gray-600 mt-1">Dokumen instruksi untuk Asesi.</p>
                </div>

                {{-- IDENTITAS --}}
                <div class="p-6 bg-white rounded-lg shadow-lg border-2 border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">I. Identitas Skema & Asesi</h2>

                    {{-- TUK --}}
                    <dt class="col-span-1 font-medium text-gray-800">TUK</dt>
                    <dd class="col-span-3 flex flex-wrap gap-x-6 gap-y-2 items-center" id="tuk_container">
                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Sewaktu" disabled
                                class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Sewaktu</span>
                        </label>

                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_TempatKerja" disabled
                                class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Tempat Kerja</span>
                        </label>

                        <label class="flex items-center text-gray-700 cursor-default">
                            <input type="checkbox" id="tuk_Mandiri" disabled
                                class="w-4 h-4 rounded border-gray-300 mr-2">
                            <span>Mandiri</span>
                        </label>
                    </dd>

                    {{-- Skema --}}
                    <dt class="col-span-1 font-medium text-gray-800">Skema</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">:
                        <span id="skema_judul">Memuat...</span>
                    </dd>

                    {{-- Nomor Skema --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nomor Skema</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">:
                        <span id="skema_nomor">Memuat...</span>
                    </dd>

                    {{-- Nama Asesor --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesor</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">:
                        <span id="nama_asesor">Memuat...</span>
                    </dd>

                    {{-- Nama Asesi --}}
                    <dt class="col-span-1 font-medium text-gray-800">Nama Asesi</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">:
                        <span id="nama_asesi">Memuat...</span>
                    </dd>

                    {{-- Tanggal --}}
                    <dt class="col-span-1 font-medium text-gray-800">Tanggal Assesmen</dt>
                    <dd class="col-span-3 text-gray-800 font-semibold">:
                        <span id="tanggal_pelaksanaan">Memuat...</span>
                    </dd>

                </div>

                {{-- PETUNJUK --}}
                <div class="bg-blue-50 border border-blue-300 p-6 rounded shadow-sm">
                    <h2 class="text-lg font-bold text-blue-800 mb-3">A. Petunjuk</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm">
                        <li>Baca instruksi kerja dengan cermat.</li>
                        <li>Tanyakan asesor jika ada hal yang kurang jelas.</li>
                        <li>Laksanakan sesuai SOP/WI (jika ada).</li>
                    </ul>
                </div>

                {{-- SKENARIO & DATA IA02 --}}
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                        B. Skenario Tugas Praktik Demonstrasi
                    </h2>

                    {{-- UNIT --}}
                    <div class="border-2 border-gray-800 rounded-lg overflow-x-auto mb-8 shadow-md">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="p-3 text-center w-1/12">No</th>
                                    <th class="p-3 text-center w-1/4">Kode Unit</th>
                                    <th class="p-3">Judul Unit</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white text-gray-800">
                                @foreach ($daftarUnitKompetensi as $unit)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-center">{{ $loop->iteration }}</td>
                                        <td class="p-3 text-center font-mono">{{ $unit->kode_unit }}</td>
                                        <td class="p-3">{{ $unit->judul_unit }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- SKENARIO IA02 --}}
                    <div id="ia02DataArea" class="space-y-6">
                        <p class="text-gray-500 italic">Memuat data...</p>
                    </div>
                </div>

                {{-- TANDA TANGAN --}}
                <div class="p-6 bg-white rounded-lg shadow-lg border-2 border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">C. Tanda Tangan</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">

                        <div class="border p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4 text-center">Asesi</h3>
                            <p class="mb-2"><strong>Nama:</strong> {{ $sertifikasi->asesi->nama }}</p>
                            <p><strong>Tanggal:</strong> {{ Carbon::now()->isoFormat('D MMMM YYYY') }}</p>

                            <div
                                class="h-32 bg-gray-200 mt-4 rounded flex items-center justify-center italic text-gray-500">
                                Tanda Tangan Digital Asesi
                            </div>
                        </div>

                        <div class="border p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-4 text-center">Asesor</h3>
                            <p class="mb-2"><strong>Nama:</strong> {{ $sertifikasi->asesor->nama }}</p>
                            <p><strong>No. Reg. MET:</strong> {{ $sertifikasi->asesor->no_reg }}</p>

                            <div
                                class="h-32 bg-gray-200 mt-4 rounded flex items-center justify-center italic text-gray-500">
                                Tanda Tangan Digital Asesor
                            </div>
                        </div>

                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-end gap-4 pb-10">
                    <a href="{{ route('ia01.index', $sertifikasi->id_data_sertifikasi_asesi) }}"
                        class="px-6 py-2 bg-white border rounded-full shadow hover:bg-gray-100">
                        Kembali
                    </a>

                    <a href="{{ route('ia03.index', $sertifikasi->id_data_sertifikasi_asesi) }}"
                        class="px-8 py-2 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700">
                        Selanjutnya
                    </a>
                </div>

            </div> <!-- END SCROLLABLE CONTENT -->
        </main> <!-- ❗ DITUTUP DI SINI -->

    </div> <!-- END FLEX WRAPPER -->

    <script>
        const idSertifikasi = "{{ $idSertifikasi }}";
        document.addEventListener("DOMContentLoaded", async function() {
            if (!idSertifikasi) {
                console.error("KRITIS ERROR: idSertifikasi tidak terdefinisi di Blade.");
                return;
            }

            const namaAsesorEl = document.getElementById("nama_asesor");
            // const noRegAsesorEl = document.getElementById("nomor_regis_asesor")
            // const ttdAsesorEl = document.getElementById("ttd_asesor");
            const namaAsesiEl = document.getElementById("nama_asesi");
            // const ttdAsesiEl = document.getElementById("ttd_asesi");
            const skemaJudulEl = document.getElementById("skema_judul");
            const skemaNomorEl = document.getElementById("skema_nomor");

            const tanggalPelaksanaanEl = document.getElementById("tanggal_pelaksanaan");

            const tukSewaktu = document.getElementById("tuk_Sewaktu");
            const tukTempatKerja = document.getElementById("tuk_TempatKerja");
            const tukMandiri = document.getElementById("tuk_Mandiri");

            const ia02Container = document.getElementById("ia02DataArea");

            try {
                const response = await fetch(`/api/v1/ia02/${idSertifikasi}`);
                const resp = await response.json();

                if (!response.ok || !resp.success) {
                    throw new Error(`[${response.status}] ${resp.message || "Gagal memuat data IA02"}`);
                }

                const data = resp.data;

                namaAsesorEl.innerText = data.asesor?.nama_lengkap ?? "-";
                namaAsesiEl.innerText = data.asesi?.nama_lengkap ?? "-";

                skemaJudulEl.innerText = data.skema?.nama_skema ?? "-";
                skemaNomorEl.innerText = data.skema?.nomor_skema ?? "-";

                tanggalPelaksanaanEl.innerText = data.tanggal_assesmen ?? "-";

                // Radio TUK: Pastikan elemen TUK tidak null
                if (tukSewaktu) tukSewaktu.checked = data.tuk === "Sewaktu";
                if (tukTempatKerja) tukTempatKerja.checked = data.tuk === "Tempat Kerja";
                if (tukMandiri) tukMandiri.checked = data.tuk === "Mandiri";

                if (!data.ia02 || data.ia02.length === 0) {
                    ia02Container.innerHTML = `<p class="text-gray-500 italic">Belum ada data IA02.</p>`;
                } else {
                    let html = ``;

                    data.ia02.forEach((row, i) => {
                        html += `
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 shadow-sm">
                            <p class="font-bold mb-2">Instruksi / Skenario ${i + 1}</p>
                            <div class="p-3 bg-white border rounded mb-3 whitespace-pre-line">${row.skenario}</div>

                            <p class="font-bold mb-2">Peralatan</p>
                            <div class="p-3 bg-white border rounded mb-3 whitespace-pre-line">${row.peralatan}</div>

                            <p class="font-bold mb-2">Waktu Pengerjaan</p>
                            <div class="p-3 bg-white border rounded">${row.waktu?.slice(0,5)}</div>
                        </div>`;
                    });

                    ia02Container.innerHTML = html;
                }

            } catch (err) {
                console.error("LOAD IA02 ERROR:", err);
                ia02Container.innerHTML = `<p class="text-red-600">Gagal memuat data IA02: ${err.message}></p>`;
            }

        });
    </script>

</body>

</html>
