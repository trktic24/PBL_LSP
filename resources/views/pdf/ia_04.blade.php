<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.IA.04 - Penjelasan Singkat Proyek</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header-table, .content-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-table td { padding: 5px; vertical-align: top; }
        .content-table th, .content-table td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .content-table th { background-color: #f0f0f0; text-align: center; }
        .title { font-size: 14px; font-weight: bold; margin-bottom: 20px; }
        .bg-gray { background-color: #e0e0e0; }
        .text-center { text-align: center; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
    </style>
</head>
<body>

    <div class="title">FR.IA.04. PENJELASAN SINGKAT PROYEK TERKAIT PEKERJAAN / KEGIATAN TERSTRUKTUR LAINNYA</div>

    {{-- INFO PESERTA --}}
    <table class="header-table" border="1">
        <tr>
            <td width="150"><b>Skema Sertifikasi</b></td>
            <td width="10">:</td>
            <td>{{ $sertifikasi->jadwal->skema->judul_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>TUK</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->masterTuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesor</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->asesor->nama_asesor ?? '-' }}</td>
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
        • Buatlah penjelasan singkat proyek terkait pekerjaan / kegiatan terstruktur lainnya.<br>
        • Validasi bukti-bukti yang disiapkan oleh asesi.
    </div>

    {{-- DAFTAR UNIT KOMPETENSI --}}
    <table class="content-table">
        <tr class="bg-gray">
            <th colspan="2">Unit Kompetensi</th>
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

    {{-- TABEL TUGAS & VALIDASI --}}
    <table class="content-table">
        <thead>
            <tr>
                <th width="5%" rowspan="2">No</th>
                <th width="40%" colspan="2">Daftar Tugas / Kegiatan</th>
                <th width="15%" rowspan="2">Kesesuaian<br>(Ya/Tidak)</th>
                <th width="40%" rowspan="2">Catatan / Umpan Balik</th>
            </tr>
            <tr>
                <th>Hal yang harus disiapkan</th>
                <th>Hal yang harus didemonstrasikan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($points as $index => $point)
                @php
                    $respon = $responses->get($point->id_poin_ia04A);
                    // Cek respon: Bisa string 'ya'/'tidak' atau boolean 1/0
                    $isValid = $respon && (strtolower($respon->respon_poin_ia04A) == 'ya' || $respon->respon_poin_ia04A == 1);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $point->hal_yang_disiapkan }}</td>
                    <td>{{ $point->hal_yang_didemonstrasikan }}</td>
                    <td class="text-center">
                        {{ $isValid ? 'Ya' : 'Tidak' }}
                    </td>
                    <td>
                        {{ $respon->umpan_balik_untuk_asesi ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada poin kegiatan yang dibuat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- REKOMENDASI --}}
    <div style="page-break-inside: avoid;">
        <table class="content-table">
            <tr>
                <td width="30%"><b>Rekomendasi Asesor:</b></td>
                <td>
                    <b>
                        @if($sertifikasi->rekomendasi_IA04B == 'kompeten')
                            Kompeten
                        @elseif($sertifikasi->rekomendasi_IA04B == 'belum_kompeten')
                            Belum Kompeten
                        @else
                            -
                        @endif
                    </b>
                </td>
            </tr>
        </table>

        {{-- TANDA TANGAN --}}
        <table class="header-table" style="margin-top: 30px;">
            <tr>
                <td width="50%" class="text-center">
                    Asesi,<br><br><br><br>
                    <b>{{ $sertifikasi->asesi->nama_lengkap }}</b>
                </td>
                <td width="50%" class="text-center">
                    Asesor,<br><br><br><br>
                    <b>{{ $sertifikasi->jadwal->asesor->nama_asesor ?? '...................' }}</b>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>