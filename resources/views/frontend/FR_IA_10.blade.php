@extends('layouts.app-sidebar')
@section('content')
    <main class="main-content">
        <div class="p-8">
        
            <x-header_form.header_form title="FR.IA.10. VPK - VERIFIKASI PIHAK KETIGA" />

            {{-- Menggunakan data dinamis dari Controller --}}
            <x-identitas_skema_form.identitas_skema_form
                skema="Junior Web Developer"
                nomorSkema="SKK.XXXXX.XXXX"
                tuk="Tempat Kerja" 
                namaAsesor="{{ $asesor->name ?? 'Nama Asesor' }}" 
                namaAsesi="{{ $asesi->nama_lengkap ?? 'Nama Asesi' }}" 
                tanggal="{{ now()->format('d F Y') }}" 
            />

            {{-- Notifikasi Sukses/Error --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form class="form-body mt-10" method="POST" action="{{ route('fr-ia-10.store') }}">
            @csrf

                {{-- WAJIB ADA: ID Asesi untuk disimpan ke database --}}
                <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $asesi->id_data_sertifikasi_asesi }}">

                {{-- Input tersembunyi pelengkap (opsional visual) --}}
                <input type="hidden" name="nama_asesi" value="{{ $asesi->nama_lengkap ?? 'Nama Asesi' }}">
                <input type="hidden" name="nama_asesor" value="{{ $asesor->name ?? 'Nama Asesor' }}">

                <div class="guide-box bg-gray-100 border-gray-100 p-6 rounded-md shadow-sm my-8">
                     <p><strong>Panduan bagi Asesor:</strong></p>
                     <ul class="list-disc pl-5 mt-2">
                        <li>Lengkapi formulir ini untuk memverifikasi bukti pihak ketiga yang diajukan oleh asesi.</li>
                        <li>Pastikan setiap pertanyaan dijawab dengan jujur berdasarkan pengamatan atau pengalaman kerja dengan asesi.</li>
                     </ul>
                </div> 
                
                {{-- Data Pihak Ketiga --}}
                <div class="form-section my-8">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Data Pihak Ketiga</h2>

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'supervisor_name',
                        'name'  => 'supervisor_name',
                        'label' => 'Nama Pengawas/penyelia/atasan/orang lain di perusahaan '
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'workplace',
                        'name'  => 'workplace',
                        'label' => 'Tempat kerja '
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'address',
                        'name'  => 'address',
                        'label' => 'Alamat'
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'phone',
                        'name'  => 'phone',
                        'label' => 'Telepon'
                    ]) 
                </div>

                {{-- TABEL PERTANYAAN (DINAMIS SEKARANG) --}}
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
                            
                            {{-- LOOPING SOAL DARI DATABASE --}}
                            @forelse($daftar_soal as $index => $soal)
                                <tr>
                                    <td class="border border-gray-900 p-2 text-sm">
                                        {{-- Menampilkan Teks Pertanyaan --}}
                                        {{ $soal->pertanyaan }}
                                    </td>
                                    
                                    {{-- Opsi YA --}}
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        <input type="radio" 
                                               name="jawaban[{{ $soal->id_ia10 }}]" 
                                               value="ya" 
                                               class="form-radio h-4 w-4 text-blue-600"
                                               {{-- Cek jika sudah ada jawaban YA di database --}}
                                               {{ (isset($jawaban_map[$soal->id_ia10]) && $jawaban_map[$soal->id_ia10] == 'ya') ? 'checked' : '' }}
                                               required>
                                    </td>

                                    {{-- Opsi TIDAK --}}
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        <input type="radio" 
                                               name="jawaban[{{ $soal->id_ia10 }}]" 
                                               value="tidak" 
                                               class="form-radio h-4 w-4 text-blue-600"
                                               {{-- Cek jika sudah ada jawaban TIDAK di database --}}
                                               {{ (isset($jawaban_map[$soal->id_ia10]) && $jawaban_map[$soal->id_ia10] == 'tidak') ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="border border-gray-900 p-2 text-sm text-center text-red-500">
                                        Data pertanyaan (IA.10) belum tersedia di Bank Soal.
                                    </td>
                                </tr>
                            @endforelse

                        </x-table>
                    </div>
                </div>

                {{-- Detail Verifikasi --}}
                <div class="form-section my-8">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Detail Verifikasi</h2>
                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'relation',
                        'name'  => 'relation',
                        'label' => 'Apa hubungan Anda dengan asesi?',
                        'type'  => 'textarea'
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'duration',
                        'name'  => 'duration',
                        'label' => 'Berapa lama Anda bekerja dengan asesi?',
                        'type'  => 'textarea'
                    ]) 
                    
                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'proximity',
                        'name'  => 'proximity',
                        'label' => 'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                        'type'  => 'textarea'
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'experience',
                        'name'  => 'experience', 
                        'label' => 'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)',
                        'type'  => 'textarea'
                    ]) 
                </div>

                {{-- Kesimpulan --}}
                <div class="form-section my-8">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Kesimpulan</h2>

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'consistency',
                        'name'  => 'consistency',
                        'label' => 'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?',
                        'type'  => 'textarea'
                    ]) 
                    
                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'training_needs',
                        'name'  => 'training_needs',
                        'label' => 'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                        'type'  => 'textarea'
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'other_comments',
                        'name'  => 'other_comments',
                        'label' => 'Ada komentar lain:',
                        'type'  => 'textarea'
                    ]) 
                    
                </div>
                
                @include('components.kolom_ttd.asesiasesor', [
                    'sertifikasi' => $asesi,
                    'showAsesi'  => false,
                    'showAsesor' => true,
                ])

                <div class="form-footer flex justify-between mt-10">
                    <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
                    <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Kirim</button>
                </div>
                
            </form>
        </div>
    </main>
@endsection