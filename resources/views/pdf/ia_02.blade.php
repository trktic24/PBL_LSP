<!DOCTYPE html>
<html>
<head>
    <title>FR.IA.02 - Tugas Praktik Demonstrasi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; }
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        
        /* Box Petunjuk */
        .instruction-box { border: 1px solid #000; padding: 10px; margin-bottom: 15px; background-color: #f0f8ff; }
        .instruction-list { padding-left: 20px; margin: 5px 0; }
    </style>
</head>
<body>

    <div class="header">
        FR.IA.02. TUGAS PRAKTIK DEMONSTRASI
    </div>

    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="150"><strong>Skema Sertifikasi</strong></td>
            <td width="10">:</td>
            <td>{{ $sertifikasi->jadwal->skema->judul_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Skema</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->skema->kode_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>TUK</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->tuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesor</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->jadwal->skema->asesor->first()->nama_asesor ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama Asesi</strong></td>
            <td>:</td>
            <td>{{ $sertifikasi->asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
        </tr>
    </table>

    <hr>

    <div class="instruction-box">
        <strong>Petunjuk:</strong>
        <ul class="instruction-list">
            <li>Baca dan pelajari setiap instruksi kerja di bawah ini dengan cermat sebelum melaksanakan praktek.</li>
            <li>Klarifikasi kepada asesor kompetensi apabila ada hal-hal yang belum jelas.</li>
            <li>Laksanakan pekerjaan sesuai dengan urutan proses yang sudah ditetapkan.</li>
            <li>Seluruh proses kerja mengacu kepada SOP/WI yang dipersyaratkan (Jika Ada).</li>
        </ul>
    </div>

    <h3 style="margin-bottom: 5px;">A. Skenario Tugas</h3>
    
    <table>
        <thead>
            <tr>
                <th width="20%">Kelompok Pekerjaan</th>
                <th width="5%">No.</th>
                <th width="25%">Kode Unit</th>
                <th width="50%">Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($unitKompetensis as $index => $uk)
                <tr>
                    {{-- Logic Rowspan sederhana (Opsional, jika semua unit 1 kelompok) --}}
                    @if ($loop->first)
                        <td rowspan="{{ $unitKompetensis->count() }}">
                            {{ $uk->kelompokPekerjaan->nama_kelompok ?? 'Kelompok Pekerjaan Utama' }}
                        </td>
                    @endif
                    
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $uk->kode_unit }}</td>
                    <td>{{ $uk->judul_unit }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada unit kompetensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="no-border" style="margin-top: 10px;">
        <tr>
            <td width="30%" style="font-weight: bold;">Skenario Tugas:</td>
            <td style="border: 1px solid #ccc; padding: 8px;">
                {{ $skenario->skenario ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="30%" style="font-weight: bold; padding-top: 10px;">Perlengkapan & Peralatan:</td>
            <td style="border: 1px solid #ccc; padding: 8px; margin-top: 10px;">
                {{ $skenario->peralatan ?? '-' }}
            </td>
        </tr>
        <tr>
            <td width="30%" style="font-weight: bold; padding-top: 10px;">Waktu:</td>
            <td style="border: 1px solid #ccc; padding: 8px; margin-top: 10px;">
                {{ $skenario->waktu ?? '-' }}
            </td>
        </tr>
    </table>

    <br><br>
    <table class="no-border">
        <tr>
            <td style="width: 50%; text-align: center;">
                <br>
                Penyusun / Validator,
                <br><br><br><br>
                <strong>(.......................)</strong>
                {{-- Validator/Penyusun usually different, keeping placeholder or adding logic if needed. Leaving as text for now as per original. --}}
            </td>
            <td style="width: 50%; text-align: center;">
                Semarang, {{ date('d-m-Y') }}<br>
                Asesor Kompetensi,
                <br><br><br><br>
                <strong>{{ $sertifikasi->jadwal->skema->asesor->first()->nama_asesor ?? '(.......................)' }}</strong>
                @if($sertifikasi->jadwal->skema->asesor->first() && $sertifikasi->jadwal->skema->asesor->first()->tanda_tangan)
                     <br>
                     <img src="{{ getTtdBase64($sertifikasi->jadwal->skema->asesor->first()->tanda_tangan) }}" style="width: 100px; height: auto;">
                @else
                    <br><br><br><br>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>