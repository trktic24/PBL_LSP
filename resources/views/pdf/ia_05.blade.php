<!DOCTYPE html>
<html>
<head>
    <title>FR.IA.05 - Lembar Jawaban Pilihan Ganda</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 4px; vertical-align: middle; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; }
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 14px; text-align: center; }
        .signature-img {
            width: 120px;        /* atur sesuai kebutuhan */
            height: auto;        /* jaga rasio */
            max-height: 80px;    /* opsional */
        }
    </style>
</head>
<body>

    <div class="header">
        FR.IA.05. PERTANYAAN TERTULIS PILIHAN GANDA
    </div>

    <table class="no-border" style="margin-bottom: 20px;">
        <tr>
            <td width="150"><strong>Skema Sertifikasi</strong></td>
            <td width="10">:</td>
            <td>{{ $asesi->jadwal->skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $asesi->jadwal->skema->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>TUK</strong></td>
            <td>:</td>
            <td>{{ $asesi->jadwal->tuk->nama_lokasi ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $asesi->jadwal->asesor->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesi</strong></td>
            <td>:</td>
            <td>{{ $asesi->asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</td>
        </tr>
    </table>

    <div style="margin-bottom: 5px; font-weight: bold;">Lembar Jawaban & Penilaian:</div>
    
    <table>
        <thead>
            <tr>
                <th width="5%" rowspan="2">No.</th>
                <th width="15%" rowspan="2">Jawaban Asesi</th>
                <th width="20%" colspan="2">Pencapaian</th>
                
                <th width="2%" rowspan="2" style="background-color: #333;"></th>

                <th width="5%" rowspan="2">No.</th>
                <th width="15%" rowspan="2">Jawaban Asesi</th>
                <th width="20%" colspan="2">Pencapaian</th>
            </tr>
            <tr>
                <th>K</th>
                <th>BK</th>
                
                <th>K</th>
                <th>BK</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($semua_soal->chunk(2) as $chunk)
                @php $chunk = $chunk->values(); @endphp 
                <tr>
                    {{-- === KOLOM KIRI === --}}
                    @if (isset($chunk[0]))
                        @php 
                            $soal_kiri = $chunk[0];
                            $jawaban_kiri = $lembar_jawab->get($soal_kiri->id_soal_ia05);
                            $nomor_kiri = ($loop->index * 2) + 1;
                        @endphp
                        
                        <td class="text-center font-bold">{{ $nomor_kiri }}.</td>
                        <td class="text-center font-bold" style="color: blue;">
                            {{-- PERBAIKAN 1: Typo 'awaban' jadi 'jawaban' --}}
                            {{ $jawaban_kiri->jawaban_asesi_ia05 ?? 'A' }}
                        </td>
                        <td class="text-center check-mark">
                            {{-- PERBAIKAN 2: Cek logic 'ya' --}}
                            {{ ($jawaban_kiri && $jawaban_kiri->pencapaian_ia05 == 'ya') ? 'V' : '' }}
                        </td>
                        <td class="text-center check-mark">
                            {{-- PERBAIKAN 3: Cek logic 'tidak' --}}
                            {{ ($jawaban_kiri && $jawaban_kiri->pencapaian_ia05 == 'tidak') ? 'V' : '' }}
                        </td>
                    @endif

                    {{-- PEMBATAS TENGAH --}}
                    <td style="background-color: #ccc;"></td>

                    {{-- === KOLOM KANAN === --}}
                    @if (isset($chunk[1]))
                        @php 
                            $soal_kanan = $chunk[1]; 
                            $jawaban_kanan = $lembar_jawab->get($soal_kanan->id_soal_ia05);
                            $nomor_kanan = ($loop->index * 2) + 2;
                        @endphp
                        
                        <td class="text-center font-bold">{{ $nomor_kanan }}.</td>
                        <td class="text-center font-bold" style="color: blue;">
                            {{ $jawaban_kanan->jawaban_asesi_ia05 ?? 'A' }}
                        </td>
                        <td class="text-center check-mark">
                            {{-- PERBAIKAN 4: Cek logic 'ya' untuk Kanan --}}
                            {{ ($jawaban_kanan && $jawaban_kanan->pencapaian_ia05 == 'ya') ? 'V' : '' }}
                        </td>
                        <td class="text-center check-mark">
                            {{-- PERBAIKAN 5: Cek logic 'tidak' untuk Kanan --}}
                            {{ ($jawaban_kanan && $jawaban_kanan->pencapaian_ia05 == 'tidak') ? 'V' : '' }}
                        </td>
                    @else
                        {{-- Jika Ganjil, Kolom Kanan Kosong --}}
                        <td colspan="4" style="background-color: #f9f9f9;"></td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="9" class="text-center">Belum ada soal.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <td width="30%" style="background-color: #e2e8f0; font-weight: bold;">
                Umpan balik untuk asesi:
            </td>
            <td>
                {{ $umpan_balik ?? 'Tercapai' }}
            </td>
        </tr>
    </table>

    <br>
    {{-- TANDA TANGAN --}}
    <div class="mt-30" style="width: 100%;">
        <table style="border: none;">
            <tr>
                {{-- ASESI --}}
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="section-title">
                        Semarang, {{ \Carbon\Carbon::parse($asesi->jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
                    </div>

                    <div class="section-title">Asesi</div>

                    <table style="border: none;">
                        <tr>
                            <td style="width: 120px; border: none;">Nama</td>
                            <td style="width: 10px; border: none;">:</td>
                            <td style="border: none;">{{ $asesi->asesi->nama_lengkap }}</td>
                        </tr>

                        <tr>
                            <td style="border: none;">Tanda Tangan</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">
                                <img src="{{ getTtdBase64($asesi->asesi->tanda_tangan) }}" class="signature-img">
                            </td>
                        </tr>
                    </table>
                </td>

                {{-- PJ KEGIATAN --}}
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="section-title" style="margin-top: 30px;">Asesor Kompetensi</div>

                    <table style="border: none;">
                        <tr>
                            <td style="width: 120px; border: none;">Nama</td>
                            <td style="width: 10px; border: none;">:</td>
                            <td style="border: none;">{{ $asesi->jadwal->asesor->nama_lengkap }}</td>
                        </tr>

                        <tr>
                            <td style="border: none;">Tanda Tangan</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">
                                <img src="{{ getTtdBase64($asesi->jadwal->asesor->tanda_tangan) }}" class="signature-img">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>