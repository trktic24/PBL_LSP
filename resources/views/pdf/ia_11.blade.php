<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FR.IA.11 - Ceklis Meninjau Instrumen (Produk)</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header-table, .content-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-table td { padding: 5px; vertical-align: top; }
        .content-table th, .content-table td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .content-table th { background-color: #f0f0f0; text-align: left; padding: 5px; }
        .title { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        .sub-title { font-weight: bold; background-color: #e0e0e0; padding: 5px; border: 1px solid black; margin-top: 10px; }
        .text-center { text-align: center; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .img-product { max-width: 150px; max-height: 150px; border: 1px solid #ccc; padding: 2px; }
    </style>
</head>
<body>

    <div class="title">FR.IA.11. CEKLIS MENINJAU INSTRUMEN ASESMEN (PRODUK)</div>

    {{-- INFO PESERTA --}}
    <table class="header-table" border="1">
        <tr>
            <td width="150"><b>Skema Sertifikasi</b></td>
            <td width="10">:</td>
            <td>{{ $sertifikasi->jadwal->skema->judul_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>TUK</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->masterTuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesor</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->asesor->nama_asesor ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesi</b></td>
            <td>:</td>
            <td>{{ $sertifikasi->asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    {{-- A. RINCIAN PRODUK --}}
    <div class="sub-title">A. RINCIAN PRODUK</div>
    <table class="content-table">
        <tr>
            <td width="30%"><b>Nama Produk</b></td>
            <td>{{ $ia11->nama_produk ?? '-' }}</td>
            <td width="30%" rowspan="4" class="text-center" style="vertical-align: middle;">
                @if($ia11->gambar_produk && file_exists(public_path($ia11->gambar_produk)))
                    <img src="{{ public_path($ia11->gambar_produk) }}" class="img-product">
                @else
                    <span style="color:gray; font-style:italic;">Tidak ada gambar</span>
                @endif
            </td>
        </tr>
        <tr>
            <td><b>Nama Rancangan / Desain</b></td>
            <td>{{ $ia11->rancangan_produk ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Standar Industri</b></td>
            <td>{{ $ia11->standar_industri ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Tanggal Pengoperasian</b></td>
            <td>{{ $ia11->tanggal_pengoperasian ? \Carbon\Carbon::parse($ia11->tanggal_pengoperasian)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
    </table>

    {{-- SPESIFIKASI UMUM --}}
    <table class="content-table">
        <tr>
            <th colspan="2">Spesifikasi Umum</th>
        </tr>
        <tr>
            <td width="30%">Dimensi</td>
            <td>{{ $ia11->spesifikasiProduk->dimensi_produk ?? '-' }}</td>
        </tr>
        <tr>
            <td>Berat</td>
            <td>{{ $ia11->spesifikasiProduk->berat_produk ?? '-' }}</td>
        </tr>
    </table>

    {{-- BAHAN & SPESIFIKASI TEKNIS --}}
    <table class="content-table">
        <tr>
            <th width="50%">Bahan Produk</th>
            <th width="50%">Spesifikasi Teknis</th>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                @if($ia11->bahanProduk && $ia11->bahanProduk->count() > 0)
                    <ol style="margin-left: -15px;">
                        @foreach($ia11->bahanProduk as $bahan)
                            <li>{{ $bahan->nama_bahan }}</li>
                        @endforeach
                    </ol>
                @else
                    -
                @endif
            </td>
            <td style="vertical-align: top;">
                @if($ia11->spesifikasiTeknis && $ia11->spesifikasiTeknis->count() > 0)
                    <ol style="margin-left: -15px;">
                        @foreach($ia11->spesifikasiTeknis as $teknis)
                            <li>{{ $teknis->data_teknis }}</li>
                        @endforeach
                    </ol>
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- B. REVIU PRODUK (CHECKLIST) --}}
    <div style="sub-title">B. REVIU / UJI PRODUK</div>
    
    {{-- 1. PENCAPAIAN SPESIFIKASI --}}
    <table class="content-table" style="margin-top: 5px;">
        <thead>
            <tr style="background-color: #e0e0e0;">
                <th width="5%" class="text-center">No</th>
                <th width="40%">Item Spesifikasi</th>
                <th width="15%" class="text-center">Hasil (Ya/Tidak)</th>
                <th width="40%">Catatan / Temuan</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="4"><b>1. Pencapaian Spesifikasi</b></td></tr>
            @forelse($ia11->pencapaianSpesifikasi ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{-- Ambil nama item dari relasi (sesuaikan nama kolom di tabel master) --}}
                        {{ $item->spesifikasiItem->nama_spesifikasi ?? 'Item Spesifikasi #' . ($index+1) }}
                    </td>
                    <td class="text-center">{{ $item->hasil_reviu ?? '-' }}</td>
                    <td>{{ $item->catatan_temuan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">- Data Kosong -</td></tr>
            @endforelse

            {{-- 2. PENCAPAIAN PERFORMA --}}
            <tr><td colspan="4"><b>2. Pencapaian Performa / Kinerja</b></td></tr>
            @forelse($ia11->pencapaianPerforma ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{-- Ambil nama item dari relasi --}}
                        {{ $item->performaItem->nama_performa ?? 'Item Performa #' . ($index+1) }}
                    </td>
                    <td class="text-center">{{ $item->hasil_reviu ?? '-' }}</td>
                    <td>{{ $item->catatan_temuan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">- Data Kosong -</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- REKOMENDASI --}}
    <div style="page-break-inside: avoid;">
        <table class="content-table">
            <tr>
                <td width="30%"><b>Rekomendasi Asesor:</b></td>
                <td>
                    <b>{{ $ia11->rekomendasi ?? '-' }}</b>
                </td>
            </tr>
        </table>

        {{-- TANDA TANGAN --}}
        <table class="header-table" style="margin-top: 30px;">
            <tr>
                <td width="50%" class="text-center">
                    Asesi,<br><br><br><br>
                    @if($sertifikasi->asesi->tanda_tangan)
                        <img src="{{ getTtdBase64($sertifikasi->asesi->tanda_tangan) }}" style="width: 100px; height: auto;">
                        <br>
                    @else
                        <br><br><br><br>
                    @endif
                    <b>{{ $sertifikasi->asesi->nama_lengkap ?? 'Asesi' }}</b>
                </td>
                <td width="50%" class="text-center">
                    Asesor,<br><br><br><br>
                    @if($sertifikasi->jadwal->asesor->tanda_tangan)
                        <img src="{{ getTtdBase64($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="width: 100px; height: auto;">
                        <br>
                    @else
                        <br><br><br><br>
                    @endif
                    <b>{{ $sertifikasi->jadwal->asesor->nama_asesor ?? 'Asesor' }}</b>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>