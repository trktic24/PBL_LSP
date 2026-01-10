@extends($layout ?? 'layouts.app-sidebar')

@section('content')
    <div class="p-3 sm:p-6 md:p-8">

        <x-header_form.header_form title="FR.AK.04. FORMULIR BANDING ASESMEN" />
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

        {{-- FORM UTAMA --}}
        <form action="{{ isset($isMasterView) ? '#' : route('ak04.store', $sertifikasi->id_data_sertifikasi_asesi) }}" method="POST">
        @csrf

        <div class="bg-white p-4 sm:p-6 rounded-md shadow-sm mb-4 sm:mb-6 border border-gray-200">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4 border-b border-gray-200 pb-2">Informasi Banding</h3>

            <dl class="grid grid-cols-1 md:grid-cols-4 gap-y-4 sm:gap-y-6 text-sm">
                {{-- TUK --}}
                <dt class="col-span-1 font-medium text-gray-500">TUK</dt>
                <dd class="col-span-3 flex flex-wrap gap-x-4 sm:gap-x-6 gap-y-2 items-center">
                    @php $jenisTuk = $sertifikasi->jadwal->jenis_tuk ?? ''; @endphp
                    <label class="flex items-center text-gray-900 font-medium text-sm">
                        <input type="checkbox" disabled {{ $jenisTuk == 'Sewaktu' ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 mr-2"> Sewaktu
                    </label>
                    <label class="flex items-center text-gray-900 font-medium text-sm">
                        <input type="checkbox" disabled {{ $jenisTuk == 'Tempat Kerja' ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 mr-2"> Tempat Kerja
                    </label>
                    <label class="flex items-center text-gray-900 font-medium text-sm">
                        <input type="checkbox" disabled {{ $jenisTuk == 'Mandiri' ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 mr-2"> Mandiri
                    </label>
                </dd>

                {{-- Nama Asesor --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesor</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block break-words">: {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Belum Ditentukan' }}</dd>

                {{-- Nama Asesi --}}
                <dt class="col-span-1 font-medium text-gray-500">Nama Asesi</dt>
                <dd class="col-span-3 text-gray-900 font-semibold block break-words">: {{ $sertifikasi->asesi->nama_lengkap }}</dd>
            </dl>
        </div>

        {{-- CARD PERTANYAAN --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 sm:mb-8">
            <div class="p-3 sm:p-4 bg-blue-50 border-b border-blue-100">
                <p class="text-xs sm:text-sm text-gray-800 font-medium">
                    Jawablah dengan <span class="font-bold">Ya</span> atau <span class="font-bold">Tidak</span> pertanyaan-pertanyaan berikut ini:
                </p>
            </div>

            {{-- TABEL DESKTOP --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left font-bold uppercase tracking-wider">Komponen</th>
                            <th class="px-6 py-3 text-center font-bold uppercase w-24">Ya</th>
                            <th class="px-6 py-3 text-center font-bold uppercase w-24">Tidak</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                        @php
                            $pertanyaan = [
                                'penjelasan_banding' => $template['q1'] ?? 'Apakah Proses Banding telah dijelaskan kepada Anda?',
                                'diskusi_dengan_asesor' => $template['q2'] ?? 'Apakah Anda telah mendiskusikan Banding dengan Asesor?',
                                'melibatkan_orang_lain' => $template['q3'] ?? 'Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?'
                            ];
                            $keys = array_keys($pertanyaan);
                        @endphp

                        @for ($i = 1; $i <= 3; $i++)
                            @php 
                                $key = $keys[$i-1];
                                $val = $respon ? $respon->$key : null; // 1 = Ya, 0 = Tidak
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">{{ $pertanyaan[$key] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="banding_{{ $i }}" value="ya" 
                                        {{ $val === 1 || $val === true ? 'checked' : '' }}
                                        class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer" required>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="radio" name="banding_{{ $i }}" value="tidak"
                                        {{ $val === 0 || $val === false ? 'checked' : '' }}
                                        class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            {{-- MOBILE VIEW --}}
            <div class="md:hidden p-3 sm:p-4 space-y-3">
               @for ($i = 1; $i <= 3; $i++)
                    @php 
                        $key = $keys[$i-1];
                        $val = $respon ? $respon->$key : null;
                    @endphp
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <p class="text-xs sm:text-sm text-gray-700 font-medium mb-2">{{ $i }}. {{ $pertanyaan[$key] }}</p>
                        <div class="flex gap-2">
                            <label class="flex-1 flex items-center justify-center bg-white border rounded-lg p-2 cursor-pointer has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                                <input type="radio" name="banding_{{ $i }}" value="ya" {{ $val === 1 ? 'checked' : '' }} class="mr-2"> Ya
                            </label>
                            <label class="flex-1 flex items-center justify-center bg-white border rounded-lg p-2 cursor-pointer has-[:checked]:bg-red-50 has-[:checked]:border-red-500">
                                <input type="radio" name="banding_{{ $i }}" value="tidak" {{ $val === 0 ? 'checked' : '' }} class="mr-2"> Tidak
                            </label>
                        </div>
                    </div>
               @endfor
            </div>

            <div class="p-3 sm:p-4 md:p-6">
                {{-- INFO SKEMA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Skema Sertifikasi</label>
                        <input type="text" value="{{ $sertifikasi->jadwal->skema->nama_skema }}" disabled class="w-full bg-gray-100 border border-gray-300 rounded px-2 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">No. Skema</label>
                        <input type="text" value="{{ $sertifikasi->jadwal->skema->nomor_skema }}" disabled class="w-full bg-gray-100 border border-gray-300 rounded px-2 py-2 text-sm">
                    </div>
                </div>

                {{-- ALASAN BANDING --}}
                <label for="alasan_banding" class="block text-sm font-bold text-gray-900 mb-2">Banding ini diajukan atas alasan sebagai berikut:</label>
                <textarea name="alasan_banding" id="alasan_banding" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 p-3 text-sm" placeholder="Jelaskan alasan banding...">{{ old('alasan_banding', $respon->alasan_banding ?? $template['default_alasan'] ?? '') }}</textarea>

                <div class="mt-4 bg-red-50 border-l-4 border-red-500 p-3">
                    <p class="text-xs text-red-700"><strong>Catatan:</strong> Anda berhak mengajukan banding jika menilai proses asesmen tidak sesuai SOP.</p>
                </div>

                {{-- TANDA TANGAN (Otomatis) --}}
                <div class="mt-6 bg-white p-4 rounded-md border border-gray-200 text-center">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Peserta</label>
                    <div class="w-full h-40 bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                        @if($sertifikasi->asesi->tanda_tangan)
                            <img src="{{ asset('storage/'.$sertifikasi->asesi->tanda_tangan) }}" class="h-32 object-contain">
                        @else
                            <p class="text-gray-400 text-sm">Tanda tangan belum tersedia di profil</p>
                        @endif
                    </div>
                </div>

                {{-- BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mt-8 border-t-2 border-gray-200 pt-6 mb-8">
                <a href="{{ isset($isMasterView) ? url()->previous() : route('tracker') }}" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 transition text-center shadow-sm">
                    Kembali
                </a>
                @if(!isset($isMasterView))
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5 text-center">
                    Ajukan Banding
                </button>
                @endif
            </div>
        </div>
        </form>
    </div>
@endsection