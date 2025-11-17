@extends('layouts.app-sidebar')
@section('content')
<main class="main-content">

    @php $is_admin = $user->role == 'admin'; @endphp

    <x-header_form.header_form title="FR.IA.05.B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA" />
    <br>
    
    {{-- (Identitas Skema... biarkan statis/disabled) --}}
    <div class="form-row grid grid-cols-1 md:grid-cols-[250px_1fr] md:gap-x-6 gap-y-1.5 items-start md:items-center">
        {{-- ... (kode identitas skema Anda di sini, biarkan sama) ... --}}
    </div>

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

    <form class="form-body mt-10" method="POST" action="{{ route('ia-05.store.kunci') }}">
        @csrf 
        
        {{-- (Tabel Unit Kompetensi... biarkan sama, disabled jika bukan admin) --}}
        <div class="form-section my-8">
            {{-- ... (kode tabel unit kompetensi Anda di sini) ... --}}
        </div>

        <div class="form-section my-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Kunci Jawaban Pertanyaan Tertulis – Pilihan Ganda:</h3>
            <div class="border border-gray-900 shadow-md">
                <table class="w-full">
                    <thead>
                        <tr class="bg-black text-white">
                            <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban (Teks)</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[10%]">No.</th>
                            <th class="border border-gray-900 p-2 font-semibold w-[40%]">Kunci Jawaban (Teks)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($semua_soal) && $semua_soal->count() > 0)
                            @foreach ($semua_soal->chunk(2) as $chunk)
                            <tr>
                                {{-- KOLOM KIRI --}}
                                @if (isset($chunk[0]))
                                    @php $soal_kiri = $chunk[0]; @endphp
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        {{ $loop->parent->index * 2 + 1 }}.
                                    </td>
                                    <td class="border border-gray-900 p-2 text-sm">
                                        {{-- UBAH: name="kunci[id]" --}}
                                        {{-- UBAH: value="$kunci_jawaban->get(...)" (sesuai DB) --}}
                                        <input type="text" 
                                               name="kunci[{{ $soal_kiri->id_soal_ia05 }}]" 
                                               class="form-input w-full border-gray-300 rounded-md shadow-sm"
                                               value="{{ $kunci_jawaban->get($soal_kiri->id_soal_ia05) ?? '' }}"
                                               placeholder="Contoh: A. {{ $soal_kiri->opsi_jawaban_a }}"
                                               @if(!$is_admin) disabled @endif>
                                    </td>
                                @else
                                    <td class="border border-gray-900 p-2"></td>
                                    <td class="border border-gray-900 p-2"></td>
                                @endif

                                {{-- KOLOM KANAN --}}
                                @if (isset($chunk[1]))
                                    @php $soal_kanan = $chunk[1]; @endphp
                                    <td class="border border-gray-900 p-2 text-sm text-center">
                                        {{ $loop->parent->index * 2 + 2 }}.
                                    </td>
                                    <td class="border border-gray-900 p-2 text-sm">
                                        <input type="text" 
                                               name="kunci[{{ $soal_kanan->id_soal_ia05 }}]" 
                                               class="form-input w-full border-gray-300 rounded-md shadow-sm"
                                               value="{{ $kunci_jawaban->get($soal_kanan->id_soal_ia05) ?? '' }}"
                                               placeholder="Contoh: B. {{ $soal_kanan->opsi_jawaban_b }}"
                                               @if(!$is_admin) disabled @endif>
                                    </td>
                                @else
                                    <td class="border border-gray-900 p-2"></td>
                                    <td class="border border-gray-900 p-2"></td>
                                @endif
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    Belum ada soal yang dibuat oleh Admin (di Form IA-05 A).
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="form-section my-8">
            @include('components.kolom_ttd.penyusunvalidator')
        </div>
        
        <div class="form-footer flex justify-between mt-10">
            <button type="button" class="btn py-2 px-5 border border-blue-600 text-blue-600 rounded-md font-semibold hover:bg-blue-50">Sebelumnya</button>
            @if($is_admin)
                <button type="submit" class="btn py-2 px-5 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700">Simpan Kunci Jawaban</button>
            @endif
        </div>
        
        <div class="footer-notes mt-10 pt-4 border-t border-gray-200 text-xs text-gray-600">
            <p>*Coret yang tidak perlu</p>
        </div>
    </form>
</main>
@endsection