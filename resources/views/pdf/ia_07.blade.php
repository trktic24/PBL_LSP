<!DOCTYPE html>
<html>
<head>
    <title>FR.IA.07 - Daftar Pertanyaan Lisan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; }
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 14px; text-align: center; }
        
        /* Box Panduan */
        .guide-box { border: 1px solid #ccc; background-color: #f9f9f9; padding: 10px; margin-bottom: 15px; }
        .guide-title { font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        ul, ol { margin: 0; padding-left: 20px; }
        li { margin-bottom: 3px; }
    </style>
</head>
<body>

    <div class="header">
        FR.IA.07. DAFTAR PERTANYAAN LISAN
    </div>

    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="150"><strong>Skema Sertifikasi</strong></td>
            <td width="10">:</td>
            <td>{{ $sertifikasi->jadwal->skema->judul_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->skema->kode_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>TUK</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->tuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->skema->asesor->first()->nama_asesor ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesi</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
        </tr>
    </table>

    <hr>

    <div class="guide-box">
        <div class="guide-title">Panduan Bagi Asesor:</div>
        <ul>
            <li>Tentukan pihak ketiga yang akan dimintai verifikasi.</li>
            <li>Ajukan pertanyaan kepada pihak ketiga.</li>
            <li>Berikan penilaian kepada asesi berdasarkan verifikasi pihak ketiga.</li>
        </ul>
    </div>

    <div class="guide-box" style="border: 1px solid #000;">
        <div class="guide-title">Instruksi:</div>
        <ol>
            <li>Ajukan pertanyaan kepada Asesi dari daftar pertanyaan di bawah ini untuk mengonfirmasi pengetahuan.</li>
            <li>Tempatkan centang di kotak pencapaian "Ya" atau "Tidak".</li>
            <li>Tulis jawaban Asesi secara singkat di tempat yang disediakan.</li>
        </ol>
    </div>

    <div style="margin-bottom: 5px; font-weight: bold;">Unit Kompetensi yang Diujikan:</div>
    <table>
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th width="30%">Kode Unit</th>
                <th width="60%">Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unitKompetensi as $index => $uk)
            <tr>
                <td class="text-center">{{ $loop->iteration }}.</td>
                <td class="text-center">{{ $uk->kode_unit }}</td>
                <td>{{ $uk->judul_unit }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Data Unit Kompetensi tidak ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-bottom: 5px; margin-top: 15px; font-weight: bold;">Pertanyaan & Jawaban:</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="75%">Pertanyaan & Jawaban Asesi</th>
                <th width="10%">Ya</th>
                <th width="10%">Tidak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftar_pertanyaan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}.</td>
                <td>
                    <div style="font-weight: bold; margin-bottom: 5px;">
                        {{ $item->pertanyaan }}
                    </div>
                    <div style="font-style: italic; color: #555; font-size: 10px; margin-bottom: 5px;">
                        (Kunci: {{ $item->jawaban_diharapkan ?? '-' }})
                    </div>
                    <div style="border-top: 1px dashed #999; padding-top: 5px; color: blue;">
                        <strong>Jawaban Asesi:</strong> <br>
                        {{ $item->jawaban_asesi ?? '-' }}
                    </div>
                </td>
                
                {{-- Logic Checklist --}}
                <td class="text-center check-mark bg-gray" style="vertical-align: middle;">
                    {{ ($item->pencapaian == 1) ? 'V' : '' }}
                </td>
                <td class="text-center check-mark bg-gray" style="vertical-align: middle;">
                    {{ ($item->pencapaian === 0) ? 'V' : '' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Belum ada daftar pertanyaan lisan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <br><br>
    <table class="no-border">
        <tr>
            <td style="width: 50%; text-align: center;">
                Asesi,
                <br><br><br><br>
                <strong>{{ $sertifikasi->asesi->nama_lengkap ?? '(.......................)' }}</strong>
            </td>
            <td style="width: 50%; text-align: center;">
                Semarang, {{ date('d F Y') }}<br>
                Asesor Kompetensi,
                <br><br><br><br>
                <strong>{{ $sertifikasi->jadwal->skema->asesor->first()->nama_asesor ?? '(.......................)' }}</strong>
            </td>
        </tr>
    </table>

</body>
</html>