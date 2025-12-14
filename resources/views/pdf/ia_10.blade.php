<!DOCTYPE html>
<html>
<head>
    <title>FR.IA.10 - Verifikasi Pihak Ketiga</title>
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
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 14px; text-align: center; }
        h3 { font-size: 13px; margin-top: 15px; margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="header">
        FR.IA.10. VPK - VERIFIKASI PIHAK KETIGA
    </div>

    <table class="no-border" style="margin-bottom: 10px;">
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
            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
        </tr>
    </table>

    <hr>

    <h3>A. Data Pihak Ketiga</h3>
    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="150"><strong>Nama Pengawas</strong></td>
            <td width="10">:</td>
            <td>{{ $header->nama_pengawas ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tempat Kerja</strong></td>
            <td>:</td>
            <td>{{ $header->tempat_kerja ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Alamat</strong></td>
            <td>:</td>
            <td>{{ $header->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Telepon</strong></td>
            <td>:</td>
            <td>{{ $header->telepon ?? '-' }}</td>
        </tr>
    </table>

    <h3>B. Daftar Pertanyaan</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="75%">Pertanyaan</th>
                <th width="10%">Ya</th>
                <th width="10%">Tidak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftar_soal as $index => $soal)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $soal->pertanyaan }}</td>
                <td class="check-mark bg-gray">
                    {{ ($soal->jawaban_pilihan_iya_tidak == 1) ? 'V' : '' }}
                </td>
                <td class="check-mark bg-gray">
                    {{ ($soal->jawaban_pilihan_iya_tidak === 0) ? 'V' : '' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Belum ada pertanyaan checklist.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>C. Detail Verifikasi</h3>
    <table>
        @php
            $essay_labels = [
                'Apa hubungan Anda dengan asesi?',
                'Berapa lama Anda bekerja dengan asesi?',
                'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)',
                'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?',
                'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                'Ada komentar lain:'
            ];
        @endphp

        @foreach($essay_labels as $label)
        <tr>
            <td width="40%" style="background-color: #f9f9f9;"><strong>{{ $label }}</strong></td>
            <td width="60%">
                {{ $essay_answers[$label] ?? '-' }}
            </td>
        </tr>
        @endforeach
    </table>

    <br><br>
    <table class="no-border">
        <tr>
            <td style="width: 50%; text-align: center;">
                Pihak Ketiga (Supervisor),
                <br><br><br><br>
                <strong>{{ $header->nama_pengawas ?? '(.......................)' }}</strong>
            </td>
            <td style="width: 50%; text-align: center;">
                Asesor Kompetensi,
                <br><br><br><br>
                <strong>{{ $asesi->jadwal->skema->asesor->first()->nama_asesor ?? '(.......................)' }}</strong>
            </td>
        </tr>
    </table>

</body>
</html>