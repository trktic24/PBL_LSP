<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.IA.08 - Ceklis Verifikasi Portofolio</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header-table, .content-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-table td { padding: 5px; vertical-align: top; }
        .content-table th, .content-table td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .content-table th { background-color: #f0f0f0; text-align: center; }
        .title { font-size: 14px; font-weight: bold; margin-bottom: 20px; }
        .bg-gray { background-color: #e0e0e0; }
        .text-center { text-align: center; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    </style>
</head>
<body>

    <div class="title">FR.IA.08. CEKLIS VERIFIKASI PORTOFOLIO</div>

    {{-- INFO PESERTA --}}
    <table class="header-table" border="1">
        <tr>
            <td width="150"><b>Skema Sertifikasi</b></td>
            <td width="10">:</td>
            <td>{{ $data->jadwal->skema->judul_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>TUK</b></td>
            <td>:</td>
            <td>{{ $data->jadwal->masterTuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesor</b></td>
            <td>:</td>
            <td>{{ $data->jadwal->asesor->nama_asesor ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesi</b></td>
            <td>:</td>
            <td>{{ $data->asesi->nama_lengkap }}</td>
        </tr>
        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <div style="margin-bottom: 10px;">
        <b>Panduan bagi Asesor:</b><br>
        • Isilah tabel ini sesuai dengan informasi sesuai pertanyaan/pernyataan dalam tabel dibawah ini.<br>
        • Beri tanda centang (✔) pada kolom V (Valid), A (Asli), T (Terkini), M (Memadai) jika sesuai.
    </div>

    {{-- DAFTAR UNIT KOMPETENSI --}}
    <table class="content-table">
        <tr class="bg-gray">
            <th colspan="2">Unit Kompetensi</th>
        </tr>
        <tr class="bg-gray">
            <th>Kode Unit</th>
            <th>Judul Unit</th>
        </tr>
        @foreach($unitKompetensi as $unit)
        <tr>
            <td width="20%">{{ $unit->kode_unit_kompetensi }}</td>
            <td>{{ $unit->judul_unit_kompetensi }}</td>
        </tr>
        @endforeach
    </table>

    {{-- TABEL VERIFIKASI PORTOFOLIO --}}
    <table class="content-table">
        <thead>
            <tr>
                <th width="5%" rowspan="2">No</th>
                <th width="35%" rowspan="2">Bukti Portofolio</th>
                <th width="40%" colspan="4">Aturan Bukti (VATM)</th>
                {{-- <th width="20%" rowspan="2">Apa Bukti Ini Memenuhi Syarat? (Ya/Tidak)</th> --}}
            </tr>
            <tr>
                <th width="10%">Valid</th>
                <th width="10%">Asli</th>
                <th width="10%">Terkini</th>
                <th width="10%">Memadai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buktiPortofolio as $index => $bukti)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <b>{{ $bukti->nama_data }}</b><br>
                        <small>File: {{ basename($bukti->file_data) }}</small>
                    </td>
                    
                    {{-- Valid --}}
                    <td class="text-center">
                        @if(in_array('valid', $bukti->array_valid)) <span class="check">✔</span> @endif
                    </td>
                    
                    {{-- Asli --}}
                    <td class="text-center">
                        @if(in_array('asli', $bukti->array_asli)) <span class="check">✔</span> @endif
                    </td>
                    
                    {{-- Terkini --}}
                    <td class="text-center">
                        @if(in_array('terkini', $bukti->array_terkini)) <span class="check">✔</span> @endif
                    </td>
                    
                    {{-- Memadai --}}
                    <td class="text-center">
                        @if(in_array('memadai', $bukti->array_memadai)) <span class="check">✔</span> @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada bukti portofolio yang diunggah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- REKOMENDASI --}}
    <div style="page-break-inside: avoid;">
        <table class="content-table">
            <tr>
                <td width="30%"><b>Rekomendasi Asesor:</b></td>
                <td>
                    <b>
                        @if($ia08Header)
                            @if($ia08Header->rekomendasi == 'kompeten')
                                Kompeten
                            @elseif($ia08Header->rekomendasi == 'perlu observasi lanjut')
                                Belum Kompeten / Perlu Observasi Lanjut
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </b>
                </td>
            </tr>
            @if($ia08Header && $ia08Header->rekomendasi == 'perlu observasi lanjut')
            <tr>
                <td><b>Catatan / Unit yg perlu diobservasi:</b></td>
                <td>
                    {{ $ia08Header->unit_kompetensi ?? '-' }} <br>
                    {{ $ia08Header->elemen ?? '-' }} <br>
                    {{ $ia08Header->kuk ?? '-' }}
                </td>
            </tr>
            @endif
        </table>

        {{-- TANDA TANGAN --}}
        <table class="header-table" style="margin-top: 30px;">
            <tr>
                <td width="50%" class="text-center">
                    Asesi,<br><br><br><br>
                    <b>{{ $data->asesi->nama_lengkap }}</b>
                    @if($data->asesi->tanda_tangan)
                        <br>
                        <img src="{{ getTtdBase64($data->asesi->tanda_tangan) }}" style="width: 100px; height: auto;">
                    @else
                        <br><br><br><br>
                    @endif
                </td>
                <td width="50%" class="text-center">
                    Asesor,<br><br><br><br>
                    <b>{{ $data->jadwal->asesor->nama_asesor ?? '...................' }}</b>
                    @if($data->jadwal->asesor->tanda_tangan)
                        <br>
                        <img src="{{ getTtdBase64($data->jadwal->asesor->tanda_tangan) }}" style="width: 100px; height: auto;">
                    @else
                        <br><br><br><br>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>