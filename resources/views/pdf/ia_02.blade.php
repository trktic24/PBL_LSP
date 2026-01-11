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

    <table class="header-table" border="0" style="width: 100%;">
        <tr>
            <td width="150"><b>Skema Sertifikasi</b></td>
            <td width="10">:</td>
            {{-- Panggil dari variabel $skema atau lewat $jadwal --}}
            <td>{{ $skema->judul_skema ?? $skema->nama_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Nomor Skema</b></td>
            <td>:</td>
            <td>{{ $skema->nomor_skema ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>TUK</b></td>
            <td>:</td>
            <td>{{ $jadwal->masterTuk->nama_tuk ?? 'Tempat Kerja' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesor</b></td>
            <td>:</td>
            {{-- Cek user->name, user->nama, atau nama_asesor --}}
            <td>{{ $jadwal->asesor->user->name ?? $jadwal->asesor->user->nama ?? $jadwal->asesor->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Nama Asesi</b></td>
            <td>:</td>
            {{-- Ambil langsung dari variabel $asesi yang dikirim controller --}}
            <td>{{ $asesi->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Tanggal</b></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
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
    {{-- TANDA TANGAN --}}
    <table class="no-border" style="margin-top: 30px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <br>
                Penyusun / Validator,
                <br><br><br><br>
                <strong>(.......................)</strong>
            </td>
            <td style="width: 50%; text-align: center;">
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