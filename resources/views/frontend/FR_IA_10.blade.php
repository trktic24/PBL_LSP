@extends('layouts.app-sidebar')
@section('content')
    <main class="main-content">
        
        <x-header_form.header_form title="FR.IA.10. VPK - VERIFIKASI PIHAK KETIGA" />

        <form class="form-body mt-6">               
            <x-identitas_skema_form.identitas_skema_form
                skema="Junior Web Developer"
                nomorSkema="SKK.XXXXX.XXXX"
                tuk="Tempat Kerja" 
                namaAsesor="Ajeng Febria Hidayati"
                namaAsesi="Tatang Sidartang"
                tanggal="3 November 2025" 
            />

        @if (session('success'))
            {{-- Menambahkan styling Tailwind pada notifikasi sukses --}}
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM BODY: Mengikuti styling IA.07 --}}
        <form class="form-body mt-10" method="POST" action="{{ route('fr-ia-10.store') }}">
        @csrf
            

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

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'supervisor_name',
                    'name'  => 'supervisor_name',
                    'label' => 'Nama Pengawas/penyelia/atasan/orang lain di perusahaan '
                ])  

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'supervisor_name',
                    'name'  => 'supervisor_name',
                    'label' => 'Tempat kerja '
                ])                

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'supervisor_name',
                    'name'  => 'supervisor_name',
                    'label' => 'Alamat'
                ])                

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'supervisor_name',
                    'name'  => 'supervisor_name',
                    'label' => 'Telepon'
                ])                 
            </div>

            {{-- TABEL PERTANYAAN: Menggunakan template --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Daftar Pertanyaan</h2>
                <div class="overflow-x-auto border border-gray-900 shadow-md">
                    <x-table>
                        <x-slot name="thead">
                            <tr class="bg-black text-white">
                                <th class="border border-gray-900 p-2 font-semibold">Pertanyaan</th>
                                <th class="border border-gray-900 p-2 font-semibold w-20">Ya</th>
                                <th class="border border-gray-900 p-2 font-semibold w-20">Tdk</th>
                            </tr>
                        </x-slot>
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
                    </x-table>
                </div>
            </div>

            {{-- FORM SECTION: Styling H2 dan Form Group ditambahkan --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Detail Verifikasi</h2>
                @include('components.kolom_form.kolom_form', [
                    'id'    => 'hubungan',
                    'name'  => 'hubungan',
                    'label' => 'Apa hubungan Anda dengan asesi?',
                    'type'    => 'textarea'
                ])                 

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'lama_kerja',
                    'name'  => 'lama_kerja',
                    'label' => 'Berapa lama Anda bekerja dengan asesi?',
                    'type'    => 'textarea'
                ])                
                
                @include('components.kolom_form.kolom_form', [
                    'id'    => 'kedekatan',
                    'name'  => 'kedekatan',
                    'label' => 'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                    'type'    => 'textarea'
                ])                

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'kedekatan',
                    'name'  => 'kedekatan',
                    'label' => 'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)',
                    'type'    => 'textarea'
                ])                 
            </div>

            {{-- FORM SECTION: Styling H2 dan Form Group ditambahkan --}}
            <div class="form-section my-8">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Kesimpulan</h2>

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'standar',
                    'name'  => 'standar',
                    'label' => 'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?',
                    'type'    => 'textarea'
                ]) 
                
                @include('components.kolom_form.kolom_form', [
                    'id'    => 'kebutuhan_lanjut',
                    'name'  => 'kebutuhan_lanjut',
                    'label' => 'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                    'type'    => 'textarea'
                ])                

                @include('components.kolom_form.kolom_form', [
                    'id'    => 'komentar_lain',
                    'name'  => 'komentar_lain',
                    'label' => 'Ada komentar lain:',
                    'type'    => 'textarea'
                ])                
                
            </div>
            
            {{-- TANDA TANGAN: Tetap menggunakan include dari IA.10 --}}
            @include('components.kolom_ttd.asesiasesor', [
                'showAsesi'  => false,
                'showAsesor' => true,

            ])

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