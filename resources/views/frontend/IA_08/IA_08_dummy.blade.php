@extends('layouts.app-sidebar') 

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif !important; }
    /* style untuk menandakan field readonly/disabled tetap terlihat */
    input[readonly], input[disabled], textarea[readonly], textarea[disabled] {
        background-color: #f9fafb;
        color: #111827;
    }
</style>

<!-- WRAPPER UNTUK MEMBERI JARAK DARI SIDEBAR -->
<div class="p-6 lg:ml-64 lg:mr-6">

    {{-- HEADER --}}
    <div class="mb-4">
        <img src="/images/bnsp.png" class="h-14 mb-3" alt="BNSP Logo">
        
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-wide leading-tight">
            FR.IA.08 – Ceklis Verifikasi Portofolio
        </h1>
    </div>


    {{-- INFO SKEMA --}}
    <div class="border border-gray-300 p-4 rounded-md bg-white mb-6">

        <h2 class="text-lg font-semibold mb-3">
            Skema Sertifikasi (KKNI/Okupasi/Klaster)*
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">Judul :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" value="Teknisi Jaringan Komputer (Level 2)" readonly>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Nomor :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" value="TJK-2025-001" readonly>
            </div>
        </div>

        {{-- TUK --}}
        <div class="mt-4">
            <label class="text-sm font-medium text-gray-700">TUK :</label>

            <div class="flex items-center gap-6 mt-2 text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" checked disabled> Sewaktu
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" disabled> Tempat Kerja
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" disabled> Mandiri
                </label>
            </div>
        </div>

        {{-- Nama Asesor / Asesi --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
            <div>
                <label class="text-sm font-medium text-gray-700">Nama Asesor :</label>
                <input type="text" id="namaAsesor" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesor-field" value="Drs. Budi Santoso, M.Kom">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Nama Asesi :</label>
                <input type="text" id="namaAsesi" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesi-field" value="Ayu Prameswari" readonly>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Tanggal :</label>
                <input type="text" id="tanggalAsesi" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesi-field" value="{{ date('d-m-Y') }}" readonly>
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-3">*Coret yang tidak perlu</p>
    </div>

    {{-- PANDUAN --}}
    <div class="border border-gray-300 bg-gray-50 p-4 rounded-md mb-6">
        <h3 class="font-semibold text-gray-800 mb-2">PANDUAN BAGI ASESOR</h3>
        <ul class="text-sm text-gray-700 space-y-1 list-disc pl-5">
            <li>Verifikasi portofolio dapat dilakukan untuk keseluruhan unit kompetensi atau masing-masing kelompok pekerjaan.</li>
            <li>Isilah bukti portofolio sesuai ketentuan bukti berkualitas dan relevan dengan standar kompetensi kerja.</li>
            <li>Lakukan verifikasi berdasarkan aturan bukti.</li>
            <li>Beri tanda centang (√) sesuai hasil verifikasi.</li>
            <li>Jika belum memenuhi aturan bukti, lanjutkan dengan wawancara atau verifikasi pihak ketiga.</li>
        </ul>
    </div>

    {{-- KELOMPOK PEKERJAAN --}}
    <div class="border border-gray-300 bg-white p-4 rounded-md mb-6">

        <h3 class="font-semibold mb-1">Kelompok Pekerjaan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" class="border border-gray-300 rounded-md px-3 py-2 text-sm asesor-field" value="Instalasi Jaringan Lokal (LAN)">
            <input type="text" class="border border-gray-300 rounded-md px-3 py-2 text-sm asesor-field" value="Pemeliharaan & Troubleshooting">
        </div>

        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-3 py-2 w-12">No.</th>
                    <th class="border border-gray-300 px-3 py-2">Kode Unit</th>
                    <th class="border border-gray-300 px-3 py-2">Judul Unit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-3 py-2">1.</td>
                    <td class="border px-3 py-2">TJK.U01</td>
                    <td class="border px-3 py-2">Mempersiapkan dan Menginstal Perangkat Jaringan</td>
                </tr>
                <tr>
                    <td class="border px-3 py-2">2.</td>
                    <td class="border px-3 py-2">TJK.U02</td>
                    <td class="border px-3 py-2">Pemeliharaan dan Troubleshooting Jaringan</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- TABEL BUKTI PORTOFOLIO --}}
    <div class="border border-gray-300 bg-white p-4 rounded-md mb-6">

        <table class="min-w-full border border-gray-300 text-sm text-center">
            <thead>
                <tr class="bg-gray-100 font-semibold">
                    <th rowspan="2" class="border px-3 py-2 text-left w-32">Bukti Portofolio:</th>
                    <th colspan="2" class="border px-3 py-2">Valid</th>
                    <th colspan="2" class="border px-3 py-2">Asli</th>
                    <th colspan="2" class="border px-3 py-2">Terkini</th>
                    <th colspan="2" class="border px-3 py-2">Memadai</th>
                </tr>
                <tr class="bg-gray-50 text-xs">
                    <th class="border px-2 py-1">Ya</th>
                    <th class="border px-2 py-1">Tidak</th>
                    <th class="border px-2 py-1">Ya</th>
                    <th class="border px-2 py-1">Tidak</th>
                    <th class="border px-2 py-1">Ya</th>
                    <th class="border px-2 py-1">Tidak</th>
                    <th class="border px-2 py-1">Ya</th>
                    <th class="border px-2 py-1">Tidak</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="border px-3 py-2 text-left">1. CV & Portofolio</td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 text-left">2. Sertifikat Pelatihan</td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 text-left">3. Laporan Proyek</td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                </tr>

                <tr>
                    <td class="border px-3 py-2 text-left">4. Foto / Bukti Kerja</td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-2 py-2"><input type="checkbox" class="asesor-field"></td>
                </tr>
            </tbody>
        </table>

    </div>

    {{-- WAWANCARA --}}
    <div class="border border-gray-300 bg-white p-4 rounded-md mb-6">
        <p class="font-semibold mb-4">
            Sebagai tindak lanjut dari hasil verifikasi bukti, substansi materi berikut harus diklarifikasi selama wawancara:
        </p>

        <table class="min-w-full border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2 w-16">Ceklist</th>
                    <th class="border px-3 py-2">No. Unit</th>
                    <th class="border px-3 py-2">Elemen</th>
                    <th class="border px-3 py-2">Materi/Substansi Wawancara</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-3 py-2 text-center"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-3 py-2">TJK.U01</td>
                    <td class="border px-3 py-2">Elemen 1</td>
                    <td class="border px-3 py-2">Penjelasan langkah instalasi kabel UTP dan konfigurasi dasar switch</td>
                </tr>
                <tr>
                    <td class="border px-3 py-2 text-center"><input type="checkbox" class="asesor-field" checked></td>
                    <td class="border px-3 py-2">TJK.U02</td>
                    <td class="border px-3 py-2">Elemen 2</td>
                    <td class="border px-3 py-2">Troubleshooting koneksi antar VLAN</td>
                </tr>
                <tr>
                    <td class="border px-3 py-2 text-center"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-3 py-2">TJK.U02</td>
                    <td class="border px-3 py-2">Elemen 3</td>
                    <td class="border px-3 py-2">Pemeriksaan dokumentasi jaringan</td>
                </tr>
                @for ($i=4;$i<=6;$i++)
                <tr>
                    <td class="border px-3 py-2 text-center"><input type="checkbox" class="asesor-field"></td>
                    <td class="border px-3 py-2"></td>
                    <td class="border px-3 py-2"></td>
                    <td class="border px-3 py-2"></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    {{-- BUKTI TAMBAHAN --}}
    <div class="border border-gray-300 bg-white p-4 rounded-md mb-6">
        <h3 class="font-semibold mb-2">Bukti tambahan diperlukan pada unit/elemen sebagai berikut:</h3>
        <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm h-24 asesor-field">Bukti tambahan: Foto topologi jaringan, Laporan praktik.</textarea>
    </div>

    {{-- REKOMENDASI --}}
    <div class="border border-gray-300 bg-white p-4 rounded-md mb-6">

        <h3 class="font-semibold mb-4">Rekomendasi Asesor</h3>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" class="asesor-field" checked>
            Asesi telah memenuhi pencapaian seluruh KUK — direkomendasikan <b>KOMPETEN</b>
        </label>

        <label class="flex items-center gap-2 text-sm mt-2">
            <input type="checkbox" class="asesor-field">
            Asesi belum memenuhi pencapaian seluruh KUK — direkomendasikan OBSERVASI LANJUT pada:
        </label>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="text-sm">Kelompok Pekerjaan</label>
                <input type="text" class="border px-3 py-2 rounded-md text-sm w-full asesor-field" value="Instalasi Jaringan Lokal (LAN)">
            </div>
            <div>
                <label class="text-sm">Unit Kompetensi</label>
                <input type="text" class="border px-3 py-2 rounded-md text-sm w-full asesor-field" value="TJK.U01">
            </div>
            <div>
                <label class="text-sm">Elemen</label>
                <input type="text" class="border px-3 py-2 rounded-md text-sm w-full asesor-field" value="Elemen 1">
            </div>
            <div>
                <label class="text-sm">KUK</label>
                <input type="text" class="border px-3 py-2 rounded-md text-sm w-full asesor-field" value="KUK 1.1">
            </div>
        </div>

    </div>

    {{-- TANDA TANGAN --}}
    <div class="border border-gray-300 bg-white p-4 rounded-md">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ASESİ --}}
            <div>
                <h3 class="font-semibold">Asesi</h3>

                <label class="text-sm">Nama :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesi-field" value="Ayu Prameswari" readonly>

                <label class="text-sm mt-3 block">Upload Tanda Tangan :</label>
                <input type="file" accept="image/*" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesi-field" disabled>

                <label class="text-sm mt-3 block">Tanggal :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesi-field" value="{{ date('d-m-Y') }}" readonly>
            </div>

            {{-- ASESOR --}}
            <div>
                <h3 class="font-semibold">Asesor</h3>

                <label class="text-sm">Nama :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesor-field" value="Drs. Budi Santoso, M.Kom">

                <label class="text-sm mt-3 block">No. Reg. MET :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesor-field" value="MET-99812">

                <label class="text-sm mt-3 block">Upload Tanda Tangan :</label>
                <input type="file" accept="image/*" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesor-field">

                <label class="text-sm mt-3 block">Tanggal :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm asesor-field" value="{{ date('d-m-Y') }}" readonly>
            </div>

        </div>

    </div>

    {{-- BUTTON --}}
    <div class="flex justify-end mt-6">
        <button id="btnToggle" class="px-6 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 text-sm font-semibold">
            Simpan
        </button>
    </div>

</div> <!-- END WRAPPER -->
@endsection

{{-- SCRIPT --}}
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnToggle');
    if (!btn) return;

    const asesorFields = Array.from(document.querySelectorAll('.asesor-field'));
    let locked = false;

    asesorFields.forEach(f => f.removeAttribute('disabled'));

    btn.addEventListener('click', function () {
        if (!locked) {
            asesorFields.forEach(f => f.setAttribute('disabled', true));

            btn.textContent = 'Edit';
            btn.classList.remove('bg-blue-600');
            btn.classList.add('bg-green-600');
            locked = true;
        } else {
            asesorFields.forEach(f => f.removeAttribute('disabled'));

            btn.textContent = 'Simpan';
            btn.classList.add('bg-blue-600');
            btn.classList.remove('bg-green-600');
            locked = false;
        }
    });
});
</script>
@endsection
