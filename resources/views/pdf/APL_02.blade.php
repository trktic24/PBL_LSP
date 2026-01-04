<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.APL.02 - Asesmen Mandiri</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; font-weight: bold; }
        .header h2 { margin: 5px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        th { background-color: #f0f0f0; }
        .no-border { border: none; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-4 { margin-bottom: 15px; }
        .w-full { width: 100%; }
        .check { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FR.APL.02. ASESMEN MANDIRI</h1>
    </div>

    <table class="no-border">
        <tr class="no-border">
            <td class="no-border" width="150">Nama Peserta</td>
            <td class="no-border" width="10">:</td>
            <td class="no-border">{{ $asesi->nama_lengkap }}</td>
        </tr>
        <tr class="no-border">
            <td class="no-border">Nama Asesor</td>
            <td class="no-border">:</td>
            <td class="no-border">{{ $asesor['nama'] }}</td>
        </tr>
        <tr class="no-border">
            <td class="no-border">Skema Sertifikasi</td>
            <td class="no-border">:</td>
            <td class="no-border">{{ $skema->nama_skema }} ({{ $skema->nomor_skema }})</td>
        </tr>
        <tr class="no-border">
            <td class="no-border">Tanggal</td>
            <td class="no-border">:</td>
            <td class="no-border">{{ now()->format('d-m-Y') }}</td>
        </tr>
    </table>

    <div class="mb-4">
        Panduan: <br>
        Tandai (V) pada kolom K jika Anda merasa Kompeten, atau BK jika Belum Kompeten.
        Sertakan bukti yang relevan untuk setiap kriteria yang diklaim Kompeten.
    </div>

    @foreach ($skema->kelompokPekerjaan as $kelompok)
        @foreach ($kelompok->unitKompetensi as $unit)
            <div class="mb-4">
                <strong>Unit Kompetensi: {{ $unit->kode_unit }} - {{ $unit->judul_unit }}</strong>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="55%">Elemen / Kriteria Unjuk Kerja</th>
                        <th width="5%">K</th>
                        <th width="5%">BK</th>
                        <th width="30%">Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unit->elemen as $elemen)
                        <tr>
                            <td class="font-bold">{{ $loop->iteration }}</td>
                            <td class="font-bold" colspan="4">Elemen: {{ $elemen->elemen }}</td>
                        </tr>
                        @foreach ($elemen->kriteria as $kuk)
                            @php
                                $saved = $existingResponses[$kuk->id_kriteria] ?? null;
                                $isKompeten = $saved && $saved->respon_asesi_apl02 == 1;
                                $isBelum = $saved && $saved->respon_asesi_apl02 == 0;
                            @endphp
                            <tr>
                                <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                <td>{{ $kuk->kriteria }}</td>
                                <td class="text-center check">{{ $isKompeten ? '✔' : '' }}</td>
                                <td class="text-center check">{{ $isBelum ? '✔' : '' }}</td>
                                <td>
                                    @if($saved && $saved->bukti_asesi_apl02)
                                        Ada Bukti
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach

    <table class="no-border" style="margin-top: 30px;">
        <tr class="no-border">
            <td class="no-border text-center" width="50%">
                Tanda Tangan Peserta<br><br><br><br>
                ({{ $asesi->nama_lengkap }})
            </td>
            <td class="no-border text-center" width="50%">
                Tanda Tangan Asesor<br><br><br><br>
                ({{ $asesor['nama'] }})
            </td>
        </tr>
    </table>
</body>
</html>
