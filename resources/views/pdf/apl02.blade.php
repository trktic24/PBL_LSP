<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APL-02 - Asesmen Mandiri</title>
    <style>
        /* ===== Umum ===== */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 16pt; font-weight: bold; margin: 0; }
        .header h2 { font-size: 12pt; margin: 5px 0 15px; }
        .info-box {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        /* ===== Tabel KUK ===== */
        .table-kuk {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .table-kuk th, .table-kuk td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .table-kuk th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .w-10 { width: 10%; }
        .w-20 { width: 20%; }
        .w-50 { width: 50%; }

        /* ===== Tanda Tangan ===== */
        .signature-section {
            width: 100%;
            margin-top: 40px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            padding: 10px;
            text-align: center;
            border: none;
            width: 50%;
        }
        .signature-line {
            height: 70px;
            border-bottom: 1px dashed #555;
            margin-top: 50px;
        }
        .ttd-box {
            width: 100px;
            height: 60px;
            border: none;
            overflow: hidden;
        }        

        /* ===== Cetak PDF ===== */
        @page { margin: 20mm; }
    </style>
</head>
<body>

<div class="container">

    {{-- Header --}}
    <div class="header">
        <h1>FR.APL.02. ASESMEN MANDIRI</h1>
        <h2>Skema Sertifikasi: {{ $skema->nama_skema ?? 'N/A' }}</h2>
        <p style="font-size: 10pt; margin-top: 0;">Nomor Skema: {{ $skema->nomor_skema ?? '-' }}</p>
    </div>

    {{-- Petunjuk --}}
    <div class="info-box">
        <p><strong>Petunjuk:</strong></p>
        <ol style="padding-left: 20px; margin: 5px 0;">
            <li>Baca dan pelajari setiap Kriteria Unjuk Kerja (KUK) pada setiap Unit Kompetensi.</li>
            <li>Beri tanda centang (&#10004;) pada kolom <strong>K</strong> jika kompeten, atau <strong>BK</strong> jika belum kompeten.</li>
            <li>Lengkapi kolom <strong>Bukti</strong> jika Anda menyatakan Kompeten.</li>
        </ol>
    </div>

    {{-- Tabel KUK --}}
    <table class="table-kuk">
        <thead>
            <tr>
                <th rowspan="2" class="w-10">Unit/KUK</th>
                <th rowspan="2" class="w-50">Kriteria Unjuk Kerja</th>
                <th colspan="2" class="w-20">Asesmen Mandiri</th>
                <th rowspan="2" class="w-20">Bukti (V/D/O)</th>
            </tr>
            <tr>
                <th class="w-10">K</th>
                <th class="w-10">BK</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($skema->kelompokPekerjaan as $kelompok)
            @foreach ($kelompok->unitKompetensi as $unit)
                {{-- Unit Kompetensi --}}
                <tr>
                    <td colspan="5" style="font-weight: bold; background-color: #e9e9e9;">
                        ({{ $unit->kode_unit }}) {{ $unit->judul_unit }}
                    </td>
                </tr>

                @foreach ($unit->elemen as $elemen)
                    @php $elemenNum = $loop->iteration; @endphp
                    {{-- Elemen --}}
                    <tr>
                        <td class="text-center" style="font-weight: bold; background-color: #f7f7f7;">E{{ $elemenNum }}</td>
                        <td colspan="4" style="font-weight: bold; background-color: #f7f7f7;">Elemen: {{ $elemen->elemen }}</td>
                    </tr>

                    {{-- KUK --}}
                    @foreach ($elemen->kriteriaUnjukKerja as $kuk)
                        @php
                            $saved = $existingResponses[$kuk->id_kriteria] ?? null;
                            $isKompeten = $saved && $saved->respon_asesi_apl02 == 1;
                            $buktiPath = $saved ? $saved->bukti_asesi_apl02 : null;
                            $buktiNama = $buktiPath ? basename($buktiPath) : '-';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $elemenNum }}.{{ $loop->iteration }}</td>
                            <td><strong>({{ $kuk->no_kriteria }})</strong> {{ $kuk->kriteria }}</td>
                            <td class="text-center">{!! $isKompeten ? '&#10004;' : '' !!}</td>
                            <td class="text-center">{!! !$isKompeten && $saved !== null ? '&#10004;' : '' !!}</td>
                            <td>{{ $isKompeten ? $buktiNama : 'Tidak Wajib' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                {{-- Kolom Asesi --}}
                <td>
                    <p>Asesi:</p>
                    <div class="ttd-box" style="margin: 0 auto;">
                        @if($asesi->tanda_tangan)
                            <img src="{{ public_path($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="width: 100%; height: 100%; object-fit: contain;" />
                        @endif
                    </div>
                    <p style="margin-top: 5px;"><strong>{{ $asesi->nama_lengkap ?? 'Nama Asesi' }}</strong></p>
                    <p style="font-size: 9pt;">
                        {{ $sertifikasi->jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($sertifikasi->tgl_verifikasi_apl02)->format('d F Y') : '....................' }}
                    </p>
                </td>

                {{-- Kolom Asesor --}}
                <td>
                    <p>Asesor:</p>
                    <div class="ttd-box" style="margin: 0 auto;">
                        @if($asesi->tanda_tangan)
                            <img src="{{ public_path($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="width: 100%; height: 100%; object-fit: contain;" />
                        @endif
                    </div>
                    <p style="margin-top: 5px;"><strong>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Nama Asesor' }}</strong></p>
                    <p style="font-size: 9pt;">
                        {{ $sertifikasi->jadwal->tanggal_pelaksanaan ? \Carbon\Carbon::parse($sertifikasi->tgl_verifikasi_apl02)->format('d F Y') : '....................' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>


</div>

</body>
</html>