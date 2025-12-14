@extends('layouts.app-sidebar')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif !important; }

    /* Checkbox kotak tapi hanya bisa pilih satu */
    .single-check {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Field nonaktif saat rekomendasi KOMPETEN */
    .disabled-field {
        background-color: #f3f4f6 !important;
        cursor: not-allowed;
    }

    /* »» PERBAIKAN: Kolom bukti portofolio ukuran MEDIUM */
    .bukti-cell {
        min-width: 200px;      /* ukuran medium */
        width: 230px;
        text-align: left !important;
        padding-left: 12px !important;
    }

    /* »» PERBAIKAN: Kolom Ya / Tidak */
    .yn-cell {
        width: 55px;
    }

    /* Section box normal */
    .section-box {
        background-color: white;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        border-radius: 6px;
    }
</style>

<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-4">
        <img src="/images/bnsp.png" class="h-12 mb-2" alt="BNSP Logo">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-wide">
            FR.IA.08 – Ceklis Verifikasi Portofolio
        </h1>
    </div>

    {{-- SKEMA SERTIFIKASI --}}
    <div class="section-box">
        <h2 class="text-lg font-semibold mb-3">
            Skema Sertifikasi (KKNI/Okupasi/Klaster)*
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">Judul :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Nomor :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
        </div>

        <div class="mt-4">
            <label class="text-sm font-medium text-gray-700">TUK :</label>

            <div class="flex items-center gap-6 mt-2 text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="single-check" name="tuk" value="sewaktu" onclick="onlyOne(this)">
                    Sewaktu
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" class="single-check" name="tuk" value="tempat_kerja" onclick="onlyOne(this)">
                    Tempat Kerja
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" class="single-check" name="tuk" value="mandiri" onclick="onlyOne(this)">
                    Mandiri
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
            <div>
                <label class="text-sm font-medium text-gray-700">Nama Asesor :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Nama Asesi :</label>
                <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Tanggal :</label>
                <input type="date" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-3">*Coret yang tidak perlu</p>
    </div>

    {{-- PANDUAN --}}
    <div class="section-box border border-gray-300 bg-gray-50">

        <h3 class="font-semibold text-gray-800 mb-2">PANDUAN BAGI ASESOR</h3>

        <ul class="text-sm text-gray-700 space-y-1 list-disc pl-5">
            <li>Verifikasi portofolio dapat dilakukan untuk keseluruhan unit kompetensi atau masing-masing kelompok pekerjaan.</li>
            <li>Isilah bukti portofolio sesuai ketentuan bukti berkualitas dan relevan…</li>
            <li>Lakukan verifikasi berdasarkan aturan bukti.</li>
            <li>Beri tanda centang (√) sesuai hasil verifikasi.</li>
            <li>Jika belum memenuhi aturan bukti, lanjutkan dengan wawancara atau verifikasi pihak ketiga.</li>
        </ul>

    </div>

    {{-- KELOMPOK PEKERJAAN --}}
    <div class="section-box">

        <h3 class="font-semibold mb-1">Kelompok Pekerjaan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" class="border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Kelompok pekerjaan 1">
            <input type="text" class="border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="Kelompok pekerjaan 2">
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
                @for ($i = 1; $i <= 2; $i++)
                <tr>
                    <td class="border px-3 py-2">{{ $i }}.</td>
                    <td class="border px-3 py-2"></td>
                    <td class="border px-3 py-2"></td>
                </tr>
                @endfor
            </tbody>
        </table>

    </div>

    {{-- BUKTI PORTOFOLIO --}}
    <div class="section-box">

        <table class="min-w-full border border-gray-300 text-sm text-center">

            <thead>
                <tr class="bg-gray-100 font-semibold">
                    <th rowspan="2" class="border px-3 py-2 bukti-cell text-left">Bukti Portofolio:</th>
                    <th colspan="2" class="border px-3 py-2">Valid</th>
                    <th colspan="2" class="border px-3 py-2">Asli</th>
                    <th colspan="2" class="border px-3 py-2">Terkini</th>
                    <th colspan="2" class="border px-3 py-2">Memadai</th>
                </tr>

                <tr class="bg-gray-50 text-xs">
                    <th class="border px-2 py-1 yn-cell">Ya</th>
                    <th class="border px-2 py-1 yn-cell">Tidak</th>

                    <th class="border px-2 py-1 yn-cell">Ya</th>
                    <th class="border px-2 py-1 yn-cell">Tidak</th>

                    <th class="border px-2 py-1 yn-cell">Ya</th>
                    <th class="border px-2 py-1 yn-cell">Tidak</th>

                    <th class="border px-2 py-1 yn-cell">Ya</th>
                    <th class="border px-2 py-1 yn-cell">Tidak</th>
                </tr>
            </thead>

            <tbody>
                @for ($i = 1; $i <= 4; $i++)
                <tr>
                    <td class="border px-3 py-2 bukti-cell">{{ $i }}.</td>

                    {{-- VALID --}}
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="valid_{{$i}}" value="ya" onclick="onlyOne(this)"></td>
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="valid_{{$i}}" value="tidak" onclick="onlyOne(this)"></td>

                    {{-- ASLI --}}
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="asli_{{$i}}" value="ya" onclick="onlyOne(this)"></td>
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="asli_{{$i}}" value="tidak" onclick="onlyOne(this)"></td>

                    {{-- TERKINI --}}
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="terkini_{{$i}}" value="ya" onclick="onlyOne(this)"></td>
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="terkini_{{$i}}" value="tidak" onclick="onlyOne(this)"></td>

                    {{-- MEMADAI --}}
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="memadai_{{$i}}" value="ya" onclick="onlyOne(this)"></td>
                    <td class="border py-2 yn-cell"><input type="checkbox" class="single-check" name="memadai_{{$i}}" value="tidak" onclick="onlyOne(this)"></td>

                </tr>
                @endfor
            </tbody>

        </table>

    </div>

    {{-- REKOMENDASI --}}
    <div class="section-box">

        <h3 class="font-semibold mb-4">Rekomendasi Asesor</h3>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" class="single-check" name="rekom" value="kompeten" id="rek_kompeten" onclick="onlyOne(this)">
            Asesi telah memenuhi pencapaian seluruh KUK — direkomendasikan <b>KOMPETEN</b>
        </label>

        <label class="flex items-center gap-2 text-sm mt-2">
            <input type="checkbox" class="single-check" name="rekom" value="belum" id="rek_tidak" onclick="onlyOne(this)">
            Asesi belum memenuhi seluruh KUK — direkomendasikan OBSERVASI LANJUT pada:
        </label>

        <div id="blok_lanjut" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="text-sm">Kelompok Pekerjaan</label>
                <input type="text" id="kp" class="border border-gray-300 px-3 py-2 rounded-md text-sm w-full">
            </div>

            <div>
                <label class="text-sm">Unit Kompetensi</label>
                <input type="text" id="unit" class="border border-gray-300 px-3 py-2 rounded-md text-sm w-full">
            </div>

            <div>
                <label class="text-sm">Elemen</label>
                <input type="text" id="elemen" class="border border-gray-300 px-3 py-2 rounded-md text-sm w-full">
            </div>

            <div>
                <label class="text-sm">KUK</label>
                <input type="text" id="kuk" class="border border-gray-300 px-3 py-2 rounded-md text-sm w-full">
            </div>
        </div>

    </div>

    {{-- TANDA TANGAN --}}
    <div class="section-box">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <h3 class="font-semibold mb-2">Asesi</h3>
                <p class="text-sm font-semibold">{{ $asesi->nama ?? 'Nama Asesi' }}</p>
                <p class="text-sm text-gray-600">{{ date('d-m-Y') }} (tanggal)</p>

                <div class="mt-4 border border-gray-400 h-32 rounded-md flex items-center justify-center text-xs text-gray-400">
                    Tanda Tangan di sini (Simulasi)
                </div>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Asesor</h3>
                <p class="text-sm font-semibold">{{ $asesor->nama ?? 'Nama Asesor' }}</p>
                <p class="text-sm text-gray-600">{{ date('d-m-Y') }} (tanggal)</p>

                <div class="mt-4 border border-gray-400 h-32 rounded-md flex items-center justify-center text-xs text-gray-400">
                    Tanda Tangan di sini (Simulasi)
                </div>
            </div>

        </div>
    </div>

    {{-- BUTTON --}}
    <div class="flex justify-end mt-4">
        <button class="px-6 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 text-sm font-semibold">
            Simpan
        </button>
    </div>

</div>

{{-- JAVASCRIPT --}}
<script>

function onlyOne(selected) {
    let group = document.getElementsByName(selected.name);
    group.forEach(x => {
        if (x !== selected) x.checked = false;
    });
}

const lanjutFields = ["kp", "unit", "elemen", "kuk"];

document.getElementById("rek_kompeten").addEventListener("change", function() {
    setLanjutDisabled(true);
});

document.getElementById("rek_tidak").addEventListener("change", function() {
    setLanjutDisabled(false);
});

function setLanjutDisabled(isDisabled) {
    lanjutFields.forEach(id => {
        const field = document.getElementById(id);
        field.disabled = isDisabled;
        field.classList.toggle("disabled-field", isDisabled);
    });
}

</script>

@endsection
