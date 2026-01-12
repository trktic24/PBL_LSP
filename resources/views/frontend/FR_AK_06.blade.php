{{--
    Nama File: resources/views/asesmen/fr-ak-06.blade.php
    Deskripsi: Form FR.AK.06 - Meninjau Proses Asesmen (Layout Wizard)
--}}

@extends($layout ?? 'layouts.app-sidebar-skema')

@section('content')

    {{-- Style khusus untuk tabel border hitam --}}
    <style>
        .form-table, .form-table td, .form-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }
    </style>

    {{-- Header Mobile (Tombol Sidebar) --}}
    <div class="lg:hidden p-4 bg-blue-600 text-white flex justify-between items-center shadow-md sticky top-0 z-30 mb-6 rounded-lg">
        <span class="font-bold">Form Assessment</span>
        <button @click="$store.sidebar.open = true" class="p-2 focus:outline-none hover:bg-blue-700 rounded-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- FORM START --}}
    <form action="{{ isset($isMasterView) ? '#' : route('asesor.ak06.store', request()->route('id_jadwal')) }}" method="POST">
        @csrf

        <div class="bg-white">

            {{-- HEADER DOKUMEN --}}
            <div class="mb-10 text-center">
                <div class="mb-4 text-gray-400 text-sm font-bold italic text-left">Logo BNSP</div>

                <h1 class="text-2xl font-bold text-black uppercase tracking-wide border-b-2 border-gray-100 pb-4 mb-2 inline-block">
                    FR.AK.06. MENINJAU PROSES ASESMEN
                </h1>
                @if(isset($isMasterView))
                    <div class="text-center font-bold text-blue-600 my-2">[TEMPLATE MASTER]</div>
                @endif
            </div>

            {{-- INFORMASI SKEMA --}}
            <div class="grid grid-cols-[250px_auto] gap-y-3 text-sm mb-10 text-gray-700">
                <div class="font-bold text-black">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)*</div>
                <div>
                    <div class="flex gap-2"><span class="font-semibold w-20">Judul</span> : {{ $jadwal->skema->nama_skema ?? '-' }}</div>
                    <div class="flex gap-2"><span class="font-semibold w-20">Nomor</span> : {{ $jadwal->skema->nomor_skema ?? '-' }}</div>
                </div>

                <div class="font-bold text-black">TUK</div>
                <div>: 
                    @php $jenisTuk = $jadwal->jenisTuk->jenis_tuk ?? ''; @endphp
                    <span class="{{ $jenisTuk == 'Sewaktu' ? 'font-bold text-blue-600' : 'text-gray-400' }}">Sewaktu</span> / 
                    <span class="{{ $jenisTuk == 'Tempat Kerja' ? 'font-bold text-blue-600' : 'text-gray-400' }}">Tempat Kerja</span> / 
                    <span class="{{ $jenisTuk == 'Mandiri' ? 'font-bold text-blue-600' : 'text-gray-400' }}">Mandiri</span>
                </div>

                <div class="font-bold text-black">Nama Asesor</div>
                <div>: {{ $jadwal->asesor->nama_lengkap ?? '-' }}</div>

                <div class="font-bold text-black">Tanggal</div>
                <div>: {{ date('d F Y') }}</div>
            </div>

            {{-- PENJELASAN --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 text-sm text-gray-700">
                <h3 class="font-bold text-blue-900 mb-2">Penjelasan :</h3>
                <ul class="list-disc pl-5 space-y-1 text-xs leading-relaxed">
                    <li>Peninjauan dapat dilakukan oleh lead asesor atau asesor yang melaksanakan asesmen.</li>
                    <li>Peninjauan dapat dilakukan secara terpadu dalam skema sertifikasi dan / atau peserta kelompok yang homogen.</li>
                    <li>Isilah pemenuhan dimensi kompetensi dengan menulis kode rekaman formulir yang membuktikan terpenuhinya dimensi kompetensi.</li>
                </ul>
            </div>

            {{-- TABEL 1: Prinsip Asesmen --}}
            <div class="mb-8 overflow-x-auto">
                <table class="w-full text-sm form-table min-w-[800px]">
                    <thead class="bg-gray-100 text-center font-bold">
                        <tr>
                            <th rowspan="2" class="p-3 text-left w-1/3 align-middle">Aspek yang ditinjau</th>
                            <th colspan="4" class="p-2 align-middle">Kesesuaian dengan prinsip asesmen</th>
                        </tr>
                        <tr>
                            <th class="p-2 w-24 align-middle">Validitas</th>
                            <th class="p-2 w-24 align-middle">Reliabel</th>
                            <th class="p-2 w-24 align-middle">Fleksibel</th>
                            <th class="p-2 w-24 align-middle">Adil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-gray-50">
                            <td colspan="5" class="p-2 font-bold">Prosedur asesmen:</td>
                        </tr>

                        {{-- Rencana Asesmen --}}
                        <tr>
                            <td class="p-2 pl-6">Rencana asesmen</td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[rencana_asesmen][]" value="validitas" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['rencana_asesmen']) && in_array('validitas', $data->tinjauan['rencana_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[rencana_asesmen][]" value="reliabel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['rencana_asesmen']) && in_array('reliabel', $data->tinjauan['rencana_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[rencana_asesmen][]" value="fleksibel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['rencana_asesmen']) && in_array('fleksibel', $data->tinjauan['rencana_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[rencana_asesmen][]" value="adil" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['rencana_asesmen']) && in_array('adil', $data->tinjauan['rencana_asesmen']) ? 'checked' : '' }}></td>
                        </tr>

                        {{-- Persiapan Asesmen --}}
                        <tr>
                            <td class="p-2 pl-6">Persiapan asesmen</td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[persiapan_asesmen][]" value="validitas" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['persiapan_asesmen']) && in_array('validitas', $data->tinjauan['persiapan_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[persiapan_asesmen][]" value="reliabel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['persiapan_asesmen']) && in_array('reliabel', $data->tinjauan['persiapan_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[persiapan_asesmen][]" value="fleksibel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['persiapan_asesmen']) && in_array('fleksibel', $data->tinjauan['persiapan_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[persiapan_asesmen][]" value="adil" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['persiapan_asesmen']) && in_array('adil', $data->tinjauan['persiapan_asesmen']) ? 'checked' : '' }}></td>
                        </tr>

                        {{-- Implementasi Asesmen --}}
                        <tr>
                            <td class="p-2 pl-6">Implementasi asesmen</td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[implementasi_asesmen][]" value="validitas" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['implementasi_asesmen']) && in_array('validitas', $data->tinjauan['implementasi_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[implementasi_asesmen][]" value="reliabel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['implementasi_asesmen']) && in_array('reliabel', $data->tinjauan['implementasi_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[implementasi_asesmen][]" value="fleksibel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['implementasi_asesmen']) && in_array('fleksibel', $data->tinjauan['implementasi_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[implementasi_asesmen][]" value="adil" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['implementasi_asesmen']) && in_array('adil', $data->tinjauan['implementasi_asesmen']) ? 'checked' : '' }}></td>
                        </tr>

                        {{-- Keputusan Asesmen --}}
                        <tr>
                            <td class="p-2 pl-6">Keputusan asesmen</td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[keputusan_asesmen][]" value="validitas" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['keputusan_asesmen']) && in_array('validitas', $data->tinjauan['keputusan_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[keputusan_asesmen][]" value="reliabel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['keputusan_asesmen']) && in_array('reliabel', $data->tinjauan['keputusan_asesmen']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center bg-gray-200"></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[keputusan_asesmen][]" value="adil" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['keputusan_asesmen']) && in_array('adil', $data->tinjauan['keputusan_asesmen']) ? 'checked' : '' }}></td>
                        </tr>

                        {{-- Umpan Balik Asesmen --}}
                        <tr>
                            <td class="p-2 pl-6">Umpan balik asesmen</td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[umpan_balik][]" value="validitas" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['umpan_balik']) && in_array('validitas', $data->tinjauan['umpan_balik']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[umpan_balik][]" value="reliabel" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['umpan_balik']) && in_array('reliabel', $data->tinjauan['umpan_balik']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center bg-gray-200"></td>
                            <td class="p-2 text-center"><input type="checkbox" name="tinjauan[umpan_balik][]" value="adil" class="w-5 h-5 cursor-pointer" {{ isset($data->tinjauan['umpan_balik']) && in_array('adil', $data->tinjauan['umpan_balik']) ? 'checked' : '' }}></td>
                        </tr>

                        {{-- Rekomendasi 1 --}}
                        <tr>
                            <td class="p-2 font-bold align-top" colspan="1">Rekomendasi untuk peningkatan :</td>
                            <td class="p-2" colspan="4">
                                <textarea name="rekomendasi_aspek" class="w-full h-20 border-none outline-none resize-none text-sm" placeholder="Tulis rekomendasi di sini...">{{ old('rekomendasi_aspek', $data->rekomendasi_aspek ?? $template['rekomendasi_aspek'] ?? '') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TABEL 2: Dimensi Kompetensi --}}
            <div class="mb-8 overflow-x-auto">
                <table class="w-full text-sm form-table min-w-[800px]">
                    <thead class="bg-gray-100 text-center font-bold text-xs">
                        <tr>
                            <th rowspan="2" class="p-3 w-1/3 align-middle">Aspek yang ditinjau</th>
                            <th colspan="5" class="p-2 align-middle">Pemenuhan dimensi kompetensi</th>
                        </tr>
                        <tr>
                            <th class="p-2 w-24 align-middle">Task Skills</th>
                            <th class="p-2 w-24 align-middle">Task Management Skills</th>
                            <th class="p-2 w-24 align-middle">Contingency Management Skills</th>
                            <th class="p-2 w-24 align-middle">Job Role/ Environment Skills</th>
                            <th class="p-2 w-24 align-middle">Transfer Skills</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2 font-bold">Konsistensi keputusan asesmen</td>
                            <td colspan="5" class="p-0 border-none bg-gray-100"></td>
                        </tr>
                        <tr>
                            <td class="p-2 pl-6 align-top">
                                Bukti dari berbagai asesmen diperiksa untuk konsistensi dimensi kompetensi
                            </td>
                            {{-- Menggunakan array name="dimensi[konsistensi][]" --}}
                            <td class="p-2 text-center align-middle"><input type="checkbox" name="dimensi[konsistensi][]" value="task_skills" class="w-5 h-5 cursor-pointer" {{ isset($data->dimensi['konsistensi']) && in_array('task_skills', $data->dimensi['konsistensi']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center align-middle"><input type="checkbox" name="dimensi[konsistensi][]" value="task_management" class="w-5 h-5 cursor-pointer" {{ isset($data->dimensi['konsistensi']) && in_array('task_management', $data->dimensi['konsistensi']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center align-middle"><input type="checkbox" name="dimensi[konsistensi][]" value="contingency_management" class="w-5 h-5 cursor-pointer" {{ isset($data->dimensi['konsistensi']) && in_array('contingency_management', $data->dimensi['konsistensi']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center align-middle"><input type="checkbox" name="dimensi[konsistensi][]" value="job_role" class="w-5 h-5 cursor-pointer" {{ isset($data->dimensi['konsistensi']) && in_array('job_role', $data->dimensi['konsistensi']) ? 'checked' : '' }}></td>
                            <td class="p-2 text-center align-middle"><input type="checkbox" name="dimensi[konsistensi][]" value="transfer_skills" class="w-5 h-5 cursor-pointer" {{ isset($data->dimensi['konsistensi']) && in_array('transfer_skills', $data->dimensi['konsistensi']) ? 'checked' : '' }}></td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold align-top" colspan="1">Rekomendasi untuk peningkatan :</td>
                            <td class="p-2" colspan="5">
                                <textarea name="rekomendasi_dimensi" class="w-full h-20 border-none outline-none resize-none text-sm" placeholder="Tulis rekomendasi di sini...">{{ old('rekomendasi_dimensi', $data->rekomendasi_dimensi ?? $template['rekomendasi_dimensi'] ?? '') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TABEL 3: Tanda Tangan --}}
            <div class="mt-8 overflow-x-auto">
                <table class="w-full text-sm form-table min-w-[600px]">
                    <thead class="bg-gray-100 text-center font-bold">
                        <tr>
                            <th class="p-2 w-1/4 align-middle">Nama Lead Asesor/Asesor</th>
                            <th class="p-2 w-1/4 align-middle">Tanggal & Tanda Tangan</th>
                            <th class="p-2 w-1/2 align-middle">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2 align-top h-32">
                                <input type="text" name="peninjau[nama]" value="{{ old('peninjau.nama', $data->peninjau['nama'] ?? $jadwal->asesor->nama_lengkap ?? '') }}" class="w-full border-b border-gray-300 outline-none mb-2 bg-transparent" placeholder="Nama...">
                            </td>
                            <td class="p-2 align-top h-32 text-center flex flex-col justify-between">
                                <input type="text" name="peninjau[tanggal]" value="{{ old('peninjau.tanggal', $data->peninjau['tanggal'] ?? date('d F Y')) }}" class="w-full border-b border-gray-300 outline-none mb-8 text-center bg-transparent" placeholder="Tanggal...">
                                <div class="text-gray-400 text-xs italic mt-auto">(Tanda Tangan)</div>
                            </td>
                            <td class="p-2 align-top h-32">
                                <textarea name="peninjau[komentar]" class="w-full h-full border-none outline-none resize-none bg-transparent" placeholder="Tulis komentar...">{{ old('peninjau.komentar', $data->peninjau['komentar'] ?? $template['peninjau_komentar'] ?? '') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="mt-12 flex justify-end gap-4 pb-8">
                @if(isset($isMasterView))
                    <a href="{{ url()->previous() }}" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg shadow hover:bg-gray-700 transition">Kembali</a>
                @else
                    <button type="button" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg shadow hover:bg-gray-300 transition">Simpan Draft</button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition">Simpan Permanen</button>
                @endif
            </div>

        </div>
    </form>
@endsection