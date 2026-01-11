<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.IA.09 - Pertanyaan Wawancara</title>
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

    <div class="title">FR.IA.09. PERTANYAAN WAWANCARA</div>

    {{-- INFO PESERTA --}}
    <table class="header-table" border="1">
        <tr>
            <td width="150"><b>Skema Sertifikasi</b></td>
            <td width="10">:</td>
            <td>{{ $data['skema']['judul'] }} ({{ $data['skema']['nomor'] }})</td>
        </tr>
        <tr>
            <td><b>TUK</b></td>
            <td>:</td>
            <td>{{ $data['info_umum']['tuk_type'] }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesor</b></td>
            <td>:</td>
            <td>{{ $data['info_umum']['nama_asesor'] }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesi</b></td>
            <td>:</td>
            <td>{{ $data['info_umum']['nama_asesi'] }}</td>
        </tr>
        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($data['info_umum']['tanggal'])->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <div style="margin-bottom: 10px;">
        <b>Panduan bagi Asesor:</b><br>
        • Tuliskan pertanyaan wawancara berdasarkan daftar pertanyaan yang telah disiapkan.<br>
        • Catat kesimpulan jawaban asesi dan tentukan pencapaiannya (Ya/Tidak).
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
        @foreach($data['unit_kompetensi'] as $unit)
        <tr>
            <td width="20%">{{ $unit['kode'] }}</td>
            <td>{{ $unit['judul'] }}</td>
        </tr>
        @endforeach
    </table>

    {{-- TABEL PERTANYAAN WAWANCARA --}}
    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Pertanyaan</th>
                <th width="35%">Kesimpulan Jawaban Asesi</th>
                <th width="25%">Rekomendasi (K/BK)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['pertanyaan'] as $item)
                @php
                    // Logic Pencapaian: Ya = Kompeten, Tidak = Belum Kompeten
                    $hasil = '-';
                    if(strtolower($item['pencapaian']) == 'ya') {
                        $hasil = 'Kompeten';
                    } elseif(strtolower($item['pencapaian']) == 'tidak') {
                        $hasil = 'Belum Kompeten';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $item['no'] }}</td>
                    <td>
                        {{ $item['pertanyaan'] ?? '-' }}
                    </td>
                    <td>
                        {{ $item['jawaban'] ?? '-' }}
                    </td>
                    <td class="text-center">
                        {{ $hasil }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada pertanyaan wawancara.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- REKOMENDASI --}}
    <div style="page-break-inside: avoid;">
        {{-- TANDA TANGAN --}}
        <table class="header-table" style="margin-top: 30px;">
            <tr>
                <td width="50%" class="text-center">
                    Asesi,<br><br><br><br>
                    <b>{{ $data['info_umum']['nama_asesi'] }}</b>
                    @if(isset($data['ttd']['asesi']) && $data['ttd']['asesi'])
                        <br>
                        <img src="{{ getTtdBase64($data['ttd']['asesi']) }}" style="width: 100px; height: auto;">
                    @else
                        <br><br><br><br>
                    @endif
                </td>
                <td width="50%" class="text-center">
                    Asesor,<br><br><br><br>
                    <b>{{ $data['info_umum']['nama_asesor'] }}</b>
                    @if(isset($data['ttd']['asesor']) && $data['ttd']['asesor'])
                        <br>
                        <img src="{{ getTtdBase64($data['ttd']['asesor']) }}" style="width: 100px; height: auto;">
                    @else
                        <br><br><br><br>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>