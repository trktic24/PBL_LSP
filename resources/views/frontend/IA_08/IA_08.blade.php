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

    .error-text {
        color: #dc2626;
        font-size: 13px;
        margin-top: 4px;
    }

    .error-border {
        border: 2px solid #dc2626 !important;
        border-radius: 4px;
    }

    .error-checkbox {
        outline: 2px solid #dc2626;
        outline-offset: 2px;
    }

</style>

<div class="p-6">

    {{-- WRAPPER --}}
    {{-- Membungkus seluruh konten dengan FORM --}}
    <form id="formIA08"
      action="{{ route('ia08.store', ['id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi]) }}"
      method="POST">

        @csrf
        @php
           $locked = $isLocked ?? false;
        @endphp

        {{-- Pastikan ID IA08 atau Data Sertifikasi dikirim --}}
        <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $id_data_sertifikasi_asesi }}">
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
            <div class="section-box overflow-x-auto">
                <table class="w-full border border-gray-300 text-center text-sm">
                    <thead>
                        <tr class="bg-gray-100 font-semibold">
                            <th rowspan="2" class="border border-gray-300 px-3 py-2 text-left w-1/3">Bukti Portofolio</th>
                            <th colspan="2" class="border">Valid</th>
                            <th colspan="2" class="border">Asli</th>
                            <th colspan="2" class="border">Terkini</th>
                            <th colspan="2" class="border">Memadai</th>
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
                    @php $nomorTotal = 1; @endphp

                    @foreach ($buktiPortofolio as $bukti)
                        <input type="hidden" name="id_portofolio[]" value="{{ $bukti->id_portofolio }}">

                        @php
                            $items = [];
                            foreach (['persyaratan_dasar', 'persyaratan_administratif'] as $kolom) {
                                if (!empty($bukti->$kolom)) {
                                    $parts = preg_split('/[\n;,]+/', $bukti->$kolom);
                                    foreach ($parts as $p) {
                                        if (trim($p) !== '') $items[] = trim($p);
                                    }
                                }
                            }
                        @endphp

                        @foreach ($items as $index => $item)
                            @php
                                $cleanItem = preg_replace('/^\d+\.\s*/', '', $item);
                                $uid = $bukti->id_portofolio . '-' . $index;
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="border px-3 py-2 text-left">
                                    <span class="font-semibold">{{ $nomorTotal++ }}.</span> {{ $cleanItem }}
                                </td>

                                {{-- VALID --}}
                                @foreach (['Ya', 'Tidak'] as $val)
                                <td class="border py-2 text-center">
                                    <input type="checkbox"
                                        name="valid[{{ $bukti->id_portofolio }}][{{ $index }}]"
                                        value="{{ $val }}"
                                        class="cb-radio"
                                        data-group="valid-{{ $uid }}"
                                        {{ ($bukti->array_valid[$index] ?? '') === $val ? 'checked' : '' }}
                                        {{ $locked ? 'disabled' : '' }}
                                    >                                        
                                </td>
                                @endforeach

                                {{-- ASLI --}}
                                @foreach (['Ya', 'Tidak'] as $val)
                                <td class="border py-2 text-center">
                                    <input type="checkbox"
                                        name="asli[{{ $bukti->id_portofolio }}][{{ $index }}]"
                                        value="{{ $val }}"
                                        class="cb-radio"
                                        data-group="asli-{{ $uid }}"
                                        {{ ($bukti->array_asli[$index] ?? '') === $val ? 'checked' : '' }}
                                        {{ $locked ? 'disabled' : '' }}
                                    >
                                </td>
                                @endforeach

                                {{-- TERKINI --}}
                                @foreach (['Ya', 'Tidak'] as $val)
                                <td class="border py-2 text-center">
                                    <input type="checkbox"
                                        name="terkini[{{ $bukti->id_portofolio }}][{{ $index }}]"
                                        value="{{ $val }}"
                                        class="cb-radio"
                                        data-group="terkini-{{ $uid }}"
                                        {{ ($bukti->array_terkini[$index] ?? '') === $val ? 'checked' : '' }}
                                        {{ $locked ? 'disabled' : '' }}
                                    >
                                </td>
                                @endforeach

                                {{-- MEMADAI --}}
                                @foreach (['Ya', 'Tidak'] as $val)
                                <td class="border py-2 text-center">
                                    <input type="checkbox"
                                        name="memadai[{{ $bukti->id_portofolio }}][{{ $index }}]"
                                        value="{{ $val }}"
                                        class="cb-radio"
                                        data-group="memadai-{{ $uid }}"
                                        {{ ($bukti->array_memadai[$index] ?? '') === $val ? 'checked' : '' }}
                                        {{ $locked ? 'disabled' : '' }}
                                    >
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>

                </table>
            </div>

        <script>
            function syncRadio(groupClass, selected) {
                document.querySelectorAll('.' + groupClass).forEach(cb => {
                    if (cb !== selected) cb.checked = false;
                });
            }
        </script>

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
                    <input
                        type="checkbox"
                        class="single-check"
                        name="rekomendasi"
                        value="kompeten"
                        id="rek_kompeten"
                        onclick="onlyOne(this)"
                        {{ ($ia08->rekomendasi ?? '') === 'kompeten' ? 'checked' : '' }}
                        {{ $locked ? 'disabled' : '' }}
                    >
                    Asesi telah memenuhi pencapaian seluruh KUK — direkomendasikan <b>KOMPETEN</b>
                </label>

                <label class="flex items-center gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="single-check"
                        name="rekomendasi"
                        value="perlu observasi lanjut"
                        id="rek_tidak"
                        onclick="onlyOne(this)"
                        {{ ($ia08->rekomendasi ?? '') === 'perlu observasi lanjut' ? 'checked' : '' }}
                        {{ $locked ? 'disabled' : '' }}
                    >
                    Asesi belum memenuhi seluruh KUK — direkomendasikan <b>OBSERVASI LANJUT</b> pada:
                </label>

                <div id="blok_lanjut" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label>Kelompok Pekerjaan</label>
                        <input
                            type="text"
                            id="kp"
                            name="kelompok_pekerjaan"
                            class="border border-gray-300 px-3 py-2 rounded-md w-full"
                            value="{{ $ia08->kelompok_pekerjaan ?? '' }}"
                            {{ $locked ? 'disabled' : '' }}
                        >
                    </div>

                    <div>
                        <label>Unit Kompetensi</label>
                        <input
                            type="text"
                            id="unit"
                            name="unit_kompetensi"
                            class="border border-gray-300 px-3 py-2 rounded-md w-full"
                            value="{{ $ia08->unit_kompetensi ?? '' }}"
                            {{ $locked ? 'disabled' : '' }}
                        >
                    </div>

                    <div>
                        <label>Elemen</label>
                        <input
                            type="text"
                            id="elemen"
                            name="elemen"
                            class="border border-gray-300 px-3 py-2 rounded-md w-full"
                            value="{{ $ia08->elemen ?? '' }}"
                            {{ $locked ? 'disabled' : '' }}
                        >
                    </div>

                    <div>
                        <label>KUK</label>
                        <input
                            type="text"
                            id="kuk"
                            name="kuk"
                            class="border border-gray-300 px-3 py-2 rounded-md w-full"
                            value="{{ $ia08->kuk ?? '' }}"
                            {{ $locked ? 'disabled' : '' }}
                        >
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
            </div>

            {{-- BUTTON --}}
            @if(!$locked)
            <div class="flex justify-end mb-16">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 font-semibold">
                    Simpan
                </button>
            </div>
            @endif
        </div>
    </form>
