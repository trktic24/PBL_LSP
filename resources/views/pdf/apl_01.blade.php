<!DOCTYPE html>
<html>
<head>
    <title>FR.APL.01 FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-weight: bold; font-size: 14px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .bg-gray { background-color: #f0f0f0; font-weight: bold; }
        .no-border { border: none !important; }
        .text-center { text-align: center; }
        .title-section { background-color: #ddd; font-weight: bold; padding: 5px; border: 1px solid black; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI
    </div>

    {{-- BAGIAN 1: RINCIAN DATA PEMOHON --}}
    <div class="title-section">Bagian 1 : Rincian Data Pemohon Sertifikasi</div>
    <table class="no-border">
        <tr>
            <td width="150" class="no-border">Nama Lengkap</td>
            <td class="no-border">: {{ $asesi->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="no-border">No. KTP/NIK</td>
            <td class="no-border">: {{ $asesi->nik }}</td>
        </tr>
        <tr>
            <td class="no-border">Tempat / Tgl. Lahir</td>
            <td class="no-border">: {{ $asesi->tempat_lahir }}, {{ \Carbon\Carbon::parse($asesi->tanggal_lahir)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td class="no-border">Alamat Rumah</td>
            <td class="no-border">: {{ $asesi->alamat }}</td>
        </tr>
        <tr>
            <td class="no-border">No. Telepon/HP</td>
            <td class="no-border">: {{ $asesi->no_telp }}</td>
        </tr>
        <tr>
            <td class="no-border">Kualifikasi Pendidikan</td>
            <td class="no-border">: {{ $asesi->pendidikan_terakhir }}</td>
        </tr>
    </table>

    {{-- BAGIAN 2: DATA SERTIFIKASI --}}
    <div class="title-section">Bagian 2 : Data Sertifikasi</div>
    <table>
        <tr>
            <td rowspan="2" width="150" class="bg-gray">Skema Sertifikasi</td>
            <td width="100">Judul</td>
            <td>: {{ $skema->judul_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>: {{ $skema->kode_skema }}</td>
        </tr>
        <tr>
            <td colspan="2">Tujuan Asesmen</td>
            <td>: {{ $sertifikasi->tujuan_asesmen ?? 'Sertifikasi' }}</td>
        </tr>
    </table>

    {{-- BAGIAN 3: BUKTI KELENGKAPAN (DARI APL 01-2) --}}
    <div class="title-section">Bagian 3 : Bukti Kelengkapan Pemohon</div>
    <table>
        <thead>
            <tr class="bg-gray text-center">
                <th width="5%">No</th>
                <th>Bukti Persyaratan Dasar</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- Pas Foto --}}
            <tr>
                <td class="text-center">1</td>
                <td>Pas Foto (Background Merah)</td>
                <td class="text-center">{{ $sertifikasi->file_pas_foto ? 'Ada' : 'Tidak' }}</td>
            </tr>
            {{-- KTP --}}
            <tr>
                <td class="text-center">2</td>
                <td>Kartu Tanda Penduduk (KTP)</td>
                <td class="text-center">{{ $sertifikasi->file_ktp ? 'Ada' : 'Tidak' }}</td>
            </tr>
            {{-- Ijazah --}}
            <tr>
                <td class="text-center">3</td>
                <td>Ijazah Terakhir</td>
                <td class="text-center">{{ $sertifikasi->file_ijazah ? 'Ada' : 'Tidak' }}</td>
            </tr>
            {{-- CV --}}
            <tr>
                <td class="text-center">4</td>
                <td>Daftar Riwayat Hidup (CV)</td>
                <td class="text-center">{{ $sertifikasi->file_cv ? 'Ada' : 'Tidak' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- BAGIAN 4: TANDA TANGAN (DARI APL 01-3) --}}
    <div class="title-section">Bagian 4 : Tanda Tangan</div>
    <br>
    <table class="no-border" style="width: 100%">
        <tr>
            <td class="no-border" width="60%" valign="top">
                <p><strong>Rekomendasi (Diisi oleh LSP):</strong></p>
                <p>Berdasarkan ketentuan persyaratan dasar, maka pemohon:</p>
                <p>
                    [ ] <strong>Diterima</strong><br>
                    [ ] <strong>Tidak Diterima</strong>
                </p>
            </td>
            <td class="no-border text-center" width="40%">
                <p><strong>Pemohon,</strong></p>
                <br>
                @if($sertifikasi->tanda_tangan_asesi)
                    {{-- Menampilkan Gambar Tanda Tangan Base64 atau Path --}}
                    <img src="{{ public_path($sertifikasi->tanda_tangan_asesi) }}" style="height: 70px; max-width: 150px;">
                @else
                    <br><br><br>
                @endif
                <br>
                <p><strong>{{ $asesi->nama_lengkap }}</strong></p>
                <p>Tanggal: {{ now()->format('d-m-Y') }}</p>
            </td>
        </tr>
    </table>

</body>
</html>