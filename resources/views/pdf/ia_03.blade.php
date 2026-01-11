<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.IA.03 - Pertanyaan Mendukung Observasi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header-table, .content-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-table td { padding: 5px; vertical-align: top; }
        .content-table th, .content-table td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .content-table th { background-color: #f0f0f0; text-align: center; }
        .title { font-size: 14px; font-weight: bold; margin-bottom: 20px; }
        .bg-gray { background-color: #e0e0e0; }
        .text-center { text-align: center; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 14px; } /* Simbol Centang */
    </style>
</head>
<body>

    <div class="title">FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI</div>

    {{-- INFO PESERTA --}}
    <table class="header-table" border="1">
        <tr>
            <td width="150"><b>Skema Sertifikasi</b></td>
            <td width="10">:</td>
            <td>{{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>TUK</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesor</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesi</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->asesi->nama_lengkap }}</td>
        </tr>
        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <div style="margin-bottom: 10px;">
        <b>Panduan bagi Asesor:</b><br>
        Formulir ini diisi oleh asesor kompetensi dengan cara mengajukan pertanyaan kepada asesi.
    </div>

    {{-- DAFTAR UNIT KOMPETENSI --}}
    <table class="content-table">
        <tr class="bg-gray">
            <th colspan="2">Unit Kompetensi yang Diujikan</th>
        </tr>
        <tr class="bg-gray">
            <th>Kode Unit</th>
            <th>Judul Unit</th>
        </tr>
        @foreach($units as $unit)
        <tr>
            <td width="20%">{{ $unit->kode_unit_kompetensi }}</td>
            <td>{{ $unit->judul_unit_kompetensi }}</td>
        </tr>
        @endforeach
    </table>

    {{-- TABEL PERTANYAAN --}}
    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Pertanyaan</th>
                <th width="35%">Tanggapan / Jawaban</th>
                <th width="12%">K</th>
                <th width="12%">BK</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pertanyaanIA03 as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->pertanyaan }}</td>
                    <td>{{ $item->tanggapan ?? '-' }}</td>
                    {{-- Logic Centang K --}}
                    <td class="text-center">
                        @if($item->pencapaian == 1) <span class="check">✔</span> @endif
                    </td>
                    {{-- Logic Centang BK --}}
                    <td class="text-center">
                        @if($item->pencapaian === 0) <span class="check">✔</span> @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada pertanyaan yang diajukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER REKOMENDASI --}}
    <div style="page-break-inside: avoid;">
        <table class="content-table">
            <tr>
                <td width="30%"><b>Umpan Balik untuk Asesi:</b></td>
                <td>{{ $umpanBalik ?: '-' }}</td>
            </tr>
            <tr>
                <td><b>Rekomendasi:</b></td>
                <td><b>{{ $rekomendasi }}</b></td>
            </tr>
        </table>

        {{-- TANDA TANGAN --}}
        <table class="header-table" style="margin-top: 30px;">
            <tr>
                <td width="50%" class="text-center">
                    Asesi,<br><br><br><br>
                    @if($sertifikasi->asesi->tanda_tangan)
                        <img src="{{ getTtdBase64($sertifikasi->asesi->tanda_tangan) }}" style="width: 100px; height: auto;">
                        <br>
                    @else
                        <br><br><br><br>
                    @endif
                    <b>{{ $sertifikasi->asesi->nama_lengkap }}</b>
                </td>
                <td width="50%" class="text-center">
                    Asesor,<br><br><br><br>
                    @if($sertifikasi->jadwal->asesor->tanda_tangan)
                        <img src="{{ getTtdBase64($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="width: 100px; height: auto;">
                        <br>
                    @else
                        <br><br><br><br>
                    @endif
                    <b>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '...................' }}</b>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>