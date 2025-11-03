@extends('layouts.app')

@push('css')
    <style>
    /* CSS Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* CSS untuk 'body' dan 'main-content' DIHAPUS dari sini. 
       Style tersebut harus ada di file app.css utama Anda 
       agar tidak merusak layout.
    */

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 16px;
    }

    .form-header img {
        height: 40px; /* Logo BNSP dari gambar */
    }

    .form-header .title-block {
        text-align: right;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: bold;
        color: #11182c;
    }

    .form-body {
        max-width: 900px;
        margin: 0 auto; /* DIPERBAIKI: Agar form rata tengah */
    }

    /* Styling untuk Konten FR.IA.10 */
    .metadata-grid {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 8px 16px;
        margin-bottom: 24px;
    }

    .metadata-grid label {
        font-weight: 600;
        color: #11182c;
    }
    
    .metadata-grid span, .metadata-grid div {
        display: flex;
        align-items: center;
    }

    .metadata-grid input[type="text"] {
        border: none;
        border-bottom: 1px solid #ccc;
        padding: 4px 0;
        font-size: 0.9rem;
        width: 100%;
    }

    .metadata-grid input[type="text"]:focus {
        outline: none;
        border-bottom-color: #3b82f6;
    }

    .metadata-grid .radio-group input {
        margin-left: 10px;
        margin-right: 4px;
    }

    .guide-box {
        background-color: #f3f4f6;
        border: 1px solid #e5e7eb;
        padding: 16px;
        border-radius: 8px;
        margin: 24px 0;
        font-size: 0.875rem;
    }

    .guide-box h3 {
        font-weight: bold;
        color: #11182c;
        margin-bottom: 8px;
    }
    
    .guide-box ul {
        list-style-position: inside;
    }
    
    .guide-box li {
        margin-bottom: 4px;
        color: #374151;
    }

    .form-section {
        margin-bottom: 24px;
    }
    
    .form-section h2 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #11182c;
        border-bottom: 1px solid #ddd;
        padding-bottom: 8px;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 0.875rem;
        color: #11182c;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    
    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .form-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }

    .form-table th,
    .form-table td {
        border: 1px solid #d1d5db;
        padding: 10px 12px;
        text-align: left;
        font-size: 0.875rem;
    }

    .form-table th {
        background-color: #000000;
        font-weight: 600;
        color: #ffffff;
    }

    .form-table td:nth-child(2),
    .form-table td:nth-child(3) {
        text-align: center;
        width: 60px;
    }
    
    .form-table input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    .signature-section {
        margin-top: 32px;
        display: flex;
        justify-content: flex-end; /* Hanya asesor di kanan */
    }

    .signature-box {
        text-align: center;
        width: 280px;
    }

    .signature-box label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #11182c;
    }

    .signature-pad {
        height: 120px;
        margin: 16px 0 8px 0;
        border-bottom: 2px solid #11182c;
    }

    .signature-box input[type="text"] {
        border: none;
        border-bottom: 1px solid #9ca3af;
        text-align: center;
        font-size: 0.875rem;
        padding: 4px;
        width: 200px;
    }
    
    .signature-box .date-input {
        margin-top: 8px;
        font-size: 0.875rem;
    }
  s .signature-box .date-input input {
        width: 120px;
        border: none;
        border-bottom: 1px solid #9ca3af;
        font-size: 0.875rem;
    }

    .form-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 48px;
    }

    .btn {
        padding: 10px 24px;
        border: 1px solid;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-secondary {
        background-color: #ffffff;
        color: #3b82f6; /* Biru dari gambar */
        border-color: #3b82f6;
    }

    .btn-primary {
        background-color: #3b82f6; /* Biru dari gambar */
        color: #ffffff;
        border-color: #3b82f6;
    }
    
    .footer-notes {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 32px;
        border-top: 1px solid #e5e7eb;
        padding-top: 16px;
    }

    </style>
@endpush

{{-- PANGGILAN @extends KEDUA DIHAPUS DARI SINI --}}
    
@section('content')
     <main class="main-content">
        <header class="form-header">
            <div class="title-block">
                <h1>FR.IA.10. VPK - VERIFIKASI PIHAK KETIGA</h1>
            </div>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Logo_BNSP.svg/2560px-Logo_BNSP.svg.png" alt="Logo BNSP">
        </header>

        @if (session('success'))
            <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
                 {{ session('success') }}
            </div>
        @endif

        <form class="form-body" method="POST" action="{{ route('fr-ia-10.store') }}">
        @csrf
            
            <div class="metadata-grid">
                <label>Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
                <div>: <input type="text" value="Junior Web Developer (Contoh)"></div>
                
                <label>Nomor</label>
                <div>: <input type="text" value="SSK.XX.XXXX (Contoh)"></div>

                <label>TUK</label>
                <div class="radio-group">
                    : 
                    <input type="radio" id="tuk_sewaktu" name="tuk_type"><label for="tuk_sewaktu">Sewaktu</label>
                    <input type="radio" id="tuk_tempatkerja" name="tuk_type" checked><label for="tuk_tempatkerja">Tempat Kerja</label>
                    <input type="radio" id="tuk_mandiri" name="tuk_type"><label for="tuk_mandiri">Mandiri</label>
                </div>

                <label>Nama Asesor</label>
                <div>: <input type="text" value="Ajeng Febria Hidayati (Contoh)" name="asesor"></div>
                
                <label>Nama Asesi</label>
                <div>: <input type="text" value="Tatang Sidartang (Contoh)" name="asesi"></div>
                
                <label>Tanggal</label>
                <div>: <input type="date" value="<?php echo date('Y-m-d'); ?>"></div>
            </div>

            <div class="guide-box">
                <h3>PANDUAN BAGI ASESOR</h3>
                <ul>
                    <li>Tentukan pihak ketiga yang akan dimintai verifikasi.</li>
                    <li>Ajukan pertanyaan kepada pihak ketiga.</li>
                    <li>Berikan penilaian kepada asesi berdasarkan verifikasi pihak ketiga.</li>
                    <li>Pertanyaan/pernyataan dapat dikembangkan sesuai dengan konteks pekerjaan dan relasi.</li>
                </ul>
            </div>
            
            <div class="form-section">
                <h2>Data Pihak Ketiga</h2>
                <div class="form-group">
                  : <label for="supervisor_name">Nama Pengawas/penyelia/atasan/orang lain di perusahaan :</label>
                    <input type="text" id="supervisor_name" name="supervisor_name">
                </div>
                <div class="form-group">
                    <label for="workplace">Tempat kerja :</label>
                    <input type="text" id="workplace" name="workplace">
                </div>
                <div class="form-group">
                    <label for="address">Alamat :</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="form-group">
                    <label for="phone">Telepon :</label>
                    <input type="text" id="phone" name="phone">
                </div>
            </div>

            <div class="form-section">
                <h2>Daftar Pertanyaan</h2>
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>Pertanyaan</th>
                            <th>Ya</th>
                            <th>Tdk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>"Apakah asesi bekerja dengan mempertimbangkan Kesehatan, Keamanan dan Keselamatan Kerja?"</td>
                            <td><input type="radio" name="q1" value="ya"></td>
                            <td><input type="radio" name="q1" value="tidak"></td>
                        </tr>
              _         <tr>
                            <td>Apakah asesi berinteraksi dengan harmonis didalam kelompoknya?</td>
                            <td><input type="radio" name="q2" value="ya"></td>
e                           <td><input type="radio" name="q2" value="tidak"></td>
                        </tr>
                        <tr>
S                         <td>Apakah asesi dapat mengelola tugas-tugas secara bersamaan?</td>
                            <td><input type="radio" name="q3" value="ya"></td>
                            <td><input type="radio" name="q3" value="tidak"></td>
e                   </tr>
                        <tr>
                            <td>Apakah asesi dapat dengan cepat beradaptasi dengan peralatan dan lingkungan yang baru?</td>
                            <td><input type="radio" name="q4" value="ya"></td>
                            <td><input type="radio" name="q4" value="tidak"></td>
                        </tr>
                        <tr>
                            <td>Apakah asesi dapat merespon dengan cepat masalah-masalah yang ada di tempat kerjanya?</td>
out                         <td><input type="radio" name="q5" value="ya"></td>
                            <td><input type="radio" name="q5" value="tidak"></td>
loc                 </tr>
                        <tr>
                            <td>Apakah Anda bersedia dihubungi jika verifikasi lebih lanjut dari pernyataan ini diperlukan?</td>
                            <td><input type="radio" name="q6" value="ya"></td>
A                           <td><input type="radio" name="q6" value="tidak"></td>
                        </tr>
                    </tbody>
      _         </table>
            </div>

            <div class="form-section">
                <h2>Detail Verifikasi</h2>
                <div class="form-group">
                    <label for="relation">Apa hubungan Anda dengan asesi?</label>
                    <textarea id="relation" name="relation"></textarea>
                </div>
                <div class="form-group">
                    <label for="duration">Berapa lama Anda bekerja dengan asesi?</label>
                    <textarea id="duration" name="duration"></textarea>
                </div>
                <div class="form-group">
s                 <label for="proximity">Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?</label>
                    <textarea id="proximity" name="proximity"></textarea>
                </div>
                <div class="form-group">
                    <label for="experience">Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)</label>
                    <textarea id="experience" name="experience"></textarea>
                </div>
            </div>

            <div class="form-section">
