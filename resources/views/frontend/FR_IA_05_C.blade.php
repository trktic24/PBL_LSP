@extends('layouts.app-sidebar')
@section('content')
<main class="main-content">

    @php $is_asesor = ($user->role_id == 3 || $user->role_id == 1); @endphp

    <form class="form-body" method="POST" action="{{ route('ia-05.store.penilaian', ['id_asesi' => $asesi->id_data_sertifikasi_asesi]) }}">
        @csrf 
        <x-header_form.header_form title="FR.IA.05.C. LEMBAR JAWABAN PILIHAN GANDA" />
        
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
        
        {{-- (Tabel Unit Kompetensi... biarkan sama, disabled) --}}
        <div class="form-section my-8">
            {{-- ... (kode tabel unit kompetensi Anda di sini, pastikan input 'disabled') ... --}}
        </div>

        <div class="form-section my-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Lembar Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
            <div class="border border-gray-900 shadow-md">
                <table class="w-full">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="border border-gray-900 p-2 font-semibold w-[5%]" rowspan="2">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" rowspan="2">Jawaban Asesi</th>
                            {{-- UBAH: Header kembali ke Ya/Tidak --}}
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" colspan="2">Pencapaian</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[5%]" rowspan="2">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" rowspan="2">Jawaban Asesi</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[20%]" colspan="2">Pencapaian</th>
                        </tr>
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 p-2 font-semibold">Ya</th>
                            <th class="border border-gray-900 p-2 font-semibold">Tidak</th>
                            <th class="border border-gray-900 p-2 font-semibold">Ya</th>
                            <th class="border border-gray-900 p-2 font-semibold">Tidak</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($semua_soal->chunk(2) as $chunk)
                        <tr>
                            {{-- KOLOM KIRI --}}
                            @if (isset($chunk[0]))
                                @php 
                                    $soal_kiri = $chunk[0];
                                    $jawaban_kiri = $lembar_jawab->get($soal_kiri->id_soal_ia05);
                                    // HITUNG NOMOR MANUAL
                                    $nomor_kiri = ($loop->index * 2) + 1;
                                @endphp
                                
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    {{ $nomor_kiri }}.
                                </td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="jawaban_disabled_kiri_{{ $soal_kiri->id_soal_ia05 }}" 
                                        class="form-input w-full border-gray-300 rounded-md shadow-sm bg-gray-50"
                                        value="{{ $jawaban_kiri->teks_jawaban_asesi_ia05 ?? 'N/A' }}"
                                        disabled>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    <input type="radio" name="penilaian[{{ $soal_kiri->id_soal_ia05 }}]" value="ya"
                                        class="form-radio h-4 w-4 text-blue-600 rounded"
                                        @checked($jawaban_kiri && $jawaban_kiri->pencapaian_ia05_iya == 1)
                                        @disabled(!$is_asesor) required>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    <input type="radio" name="penilaian[{{ $soal_kiri->id_soal_ia05 }}]" value="tidak"
                                        class="form-radio h-4 w-4 text-blue-600 rounded"
                                        @checked($jawaban_kiri && $jawaban_kiri->pencapaian_ia05_tidak == 1)
                                        @disabled(!$is_asesor)>
                                </td>
                            @else
                                <td class="border border-gray-900 p-2"></td>
                                <td class="border border-gray-900 p-2"></td>
                                <td class="border border-gray-900 p-2"></td>
                                <td class="border border-gray-900 p-2"></td>
                            @endif

                            {{-- KOLOM KANAN --}}
                            @if (isset($chunk[1]))
                                @php 
                                    $soal_kanan = $chunk[1]; 
                                    $jawaban_kanan = $lembar_jawab->get($soal_kanan->id_soal_ia05);
                                    // HITUNG NOMOR MANUAL
                                    $nomor_kanan = ($loop->index * 2) + 2;
                                @endphp
                                
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    {{ $nomor_kanan }}.
                                </td>
                                <td class="border border-gray-900 p-2 text-sm">
                                    <input type="text" name="jawaban_disabled_kanan_{{ $soal_kanan->id_soal_ia05 }}" 
                                        class="form-input w-full border-gray-300 rounded-md shadow-sm bg-gray-50"
                                        value="{{ $jawaban_kanan->teks_jawaban_asesi_ia05 ?? 'N/A' }}"
                                        disabled>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    <input type="radio" name="penilaian[{{ $soal_kanan->id_soal_ia05 }}]" value="ya"
                                        class="form-radio h-4 w-4 text-blue-600 rounded"
                                        @checked($jawaban_kanan && $jawaban_kanan->pencapaian_ia05_iya == 1)
                                        @disabled(!$is_asesor) required>
                                </td>
                                <td class="border border-gray-900 p-2 text-sm text-center">
                                    <input type="radio" name="penilaian[{{ $soal_kanan->id_soal_ia05 }}]" value="tidak"
                                        class="form-radio h-4 w-4 text-blue-600 rounded"
                                        @checked($jawaban_kanan && $jawaban_kanan->pencapaian_ia05_tidak == 1)
                                        @disabled(!$is_asesor)>
                                </td>
                            @else
                                <td class="border border-gray-900 p-2"></td>
                                <td class="border border-gray-900 p-2"></td>
                                <td class="border border-gray-900 p-2"></td>
                                <td class="border border-gray-900 p-2"></td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-4 text-center text-gray-500">Belum ada soal.</td></tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
            
        <div class="form-section my-8">
            <div class="form-section my-8">
                <div class="border border-gray-900 shadow-md w-full">
                    <table class="w-full border-collapse">
                        <tbody>
                            <tr>
                                <td class="border border-gray-900 p-2 font-semibold w-40 bg-black text-white align-top">Umpan balik untuk asesi</td>
                                <td class="border border-gray-900 p-2">
                                    <p class="text-sm font-medium text-gray-800">Aspek pengetahuan seluruh unit kompetensi yang diujikan (tercapai / belum tercapai)*</p>
                                    <textarea name="umpan_balik" 
                                              class="form-textarea w-full border-gray-300 rounded-md shadow-sm mt-2" 
                                              rows="3" 
                                              placeholder="Tuliskan unit/elemen/KUK jika belum tercapai..."
                                              @disabled(!$is_asesor)
                                    >{{-- 
                                        CATATAN: Kolom 'umpan_balik' tidak ada di tabel 'lembar_jawab_ia05'.
                                        Controller di atas TIDAK menyimpan ini.
                                        Anda perlu menambahkan kolom 'umpan_balik' (text, nullable)
                                        ke tabel 'lembar_jawab_ia05' jika ingin ini disimpan.
                                    --}}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @include('components.kolom_ttd.asesiasesor')
        </div>
            
        <div class="form-footer flex justify-between mt-10">
            <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
            @if($is_asesor)
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Simpan Penilaian</button>
            @endif
        </div>
    </form>
</main>
@endsection