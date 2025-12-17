@extends('layouts.app-sidebar')
@section('content')
    <main class="main-content">
        <div class="p-8">
        
            <x-header_form.header_form title="FR.IA.10. VPK - VERIFIKASI PIHAK KETIGA" />

            <x-identitas_skema_form.identitas_skema_form
                skema="Junior Web Developer"
                nomorSkema="SKK.XXXXX.XXXX"
                tuk="Tempat Kerja" 
                namaAsesor="{{ $asesor->name ?? 'Nama Asesor' }}" 
                namaAsesi="{{ $asesi->nama_lengkap ?? 'Nama Asesi' }}" 
                tanggal="{{ now()->format('d F Y') }}" 
            />

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form class="form-body mt-10" method="POST" action="{{ route('fr-ia-10.store') }}">
            @csrf
                <input type="hidden" name="id_data_sertifikasi_asesi" value="{{ $asesi->id_data_sertifikasi_asesi }}">

                {{-- --- BAGIAN 1: HEADER (Data Pihak Ketiga) --- --}}
                <div class="form-section my-8">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Data Pihak Ketiga</h2>

                    {{-- Perhatikan: value="{{ $header->kolom ?? '' }}" untuk pre-fill data lama --}}
                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'supervisor_name',
                        'name'  => 'supervisor_name',
                        'label' => 'Nama Pengawas/penyelia',
                        'value' => $header->nama_pengawas ?? '' 
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'workplace',
                        'name'  => 'workplace',
                        'label' => 'Tempat kerja',
                        'value' => $header->tempat_kerja ?? ''
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'address',
                        'name'  => 'address',
                        'label' => 'Alamat',
                        'value' => $header->alamat ?? ''
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'phone',
                        'name'  => 'phone',
                        'label' => 'Telepon',
                        'value' => $header->telepon ?? ''
                    ]) 
                </div>

                {{-- --- BAGIAN 2: CHECKLIST PERTANYAAN (Tabel pertanyaan_ia10) --- --}}
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
                            
                            @forelse($daftar_soal as $soal)
                                <tr>
                                    <td class="border border-gray-900 p-2 text-sm">
                                        {{ $soal->pertanyaan }}
                                    </td>
                                    
                                    {{-- Opsi YA (Value 1) --}}
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        <input type="radio" 
                                               name="checklist[{{ $soal->id_pertanyaan_ia10 }}]" 
                                               value="1" 
                                               class="form-radio h-4 w-4 text-blue-600"
                                               {{ ($soal->jawaban_pilihan_iya_tidak == 1) ? 'checked' : '' }}
                                               required>
                                    </td>

                                    {{-- Opsi TIDAK (Value 0) --}}
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        <input type="radio" 
                                               name="checklist[{{ $soal->id_pertanyaan_ia10 }}]" 
                                               value="0" 
                                               class="form-radio h-4 w-4 text-blue-600"
                                               {{ ($soal->jawaban_pilihan_iya_tidak === 0) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="border border-gray-900 p-2 text-center text-red-500">
                                        Soal belum digenerate untuk asesi ini (Tabel pertanyaan_ia10 kosong).
                                    </td>
                                </tr>
                            @endforelse
                        </x-table>
                    </div>
                </div>

                {{-- --- BAGIAN 3: DETAIL JAWABAN ESSAY (Tabel detail_ia10) --- --}}
                {{-- Kita gunakan array name="essay[key]" --}}
                <div class="form-section my-8">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Detail Verifikasi</h2>

                    @php
                        // Helper kecil untuk ambil value lama dari array essay_answers
                        $getVal = function($key) use ($essay_answers) {
                            // Map key form ke pertanyaan lengkap (sama seperti di Controller)
                            $map = [
                                'relation'       => 'Apa hubungan Anda dengan asesi?',
                                'duration'       => 'Berapa lama Anda bekerja dengan asesi?',
                                'proximity'      => 'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                                'experience'     => 'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)',
                                'consistency'    => 'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?',
                                'training_needs' => 'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                                'other_comments' => 'Ada komentar lain:'
                            ];
                            $fullQuestion = $map[$key] ?? '';
                            return $essay_answers[$fullQuestion] ?? '';
                        };
                    @endphp

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'relation',
                        'name'  => 'essay[relation]',  
                        'label' => 'Apa hubungan Anda dengan asesi?',
                        'type'  => 'textarea',
                        'value' => $getVal('relation')
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'duration',
                        'name'  => 'essay[duration]',
                        'label' => 'Berapa lama Anda bekerja dengan asesi?',
                        'type'  => 'textarea',
                        'value' => $getVal('duration')
                    ]) 
                    
                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'proximity',
                        'name'  => 'essay[proximity]',
                        'label' => 'Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?',
                        'type'  => 'textarea',
                        'value' => $getVal('proximity')
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'experience',
                        'name'  => 'essay[experience]',
                        'label' => 'Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai?',
                        'type'  => 'textarea',
                        'value' => $getVal('experience')
                    ]) 
                </div>

                {{-- --- BAGIAN 4: KESIMPULAN (Masih masuk detail_ia10) --- --}}
                <div class="form-section my-8">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Kesimpulan</h2>

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'consistency',
                        'name'  => 'essay[consistency]',
                        'label' => 'Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar?',
                        'type'  => 'textarea',
                        'value' => $getVal('consistency')
                    ]) 
                    
                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'training_needs',
                        'name'  => 'essay[training_needs]',
                        'label' => 'Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:',
                        'type'  => 'textarea',
                        'value' => $getVal('training_needs')
                    ]) 

                    @include('components.kolom_form.kolom_form', [
                        'id'    => 'other_comments',
                        'name'  => 'essay[other_comments]',
                        'label' => 'Ada komentar lain:',
                        'type'  => 'textarea',
                        'value' => $getVal('other_comments')
                    ]) 
                </div>
                
                @include('components.kolom_ttd.asesiasesor', [
                    'showAsesi'  => false,
                    'showAsesor' => true,
                ])

                <div class="form-footer flex justify-between mt-10">
                    <button type="button" class="btn border border-blue-600 text-blue-600 px-5 py-2 rounded">Batal</button>
                    <button type="submit" class="btn bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Simpan Verifikasi</button>
                </div>
                
            </form>
        </div>
    </main>
@endsection