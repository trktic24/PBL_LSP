<!DOCTYPE html>
<html>
<head>
    <title>FR.AK.05. LAPORAN ASESMEN</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { background-color: #e2e8f0; font-weight: bold; text-align: left;}
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        
        .header-section { margin-bottom: 20px; }
        .header-section div { margin-bottom: 5px; }
        .label { display: inline-block; width: 150px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        FR.AK.05. LAPORAN ASESMEN
    </div>

    <div class="header-section">
        <div><span class="label">Skema Sertifikasi</span>: {{ $jadwal->skema->nama_skema ?? '-' }}</div>
        <div><span class="label">Nomor Skema</span>: {{ $jadwal->skema->nomor_skema ?? '-' }}</div>
        <div><span class="label">TUK</span>: {{ $jadwal->masterTuk->nama_tuk ?? 'Tempat Kerja' }}</div>
        <div><span class="label">Nama Asesor</span>: {{ $asesor->nama_lengkap ?? '-' }}</div>
        <div><span class="label">Tanggal</span>: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}</div>
    </div>

    <div style="margin-bottom: 10px; font-weight: bold;">Aspek Negatif dan Positif dalam Asesmen</div>
    <div style="border: 1px solid black; padding: 10px; min-height: 50px; margin-bottom: 15px;">
        {{ $listAsesi->first()?->komentarAk05?->Ak05?->aspek_negatif_positif ?? '-' }}
    </div>

    <div style="margin-bottom: 10px; font-weight: bold;">Pencatatan Penolakan Hasil Asesmen</div>
    <div style="border: 1px solid black; padding: 10px; min-height: 50px; margin-bottom: 15px;">
        {{ $listAsesi->first()?->komentarAk05?->Ak05?->penolakan_hasil_asesmen ?? '-' }}
    </div>

    <div style="margin-bottom: 10px; font-weight: bold;">Saran Perbaikan (Asesmen Berikutnya)</div>
    <div style="border: 1px solid black; padding: 10px; min-height: 50px; margin-bottom: 15px;">
        {{ $listAsesi->first()?->komentarAk05?->Ak05?->saran_perbaikan ?? '-' }}
    </div>

    <div style="margin-bottom: 10px; font-weight: bold;">Rekomendasi</div>
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="25%">Nama Asesi</th>
                <th width="15%">Rekomendasi</th>
                <th width="35%">Keterangan</th>
                <th width="20%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listAsesi as $index => $asesi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $asesi->asesi->nama_lengkap }}</td>
                <td>
                    @if($asesi->komentarAk05 && $asesi->komentarAk05->rekomendasi == 'K')
                        Kompeten
                    @elseif($asesi->komentarAk05 && $asesi->komentarAk05->rekomendasi == 'BK')
                        Belum Kompeten
                    @else
                        -
                    @endif
                </td>
                <td>{{ $asesi->komentarAk05->keterangan ?? '-' }}</td>
                <td>{{ $asesi->komentarAk05->catatan_ak05 ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data asesi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="page-break-inside: avoid;">
        <div style="margin-bottom: 10px; font-weight: bold;">Tanda Tangan</div>
        <table class="no-border">
            <tr>
                <td width="50%">
                    <div style="margin-bottom: 50px;">
                    Nama Asesor:<br>
                    <strong>{{ $asesor->nama_lengkap }}</strong>
                    </div>
                    @if($asesor->tanda_tangan)
                        <img src="{{ getTtdBase64($asesor->tanda_tangan) }}" style="max-height: 80px; max-width: 200px;">
                    @else
                        <br><br><br>
                        (.......................)
                    @endif
                </td>
                <td width="50%">
                    {{-- Space for verification/notes if needed --}}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
