<!DOCTYPE html>
<html>
<head>
    <title>FR.IA.06 - Pertanyaan Tertulis Esai</title>
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
        
        /* Style khusus untuk jawaban */
        .answer-box { font-family: "Courier New", Courier, monospace; color: #00008B; }
        .signature-img {
            width: 120px;        /* atur sesuai kebutuhan */
            height: auto;        /* jaga rasio */
            max-height: 80px;    /* opsional */
        }        
    </style>
</head>
<body>

    <div class="header">
        FR.IA.06. PERTANYAAN TERTULIS ESAI
    </div>

    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="150"><strong>Skema Sertifikasi</strong></td>
            <td width="10">:</td>
            <td>{{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->skema->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>TUK</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->tuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}</td>
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

    <div style="margin-bottom: 5px;"><strong>Jawaban & Penilaian:</strong></div>
    
    <table>
        <thead>
            <tr>
                <th width="5%" rowspan="2">No.</th>
                <th width="75%" rowspan="2">Pertanyaan & Jawaban Asesi</th>
                <th width="20%" colspan="2">Rekomendasi</th>
            </tr>
            <tr>
                <th width="10%">K</th>
                <th width="10%">BK</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftar_soal as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 5px;">
                            {{ $item->soal->soal_ia06 }}
                        </div>
                        
                        <div style="border-top: 1px dashed #ccc; padding-top: 5px; margin-top: 5px;">
                            <span style="font-size: 10px; color: #555;">Jawaban:</span><br>
                            <span class="answer-box">
                                {!! nl2br(e($item->jawaban_asesi ?? '-')) !!}
                            </span>
                        </div>
                    </td>
                    
                    <td class="text-center check-mark bg-gray" style="vertical-align: middle;">
                        {{ ($item->pencapaian === 1) ? 'V' : '' }}
                    </td>
                    
                    <td class="text-center check-mark bg-gray" style="vertical-align: middle;">
                        {{ ($item->pencapaian === 0) ? 'V' : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada soal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <td width="30%" style="background-color: #e2e8f0; font-weight: bold;">
                Umpan balik untuk asesi:
            </td>
            <td>
                {{ $umpanBalik->umpan_balik ?? '-' }}
            </td>
        </tr>
    </table>

    <br><br>
    {{-- TANDA TANGAN --}}
    <div class="mt-30" style="width: 100%;">
        <table style="border: none;">
            <tr>
                {{-- ASESI --}}
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="section-title">
                        Semarang, {{ \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
                    </div>

                    <div class="section-title">Asesi</div>

                    <table style="border: none;">
                        <tr>
                            <td style="width: 120px; border: none;">Nama</td>
                            <td style="width: 10px; border: none;">:</td>
                            <td style="border: none;">{{ $sertifikasi->asesi->nama_lengkap }}</td>
                        </tr>

                        <tr>
                            <td style="border: none;">Tanda Tangan</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('private_docs')->path($sertifikasi->asesi->tanda_tangan) }}" class="signature-img">
                            </td>
                        </tr>
                    </table>
                </td>

                {{-- ASESOR --}}
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="section-title" style="margin-top: 30px;">Asesor Kompetensi</div>

                    <table style="border: none;">
                        <tr>
                            <td style="width: 120px; border: none;">Nama</td>
                            <td style="width: 10px; border: none;">:</td>
                            <td style="border: none;">{{ $sertifikasi->jadwal->asesor->nama_lengkap }}</td>
                        </tr>

                        <tr>
                            <td style="border: none;">Tanda Tangan</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('private_docs')->path($sertifikasi->jadwal->asesor->tanda_tangan) }}" class="signature-img">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>