<!DOCTYPE html>
<html>
<head>
    <title>FR.AK.02 - Rekaman Asesmen Kompetensi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 4px; vertical-align: middle; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; font-size: 10px; }
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        
        /* Simbol Checklist */
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 12px; text-align: center; }
        
        /* Judul Section */
        h3 { font-size: 12px; margin-bottom: 5px; margin-top: 15px; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
    </style>
</head>
<body>

    <div class="header">
        FR.AK.02. REKAMAN ASESMEN KOMPETENSI
    </div>

    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td width="150"><strong>Skema Sertifikasi</strong></td>
            <td width="10">:</td>
            <td>{{ $data['skema'] ?? 'Junior Web Developer' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $data['nomor_skema'] ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>TUK</strong></td>
            <td>:</td>
            <td>{{ $data['tuk'] ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $data['nama_asesor'] ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesi</strong></td>
            <td>:</td>
            <td>{{ $data['nama_asesi'] ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ $data['tanggal'] ?? date('d-m-Y') }}</td>
        </tr>
    </table>

    <hr>

    <h3>A. Kelompok Pekerjaan</h3>
    <table>
        <thead>
            <tr>
                <th width="20%">Kelompok Pekerjaan</th>
                <th width="5%">No.</th>
                <th width="20%">Kode Unit</th>
                <th width="55%">Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data['unit_kompetensi']) && count($data['unit_kompetensi']) > 0)
                @foreach($data['unit_kompetensi'] as $index => $unit)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ count($data['unit_kompetensi']) }}" class="text-center" style="vertical-align: top;">
                            {{ $data['kelompok_pekerjaan'] ?? '-' }}
                        </td>
                    @endif
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td class="text-center">{{ $unit['kode'] ?? '-' }}</td>
                    <td>{{ $unit['judul'] ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">Data Unit Kompetensi belum tersedia.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <h3>B. Bukti-Bukti Kompetensi</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="25%">Unit Kompetensi</th>
                <th colspan="7">Bukti-Bukti (Ceklis jika ada)</th>
            </tr>
            <tr>
                <th width="10%">Observasi</th>
                <th width="10%">Portofolio</th>
                <th width="10%">Pihak Ketiga</th>
                <th width="10%">Lisan</th>
                <th width="10%">Tertulis</th>
                <th width="10%">Proyek</th>
                <th width="10%">Lainnya</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data['bukti_kompetensi']) && count($data['bukti_kompetensi']) > 0)
                @foreach($data['bukti_kompetensi'] as $bukti)
                <tr>
                    <td>{{ $bukti['unit'] ?? '-' }}</td>
                    <td class="check-mark bg-gray">{{ isset($bukti['observasi']) && $bukti['observasi'] ? 'V' : '' }}</td>
                    <td class="check-mark bg-gray">{{ isset($bukti['portofolio']) && $bukti['portofolio'] ? 'V' : '' }}</td>
                    <td class="check-mark bg-gray">{{ isset($bukti['pihak_ketiga']) && $bukti['pihak_ketiga'] ? 'V' : '' }}</td>
                    <td class="check-mark bg-gray">{{ isset($bukti['lisan']) && $bukti['lisan'] ? 'V' : '' }}</td>
                    <td class="check-mark bg-gray">{{ isset($bukti['tertulis']) && $bukti['tertulis'] ? 'V' : '' }}</td>
                    <td class="check-mark bg-gray">{{ isset($bukti['proyek']) && $bukti['proyek'] ? 'V' : '' }}</td>
                    <td class="check-mark bg-gray">{{ isset($bukti['lainnya']) && $bukti['lainnya'] ? 'V' : '' }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="8" class="text-center">Belum ada data bukti.</td></tr>
            @endif
        </tbody>
    </table>

    <h3>C. Rekomendasi & Tindak Lanjut</h3>
    <table class="no-border">
        <tr>
            <td width="30%" style="font-weight: bold; background-color: #f0f0f0; border: 1px solid #000;">Rekomendasi Asesor:</td>
            <td style="border: 1px solid #000; padding: 10px;">
                @if(isset($data['hasil_asesmen']))
                    [ {{ $data['hasil_asesmen'] == 'kompeten' ? 'X' : ' ' }} ] Kompeten <br>
                    [ {{ $data['hasil_asesmen'] == 'belum_kompeten' ? 'X' : ' ' }} ] Belum Kompeten
                @else
                    [ ] Kompeten  [ ] Belum Kompeten
                @endif
            </td>
        </tr>
        <tr>
            <td width="30%" style="font-weight: bold; background-color: #f0f0f0; border: 1px solid #000;">Tindak Lanjut:</td>
            <td style="border: 1px solid #000; height: 50px; vertical-align: top;">
                {{ $data['tindak_lanjut'] ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="30%" style="font-weight: bold; background-color: #f0f0f0; border: 1px solid #000;">Komentar Asesor:</td>
            <td style="border: 1px solid #000; height: 50px; vertical-align: top;">
                {{ $data['komentar_asesor'] ?? '-' }}
            </td>
        </tr>
    </table>

    <br><br>
    <table class="no-border">
        <tr>
            <td style="width: 50%; text-align: center;">
                Asesi,
                <br><br><br><br>
                <strong>{{ $data['nama_asesi'] ?? '(.......................)' }}</strong>
                <br>Tanggal: {{ date('d-m-Y') }}
            </td>
            <td style="width: 50%; text-align: center;">
                Asesor Kompetensi,
                <br><br><br><br>
                <strong>{{ $data['nama_asesor'] ?? '(.......................)' }}</strong>
                <br>No. Reg: -
            </td>
        </tr>
    </table>

</body>
</html>