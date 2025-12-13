@extends('layouts.app-sidebar')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif !important; }

    .single-check { width: 18px; height: 18px; cursor: pointer; }
    .disabled-field { background-color: #f3f4f6 !important; cursor: not-allowed; }

    .bukti-cell { width: 240px; text-align: left !important; }
    .yn-cell { width: 55px; }

    /* ================================
       FONT UNIFORM — FINAL FIX
    ================================= */
    .text-uniform {
        font-size: 15px !important;      /* Semua teks default */
        line-height: 1.45;
    }

    /* JUDUL UTAMA — HANYA INI YANG BESAR */
    .text-uniform h1 {
        font-size: 30px !important;
        font-weight: 700;
        line-height: 1.3;
    }

    /* SECTION TITLE */
    .text-uniform .section-title,
    .text-uniform h2,
    .text-uniform h3 {
        font-size: 17px !important;
        font-weight: 600;
        margin-bottom: 10px;
    }

    /* LABEL */
    .text-uniform .form-label,
    .text-uniform label {
        font-size: 15px !important;
        font-weight: 500;
    }

    /* TABLE TEXT */
    .text-uniform table,
    .text-uniform table th,
    .text-uniform table td {
        font-size: 15px !important;
    }

    /* KETERANGAN KECIL */
    .text-uniform .text-xs {
        font-size: 13px !important;
    }

    /* ================================
       Style Bawaan Kamu (dipertahankan)
    ================================= */
    .section-box {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 32px;
    }

    .section-title {
        color: #111827;
    }

    .form-label {
        margin-bottom: 4px;
        color: #374151;
    }

    .form-input {
        width: 100%;
        border: 1px solid #d1d5db;
        padding: 8px 12px;
        border-radius: 6px;
        background: white;
    }
</style>

<div class="p-6">

    {{-- WRAPPER --}}
    <div class="text-uniform">

        {{-- HEADER --}}
        <div class="relative mb-8">
            <div class="mb-4">
                <img src="{{ asset('images/Logo_BNSP.png') }}"
                    alt="BNSP"
                    class="h-12 object-contain max-w-full">
            </div>

            
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                FR.IA.08 – Ceklis Verifikasi Portofolio
            </h1>
        </div>

        {{-- SKEMA --}}
        <div class="section-box">
            <h2 class="section-title">Skema Sertifikasi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Judul</label>
                    <input type="text" class="form-input"
                        value="{{ $skema->nama_skema ?? '' }}" disabled>
                </div>

                <div>
                    <label class="form-label">Nomor</label>
                    <input type="text" class="form-input"
                        value="{{ $skema->nomor_skema ?? '' }}" disabled>
                </div>
            </div>

            <div class="mt-4">
                <label class="form-label">TUK</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" disabled class="single-check"
                            {{ strtolower($jenisTuk->jenis_tuk ?? '') == 'sewaktu' ? 'checked' : '' }}>
                        Sewaktu
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" disabled class="single-check"
                            {{ strtolower($jenisTuk->jenis_tuk ?? '') == 'tempat kerja' ? 'checked' : '' }}>
                        Tempat Kerja
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <div>
                    <label class="form-label">Nama Asesor</label>
                    <input type="text" class="form-input" value="{{ $asesor->nama_lengkap ?? '' }}" disabled>
                </div>

                <div>
                    <label class="form-label">Nama Asesi</label>
                    <input type="text" class="form-input" value="{{ $asesi->nama_lengkap ?? '' }}" disabled>
                </div>

                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="text" class="form-input"
                        value="{{ old('tanggal_asesmen', $data_sesi['tanggal_asesmen']) }}" disabled>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-2">*Coret yang tidak perlu</p>
        </div>

        {{-- PANDUAN --}}
        <div class="section-box border border-gray-300 bg-gray-50">

            <h3 class="mb-2">PANDUAN BAGI ASESOR</h3>

            <ul class="text-gray-700 space-y-1 list-disc pl-5">
                <li>Verifikasi portofolio dapat dilakukan untuk keseluruhan unit kompetensi atau masing-masing kelompok pekerjaan.</li>
                <li>Isilah bukti portofolio sesuai ketentuan bukti berkualitas dan relevan…</li>
                <li>Lakukan verifikasi berdasarkan aturan bukti.</li>
                <li>Beri tanda centang (√) sesuai hasil verifikasi.</li>
                <li>Jika belum memenuhi aturan bukti, lanjutkan dengan wawancara atau verifikasi pihak ketiga.</li>
            </ul>

        </div>

        {{-- KELOMPOK --}}
        <div class="section-box">
            <h3 class="section-title">Kelompok Pekerjaan</h3>

            <div class="mb-4">
                <div class="form-input bg-white">
                    {{ $kelompokPekerjaan[0]->nama_kelompok_pekerjaan ?? '' }}
                </div>
            </div>

            {{-- TABEL UNIT --}}
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-3 py-2 w-12">No.</th>
                        <th class="border border-gray-300 px-3 py-2">Kode Unit</th>
                        <th class="border border-gray-300 px-3 py-2">Judul Unit</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($unitKompetensi as $index => $unit)
                    <tr>
                        <td class="border border-gray-300 px-3 py-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-3 py-2">{{ $unit->kode_unit }}</td>
                        <td class="border border-gray-300 px-3 py-2">{{ $unit->judul_unit }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TABEL BUKTI PORTOFOLIO --}}
        <div class="section-box">
            <table class="w-full border border-gray-300 text-center text-sm">
                <thead>
                    <tr class="bg-gray-100 font-semibold">
                        <th rowspan="2" class="border border-gray-300 px-3 py-2 text-left w-1/3">
                            Bukti Portofolio
                        </th>
                        <th colspan="2" class="border px-3 py-2 w-20">Valid</th>
                        <th colspan="2" class="border px-3 py-2 w-20">Asli</th>
                        <th colspan="2" class="border px-3 py-2 w-20">Terkini</th>
                        <th colspan="2" class="border px-3 py-2 w-20">Memadai</th>
                    </tr>
                    <tr class="bg-gray-50">
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

                    @php $nomor = 1; @endphp

                    @foreach ($buktiPortofolio as $rowIndex => $bukti)

                        @php
                            $items = [];
                            foreach (['persyaratan_dasar', 'persyaratan_administratif'] as $kolom) {
                                if (!empty($bukti->$kolom)) {
                                    if (is_string($bukti->$kolom) && str_starts_with(trim($bukti->$kolom), '[')) {
                                        $decoded = json_decode($bukti->$kolom, true);
                                        if (is_array($decoded)) {
                                            $items = array_merge($items, $decoded);
                                        }
                                    } else {
                                        $parts = preg_split('/[\n;,]+/', $bukti->$kolom);
                                        $items = array_merge($items, array_filter(array_map('trim', $parts)));
                                    }
                                }
                            }
                            if (count($items) === 0) {
                                $items = ['Tidak ada data'];
                            }
                        @endphp

                        @foreach ($items as $itemIndex => $item)
                            @php
                                $rowID = "row{$rowIndex}_{$itemIndex}";
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="border px-3 py-2 text-left">
                                    <span class="font-semibold">{{ $nomor++ }}.</span>
                                    {{ preg_replace('/^\d+\.\s*/', '', $item) }}
                                </td>

                                {{-- VALID --}}
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_valid" value="ya">
                                </td>
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_valid" value="tidak">
                                </td>

                                {{-- ASLI --}}
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_asli" value="ya">
                                </td>
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_asli" value="tidak">
                                </td>

                                {{-- TERKINI --}}
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_terkini" value="ya">
                                </td>
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_terkini" value="tidak">
                                </td>

                                {{-- MEMADAI --}}
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_memadai" value="ya">
                                </td>
                                <td class="border py-2">
                                    <input type="checkbox" class="radio-like" data-group="{{ $rowID }}_memadai" value="tidak">
                                </td>
                            </tr>
                        @endforeach

                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- JS UNTUK MEMBUAT CHECKBOX BERSIFAT RADIO --}}
        <script>
        document.querySelectorAll('.radio-like').forEach(cb => {
            cb.addEventListener('change', function () {
                const group = this.dataset.group;

                // Uncheck all in this group
                document.querySelectorAll(`.radio-like[data-group="${group}"]`).forEach(x => {
                    if (x !== this) x.checked = false;
                });
            });
        });
        </script>


        {{-- REKOMENDASI --}}
        <div class="section-box">

            <h3 class="font-semibold mb-4">Rekomendasi Asesor</h3>

            <label class="flex items-center gap-2">
                <input type="checkbox" class="single-check" name="rekom" value="kompeten" id="rek_kompeten" onclick="onlyOne(this)">
                Asesi telah memenuhi pencapaian seluruh KUK — direkomendasikan <b>KOMPETEN</b>
            </label>

            <label class="flex items-center gap-2 mt-2">
                <input type="checkbox" class="single-check" name="rekom" value="belum" id="rek_tidak" onclick="onlyOne(this)">
                Asesi belum memenuhi seluruh KUK — direkomendasikan <b>OBSERVASI LANJUT</b> pada:
            </label>

            <div id="blok_lanjut" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label>Kelompok Pekerjaan</label>
                    <input type="text" id="kp" class="border border-gray-300 px-3 py-2 rounded-md w-full">
                </div>

                <div>
                    <label>Unit Kompetensi</label>
                    <input type="text" id="unit" class="border border-gray-300 px-3 py-2 rounded-md w-full">
                </div>

                <div>
                    <label>Elemen</label>
                    <input type="text" id="elemen" class="border border-gray-300 px-3 py-2 rounded-md w-full">
                </div>

                <div>
                    <label>KUK</label>
                    <input type="text" id="kuk" class="border border-gray-300 px-3 py-2 rounded-md w-full">
                </div>
            </div>

        </div>

        {{-- TANDA TANGAN --}}
        <div class="section-box">
            <h2 class="section-title">Tanda Tangan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                {{-- ASESI --}}
                <div>
                    <p class="font-bold">Asesi</p>
                    <p class="mb-4 text-gray-800">{{ now()->format('d-m-Y') }}</p>

                    <div class="border border-gray-300 rounded-lg p-4 h-40 flex items-center justify-center mb-2">
                        @if($asesi->ttd_path)
                            <img src="{{ asset($asesi->ttd_path) }}" class="h-full object-contain">
                        @else
                            <svg width="150" height="80" viewBox="0 0 200 100">
                                <path d="M10,80 Q50,10 90,80 T180,80"
                                      stroke="black" fill="transparent" stroke-width="2"/>
                            </svg>
                        @endif
                    </div>

                    <p class="font-medium text-center">{{ $asesi->nama_lengkap }}</p>
                </div>

                {{-- ASESOR --}}
                <div>
                    <p class="font-bold">Asesor</p>
                    <p>{{ now()->format('d-m-Y') }}</p>

                    <div class="border border-gray-300 rounded-lg p-4 h-40 flex items-center justify-center mt-4">
                        @if($asesor->ttd_path ?? false)
                            <img src="{{ asset($asesor->ttd_path) }}" class="h-full object-contain">
                        @else
                            <svg width="150" height="80" viewBox="0 0 200 100">
                                <path d="M20,50 C50,20 80,80 110,50 S170,20 190,80"
                                      stroke="black" fill="transparent" stroke-width="2"/>
                            </svg>
                        @endif
                    </div>

                    <p class="font-medium mt-2 text-center">{{ $asesor->nama_lengkap }}</p>
                </div>

            </div>

            <p class="text-red-500 text-sm mt-4">* Tanda tangan ini, hanya simulasi.</p>
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end mb-16">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 font-semibold">
                Simpan
            </button>
        </div>

    </div>
