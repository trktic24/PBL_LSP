<!DOCTYPE html>
<html>
<head>
    <title>FR.MAPA.02 - Peta Instrumen Asesmen</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 4px; vertical-align: middle; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; } /* Warna abu mirip Tailwind bg-gray-200 */
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        
        /* Simbol Centang */
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 14px; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        FR.MAPA.02. PETA INSTRUMEN ASESMEN
    </div>

    <table class="no-border" style="margin-bottom: 20px;">
        <tr>
            <td width="150"><strong>Skema Sertifikasi</strong></td>
            <td width="10">:</td>
            <td>{{ $sertifikasi->jadwal->skema->nama_skema ?? '[Judul Skema Kosong]' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->skema->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>TUK</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->tuk->nama_lokasi ?? '-' }}</td>
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
            <td>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</td>
        </tr>
    </table>

    @if($sertifikasi->jadwal->skema->kelompokPekerjaan->isEmpty())
        <div style="color: red; border: 1px solid red; padding: 10px;">
            DATA KELOMPOK PEKERJAAN KOSONG / BELUM DIINPUT DI MASTER DATA.
        </div>
    @else
        @foreach($sertifikasi->jadwal->skema->kelompokPekerjaan as $kp)
            
            <div style="margin-bottom: 5px; font-weight: bold; font-size: 12px;">
                Kelompok Pekerjaan: {{ $kp->nama_kelompok_pekerjaan }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="25%">Kode Unit</th>
                        <th>Judul Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kp->unitKompetensi as $index => $unit)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}.</td>
                        <td class="text-center">{{ $unit->kode_unit }}</td>
                        <td>{{ $unit->judul_unit }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-bottom: 5px; margin-top: 10px;"><strong>Potensi Asesi:</strong></div>
            
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" width="5%">No.</th>
                        <th rowspan="2">Instrumen Asesmen</th>
                        <th colspan="5">Potensi Asesi **</th>
                    </tr>
                    <tr>
                        <th width="6%">1</th>
                        <th width="6%">2</th>
                        <th width="6%">3</th>
                        <th width="6%">4</th>
                        <th width="6%">5</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $instruments = [
                            'FR.IA.01. CL - Ceklis Observasi',
                            'FR.IA.02. TPD - Tugas Praktik Demonstrasi',
                            'FR.IA.03. PMO - Pertanyaan Untuk Mendukung Observasi',
                            'FR.IA.04A. DIT - Daftar Instruksi Terstruktur (Proyek)',
                            'FR.IA.04B. DIT - Daftar Instruksi Terstruktur (Lainnya)',
                            'FR.IA.05. DPT - Daftar Pertanyaan Tertulis Pilihan Ganda',
                            'FR.IA.06. DPT - Daftar Pertanyaan Tertulis Pilihan Esai',
                            'FR.IA.07. DPL - Daftar Pertanyaan Lisan',
                            'FR.IA.08. CVP - Ceklis Verifikasi Portofolio',
                            'FR.IA.09. PW - Pertanyaan Wawancara',
                            'FR.IA.10. VPK - Verifikasi Pihak Ketiga',
                            'FR.IA.11. CRP - Ceklis Reviu Produk',
                        ];
                    @endphp

                    @foreach($instruments as $index => $instrument)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ $instrument }}</td>
                        
                        @for($i = 1; $i <= 5; $i++)
                            <td class="check-mark bg-gray">
                                @if(isset($mapa02Map[$kp->id_kelompok_pekerjaan][$instrument]) && $mapa02Map[$kp->id_kelompok_pekerjaan][$instrument] == $i)
                                    X
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="font-size: 10px; margin-bottom: 25px; color: #555;">
                **) Keterangan Potensi: 1:Sangat Baik, 2:Baik, 3:Cukup, 4:Kurang, 5:Sangat Kurang
            </div>

        @endforeach
    @endif

    <br>
    {{-- TANDA TANGAN --}}
    <table class="no-border" style="margin-top: 30px;">
        <tr>
            <td style="width: 70%;"></td>
            <td style="text-align: center;">
                Semarang, {{ date('d-m-Y') }}<br>
                Asesor Kompetensi,<br><br>
                @if($sertifikasi->jadwal->asesor && $sertifikasi->jadwal->asesor->tanda_tangan)
                    <img src="{{ getTtdBase64($sertifikasi->jadwal->asesor->tanda_tangan) }}" style="height: 60px; width: auto;">
                    <br>
                @else
                    <br><br><br>
                @endif
                <strong>{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? '(.......................)' }}</strong>
            </td>
        </tr>
    </table>

</body>
</html>