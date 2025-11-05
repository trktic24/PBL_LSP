@extends('layouts.app-sidebar')

@section('content')
    <main class="main-content">
       <x-header_form.header_form title="FR.IA.05A. DPT - PERTANYAAN TERTULIS PILIHAN GANDA" />

        <form class="form-body mt-6" action="{{ route('pertanyaan.store') }}" method="POST">  
            @csrf             
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