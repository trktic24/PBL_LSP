@extends($layout ?? 'layouts.app-sidebar')

@section('content')
    {{-- Style Internal --}}
    <style>
        .custom-radio:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        textarea:focus, input:focus {
            outline: none;
            --tw-ring-color: #3b82f6;
        }
    </style>

    <div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">

        {{-- HEADER --}}
        <x-header_form.header_form title="FR.AK.03. UMPAN BALIK DAN CATATAN ASESMEN" />
        @if(isset($isMasterView))
            <div class="text-center font-bold text-blue-600 my-2">[TEMPLATE MASTER]</div>
        @endif
        <br>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM WRAPPER --}}
        <form action="{{ isset($isMasterView) ? '#' : route('ak03.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
            @csrf

            {{-- 1. IDENTITAS SKEMA --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Identitas Skema & Jadwal</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <tr>
                            <td colspan="3" class="font-bold text-gray-900 pb-2">
                                Skema Sertifikasi (KKNI/Okupasi/Klaster)
                            </td>
                        </tr>
                        <tr>
                            <td class="w-40 font-bold text-gray-700 py-2 align-top">Judul</td>
                            <td class="w-4 py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium">{{ $sertifikasi->jadwal->skema->nama_skema ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Nomor</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium">{{ $sertifikasi->jadwal->skema->nomor_skema ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">TUK</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2">
                                <div class="flex flex-wrap gap-4">
                                    @php $jenisTuk = $sertifikasi->jadwal->jenis_tuk ?? ''; @endphp
                                    <label class="inline-flex items-center">
                                        <input type="radio" disabled {{ $jenisTuk == 'Sewaktu' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300">
                                        <span class="ml-2 text-gray-700">Sewaktu</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" disabled {{ $jenisTuk == 'Tempat Kerja' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300">
                                        <span class="ml-2 text-gray-700">Tempat Kerja</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" disabled {{ $jenisTuk == 'Mandiri' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300">
                                        <span class="ml-2 text-gray-700">Mandiri</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Nama Asesor</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium">{{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Nama Asesi</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium">{{ $sertifikasi->asesi->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold text-gray-700 py-2 align-top">Tanggal Asesmen</td>
                            <td class="py-2 align-top">:</td>
                            <td class="py-2 text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_awal)->isoFormat('D MMMM Y') }} 
                                s/d 
                                {{ \Carbon\Carbon::parse($sertifikasi->jadwal->tanggal_akhir)->isoFormat('D MMMM Y') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- 2. TABEL KOMPONEN UMPAN BALIK --}}
            <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-200">
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Umpan Balik Asesi</h3>
                    <p class="text-sm text-gray-500 mt-2 italic">
                        (Diisi oleh Asesi setelah pengambilan keputusan)
                    </p>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th rowspan="2" class="px-4 py-4 text-left text-xs font-bold uppercase w-[50%] border-r border-gray-700">Komponen</th>
                                <th colspan="2" class="px-2 py-2 text-center text-xs font-bold uppercase border-b border-gray-700 border-r border-gray-700 w-24">Hasil</th>
                                <th rowspan="2" class="px-4 py-4 text-left text-xs font-bold uppercase w-[30%]">Catatan / Komentar Asesi</th>
                            </tr>
                            <tr>
                                <th class="px-2 py-2 text-center text-xs font-bold uppercase bg-gray-800 border-r border-gray-700">Ya</th>
                                <th class="px-2 py-2 text-center text-xs font-bold uppercase bg-gray-800 border-r border-gray-700">Tidak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            {{-- Looping Variable $questions dari Controller --}}
                            @foreach ($questions as $index => $question)
                                @php
                                    $idPoin = $index + 1;
                                    $data = $jawaban[$idPoin] ?? null;
                                    $hasil = $data ? strtolower($data->hasil) : null;
                                @endphp
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-800 align-middle border-r border-gray-200 leading-relaxed">
                                        {{ $question }}
                                    </td>
                                    {{-- YA --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="umpan_balik[{{ $index }}]" value="ya" 
                                            {{ $hasil == 'ya' ? 'checked' : '' }} required
                                            class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                                    </td>
                                    {{-- TIDAK --}}
                                    <td class="px-2 py-3 text-center align-middle border-r border-gray-200">
                                        <input type="radio" name="umpan_balik[{{ $index }}]" value="tidak" 
                                            {{ $hasil == 'tidak' ? 'checked' : '' }}
                                            class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                    </td>
                                    {{-- CATATAN --}}
                                    <td class="px-4 py-3 align-middle">
                                        <input type="text" name="catatan[{{ $index }}]" 
                                            value="{{ $data->catatan ?? '' }}"
                                            class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                                            placeholder="Tulis catatan...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Catatan Lainnya --}}
                <div class="mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan/komentar lainnya (apabila ada) :</label>
                    <textarea name="komentar_lain" rows="3" 
                        class="block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-3 resize-none"
                        placeholder="Tambahkan komentar tambahan di sini...">{{ $sertifikasi->catatan_asesi_AK03 ?? '' }}</textarea>
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mt-8 border-t-2 border-gray-200 pt-6 mb-8">
                <a href="{{ isset($isMasterView) ? url()->previous() : route('tracker') }}" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 transition text-center shadow-sm">
                    Kembali
                </a>
                @if(!isset($isMasterView))
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center">
                    Simpan Form FR.AK.03
                </button>
                @endif
            </div>

        </form>
    </div>
@endsection