</div>
<script>
/* =====================================================
   GLOBAL LOCK FLAG (DARI BLADE)
===================================================== */
const IS_LOCKED = @json($locked);

/* =====================================================
   REKOMENDASI — CHECKBOX SINGLE SELECT
===================================================== */
function onlyOne(selected) {

    // 🔒 Jika locked, hentikan interaksi
    if (IS_LOCKED) return;

    const group = document.getElementsByName(selected.name);

    group.forEach(el => {
        if (el !== selected) el.checked = false;
    });

    updateLanjutFields();
}

/* =====================================================
   OBSERVASI LANJUT — ENABLE / DISABLE FIELD
===================================================== */
const lanjutFields = ['kp', 'unit', 'elemen', 'kuk'];

/* 🔥 TAMBAHAN: clear field observasi */
function clearLanjutFields() {
    lanjutFields.forEach(id => {
        const field = document.getElementById(id);
        if (!field) return;
        field.value = '';
    });
}

function setLanjutDisabled(isDisabled) {

    // 🔒 Jika locked, JANGAN ubah state field
    if (IS_LOCKED) return;

    lanjutFields.forEach(id => {
        const field = document.getElementById(id);
        if (!field) return;

        field.disabled = isDisabled;

        if (isDisabled) {
            field.classList.add('disabled-field');
        } else {
            field.classList.remove('disabled-field');
        }
    });
}

