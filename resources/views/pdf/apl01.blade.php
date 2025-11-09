<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>FR.APL.01 - Testing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 11pt;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
        }

        h2 {
            text-align: center;
            color: #666;
            font-size: 13pt;
            margin-top: 0;
        }

        .section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .section h3 {
            margin-top: 0;
            color: #2563eb;
            font-size: 12pt;
        }

        .info-row {
            margin: 8px 0;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        .signature {
            margin-top: 30px;
            text-align: center;
        }

        .signature img {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid #ccc;
        }

        .empty-state {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>
    <h1>FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI</h1>
    <h2>FR.APL.01 (Testing Version)</h2>

    <hr>

    <!-- BAGIAN 1: DATA PRIBADI -->
    <div class="section">
        <h3>Bagian 1: Data Pribadi Asesi</h3>

        <div class="info-row">
            <span class="label">Nama Lengkap:</span>
            {{ $asesi->nama_lengkap ?? 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">NIK:</span>
            {{ $asesi->nik ?? 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">Tempat Lahir:</span>
            {{ $asesi->tempat_lahir ?? 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">Tanggal Lahir:</span>
            {{ $asesi->tanggal_lahir ? \Carbon\Carbon::parse($asesi->tanggal_lahir)->format('d-m-Y') : 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">Jenis Kelamin:</span>
            {{ $asesi->jenis_kelamin ? ($asesi->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan') : 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">Kebangsaan:</span>
            {{ $asesi->kebangsaan ?? 'Indonesia' }}
        </div>

        <div class="info-row">
            <span class="label">Alamat Rumah:</span>
            {{ $asesi->alamat_rumah ?? 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">Kode Pos:</span>
            {{ $asesi->kode_pos ?? 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">Pendidikan:</span>
            {{ $asesi->pendidikan ?? 'Belum diisi' }}
        </div>

        <div class="info-row">
            <span class="label">No. HP:</span>
            {{ $asesi->nomor_hp ?? 'Belum diisi' }}
        </div>
    </div>

    <!-- BAGIAN 2: DATA PEKERJAAN -->
    <div class="section">
        <h3>Bagian 2: Data Pekerjaan Sekarang</h3>

        @if ($dataPekerjaan)
            <div class="info-row">
                <span class="label">Nama Perusahaan:</span>
                {{ $dataPekerjaan->nama_perusahaan ?? ($dataPekerjaan->nama_institusi_pekerjaan ?? 'Belum diisi') }}
            </div>

            <div class="info-row">
                <span class="label">Jabatan:</span>
                {{ $dataPekerjaan->jabatan ?? 'Belum diisi' }}
            </div>

            <div class="info-row">
                <span class="label">Alamat Kantor:</span>
                {{ $dataPekerjaan->alamat_kantor ?? ($dataPekerjaan->alamat_institusi ?? 'Belum diisi') }}
            </div>

            <div class="info-row">
                <span class="label">No. Telepon Kantor:</span>
                {{ $dataPekerjaan->no_telp_kantor ?? ($dataPekerjaan->no_telepon_institusi ?? 'Belum diisi') }}
            </div>
            <div class="info-row">
                <span class="label">Kode Pos Kantor:</span>
                {{ $dataPekerjaan->kode_pos_institusi ?? 'Belum diisi' }}
            </div>
        @else
            <p class="empty-state">Data pekerjaan belum diisi</p>
        @endif
    </div>

    <!-- BAGIAN 3: DATA SERTIFIKASI -->
    <div class="section">
        <h3>Bagian 3: Data Sertifikasi</h3>

        @if ($dataSertifikasi)
            <div class="info-row">
                <span class="label">Tujuan Asesmen:</span>
                {{ ucfirst(str_replace('_', ' ', $tujuanAsesmen ?? 'Belum diisi')) }}
            </div>

            @if ($dataSertifikasi->jadwal && $dataSertifikasi->jadwal->masterSkema)
                <div class="info-row">
                    <span class="label">Skema Sertifikasi:</span>
                    {{ $dataSertifikasi->jadwal->masterSkema->nama_skema ?? 'Belum diisi' }}
                </div>

                <div class="info-row">
                    <span class="label">Nomor Skema:</span>
                    {{ $dataSertifikasi->jadwal->masterSkema->nomor_skema ?? 'Belum diisi' }}
                </div>
            @else
                <p class="empty-state">Data skema belum tersedia</p>
            @endif
        @else
            <p class="empty-state">Data sertifikasi belum diisi</p>
        @endif
    </div>

    <!-- BAGIAN 4: UNIT KOMPETENSI -->
    <div class="section">
        <h3>Bagian 4: Daftar Unit Kompetensi</h3>

        @if (is_countable($unitKompetensi) && count($unitKompetensi) > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">No.</th>
                        <th style="width: 120px;">Kode Unit</th>
                        <th>Nama Unit Kompetensi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unitKompetensi as $index => $unit)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $unit->kode_unit ?? '-' }}</td>
                            <td>{{ $unit->nama_unit ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-state">Belum ada unit kompetensi terdaftar</p>
        @endif
    </div>

    <!-- BAGIAN 5: BUKTI KELENGKAPAN -->
    <div class="section">
        <h3>Bagian 5: Bukti Kelengkapan Pemohon</h3>

        @if ($buktiKelengkapan && count($buktiKelengkapan) > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">No.</th>
                        <th>Nama Bukti</th>
                        <th style="width: 150px; text-align: center;">Status Kelengkapan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buktiKelengkapan as $index => $bukti)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $bukti->bukti_kelengkapan ?? '-' }}</td>
                            <td style="text-align: center;">
                                @if ($bukti->status_kelengkapan == 'memenuhi')
                                    <strong style="color: green;">✓ Memenuhi</strong>
                                @elseif($bukti->status_kelengkapan == 'tidak_memenuhi')
                                    <strong style="color: red;">✗ Tidak Memenuhi</strong>
                                @else
                                    <span style="color: #999;">Tidak Ada</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-state">Belum ada bukti kelengkapan terdaftar</p>
        @endif
    </div>

    <!-- BAGIAN 6: TANDA TANGAN -->
    <div class="signature">
        <h3>Tanda Tangan Pemohon</h3>

        <div class="signature">
            <h3>Tanda Tangan Pemohon</h3>

            @if ($fullPathTandaTangan)
                @php
                    // Check jika path-nya adalah URL eksternal (teratasi di Controller)
                    $isUrl = filter_var($fullPathTandaTangan, FILTER_VALIDATE_URL);
                @endphp

                @if (!$isUrl && file_exists($fullPathTandaTangan))
                    {{-- PERBAIKAN KRITIS: Konversi gambar lokal ke Base64 --}}
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($fullPathTandaTangan)) }}"
                        alt="Tanda Tangan">
                @elseif($isUrl)
                    {{-- Jika itu URL (eksternal), Dompdf bisa memuatnya (karena isRemoteEnabled = true) --}}
                    <img src="{{ $fullPathTandaTangan }}" alt="Tanda Tangan">
                @else
                    <div style="border: 1px dashed #ccc; padding: 30px; color: #999; background-color: #f5f5f5;">
                        (Tanda tangan belum tersedia atau path tidak valid)
                    </div>
                @endif
            @else
                <div style="border: 1px dashed #ccc; padding: 30px; color: #999; background-color: #f5f5f5;">
                    (Tanda tangan belum tersedia)
                </div>
            @endif

            {{-- Sisanya tetap --}}
        </div>

        <p style="margin-top: 10px;">
            <strong>Nama:</strong> {{ $asesi->nama_lengkap ?? '-' }}<br>
            <strong>Tanggal:</strong> {{ date('d F Y') }}
        </p>
    </div>

    <hr style="margin-top: 30px;">

    <p style="text-align: center; color: #999; font-size: 10px;">
        Dokumen ini dicetak pada: {{ date('d F Y, H:i:s') }} WIB
    </p>
</body>

</html>