</div>

<script>
function onlyOne(selected) {
    let group = document.getElementsByName(selected.name);

    group.forEach(x => { 
        if (x !== selected) x.checked = false; 
    });

    // Update disable/enable setelah memilih
    updateLanjutFields();
}

// Field yang ingin di-disable/enable
const lanjutFields = ["kp", "unit", "elemen", "kuk"];

// Update status field berdasarkan checkbox
function updateLanjutFields() {
    const kompeten = document.getElementById("rek_kompeten").checked;
    const tidak = document.getElementById("rek_tidak").checked;

    // Jika "kompeten" → semua field disable
    if (kompeten) {
        setLanjutDisabled(true);
        return;
    }

    // Jika "observasi lanjut" → field aktif
    if (tidak) {
        setLanjutDisabled(false);
        return;
    }

    // Jika keduanya tidak dicentang → tetap disable
    setLanjutDisabled(true);
}

// Function untuk set disable / enable
function setLanjutDisabled(isDisabled) {
    lanjutFields.forEach(id => {
        const field = document.getElementById(id);
        field.disabled = isDisabled;

        if (isDisabled) {
            field.classList.add("disabled-field");
            field.value = ""; // opsional, supaya kosong saat disable
        } else {
            field.classList.remove("disabled-field");
        }
    });
}

// Set awal (disable semua)
updateLanjutFields();
</script>
 
@endsection
