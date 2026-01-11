<!DOCTYPE html>
<html>
<head>
    <title>FR.AK.06. MENINJAU PROSES ASESMEN</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { background-color: #e2e8f0; font-weight: bold; text-align: center;}
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        
        .header-section { margin-bottom: 20px; }
        .header-section div { margin-bottom: 5px; }
        .label { display: inline-block; width: 150px; font-weight: bold; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        FR.AK.06. MENINJAU PROSES ASESMEN
    </div>

    <div class="header-section">
        <div><span class="label">Skema Sertifikasi</span>: {{ $skema->nama_skema ?? '-' }}</div>
        <div><span class="label">Nomor Skema</span>: {{ $skema->kode_skema ?? '-' }}</div>
        <div><span class="label">TUK</span>: {{ $jadwal->masterTuk->nama_tuk ?? 'Tempat Kerja' }}</div>
        <div><span class="label">Nama Asesor</span>: {{ $asesor->nama_lengkap ?? '-' }}</div>
        <div><span class="label">Tanggal</span>: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</div>
    </div>

    <div style="margin-bottom: 10px; font-style: italic;">
        Penjelasan:<br>
        1. Peninjauan dapat dilakukan oleh lead asesor atau asesor yang melaksanakan asesmen.<br>
        2. Isi tabel dengan tanda centang (V) pada kolom yang sesuai.
    </div>

    {{-- TABEL 1: Prinsip Asesmen --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="30%">Aspek yang ditinjau</th>
                <th colspan="4">Kesesuaian dengan prinsip asesmen</th>
            </tr>
            <tr>
                <th width="17%">Validitas</th>
                <th width="17%">Reliabel</th>
                <th width="17%">Fleksibel</th>
                <th width="19%">Adil</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-gray">
                <td colspan="5"><strong>Prosedur asesmen:</strong></td>
            </tr>
            <tr>
                <td>Rencana asesmen</td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
            </tr>
            <tr>
                <td>Persiapan asesmen</td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
            </tr>
            <tr>
                <td>Implementasi asesmen</td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
            </tr>
            <tr>
                <td>Keputusan asesmen</td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center bg-gray"></td>
                <td class="text-center"><span class="check">V</span></td>
            </tr>
            <tr>
                <td>Umpan balik asesmen</td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center bg-gray"></td>
                <td class="text-center"><span class="check">V</span></td>
            </tr>
             <tr>
                <td colspan="5">
                    <strong>Rekomendasi untuk peningkatan:</strong><br>
                    <div style="min-height: 40px;">{{ $ak06->rekomendasi_aspek ?? '-' }}</div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL 2: Dimensi Kompetensi --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="30%">Aspek yang ditinjau</th>
                <th colspan="5">Pemenuhan dimensi kompetensi</th>
            </tr>
            <tr>
                <th>Task Skills</th>
                <th>Task Mgmt</th>
                <th>Contingency Mgmt</th>
                <th>Job Role/Env</th>
                <th>Transfer Skills</th>
            </tr>
        </thead>
        <tbody>
             <tr class="bg-gray">
                <td colspan="6"><strong>Konsistensi keputusan asesmen:</strong></td>
            </tr>
            <tr>
                <td>Bukti dari berbagai asesmen diperiksa untuk konsistensi dimensi kompetensi</td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
                <td class="text-center"><span class="check">V</span></td>
            </tr>
            <tr>
                <td colspan="6">
                    <strong>Rekomendasi untuk peningkatan:</strong><br>
                    <div style="min-height: 40px;">{{ $ak06->rekomendasi_dimensi ?? '-' }}</div>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="page-break-inside: avoid;">
        <table class="no-border">
            <tr>
                <td width="30%">Asesor:</td>
                <td width="70%"><strong>{{ $asesor->nama_lengkap ?? '' }}</strong></td>
            </tr>
             <tr>
                <td>Tanggal:</td>
                <td>{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    Komentar:<br>
                    <div style="border: 1px solid black; padding: 5px; min-height: 40px;">{{ $ak06->komentar ?? '-' }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Tanda tangan:<br><br>
                    @if($asesor->tanda_tangan)
                        <img src="{{ getTtdBase64($asesor->tanda_tangan) }}" style="max-height: 80px; max-width: 200px;">
                    @else
                        (.......................)
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
