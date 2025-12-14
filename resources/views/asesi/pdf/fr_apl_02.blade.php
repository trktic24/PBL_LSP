<!DOCTYPE html>
<html>
<head>
    <title>FR.APL.02 Asesmen Mandiri</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid black; padding: 4px; vertical-align: top; }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .bg-grey { background-color: #f0f0f0; }
        .no-border { border: none !important; }
        .no-border-top { border-top: none !important; }
        .no-border-bottom { border-bottom: none !important; }
        
        .header-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; }
        
        /* Checkbox Style (Kotak Kosong) */
        .checkbox { 
            display: inline-block; 
            width: 12px; 
            height: 12px; 
            border: 1px solid black; 
            margin: 0 auto;
        }
        
        /* Layout adjustments specific to the reference */
        .w-fit { width: 1%; white-space: nowrap; }
    </style>
</head>
<body>

    <div class="header-title">FR.APL.02. ASESMEN MANDIRI</div>

    <table style="border: 2px solid black;">
        <tr>
            <td rowspan="2" width="20%" class="text-bold">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td width="15%">Judul</td>
            <td width="1%">:</td>
            <td>{{ $skema->nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $skema->nomor_skema }}</td>
        </tr>
    </table>

    <div style="border: 1px solid black; padding: 5px; margin-bottom: 10px;">
        <span class="text-bold">PANDUAN ASESMEN MANDIRI</span><br>
        <strong>Instruksi:</strong><br>
        • Baca setiap pertanyaan di kolom sebelah kiri.<br>
        • Beri tanda centang (√) pada kotak jika Anda yakin dapat melakukan tugas yang dijelaskan.<br>
        • Isi kolom di sebelah kanan dengan menuliskan bukti yang relevan anda miliki untuk menunjukkan bahwa anda melakukan pekerjaan.
    </div>

    @foreach($skema->unitKompetensi as $unit)
        
        <table style="margin-bottom: 0;">
            <tr>
                <td width="15%" class="bg-grey text-bold">Kode Unit</td>
                <td width="1%">:</td>
                <td>{{ $unit->kode_unit }}</td>
            </tr>
            <tr>
                <td class="bg-grey text-bold">Judul Unit</td>
                <td>:</td>
                <td>{{ $unit->judul_unit }}</td>
            </tr>
        </table>

        <table style="margin-top: -1px;">
            <thead>
                <tr class="bg-grey">
                    <th width="50%">Dapatkah Saya?</th> <th width="5%">K</th>  <th width="5%">BK</th> <th width="40%">Bukti yang relevan</th> </tr>
            </thead>
            <tbody>
                @if($unit->elemen && count($unit->elemen) > 0)
                    @foreach($unit->elemen as $idxElemen => $elemen)
                        <tr>
                            <td colspan="4" class="bg-grey" style="font-style: italic;">
                                <strong>Elemen {{ $idxElemen + 1 }}:</strong> {{ $elemen->nama_elemen }}
                            </td>
                        </tr>

                        @if($elemen->kriteria && count($elemen->kriteria) > 0)
                            @foreach($elemen->kriteria as $idxKuk => $kuk)
                                <tr>
                                    <td>
                                        <strong>{{ $idxElemen + 1 }}.{{ $idxKuk + 1 }} Kriteria Unjuk Kerja:</strong><br>
                                        {{ $kuk->kriteria ?? $kuk->nama_kriteria ?? 'Deskripsi KUK' }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="checkbox"></div> 
                                        {{-- Jika ingin otomatis tercentang K: <div class="checkbox">√</div> --}}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="checkbox"></div>
                                    </td>
                                    <td>
                                        {{-- Placeholder Bukti --}}
                                        Portofolio/Sertifikat
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="4">Data Kriteria belum tersedia di database.</td></tr>
                        @endif
                    @endforeach
                @else
                    <tr><td colspan="4">Rincian Elemen Kompetensi belum tersedia.</td></tr>
                @endif
            </tbody>
        </table>
        <br>
    @endforeach

    <table style="border: 2px solid black;">
        <tr>
            <td width="50%" class="bg-grey text-bold">Nama Asesi:</td>
            <td width="50%" class="bg-grey text-bold">Tanggal:</td>
        </tr>
        <tr>
            <td style="height: 30px;">{{ $asesi->nama_lengkap }}</td>
            <td>{{ $tanggal }}</td>
        </tr>
        <tr>
            <td colspan="2" class="bg-grey text-bold">Tanda Tangan Asesi:</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 60px;"></td> </tr>
    </table>

    <br>

    <table style="border: 2px solid black;">
        <tr class="bg-grey">
            <td colspan="3" class="text-bold">Ditinjau oleh Asesor:</td>
        </tr>
        <tr>
            <td width="30%"><strong>Nama Asesor:</strong></td>
            <td colspan="2">{{ $sertifikasi->jadwal->asesor->nama_asesor ?? '____________________' }}</td>
        </tr>
        <tr>
            <td><strong>Rekomendasi:</strong></td>
            <td colspan="2">
                Asesmen dapat dilanjutkan / tidak dapat dilanjutkan [cite: 62]
            </td>
        </tr>
        <tr>
            <td style="height: 60px;"><strong>Tanda Tangan dan Tanggal:</strong></td>
            <td style="vertical-align: bottom;">__________________</td> {{-- TTD --}}
            <td style="vertical-align: bottom;">Tanggal: _____________</td>
        </tr>
    </table>

</body>
</html>