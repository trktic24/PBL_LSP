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
            <td>{{ $asesi->jadwal->skema->judul_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $asesi->jadwal->skema->kode_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>TUK</strong></td>
            <td>:</td>
            <td>{{ $asesi->jadwal->tuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $asesi->jadwal->skema->asesor->first()->nama_asesor ?? '-' }}</td>
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
                            {{ $jawaban_kiri->teks_jawaban_asesi_ia05 ?? '-' }}
                        </td>
                        <td class="text-center check-mark">
                            {{ ($jawaban_kiri && $jawaban_kiri->pencapaian_ia05_iya == 1) ? 'V' : '' }}
                        </td>
                        <td class="text-center check-mark">
                            {{ ($jawaban_kiri && $jawaban_kiri->pencapaian_ia05_tidak == 1) ? 'V' : '' }}
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
                            {{ $jawaban_kanan->teks_jawaban_asesi_ia05 ?? '-' }}
                        </td>
                        <td class="text-center check-mark">
                            {{ ($jawaban_kanan && $jawaban_kanan->pencapaian_ia05_iya == 1) ? 'V' : '' }}
                        </td>
                        <td class="text-center check-mark">
                            {{ ($jawaban_kanan && $jawaban_kanan->pencapaian_ia05_tidak == 1) ? 'V' : '' }}
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
                {{ $umpan_balik ?? '-' }}
            </td>
        </tr>
    </table>

    <br>
    <table class="no-border">
        <tr>
            <td style="width: 50%; text-align: center;">
                <br>
                Asesi,
                <br><br><br><br>
                <strong>{{ $asesi->asesi->nama_lengkap ?? '(.......................)' }}</strong>
            </td>
            <td style="width: 50%; text-align: center;">
                Semarang, {{ date('d-m-Y') }}<br>
                Asesor Kompetensi,
                <br><br><br><br>
                <strong>{{ $asesi->jadwal->skema->asesor->first()->nama_asesor ?? '(.......................)' }}</strong>
            </td>
        </tr>
    </table>

</body>
</html>