<!DOCTYPE html>
<html>
<head>
    <title>FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        .header { font-weight: bold; font-size: 12pt; text-align: center; margin-bottom: 10px; border-bottom: 2px solid black; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; page-break-inside: avoid; }
        th, td { border: 1px solid black; padding: 4px; vertical-align: top; }
        .no-border { border: none !important; }
        .no-border td { border: none !important; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f0f0f0; }
        .checkbox { display: inline-block; width: 12px; height: 12px; border: 1px solid black; margin-right: 5px; position: relative; top: 2px; }
        .checkbox.checked { background-color: black; }
        .input-line { border-bottom: 1px solid black; display: inline-block; min-width: 100px; }
        .text-sm { font-size: 9pt; }
    </style>
</head>
<body>

    <div class="header">
        FR.MAPA.01. MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN
    </div>

    {{-- INFO SKEMA --}}
    <table class="no-border" style="margin-bottom: 15px;">
        <tr>
            <td width="200" class="font-bold">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td width="10">:</td>
            <td>
                <strong>Judul:</strong> Junior Web Programmer<br>
                <strong>Nomor:</strong> -
            </td>
        </tr>
    </table>

    {{-- 1. MENENTUKAN PENDEKATAN ASESMEN --}}
    <div class="font-bold" style="margin-bottom: 5px;">1. Menentukan Pendekatan Asesmen</div>
    <table>
        <tr>
            <td rowspan="5" class="text-center font-bold" width="30">1.1</td>
            <td rowspan="5" class="font-bold" width="100">Asesi</td>
            <td>
                <div class="checkbox"></div> Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi
            </td>
        </tr>
        <tr>
            <td>
                <div class="checkbox"></div> Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.
            </td>
        </tr>
        <tr>
            <td>
                <div class="checkbox"></div> Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi
            </td>
        </tr>
        <tr>
            <td>
                <div class="checkbox"></div> Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.
            </td>
        </tr>
        <tr>
            <td>
                <div class="checkbox"></div> Pelatihan / belajar mandiri atau otodidak.
            </td>
        </tr>
        {{-- Tujuan Sertifikasi --}}
        <tr>
            <td></td>
            <td class="font-bold">Tujuan Sertifikasi</td>
            <td>
                <span style="margin-right: 15px;"><div class="checkbox checked"></div> Sertifikasi</span>
                <span style="margin-right: 15px;"><div class="checkbox"></div> PKT</span>
                <span style="margin-right: 15px;"><div class="checkbox"></div> RPL</span>
                <span style="margin-right: 15px;"><div class="checkbox"></div> Lainnya</span>
            </td>
        </tr>
        {{-- Konteks Asesmen --}}
        <tr>
            <td rowspan="3"></td>
            <td rowspan="3" class="font-bold">Konteks Asesmen</td>
            <td>
                <strong>Lingkungan:</strong>
                <span style="margin-left: 10px;"><div class="checkbox"></div> Tempat kerja nyata</span>
                <span style="margin-left: 10px;"><div class="checkbox"></div> Tempat kerja simulasi</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Peluang mengumpulkan bukti:</strong>
                <span style="margin-left: 10px;"><div class="checkbox"></div> Tersedia</span>
                <span style="margin-left: 10px;"><div class="checkbox"></div> Terbatas</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Siapa yang melakukan asesmen:</strong><br>
                <div class="checkbox"></div> Lembaga Sertifikasi<br>
                <div class="checkbox"></div> Organisasi Pelatihan<br>
                <div class="checkbox"></div> Asesor Perusahaan
            </td>
        </tr>
        {{-- Konfirmasi Orang Relevan --}}
        <tr>
            <td rowspan="4"></td>
            <td rowspan="4" class="font-bold">Konfirmasi dengan orang yang relevan</td>
            <td><div class="checkbox"></div> Manajer sertifikasi LSP</td>
        </tr>
        <tr>
            <td><div class="checkbox"></div> Master Asesor / Master Trainer / Lead Asesor Kompetensi</td>
        </tr>
        <tr>
            <td><div class="checkbox"></div> Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</td>
        </tr>
        <tr>
            <td><div class="checkbox"></div> Manajer atau Supervisor ditempat kerja</td>
        </tr>
        {{-- 1.2 Standar Industri --}}
        <tr>
            <td class="text-center font-bold">1.2</td>
            <td class="font-bold">Standar Industri / Tempat Kerja</td>
            <td>
                <div style="margin-bottom: 5px;"><strong>Standar Kompetensi:</strong> SKKNI Junior Web Programmer</div>
                <div style="margin-bottom: 5px;"><strong>Spesifikasi Produk:</strong> Aplikasi Web Sederhana</div>
                <div><strong>Pedoman Khusus:</strong> SOP Pengembangan Perangkat Lunak</div>
            </td>
        </tr>
    </table>

    {{-- 2. PERENCANAAN ASESMEN --}}
    <div class="font-bold" style="margin-bottom: 5px; margin-top: 15px;">2. Perencanaan Asesmen</div>
    
    <div style="font-size: 9pt; font-weight: bold; margin-bottom: 5px;">Kelompok Pekerjaan 1</div>
    <table style="background-color: #f9f9f9; width: 60%;">
        <thead>
            <tr>
                <th width="30">No.</th>
                <th>Kode Unit</th>
                <th>Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1.</td>
                <td class="text-center">J.620100.004.02</td>
                <td>Mengimplementasikan User Interface</td>
            </tr>
            <tr>
                <td class="text-center">2.</td>
                <td class="text-center">J.620100.011.01</td>
                <td>Melakukan Debugging</td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL CHECKLIST BESAR --}}
    <table style="font-size: 8pt;">
        <thead>
            <tr class="bg-gray">
                <th rowspan="2" width="15%">Unit Kompetensi</th>
                <th rowspan="2" width="25%">Bukti-Bukti<br><i>(Kinerja, Produk, Portofolio, dan / atau Pengetahuan)</i></th>
                <th colspan="3">Jenis Bukti</th>
                <th colspan="6">Metode dan Perangkat Asesmen</th>
            </tr>
            <tr class="bg-gray">
                <th width="3%">L</th>
                <th width="3%">TL</th>
                <th width="3%">T</th>
                <th width="8%">Observasi langsung</th>
                <th width="8%">Kegiatan Terstruktur</th>
                <th width="8%">Tanya Jawab</th>
                <th width="8%">Verifikasi Portofolio</th>
                <th width="8%">Reviu Produk</th>
                <th width="8%">Verifikasi Pihak Ketiga</th>
            </tr>
        </thead>
        <tbody>
            {{-- Unit 1 --}}
            <tr>
                <td><strong>1. Mengimplementasikan User Interface</strong></td>
                <td>
                    {{-- Area untuk Bukti --}}
                    <div style="height: 50px; border-bottom: 1px dotted #ccc;"></div>
                </td>
                
                <td class="text-center"><div class="checkbox"></div></td> {{-- L --}}
                <td class="text-center"><div class="checkbox"></div></td> {{-- TL --}}
                <td class="text-center"><div class="checkbox"></div></td> {{-- T --}}

                <td class="text-center"><div class="checkbox checked"></div></td> {{-- Observasi (Checked) --}}
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox checked"></div></td> {{-- Reviu Produk (Checked) --}}
                <td class="text-center"><div class="checkbox"></div></td>
            </tr>

            {{-- Unit 2 --}}
            <tr>
                <td><strong>2. Melakukan Debugging</strong></td>
                <td>
                    <div style="height: 50px; border-bottom: 1px dotted #ccc;"></div>
                </td>
                
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox"></div></td>

                <td class="text-center"><div class="checkbox checked"></div></td> {{-- Observasi (Checked) --}}
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox checked"></div></td> {{-- Tanya Jawab (Checked) --}}
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox"></div></td>
                <td class="text-center"><div class="checkbox"></div></td>
            </tr>
        </tbody>
    </table>

    {{-- 3. MODIFIKASI DAN KONTEKSTUALISASI --}}
    <div class="font-bold" style="margin-bottom: 5px; margin-top: 15px;">3. Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi</div>
    <table>
        {{-- 3.1 a --}}
        <tr>
            <td rowspan="2" class="text-center font-bold" width="30">3.1</td>
            <td width="200">a. Karakteristik Kandidat</td>
            <td>
                <div style="margin-bottom: 5px;"><div class="checkbox"></div> Ada / Tidak ada karakteristik khusus:</div>
                <div style="border: 1px solid #ccc; height: 40px;"></div>
            </td>
        </tr>
        {{-- 3.1 b --}}
        <tr>
            <td>b. Kebutuhan kontekstualisasi</td>
            <td>
                <div style="margin-bottom: 5px;"><div class="checkbox"></div> Ada / Tidak ada kebutuhan kontekstualisasi:</div>
                <div style="border: 1px solid #ccc; height: 40px;"></div>
            </td>
        </tr>
        {{-- 3.2 --}}
        <tr>
            <td class="text-center font-bold">3.2</td>
            <td>Saran dari paket pelatihan / pengembang</td>
            <td>
                <div style="margin-bottom: 5px;"><div class="checkbox"></div></div>
                <div style="border: 1px solid #ccc; height: 40px;"></div>
            </td>
        </tr>
        {{-- 3.3 --}}
        <tr>
            <td class="text-center font-bold">3.3</td>
            <td>Penyesuaian perangkat asesmen</td>
            <td>
                <div style="margin-bottom: 5px;"><div class="checkbox"></div></div>
                <div style="border: 1px solid #ccc; height: 40px;"></div>
            </td>
        </tr>
    </table>

    {{-- KONFIRMASI DENGAN ORANG RELEVAN --}}
    <div class="font-bold" style="margin-bottom: 5px; margin-top: 15px;">Konfirmasi dengan orang yang relevan</div>
    <table>
        <thead class="bg-gray">
            <tr>
                <th colspan="2">Orang yang relevan</th>
                <th>Nama</th>
                <th>Tandatangan dan Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center" width="30"><div class="checkbox"></div></td>
                <td>Manajer sertifikasi LSP</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center"><div class="checkbox"></div></td>
                <td>Master Asesor / Master Trainer / Lead Asesor Kompetensi</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center"><div class="checkbox"></div></td>
                <td>Manajer pelatihan Lembaga Training terakreditasi</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center"><div class="checkbox"></div></td>
                <td>Manajer atau supervisor ditempat kerja</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- PENYUSUN DAN VALIDATOR --}}
    <div class="font-bold" style="margin-bottom: 5px; margin-top: 15px;">PENYUSUN DAN VALIDATOR</div>
    <table>
        <thead class="bg-gray">
            <tr>
                <th width="15%">STATUS</th>
                <th width="5%">NO</th>
                <th width="30%">NAMA</th>
                <th width="20%">NOMOR MET</th>
                <th>TANDA TANGAN DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            {{-- Penyusun --}}
            <tr>
                <td rowspan="2" class="text-center font-bold align-middle bg-gray">PENYUSUN</td>
                <td class="text-center">1</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            {{-- Validator --}}
            <tr>
                <td rowspan="2" class="text-center font-bold align-middle bg-gray">VALIDATOR</td>
                <td class="text-center">1</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>