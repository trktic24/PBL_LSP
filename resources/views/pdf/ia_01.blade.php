<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.IA.01 - Ceklis Observasi Aktivitas</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header-table, .content-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-table td { padding: 5px; vertical-align: top; }
        .content-table th, .content-table td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .content-table th { background-color: #f0f0f0; text-align: center; }
        .title { font-size: 14px; font-weight: bold; margin-bottom: 20px; }
        .bg-gray { background-color: #e0e0e0; }
        .text-center { text-align: center; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 14px; } /* Untuk simbol centang */
    </style>
</head>
<body>

    <div class="title">FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI</div>

    {{-- INFO PESERTA --}}
    <table class="header-table" border="1">
        <tr>
            <td width="150"><b>Skema Sertifikasi</b></td>
            <td width="10">:</td>
            <td>{{ $skema->judul_skema ?? $skema->nama_skema }}</td>
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
        Lakukan pengamatan terhadap kinerja asesi dan catat hasil observasi pada kolom yang tersedia.
    </div>

    {{-- TABEL PENILAIAN --}}
    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Elemen & Kriteria Unjuk Kerja (KUK)</th>
                <th width="25%">Benchmark (SOP / Spesifikasi Produk)</th>
                <th width="5%">K</th>
                <th width="5%">BK</th>
                <th width="25%">Penilaian Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units as $unit)
                {{-- HEADER UNIT --}}
                <tr class="bg-gray">
                    <td colspan="6">
                        <b>Kode Unit:</b> {{ $unit->kode_unit_kompetensi }}<br>
                        <b>Judul Unit:</b> {{ $unit->judul_unit_kompetensi }}
                    </td>
                </tr>

                @foreach($unit->elemen as $elemen)
                    {{-- HEADER ELEMEN --}}
                    <tr>
                        <td></td>
                        <td colspan="5"><b>Elemen: {{ $elemen->nama_elemen }}</b></td>
                    </tr>

                    @foreach($elemen->kriteria as $kuk)
                        @php
                            // Ambil data jawaban dari database
                            $respon = $responses->get($kuk->id_kriteria);
                            $isK = $respon && $respon->pencapaian_ia01 == 1;
                            $isBK = $respon && $respon->pencapaian_ia01 === 0; // Cek ketat 0
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                {{ $kuk->kriteria_unjuk_kerja }}
                            </td>
                            <td>
                                {{-- Tampilkan Standar Industri yang diinput Asesor --}}
                                {{ $respon->standar_industri_ia01 ?? ($templateContent[$kuk->id_kriteria] ?? ($kuk->standar_industri_kerja ?? '-')) }}
                            </td>
                            <td class="text-center">
                                @if($isK) <span class="check">✔</span> @endif
                            </td>
                            <td class="text-center">
                                @if($isBK) <span class="check">✔</span> @endif
                            </td>
                            <td>
                                {{ $respon->penilaian_lanjut_ia01 ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>

    {{-- FOOTER REKOMENDASI --}}
    <div style="page-break-inside: avoid;">
        <table class="content-table">
            <tr>
                <td width="30%"><b>Umpan Balik untuk Asesi:</b></td>
                <td>{{ $sertifikasi->feedback_ia01 ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Rekomendasi:</b></td>
                <td>
                    <b>
                        @if($sertifikasi->rekomendasi_ia01 == 'kompeten')
                            Kompeten
                        @elseif($sertifikasi->rekomendasi_ia01 == 'belum_kompeten')
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
                    @if($sertifikasi->asesi->tanda_tangan)
                        <br>
                        <img src="{{ getTtdBase64($sertifikasi->asesi->tanda_tangan) }}" style="width: 100px; height: auto;">
                    @else
                        <br><br><br><br>
                    @endif
                </td>
                <td width="50%" class="text-center">
                    Asesor,<br><br><br><br>
                    <b>{{ $sertifikasi->jadwal->asesor->nama_asesor ?? '...................' }}</b>
                    @if($sertifikasi->jadwal->asesor->tanda_tangan)
                        <br>
                        <img src="{{ getTtdBase64($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="width: 100px; height: auto;">
                    @else
                        <br><br><br><br>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>