function updateLanjutFields() {

    // 🔒 Jika locked, hentikan logika JS
    if (IS_LOCKED) return;

    const kompeten = document.getElementById('rek_kompeten')?.checked;
    const tidak    = document.getElementById('rek_tidak')?.checked;

    // ✔ KOMPETEN → disable + KOSONGKAN
    if (kompeten || !tidak) {
        clearLanjutFields();      // 🔥 inti perubahan
        setLanjutDisabled(true);
        return;
    }

    // ✔ OBSERVASI LANJUT
    setLanjutDisabled(false);
}

/* =====================================================
   CHECKBOX BERSIFAT RADIO (BUKTI PORTOFOLIO)
===================================================== */
document.querySelectorAll('.cb-radio').forEach(cb => {
    cb.addEventListener('change', function () {

        // 🔒 Jika locked, cegah perubahan
        if (IS_LOCKED) {
            this.checked = !this.checked;
            return;
        }

        if (!this.checked) return;

        const group = this.dataset.group;
        document
            .querySelectorAll(`.cb-radio[data-group="${group}"]`)
            .forEach(el => {
                if (el !== this) el.checked = false;
            });
    });
});

/* ===============================
   HELPER VALIDATION FUNCTIONS
=============================== */
function clearErrors() {
    document.querySelectorAll('.error-text').forEach(e => e.remove());
    document.querySelectorAll('.error-border').forEach(e => e.classList.remove('error-border'));
    document.querySelectorAll('.error-checkbox').forEach(e => e.classList.remove('error-checkbox'));
}

function showError(element, message) {
    if (!element) return;

    element.classList.add('error-border');

    const msg = document.createElement('div');
    msg.className = 'error-text';
    msg.innerText = message;

    element.parentNode.appendChild(msg);
}

function scrollToElement(el) {
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/* =====================================================
   VALIDASI SUBMIT FORM (FINAL FIX)
===================================================== */
document
    .getElementById('formIA08')
    ?.addEventListener('submit', function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        if (IS_LOCKED) return;

        clearErrors();

        let firstErrorElement = null;

        /* ===============================
           1️⃣ VALIDASI BUKTI PORTOFOLIO
        =============================== */
        const groups = {};

        document.querySelectorAll('.cb-radio').forEach(cb => {
            const group = cb.dataset.group;
            if (!groups[group]) groups[group] = [];
            groups[group].push(cb);
        });

        Object.values(groups).forEach(group => {
            if (!group.some(cb => cb.checked)) {
                group.forEach(cb => cb.classList.add('error-checkbox'));

                if (!firstErrorElement) {
                    firstErrorElement = group[0];
                }
            }
        });

        /* ===============================
           2️⃣ VALIDASI REKOMENDASI ASESOR
        =============================== */
        const rekKompeten = document.getElementById('rek_kompeten');
        const rekTidak    = document.getElementById('rek_tidak');

        const rekomendasiCard = rekKompeten.closest('.section-box');

        // hapus pesan lama jika ada
        rekomendasiCard
           .querySelectorAll('.error-text')
           .forEach(e => e.remove());
           
        if (!rekKompeten.checked && !rekTidak.checked) {

            const msg = document.createElement('div');
            msg.className = 'error-text mt-2';
            msg.innerText = 'Pilih salah satu rekomendasi asesor.';

            // ⬇️ tempel DI BAWAH CARD (seperti sebelum diperbaiki)
            rekomendasiCard.appendChild(msg);
            firstErrorElement ??= rekKompeten;
            }

        /* ===============================
           3️⃣ VALIDASI OBSERVASI LANJUT
        =============================== */
        if (rekTidak.checked) {
            lanjutFields.forEach(id => {
                const field = document.getElementById(id);
                if (!field || field.value.trim() === '') {
                    showError(field, 'Kolom ini wajib diisi.');
                    firstErrorElement ??= field;
                }
            });
        }

        /* ===============================
           ❌ JIKA ADA ERROR
        =============================== */
        if (firstErrorElement) {
            scrollToElement(firstErrorElement);
            return;
        }

        /* ===============================
           ✅ JIKA SEMUA VALID
        =============================== */
        this.submit();
    });
    
/* =====================================================
   INIT
===================================================== */
updateLanjutFields();
</script>

@if($locked)
<script>
/* =====================================================
   HARD READ-ONLY MODE (FINAL)
===================================================== */
document
    .querySelectorAll('input, textarea, select')
    .forEach(el => {
        el.setAttribute('disabled', 'disabled');
        el.setAttribute('readonly', 'readonly');
        el.style.pointerEvents = 'none';
    });
</script>

@endif

@endsection