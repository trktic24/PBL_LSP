<!DOCTYPE html>
<html>
<head>
    <title>FR.APL.02 - Asesmen Mandiri</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 4px; vertical-align: top; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; }
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 12px; text-align: center; }
        
        /* Style Accordion Simulation */
        .unit-header { background-color: #d1d5db; font-weight: bold; padding: 5px; border: 1px solid black; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        FR.APL.02. ASESMEN MANDIRI
    </div>

    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td width="150"><strong>Skema Sertifikasi</strong></td>
            <td width="10">:</td>
            <td>{{ $skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $skema->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesi</strong></td>
            <td>:</td>
            <td>{{ $asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
        </tr>
    </table>

    <div style="margin-bottom: 10px; font-style: italic; font-size: 10px;">
        *Panduan: K = Kompeten, BK = Belum Kompeten
    </div>

    @foreach ($skema->kelompokPekerjaan as $kp)
        @foreach ($kp->unitKompetensi as $unit)
            
            <div class="unit-header">
                Unit: {{ $unit->kode_unit }} - {{ $unit->judul_unit }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="50%">Daftar Pertanyaan (Asesmen Mandiri/Self Assessment)</th>
                        <th width="5%">K</th>
                        <th width="5%">BK</th>
                        <th width="35%">Bukti Pendukung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unit->elemen as $elemen)
                        @php $elemenNum = $loop->iteration; @endphp
                        
                        <tr style="background-color: #f9fafb;">
                            <td class="font-bold text-center">{{ $elemenNum }}</td>
                            <td colspan="4" class="font-bold">Elemen: {{ $elemen->elemen }}</td>
                        </tr>

                        @foreach ($elemen->kriteria as $kuk)
                            @php 
                                $respon = $existingResponses[$kuk->id_kriteria] ?? null; 
                                $isK = $respon && $respon->respon_asesi_apl02 == 1;
                                $isBK = $respon && $respon->respon_asesi_apl02 === 0;
                            @endphp
                            <tr>
                                <td
                                    class="p-3 text-sm text-gray-600 border-r border-gray-200 align-middle">
                                    {{ $elemenNum }}.{{ $loop->iteration }}
                                </td>                                
                                <td class="p-3 text-sm text-gray-700 align-top">
                                    <span
                                        class="font-mono text-xs text-gray-500 mr-1">({{ $kuk->no_kriteria }})</span>
                                    {{ $kuk->kriteria }}
                                </td>

                                <td class="check-mark bg-gray">{{ $isK ? 'V' : '' }}</td>
                                <td class="check-mark bg-gray">{{ $isBK ? 'V' : '' }}</td>
                                
                                <td style="font-size: 10px;">
                                    @if ($respon && $respon->bukti_asesi_apl02)
                                        [File Terlampir] <br>
                                        {{ basename($respon->bukti_asesi_apl02) }}
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

    <br><br>
    <div style="border: 1px solid black; padding: 10px;">
        <strong>Catatan:</strong> Asesmen mandiri ini akan diverifikasi oleh Asesor. Pastikan bukti yang Anda lampirkan valid dan asli.
    </div>
    <br>

    <table class="no-border">
        <tr>
            <td style="width: 50%; text-align: center;">
                Asesi,
                @if($asesi->tanda_tangan)
                    <img src="{{ getTtdBase64($asesi->tanda_tangan) }}" style="height: 100px; width: auto;">
                @else
                    <br><br><br><br>
                @endif
                <br><br><br><br>
                <strong>{{ $asesi->nama_lengkap ?? '(.......................)' }}</strong>
                <br>{{ \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
            </td>
            <td style="width: 50%; text-align: center;">
                Asesor,
                <br><br><br><br>
                @if($sertifikasi->jadwal->asesor && $sertifikasi->jadwal->asesor->tanda_tangan)
                    <img src="{{ getTtdBase64($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="height: 100px; width: auto;">
                @else
                    <br><br><br><br>
                @endif
                <br><br><br><br>
                <strong>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '(.......................)' }}</strong>
                <br>{{ \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding: 5px;">
                <div style="margin-top: 50px; border: 1px solid black; padding: 10px;">
                    @if($sertifikasi->rekomendasi_apl02 == 'diterima')
                        <span style="font-style: italic;">Berdasarkan hasil verifikasi Asesor, Asesmen Mandiri dinyatakan <strong>DITERIMA</strong></span>
                    @elseif($sertifikasi->rekomendasi_apl02 == 'tidak diterima') 
                        <span style="font-style: italic;">Berdasarkan hasil verifikasi Asesor, Asesmen Mandiri dinyatakan <strong>TIDAK DITERIMA</strong></span>
                    @else
                        <span style="font-style: italic;"><strong>Menunggu Verifikasi Asesor</strong></span> 
                    @endif
                </div> 
            </td>         
        </tr>
    </table>

</body>
</html>