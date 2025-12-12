<!DOCTYPE html>
<html>
<head>
    <title>FR.MAPA.01 - Merencanakan Aktivitas dan Proses Asesmen</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { font-size: 14px; font-weight: bold; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 4px; vertical-align: top; }
        th { background-color: #e2e8f0; text-align: center; font-weight: bold; }
        .no-border, .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f3f4f6; }
        .section-title { font-weight: bold; font-size: 12px; margin-top: 15px; margin-bottom: 5px; }
        
        /* Simbol Checklist */
        .check-mark { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        FR.MAPA.01. MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN
    </div>

    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="200"><strong>Skema Sertifikasi (KKNI/Okupasi/Klaster)</strong></td>
            <td width="10">:</td>
            <td>
                <strong>Judul:</strong> {{ $sertifikasi->jadwal->skema->judul_skema ?? '-' }} <br>
                <strong>Nomor:</strong> {{ $sertifikasi->jadwal->skema->kode_skema ?? '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">1. Menentukan Pendekatan Asesmen</div>
    <table>
        <tr>
            <td rowspan="5" width="5%" class="text-center font-bold">1.1</td>
            <td rowspan="5" width="20%" class="font-bold">Asesi</td>
            <td>
                <span class="check-mark">[{!! in_array('Hasil pelatihan dan atau pendidikan, Kurikulum & fasilitas telusur', $mapa01->pendekatan_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> 
                Hasil pelatihan dan / atau pendidikan...
            </td>
        </tr>
        <tr>
            <td>
                <span class="check-mark">[{!! in_array('Hasil pelatihan - belum berbasis kompetensi', $mapa01->pendekatan_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> 
                Hasil pelatihan - belum berbasis kompetensi
            </td>
        </tr>
        <tr>
            <td>
                <span class="check-mark">[{!! in_array('Pekerja berpengalaman - telusur', $mapa01->pendekatan_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> 
                Pekerja berpengalaman - telusur
            </td>
        </tr>
        <tr>
            <td>
                <span class="check-mark">[{!! in_array('Pekerja berpengalaman - belum berbasis kompetensi', $mapa01->pendekatan_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> 
                Pekerja berpengalaman - belum berbasis kompetensi
            </td>
        </tr>
        <tr>
            <td>
                <span class="check-mark">[{!! in_array('Pelatihan / belajar mandiri', $mapa01->pendekatan_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> 
                Pelatihan / belajar mandiri atau otodidak
            </td>
        </tr>

        <tr>
            <td></td>
            <td class="font-bold">Tujuan Sertifikasi</td>
            <td>
                @php $tujuan = $mapa01->tujuan_sertifikasi ?? 'Sertifikasi'; @endphp
                [{!! $tujuan == 'Sertifikasi' ? 'V' : '&nbsp;&nbsp;' !!}] Sertifikasi &nbsp;&nbsp;
                [{!! $tujuan == 'PKT' ? 'V' : '&nbsp;&nbsp;' !!}] PKT &nbsp;&nbsp;
                [{!! $tujuan == 'RPL' ? 'V' : '&nbsp;&nbsp;' !!}] RPL &nbsp;&nbsp;
                [{!! $tujuan == 'Lainnya' ? 'V' : '&nbsp;&nbsp;' !!}] Lainnya
            </td>
        </tr>

        <tr>
            <td rowspan="3"></td>
            <td rowspan="3" class="font-bold">Konteks Asesmen</td>
            <td>
                <strong>Lingkungan:</strong><br>
                <span class="check-mark">[{!! in_array('Tempat kerja nyata', $mapa01->konteks_lingkungan ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Tempat kerja nyata &nbsp;&nbsp;
                <span class="check-mark">[{!! in_array('Tempat kerja simulasi', $mapa01->konteks_lingkungan ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Tempat kerja simulasi
            </td>
        </tr>
        <tr>
            <td>
                <strong>Peluang mengumpulkan bukti:</strong><br>
                <span class="check-mark">[{!! in_array('Tersedia', $mapa01->peluang_bukti ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Tersedia &nbsp;&nbsp;
                <span class="check-mark">[{!! in_array('Terbatas', $mapa01->peluang_bukti ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Terbatas
            </td>
        </tr>
        <tr>
            <td>
                <strong>Siapa yang melakukan asesmen:</strong><br>
                <span class="check-mark">[{!! in_array('Lembaga Sertifikasi', $mapa01->pelaksana_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Lembaga Sertifikasi<br>
                <span class="check-mark">[{!! in_array('Organisasi Pelatihan', $mapa01->pelaksana_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Organisasi Pelatihan<br>
                <span class="check-mark">[{!! in_array('Asesor Perusahaan', $mapa01->pelaksana_asesmen ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Asesor Perusahaan
            </td>
        </tr>
        
        <tr>
            <td rowspan="4"></td>
            <td rowspan="4" class="font-bold">Konfirmasi dengan orang relevan</td>
            <td><span class="check-mark">[{!! in_array('Manajer sertifikasi LSP', $mapa01->konfirmasi_relevan ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Manajer sertifikasi LSP</td>
        </tr>
        <tr><td><span class="check-mark">[{!! in_array('Master Asesor / Master Trainer / Lead Asesor Kompetensi', $mapa01->konfirmasi_relevan ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Master Asesor...</td></tr>
        <tr><td><span class="check-mark">[{!! in_array('Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar', $mapa01->konfirmasi_relevan ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Manajer Pelatihan...</td></tr>
        <tr><td><span class="check-mark">[{!! in_array('Manajer atau Supervisor ditempat kerja', $mapa01->konfirmasi_relevan ?? []) ? 'V' : '&nbsp;&nbsp;' !!}]</span> Manajer atau Supervisor...</td></tr>

        <tr>
            <td class="font-bold text-center">1.2</td>
            <td class="font-bold">Standar Industri</td>
            <td>
                <strong>Standar Kompetensi:</strong> {{ $mapa01->standar_kompetensi ?? '-' }} <br>
                <strong>Spesifikasi Produk:</strong> {{ $mapa01->spesifikasi_produk ?? '-' }} <br>
                <strong>Pedoman Khusus:</strong> {{ $mapa01->pedoman_khusus ?? '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">2. Perencanaan Asesmen</div>
    
    <div style="font-weight: bold; margin-bottom: 5px;">Kelompok Pekerjaan 1</div>
    <table>
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th width="30%">Kode Unit</th>
                <th width="60%">Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($mapa01->kelompok1) && count($mapa01->kelompok1) > 0)
                @foreach($mapa01->kelompok1 as $idx => $unit)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}.</td>
                    <td class="text-center">{{ $unit['kode_unit'] ?? '-' }}</td>
                    <td>{{ $unit['judul_unit'] ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="3" class="text-center">Data Unit belum diisi.</td></tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 10px;"></div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="25%">Unit Kompetensi</th>
                <th rowspan="2" width="25%">Bukti-Bukti</th>
                <th colspan="3">Jenis Bukti</th>
                <th colspan="6">Metode dan Perangkat Asesmen</th>
            </tr>
            <tr>
                <th width="5%">L</th>
                <th width="5%">TL</th>
                <th width="5%">T</th>
                <th width="5%">Obs</th>
                <th width="5%">Keg</th>
                <th width="5%">Tny</th>
                <th width="5%">Ver</th>
                <th width="5%">Rev</th>
                <th width="5%">VPK</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($mapa01->unit_kompetensi) && count($mapa01->unit_kompetensi) > 0)
                @foreach($mapa01->unit_kompetensi as $idx => $u)
                <tr>
                    <td>{{ $idx + 1 }}. {{ $u['judul_unit'] ?? 'Unit ' . ($idx+1) }}</td> <td>{{ $u['bukti'] ?? '-' }}</td>
                    
                    <td class="text-center check-mark">{{ isset($u['L']) ? 'V' : '' }}</td>
                    <td class="text-center check-mark">{{ isset($u['TL']) ? 'V' : '' }}</td>
                    <td class="text-center check-mark">{{ isset($u['T']) ? 'V' : '' }}</td>
                    
                    <td class="text-center check-mark">{{ isset($u['observasi']) ? 'V' : '' }}</td>
                    <td class="text-center check-mark">{{ isset($u['kegiatan_terstruktur']) ? 'V' : '' }}</td>
                    <td class="text-center check-mark">{{ isset($u['tanya_jawab']) ? 'V' : '' }}</td>
                    <td class="text-center check-mark">{{ isset($u['verifikasi_portofolio']) ? 'V' : '' }}</td>
                    <td class="text-center check-mark">{{ isset($u['reviu_produk']) ? 'V' : '' }}</td>
                    <td class="text-center check-mark">{{ isset($u['verifikasi_pihak_ketiga']) ? 'V' : '' }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="11" class="text-center">Belum ada data bukti.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">3. Modifikasi dan Kontekstualisasi</div>
    <table>
        <tr>
            <td width="5%" class="text-center font-bold">3.1</td>
            <td width="35%">a. Karakteristik Kandidat</td>
            <td>
                <strong>[{!! ($mapa01->karakteristik_ada_checkbox ?? false) ? 'V' : '&nbsp;&nbsp;' !!}] Ada</strong> <br>
                {{ $mapa01->karakteristik_kandidat ?? '-' }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>b. Kebutuhan Kontekstualisasi</td>
            <td>
                <strong>[{!! ($mapa01->kebutuhan_kontekstualisasi_checkbox ?? false) ? 'V' : '&nbsp;&nbsp;' !!}] Ada</strong> <br>
                {{ $mapa01->kebutuhan_kontekstualisasi ?? '-' }}
            </td>
        </tr>
        <tr>
            <td class="text-center font-bold">3.2</td>
            <td>Saran dari paket pelatihan</td>
            <td>
                <strong>[{!! ($mapa01->saran_paket_checkbox ?? false) ? 'V' : '&nbsp;&nbsp;' !!}] Ada</strong> <br>
                {{ $mapa01->saran_paket_pelatihan ?? '-' }}
            </td>
        </tr>
        <tr>
            <td class="text-center font-bold">3.3</td>
            <td>Penyesuaian perangkat asesmen</td>
            <td>
                <strong>[{!! ($mapa01->penyesuaian_perangkat_checkbox ?? false) ? 'V' : '&nbsp;&nbsp;' !!}] Ada</strong> <br>
                {{ $mapa01->penyesuaian_perangkat_asesmen ?? '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">Penyusun dan Validator</div>
    <table>
        <thead>
            <tr>
                <th>STATUS</th>
                <th>NO</th>
                <th>NAMA</th>
                <th>NOMOR MET</th>
                <th>TANDA TANGAN DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="2" class="text-center font-bold">PENYUSUN</td>
                <td class="text-center">1</td>
                <td>{{ $mapa01->penyusun[0]['nama'] ?? '-' }}</td>
                <td>{{ $mapa01->penyusun[0]['nomor_met'] ?? '-' }}</td>
                <td>{{ $mapa01->penyusun[0]['ttd_tanggal'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>{{ $mapa01->penyusun[1]['nama'] ?? '-' }}</td>
                <td>{{ $mapa01->penyusun[1]['nomor_met'] ?? '-' }}</td>
                <td>{{ $mapa01->penyusun[1]['ttd_tanggal'] ?? '-' }}</td>
            </tr>
            <tr>
                <td rowspan="2" class="text-center font-bold">VALIDATOR</td>
                <td class="text-center">1</td>
                <td>{{ $mapa01->validator[0]['nama'] ?? '-' }}</td>
                <td>{{ $mapa01->validator[0]['nomor_met'] ?? '-' }}</td>
                <td>{{ $mapa01->validator[0]['ttd_tanggal'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>{{ $mapa01->validator[1]['nama'] ?? '-' }}</td>
                <td>{{ $mapa01->validator[1]['nomor_met'] ?? '-' }}</td>
                <td>{{ $mapa01->validator[1]['ttd_tanggal'] ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>