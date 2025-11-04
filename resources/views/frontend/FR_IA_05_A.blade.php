@extends('layouts.app-sidebar')

@section('content')
    <main class="main-content">
        
        {{-- HEADER: Mengikuti struktur IA.07 (Logo dulu, lalu Judul) --}}
        <header class="form-header">
            <img src="{{ asset('images/logo_bnsp.png') }}" alt="Logo BNSP" class="h-12 w-auto">
            <div class="text-center mt-4 md:mt-0 flex-grow">
                <h1 class="text-2xl md:text-2xl font-bold text-gray-900">FR.IA.05A. DPT - PERTANYAAN TERTULIS PILIHAN GANDA</h1>
            </div>
        </header>

        @if (session('success'))
            {{-- Menambahkan styling Tailwind pada notifikasi sukses --}}
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- FORM BODY: Mengikuti styling IA.07 --}}
        <form class="form-body mt-10" method="POST" action="">
            @csrf 
            
            {{-- METADATA: Menggunakan grid layout dari IA.07 --}}
            <div class="form-row grid grid-cols-[250px_1fr] gap-x-6 gap-y-4 items-center mb-8">
                
                <label class="text-sm font-medium text-gray-700">Skema Sertifikasi (KKNI/Okupasi/Klaster)</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" name="judul" placeholder="Judul Skema..." 
                           class="form-input w-full ml-2">
                </div>
                
                <label class="text-sm font-medium text-gray-700">Nomor</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" name="nomor" placeholder="Nomor Skema..." 
                           class="form-input w-full ml-2">
                </div>

                <label class="text-sm font-medium text-gray-700">TUK</label>
                <div class="radio-group flex items-center space-x-4">
                    <span>:</span>
                    <div class="flex items-center space-x-2 ml-2">
                        <input type="radio" id="tuk_sewaktu" name="tuk_type" value="sewaktu" class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_sewaktu" class="text-sm text-gray-700">Sewaktu</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="tuk_tempatkerja" name="tuk_type" value="tempat_kerja" checked class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_tempatkerja" class="text-sm text-gray-700">Tempat Kerja</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="tuk_mandiri" name="tuk_type" value="mandiri" class="form-radio h-4 w-4 text-blue-600">
                        <label for="tuk_mandiri" class="text-sm text-gray-700">Mandiri</label>
                    </div>
                </div>

                <label class="text-sm font-medium text-gray-700">Nama Asesor</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" name="asesor" placeholder="Nama Asesor..." 
                           class="form-input w-full ml-2">
                </div>
                
                <label class="text-sm font-medium text-gray-700">Nama Asesi</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="text" name="asesi" placeholder="Nama Asesi..." 
                           class="form-input w-full ml-2">
                </div>
                
                <label class="text-sm font-medium text-gray-700">Tanggal</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" 
                           class="form-input w-full ml-2">
                </div>

                <label class="text-sm font-medium text-gray-700">Waktu</label>
                <div class="flex items-center">
                    <span>:</span>
                    <input type="time" name="waktu" class="form-input w-full ml-2">
                </div>
            </div>

            <div class="form-section my-8">
                <h3 class="mb-4 font-semibold text-lg text-gray-800">Jawab semua pertanyaan berikut:</h3>

                {{-- Pertanyaan 1 --}}
                <div class="form-group bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-start space-x-3">
                        <label for="q1" class="text-base font-semibold text-gray-800 pt-2">1.</label>
                        <textarea id="q1" name="q1" rows="2" placeholder="Tulis pertanyaan nomor 1 di sini..." 
                                  class="form-textarea w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 items-center mt-3 ml-8">
                        <label for="q1a" class="text-sm">a.</label> <input type="text" id="q1a" name="q1a" placeholder="Opsi jawaban a..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q1b" class="text-sm">b.</label> <input type="text" id="q1b" name="q1b" placeholder="Opsi jawaban b..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q1c" class="text-sm">c.</label> <input type="text" id="q1c" name="q1c" placeholder="Opsi jawaban c..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q1d" class="text-sm">d.</label> <input type="text" id="q1d" name="q1d" placeholder="Opsi jawaban d..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                </div>

                {{-- Pertanyaan 2 --}}
                <div class="form-group bg-white p-4 rounded-lg border border-gray-200 mt-4">
                    <div class="flex items-start space-x-3">
                        <label for="q2" class="text-base font-semibold text-gray-800 pt-2">2.</label>
                        <textarea id="q2" name="q2" rows="2" placeholder="Tulis pertanyaan nomor 2 di sini..." 
                                  class="form-textarea w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 items-center mt-3 ml-8">
                        <label for="q2a" class="text-sm">a.</label> <input type="text" id="q2a" name="q2a" placeholder="Opsi jawaban a..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q2b" class="text-sm">b.</label> <input type="text" id="q2b" name="q2b" placeholder="Opsi jawaban b..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q2c" class="text-sm">c.</label> <input type="text" id="q2c" name="q2c" placeholder="Opsi jawaban c..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q2d" class="text-sm">d.</label> <input type="text" id="q2d" name="q2d" placeholder="Opsi jawaban d..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                </div>

                {{-- Pertanyaan 3 --}}
                <div class="form-group bg-white p-4 rounded-lg border border-gray-200 mt-4">
                    <div class="flex items-start space-x-3">
                        <label for="q3" class="text-base font-semibold text-gray-800 pt-2">3.</label>
                        <textarea id="q3" name="q3" rows="2" placeholder="Tulis pertanyaan nomor 3 di sini..." 
                                  class="form-textarea w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 items-center mt-3 ml-8">
                        <label for="q3a" class="text-sm">a.</label> <input type="text" id="q3a" name="q3a" placeholder="Opsi jawaban a..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q3b" class="text-sm">b.</label> <input type="text" id="q3b" name="q3b" placeholder="Opsi jawaban b..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q3c" class="text-sm">c.</label> <input type="text" id="q3c" name="q3c" placeholder="Opsi jawaban c..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q3d" class="text-sm">d.</label> <input type="text" id="q3d" name="q3d" placeholder="Opsi jawaban d..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                </div>
            </div>
            
            {{-- 
            TABEL PENYUSUN/VALIDATOR 
            Blok ini diganti dengan @include
            --}}
            <div class="form-section my-8">
                @include('components.kolom_ttd.penyusunvalidator')
            </div>
            
            {{-- FOOTER BUTTONS: Diberi styling Tailwind --}}
            <div class="form-footer flex justify-between mt-10">
                <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Kirim</button>
            </div>
            
            {{-- FOOTER NOTES: Diberi styling Tailwind --}}
            <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600">
                <p>*Coret yang tidak perlu</p>
            </div>

        </form>

    </main>
@endsection