Ind           <h2>Kesimpulan</h2>
                <div class="form-group">
                    <label for="consistency">"Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?"</label>
                    <textarea id="consistency" name="consistency"></textarea>
                </div>
                <div class="form-group">
s               <label for="training_needs">Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:</label>
                    <textarea id="training_needs" name="training_needs"></textarea>
                </div>
                <div class="form-group">
A               <label for="other_comments">Ada komentar lain:</label>
                    <textarea id="other_comments" name="other_comments"></textarea>
                </div>
            </div>
            
            {{-- Bagian Tanda Tangan Asesor Dihapus --}}
ci         {{-- <div class="signature-section"> ... </div> --}}

            {{-- Memanggil komponen tanda tangan Asesi & Asesor --}}
            {{-- Ann Asumsi file ini ada di resources/views/layouts/ttdasesiasesor.blade.php --}}
tr         @include('components.kolom_ttd.asesiasesor')

            <div class="form-footer">
                <button type="button" class="btn btn-secondary">Sebelumnya</button>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
  Data         
            <div class="footer-notes">
                <p>*Coret yang tidak perlu</p>
                <p>Informasi Rahasia</p>
                <br>
                <p>Diadopsi dari templat yang disediakan di Departemen Pendidikan dan Pelatihan, Australia. Merancang alat asesmen untuk hasil yang berkualitas di VET. 2008</p>
            </div>

        </form>

    </main>
@endsection
di file ttdasesiasesor.blade.php panggil juga file ttdpenyusunvalidator.blade.php