<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara Asesmen</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 90px;
            display: block;
            margin: 0 auto 10px auto;
        }

        .box {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #f0f0f0;
            padding: 6px;
            border: 1px solid #ccc;
            font-size: 11px;
        }

        td {
            padding: 6px;
            border: 1px solid #ccc;
            font-size: 11px;
        }

        .signature-img {
            width: 110px;
            height: auto;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .mt-30 { margin-top: 30px; }
    </style>
</head>

<body>

    {{-- LOGO --}}
    <div style="text-align: center; width: 100%; margin-bottom: 10px;">
        <img 
            src="{{ public_path('images/Logo_LSP_No_BG.png') }}" 
            style="width: 130px;"
        >
    </div>

    <div class="title">Berita Acara Asesmen</div>

    {{-- PARAGRAF PEMBUKA --}}
    <div class="box">
        <p style="text-align: justify;">
            Pada hari ini, Hari/Tanggal: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }},
            Waktu: Pukul {{ \Carbon\Carbon::parse($jadwal->waktu_mulai ?? '10:20:00')->format('H:i') }} s/d Selesai, 
            bertempat di TUK {{ $jadwal->masterTuk->nama_lokasi }}, telah dilaksanakan proses asesmen terhadap asesi 
            pada sektor/sub sektor/bidang profesi <strong>{{ $jadwal->skema->nama_skema }}</strong> 
            yang diikuti oleh <strong>{{ $pendaftar->count() }} orang peserta</strong>.
            <br><br>
            Dari hasil asesmen, peserta yang dinyatakan <strong>kompeten</strong> adalah 
            <strong>{{ $jumlahKompeten > 0 ? $jumlahKompeten . ' orang peserta' : 'tidak ada' }}</strong> dan yang <strong>belum kompeten</strong> 
            adalah <strong>{{ $jumlahBelumKompeten > 0 ? $jumlahBelumKompeten . ' orang peserta' : 'tidak ada' }}</strong> dengan perincian sebagai berikut:
        </p>
    </div>

    {{-- TABEL PESERTA --}}
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">ID</th>
                <th>Nama Peserta</th>
                <th>Hasil Asesmen</th>
                <th>Rekomendasi/Tindak Lanjut</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pendaftar as $data)
                <tr>
                    <td style="text-align: center;">{{ $data->id_data_sertifikasi_asesi }}</td>
                    <td>{{ $data->asesi->nama_lengkap }}</td>
                    <td>{{ $data->komentarAk05->rekomendasi === 'K' ? 'Kompeten' : 'Belum Kompeten' }}</td>
                    <td>{{ $data->komentarAk05->rekomendasi === 'K' ? 'Terbitkan Sertifikat' : 'Pengulangan Asesmen' }}</td>
                    <td>{{ $data->komentarAk05->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada peserta.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PARAGRAF PENUTUP --}}
    <div class="box" style="margin-top: 20px;">
        <p style="text-align: justify;">
            Demikian berita acara ini dibuat dengan sebenarnya, untuk digunakan sebagaimana mestinya.
        </p>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="mt-30" style="width: 100%;">
        <table style="border: none;">
            <tr>
                {{-- ASESOR --}}
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="section-title">
                        Semarang, {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->isoFormat('D MMMM Y') }}
                    </div>

                    <div class="section-title">Asesor</div>

                    <table style="border: none;">
                        <tr>
                            <td style="width: 120px; border: none;">Nama</td>
                            <td style="width: 10px; border: none;">:</td>
                            <td style="border: none;">{{ $jadwal->asesor->nama_lengkap }}</td>
                        </tr>

                        <tr>
                            <td style="border: none;">Tanda Tangan</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('private_docs')->path($jadwal->asesor->tanda_tangan) }}" class="signature-img">
                            </td>
                        </tr>
                    </table>
                </td>

                {{-- PJ KEGIATAN --}}
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="section-title" style="margin-top: 30px;">Penanggungjawab Kegiatan</div>

                    <table style="border: none;">
                        <tr>
                            <td style="width: 120px; border: none;">Nama</td>
                            <td style="width: 10px; border: none;">:</td>
                            <td style="border: none;">Ajeng Febria H.</td>
                        </tr>

                        <tr>
                            <td style="border: none;">Tanda Tangan</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('private_docs')->path($jadwal->asesor->tanda_tangan) }}" class="signature-img">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>