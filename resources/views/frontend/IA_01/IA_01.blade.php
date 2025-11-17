@extends('layouts.wizard', ['currentStep' => $unitKompetensi->urutan])

@section('wizard-content')

    {{-- ... Bagian Error Alert ... --}}

    <form action="{{ route('ia01.storeStep', ['skema_id' => $unitKompetensi->id_kelompok_pekerjaan, 'urutan' => $unitKompetensi->urutan]) }}" method="POST">
        @csrf

        {{-- Header Unit Kompetensi --}}
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{-- Judul Dinamis Berdasarkan Tipe --}}
                    @if($formType == 'aktivitas')
                        Ceklis Observasi Aktivitas (Lanjutan)
                    @else
                        Cek Observasi Demonstrasi / Praktik
                    @endif
                </h1>
                <div class="mt-2 text-gray-600">
                    <span class="font-semibold block">Unit Kompetensi:</span>
                    {{ $unitKompetensi->kode_unit }} - {{ $unitKompetensi->judul_unit }}
                </div>
            </div>
            <img src="{{ asset('images/bnsp_logo.png') }}" alt="BNSP" class="h-16 object-contain">
        </div>

        {{-- TABEL KUK DINAMIS --}}
        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-4 text-sm font-semibold w-1/2">Kriteria Unjuk Kerja</th>
                        <th class="p-4 text-sm font-semibold w-1/4">
                            @if($formType == 'aktivitas') Standar Industri @else Benchmark (SOP) @endif
                        </th>
                        {{-- HEADER DINAMIS --}}
                        <th class="p-4 text-center text-sm font-semibold w-16">
                            @if($formType == 'aktivitas') Ya @else K @endif
                        </th>
                        <th class="p-4 text-center text-sm font-semibold w-16">
                            @if($formType == 'aktivitas') Tidak @else BK @endif
                        </th>
                        <th class="p-4 text-left text-sm font-semibold">Penilaian Lanjut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($kuks as $kuk)
                        @php $oldVal = old('kuk.'.$kuk->id_kriteria, $data_sesi['kuk'][$kuk->id_kriteria] ?? ''); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 text-sm text-gray-700 align-top">
                                <span class="font-bold mr-1">{{ $kuk->no_kriteria }}</span> {{ $kuk->kriteria }}
                            </td>
                            <td class="p-4 text-sm text-gray-500 align-top italic">{{ $kuk->standar_industri_kerja ?? '-' }}</td>

                            {{-- TOMBOL INPUT --}}
                            <td class="p-4 text-center align-top">
                                <input type="radio" name="kuk[{{ $kuk->id_kriteria }}]" value="1" class="form-radio h-5 w-5 text-green-600" {{ $oldVal == '1' ? 'checked' : '' }}>
                            </td>
                            <td class="p-4 text-center align-top">
                                <input type="radio" name="kuk[{{ $kuk->id_kriteria }}]" value="0" class="form-radio h-5 w-5 text-red-600" {{ $oldVal == '0' ? 'checked' : '' }}>
                            </td>
                            <td class="p-4 align-top"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tombol Navigasi --}}
        <div class="flex justify-between items-center mt-10">
            @if ($unitKompetensi->urutan == 1)
                {{-- Kalau step 1, tombol back-nya ke halaman Cover --}}
                <a href="{{ route('ia01.cover', ['skema_id' => $unitKompetensi->id_kelompok_pekerjaan]) }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-lg">← Kembali ke Data Diri</a>
            @else
                <a href="{{ route('ia01.showStep', ['skema_id' => $unitKompetensi->id_kelompok_pekerjaan, 'urutan' => $unitKompetensi->urutan - 1]) }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-lg">← Sebelumnya</a>
            @endif

            @if ($unitKompetensi->urutan == $totalSteps)
                <button type="submit" class="bg-green-600 text-white font-bold py-2 px-8 rounded-lg">Simpan & Selesai ✓</button>
            @else
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-8 rounded-lg">Selanjutnya →</button>
            @endif
        </div>
    </form>
@endsection
