<!DOCTYPE html>
<html>
<head>
    <title>FR.APL.01 - Permohonan Sertifikasi Kompetensi</title>
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
        h3 { font-size: 12px; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
    </style>
</head>
<body>

    <div class="header">
        FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI
    </div>

    <h3>Bagian 1 : Rincian Data Pemohon Sertifikasi</h3>
    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td width="150"><strong>a. Data Pribadi</strong></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Nama Lengkap</td>
            <td>: {{ $asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">No. KTP/NIK/Paspor</td>
            <td>: {{ $asesi->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Tempat / Tgl. Lahir</td>
            <td>: {{ $asesi->tempat_lahir ?? '-' }}, {{ isset($asesi->tanggal_lahir) ? \Carbon\Carbon::parse($asesi->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Jenis Kelamin</td>
            <td>: {{ $asesi->jenis_kelamin ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Kebangsaan</td>
            <td>: {{ $asesi->kebangsaan ?? 'Indonesia' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Alamat Rumah</td>
            <td>: {{ $asesi->alamat_rumah ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">No. Telepon/HP</td>
            <td>: {{ $asesi->nomor_hp ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Email</td>
            <td>: {{ $asesi->user->email ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Kualifikasi Pendidikan</td>
            <td>: {{ $asesi->pendidikan ?? '-' }}</td>
        </tr>
    </table>

    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td width="150"><strong>b. Data Pekerjaan Sekarang</strong></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Nama Perusahaan</td>
            <td>: {{ $asesi->dataPekerjaan->nama_institusi_pekerjaan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Jabatan</td>
            <td>: {{ $asesi->dataPekerjaan->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">Alamat Kantor</td>
            <td>: {{ $asesi->dataPekerjaan->alamat_institusi ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">No. Telp Perusahaan</td>
            <td>: {{ $asesi->dataPekerjaan->no_telepon_institusi ?? '-' }}</td>
        </tr>
    </table>

    <h3>Bagian 2 : Data Sertifikasi</h3>
    <table class="no-border" style="margin-bottom: 10px;">
        <tr>
            <td width="150">Judul Skema</td>
            <td>: {{ $skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Skema</td>
            <td>: {{ $skema->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tujuan Asesmen</td>
            <td>: {{ $sertifikasi->tujuan_asesmen ?? '-' }}</td> </tr>
    </table>

    <div style="margin-bottom: 5px; font-weight: bold;">Daftar Unit Kompetensi:</div>
    <table>
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th width="30%">Kode Unit</th>
                <th width="60%">Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($unitKompetensi) && $unitKompetensi->count() > 0)
                @foreach($unitKompetensi as $index => $uk)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}.</td>
                    <td class="text-center">{{ $uk->kode_unit }}</td>
                    <td>{{ $uk->judul_unit }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="3" class="text-center">Unit Kompetensi tidak ditemukan.</td></tr>
            @endif
        </tbody>
    </table>

    <h3>Bagian 3 : Bukti Kelengkapan Pemohon</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th width="50%">Bukti Persyaratan Dasar</th>
                <th width="20%">Ada</th>
                <th width="20%">Tidak Ada</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1.</td>
                <td>Pas Foto 3x4 Background Merah</td>
                <td class="text-center check-mark">V</td> <td></td>
            </tr>
            <tr>
                <td class="text-center">2.</td>
                <td>Kartu Tanda Penduduk (KTP)</td>
                <td class="text-center check-mark">V</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center">3.</td>
                <td>Ijazah Terakhir</td>
                <td class="text-center check-mark">V</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center">4.</td>
                <td>Daftar Riwayat Hidup (CV)</td>
                <td class="text-center check-mark">V</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <div style="font-weight: bold; margin-bottom: 10px;">Rekomendasi (diisi oleh LSP):</div>
    <table style="width: 100%; border: 1px solid black;">
        <tr>
            <td style="width: 60%; border-right: 1px solid black; vertical-align: top;">
                Berdasarkan ketentuan persyaratan dasar, maka pemohon: <br>
                <strong>{{ $sertifikasi->rekomendasi_apl01 ?? '-' }}</strong> <br>
                sebagai peserta sertifikasi. <br><br>
                <strong>Catatan:</strong> <br>
                ..................................................................................
            </td>
            <td style="width: 40%; vertical-align: top; text-align: center;">
                <strong>Pemohon,</strong>
                <br><br>

                {{-- TANDA TANGAN ASESI --}}
                @if(!empty($sertifikasi->asesi->tanda_tangan))
                    <img
                        src="{{ getTtdBase64($sertifikasi->asesi->tanda_tangan) }}"
                        style="width: 100px; height: auto; display: block; margin: 0 auto;"
                    >
                @else
                    <br><br><br>
                @endif

                <br>

                <strong>{{ $asesi->nama_lengkap ?? '(.......................)' }}</strong>
                <br>
                Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-top: 1px solid black; padding: 10px;">
                <strong>Admin LSP:</strong> <br>
                Nama: {{ $admin->nama_admin ?? '.......................' }} <br>
                <br>
                @if(!empty($admin->tanda_tangan))
                    <img
                        src="{{ getTtdBase64($admin->tanda_tangan) }}"
                        style="width: 100px; height: auto; display: block; margin: 0 auto;"
                    >
                @else
                    <br><br><br>
                @endif
                <br>
                Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
            </td>
        </tr>
    </table>

</body>
</html>