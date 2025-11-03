@extends('layouts.app-sidebar')
{{-- BLOK @push('css') DIHAPUS KARENA KITA MENGGUNAKAN TAILWIND UTILITY CLASSES --}}
    
@section('content')
    <main class="main-content">
        
        {{-- HEADER: Mengikuti struktur IA.07 (Logo dulu, lalu Judul) --}}
        <header class="form-header">
            <img src="{{ asset('images/logo_bnsp.png') }}" alt="Logo BNSP" class="h-12 w-auto">
            <div class="text-center mt-4 md:mt-0 flex-grow">
                {{-- Judul disesuaikan dengan IA.10, styling dari IA.07 --}}
                <h1 class="text-2xl md:text-2xl font-bold text-gray-900">FR.IA.10. VPK - VERIFIKASI PIHAK KETIGA</h1>
            </div>
        </header>

        @if (session('success'))
            {{-- Menambahkan styling Tailwind pada notifikasi sukses --}}
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM BODY: Mengikuti styling IA.07 --}}
        <form class="form-body mt-10" method="POST" action="{{ route('fr-ia-10.store') }}">
        @csrf
            
            {{-- METADATA: Menggunakan grid layout dari IA.07 --}}
            <div class="form-row grid grid-cols-[250px_1fr] gap-x-6 gap-y-4 items-center mb-8">
                
                <label class="text-sm font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="Junior Web Developer (Contoh)" 
                           class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">Nomor</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="SSK.XX.XXXX (Contoh)" 
                           class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">TUK</label>
                <div class="radio-group flex items-center space-x-4">
                    <span>:</span>
                    <div class="flex items-center space-x-2 ml-2">
                        <input type="radio" id="tuk_sewaktu" name="tuk_type" class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="tuk_tempatkerja" name="tuk_type" checked class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="tuk_mandiri" name="tuk_type" class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                    </div>
                </div>

                <label class="text-sm font-medium text-gray-700">Nama Asesor</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="Ajeng Febria Hidayati (Contoh)" name="asesor"
                           class="form-input w-full ml-2" readonly>
                </div>
                
                <label class="text-sm font-medium text-gray-700">Nama Asesi</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" value="Tatang Sidartang (Contoh)" name="asesi"
                           class="form-input w-full ml-2" readonly>
                </div>

                <label class="text-sm font-medium text-gray-700">Tanggal</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="date" value="<?php echo date('Y-m-d'); ?>" 
                           class="form-input w-full ml-2">
                </div>
            </div>

            {{-- GUIDE BOX: Menggunakan styling IA.07 --}}
            <div class="guide-box bg-gray-100 border-gray-100 p-6 rounded-md shadow-sm my-8">
                <h3 class="text-lg font-bold text-black mb-3">PANDUAN BAGI ASESOR</h3>
                <ul class="list-disc list-inside space-y-2 text-black">
                    <li>Tentukan pihak ketiga yang akan dimintai verifikasi.</li>
                    <li>Ajukan pertanyaan kepada pihak ketiga.</li>
                    <li>Berikan penilaian kepada asesi berdasarkan verifikasi pihak ketiga.</li>
                    <li>Pertanyaan/pernyataan dapat dikembangkan sesuai dengan konteks pekerjaan dan relasi.</li>
                </ul>
            </div>
            
            {{-- FORM SECTION: Styling H2 dan Form Group ditambahkan --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Data Pihak Ketiga</h2>
                
                <div class="form-group mb-4">
                    <label for="supervisor_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pengawas/penyelia/atasan/orang lain di perusahaan :</label>
                    <input type="text" id="supervisor_name" name="supervisor_name" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="form-group mb-4">
                    <label for="workplace" class="block text-sm font-medium text-gray-700 mb-1">Tempat kerja :</label>
                    <input type="text" id="workplace" name="workplace" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="form-group mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat :</label>
                    <input type="text" id="address" name="address" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="form-group mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telepon :</label>
                    <input type="text" id="phone" name="phone" class="form-input w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            {{-- TABEL PERTANYAAN: Menggunakan styling tabel IA.07 --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Daftar Pertanyaan</h2>
                <div class="border border-gray-900 shadow-md">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="border border-gray-900 p-2 font-semibold">Pertanyaan</th>
                                <th class="border border-gray-900 p-2 font-semibold w-20">Ya</th>
                                <th class="border border-gray-900 p-2 font-semibold w-20">Tdk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm">"Apakah asesi bekerja dengan mempertimbangkan Kesehatan, Keamanan dan Keselamatan Kerja?"</td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q1" value="ya" class="form-radio h-4 w-4 text-blue-600"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q1" value="tidak" class="form-radio h-4 w-4 text-blue-600"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm">Apakah asesi berinteraksi dengan harmonis didalam kelompoknya?</td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q2" value="ya" class="form-radio h-4 w-4 text-blue-600"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q2" value="tidak" class="form-radio h-4 w-4 text-blue-600"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm">Apakah asesi dapat mengelola tugas-tugas secara bersamaan?</td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q3" value="ya" class="form-radio h-4 w-4 text-blue-600"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q3" value="tidak" class="form-radio h-4 w-4 text-blue-600"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm">Apakah asesi dapat dengan cepat beradaptasi dengan peralatan dan lingkungan yang baru?</td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q4" value="ya" class="form-radio h-4 w-4 text-blue-600"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q4" value="tidak" class="form-radio h-4 w-4 text-blue-600"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm">Apakah asesi dapat merespon dengan cepat masalah-masalah yang ada di tempat kerjanya?</td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q5" value="ya" class="form-radio h-4 w-4 text-blue-600"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q5" value="tidak" class="form-radio h-4 w-4 text-blue-600"></td>
                            </tr>
                            <tr>
                                <td class="border border-gray-900 p-2 text-sm">Apakah Anda bersedia dihubungi jika verifikasi lebih lanjut dari pernyataan ini diperlukan?</td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q6" value="ya" class="form-radio h-4 w-4 text-blue-600"></td>
                                <td class="border border-gray-900 p-2 text-sm text-center"><input type="radio" name="q6" value="tidak" class="form-radio h-4 w-4 text-blue-600"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FORM SECTION: Styling H2 dan Form Group ditambahkan --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Detail Verifikasi</h2>
                <div class="form-group mb-4">
                    <label for="relation" class="block text-sm font-medium text-gray-700 mb-1">Apa hubungan Anda dengan asesi?</label>
                    <textarea id="relation" name="relation" class="form-textarea w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Berapa lama Anda bekerja dengan asesi?</label>
                    <textarea id="duration" name="duration" class="form-textarea w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="proximity" class="block text-sm font-medium text-gray-700 mb-1">Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?</label>
                    <textarea id="proximity" name="proximity" class="form-textarea w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)</label>

                    <textarea id="experience" name="experience" class="form-textarea w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
            </div>

            {{-- FORM SECTION: Styling H2 dan Form Group ditambahkan --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Kesimpulan</h2>
                <div class="form-group mb-4">
                    <label for="consistency" class="block text-sm font-medium text-gray-700 mb-1">"Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?"</label>
                    <textarea id="consistency" name="consistency" class="form-textarea w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="training_needs" class="block text-sm font-medium text-gray-700 mb-1">Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:</label>
                    <textarea id="training_needs" name="training_needs" class="form-textarea w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="other_comments" class="block text-sm font-medium text-gray-700 mb-1">Ada komentar lain:</label>
                    <textarea id="other_comments" name="other_comments" class="form-textarea w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
            </div>
            
            {{-- TANDA TANGAN: Tetap menggunakan include dari IA.10 --}}
            @include('components.kolom_ttd.asesiasesor')

            {{-- FOOTER BUTTONS: Diberi styling Tailwind --}}
            <div class="form-footer flex justify-between mt-10">
                <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Kirim</button>
            </div>
            
            {{-- FOOTER NOTES: Diberi styling Tailwind --}}
            <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600 space-y-1">
                <p>*Coret yang tidak perlu</p>
                <p>Informasi Rahasia</p>
                <br>
                <p>Diadopsi dari templat yang disediakan di Departemen Pendidikan dan Pelatihan, Australia. Merancang alat asesmen untuk hasil yang berkualitas di VET. 2008</p>
            </div>

        </form>

    </main>
@endsection