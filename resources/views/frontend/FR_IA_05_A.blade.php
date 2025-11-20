@extends('layouts.app-sidebar')
@section('content')
<main class="main-content">
<x-header_form.header_form title="FR.IA.05A. DPT - PERTANYAAN TERTULIS PILIHAN GANDA" />

    @php
        $formAction = '#'; 
        if ($user->role_id == 1) { // Admin
            $formAction = route('ia-05.store.soal');
        } elseif ($user->role_id == 2) { // Asesi
            $formAction = route('ia-05.store.jawaban', ['id_asesi' => $asesi->id_data_sertifikasi_asesi]);
        }
    @endphp

    <form class="form-body mt-6" action="{{ $formAction }}" method="POST"> 
        @csrf
        <x-identitas_skema_form.identitas_skema_form
            {{-- Mengambil Judul Skema dari relasi: DataSertifikasi -> Jadwal -> Skema --}}
            skema="{{ $asesi->jadwal->skema->judul_skema ?? 'Judul Skema Tidak Ditemukan' }}"
            
            {{-- Mengambil Kode Skema --}}
            nomorSkema="{{ $asesi->jadwal->skema->kode_skema ?? 'Kode Tidak Ditemukan' }}"
            
            tuk="Tempat Kerja" {{-- (Bisa dibuat dinamis juga jika ada di tabel Jadwal) --}}
            
            {{-- Mengambil Nama Asesor dari relasi: DataSertifikasi -> Asesor --}}
            namaAsesor="{{ $asesi->asesor->nama_asesor ?? 'Nama Asesor Tidak Ditemukan' }}"
            
            {{-- Mengambil Nama Asesi dari relasi: DataSertifikasi -> Asesi --}}
            namaAsesi="{{ $asesi->asesi->nama_asesi ?? 'Nama Asesi Tidak Ditemukan' }}"
            
            tanggal="{{ now()->format('d F Y') }}"
        />

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

        <div class="form-section my-8">
            <h3 class="mb-4 font-semibold text-lg text-gray-800">
                @if($user->role_id == 1) Input Pertanyaan (Mode Admin):
                @elseif($user->role_id == 2) Lembar Jawaban Pilihan Ganda:
                @else Formulir Pertanyaan (Read-Only untuk Asesor):
                @endif
            </h3>

            @forelse ($semua_soal as $loop => $soal)
            <div class="form-group bg-white p-4 rounded-lg border border-gray-200 @if(!$loop->first) mt-4 @endif">
                
                {{-- TAMPILAN ADMIN --}}
                @if ($user->role == 'admin')
                    <div class="flex items-start space-x-3">
                        <label for="q{{ $soal->id_soal_ia05 }}" class="text-base font-semibold text-gray-800 pt-2">{{ $loop->iteration }}.</label>
                        {{-- UBAH: name="soal[...][pertanyaan]" (controller akan handle mapping) --}}
                        {{-- UBAH: value="$soal->soal_ia05" (sesuai DB) --}}
                        <textarea id="q{{ $soal->id_soal_ia05 }}" name="soal[{{ $soal->id_soal_ia05 }}][pertanyaan]" rows="2" placeholder="Tulis pertanyaan..." 
                                  class="form-textarea w-full border-gray-300 rounded-md shadow-sm"
                        >{{ $soal->soal_ia05 }}</textarea>
                    </div>
                    <div class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-2 items-center mt-3 ml-8">
                        {{-- UBAH: value="$soal->opsi_jawaban_a" (sesuai DB) --}}
                        <label for="q{{ $soal->id_soal_ia05 }}a" class="text-sm">a.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}a" name="soal[{{ $soal->id_soal_ia05 }}][opsi_a]" value="{{ $soal->opsi_jawaban_a }}" placeholder="Opsi a..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q{{ $soal->id_soal_ia05 }}b" class="text-sm">b.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}b" name="soal[{{ $soal->id_soal_ia05 }}][opsi_b]" value="{{ $soal->opsi_jawaban_b }}" placeholder="Opsi b..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q{{ $soal->id_soal_ia05 }}c" class="text-sm">c.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}c" name="soal[{{ $soal->id_soal_ia05 }}][opsi_c]" value="{{ $soal->opsi_jawaban_c }}" placeholder="Opsi c..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <label for="q{{ $soal->id_soal_ia05 }}d" class="text-sm">d.</label> <input type="text" id="q{{ $soal->id_soal_ia05 }}d" name="soal[{{ $soal->id_soal_ia05 }}][opsi_d]" value="{{ $soal->opsi_jawaban_d }}" placeholder="Opsi d..." class="form-input w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>
                
                {{-- TAMPILAN ASESI --}}
                @elseif ($user->role == 'asesi')
                    <div class="flex items-start space-x-3">
                        <label class="text-base font-semibold text-gray-800 pt-2">{{ $loop->iteration }}.</label>
                        <textarea rows="2" class="form-textarea w-full border-gray-300 rounded-md shadow-sm bg-gray-50" readonly>{{ $soal->soal_ia05 }}</textarea>
                    </div>
                    <div class="space-y-2 mt-3 ml-8">
                        {{-- UBAH: Tampilkan $soal->opsi_jawaban_a --}}
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="jawaban[{{ $soal->id_soal_ia05 }}]" value="A" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'A') ? 'checked' : '' }} required>
                            <span class="text-sm"><strong>a.</strong> {{ $soal->opsi_jawaban_a }}</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="jawaban[{{ $soal->id_soal_ia05 }}]" value="B" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'B') ? 'checked' : '' }} required>
                            <span class="text-sm"><strong>b.</strong> {{ $soal->opsi_jawaban_b }}</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="jawaban[{{ $soal->id_soal_ia05 }}]" value="C" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'C') ? 'checked' : '' }} required>
                            <span class="text-sm"><strong>c.</strong> {{ $soal->opsi_jawaban_c }}</span>
                        </label>
                        @if(!empty($soal->opsi_jawaban_d))
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="jawaban[{{ $soal->id_soal_ia05 }}]" value="D" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'D') ? 'checked' : '' }} required>
                            <span class="text-sm"><strong>d.</strong> {{ $soal->opsi_jawaban_d }}</span>
                        </label>
                        @endif
                    </div>

                {{-- TAMPILAN ASESOR --}}
                @else
                    <div class="flex items-start space-x-3">
                        <label class="text-base font-semibold text-gray-800 pt-2">{{ $loop->iteration }}.</label>
                        <textarea rows="2" class="form-textarea w-full border-gray-300 rounded-md shadow-sm bg-gray-50" readonly>{{ $soal->soal_ia05 }}</textarea>
                    </div>
                    <div class="space-y-2 mt-3 ml-8">
                        <label class="flex items-center space-x-3 cursor-not-allowed">
                            <input type="radio" name="jawaban_disabled[{{ $soal->id_soal_ia05 }}]" value="A" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'A') ? 'checked' : '' }} disabled>
                            <span class="text-sm"><strong>a.</strong> {{ $soal->opsi_jawaban_a }}</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-not-allowed">
                            <input type="radio" name="jawaban_disabled[{{ $soal->id_soal_ia05 }}]" value="B" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'B') ? 'checked' : '' }} disabled>
                            <span class="text-sm"><strong>b.</strong> {{ $soal->opsi_jawaban_b }}</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-not-allowed">
                            <input type="radio" name="jawaban_disabled[{{ $soal->id_soal_ia05 }}]" value="C" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'C') ? 'checked' : '' }} disabled>
                            <span class="text-sm"><strong>c.</strong> {{ $soal->opsi_jawaban_c }}</span>
                        </label>
                        @if(!empty($soal->opsi_jawaban_d))
                        <label class="flex items-center space-x-3 cursor-not-allowed">
                            <input type="radio" name="jawaban_disabled[{{ $soal->id_soal_ia05 }}]" value="D" class="form-radio h-4 w-4 text-blue-600"
                                   {{ ($data_jawaban_asesi->get($soal->id_soal_ia05) == 'D') ? 'checked' : '' }} disabled>
                            <span class="text-sm"><strong>d.</strong> {{ $soal->opsi_jawaban_d }}</span>
                        </label>
                        @endif
                    </div>
                @endif 
            </div>
            @empty
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                Data soal belum diinput oleh Admin.
            </div>
            @endforelse 
        </div>
        
        <div class="form-section my-8">
            @include('components.kolom_ttd.penyusunvalidator')
        </div>
        
        <div class="form-footer flex justify-between mt-10">
            <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
            
            @if($user->role == 'admin')
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Simpan Soal</button>
            @elseif($user->role == 'asesi')
                 <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Kirim Jawaban</button>
            @endif
        </div>
        
        <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600">
            <p>*Coret yang tidak perlu</p>
        </div>
    </form>
</main>
@endsection