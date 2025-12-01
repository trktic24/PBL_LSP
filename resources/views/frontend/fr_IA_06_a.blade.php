@extends('layouts.app-sidebar')
@section('content')

<div class="p-8">

    <x-header_form.header_form title="FR.IA.06.A. DPT - DAFTAR PERTANYAAN TERTULIS ESAI" />

    <form class="form-body mt-6">               
        <x-identitas_skema_form.identitas_skema_form
            skema="Junior Web Developer"
            nomorSkema="SKK.XXXXX.XXXX"
            tuk="Tempat Kerja" 
            namaAsesor="Ajeng Febria Hidayati"
            namaAsesi="Tatang Sidartang"
            tanggal="3 November 2025" 
        />

    <!-- Box Daftar Pertanyaan -->
    <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4 mt-10">Daftar Pertanyaan</h2>
            <div class="overflow-x-auto ">             
                @for ($i = 1; $i <= 5; $i++)
                    <div class="p-3">
                        {{-- 
                        UBAH DI SINI:
                        - Kita panggil komponen 'kolom_form'
                        - Kita hilangkan 'flex-row' dan 'span'
                        - Kita gunakan '$i' sebagai 'label'
                        - Kita set 'type' menjadi 'textarea'
                        - Kita tambahkan 'placeholder'
                        --}}
                        @include('components.kolom_form.kolom_form', [
                            'id'          => 'pertanyaan_' . $i,
                            'name'        => 'pertanyaan_' . $i,
                            'label'       => "Pertanyaan $i", // 'Label' komponen diisi nomor
                            'type'        => 'textarea',
                            'rows'        => 2,
                            'placeholder' => "Tulis PERTANYAAN esai ke-$i di sini..."
                        ])
                    </div>
                @endfor 
            </div>     
    </div>

    <!-- Box Penyusun dan Validator (Sama seperti 06B) -->
    <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 border-b pb-2 mb-4">Penyusun dan Validator</h2>
            <div class="overflow-x-auto">
                <x-kolom_ttd.penyusunvalidator/>
            
            </div>
</div>
@endsection
