{{-- File: resources/views/frontend/IA_01/single_page.blade.php --}}
@extends($layout ?? 'layouts.app-sidebar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <form action="{{ route('ia01.store', ['id_sertifikasi' => $sertifikasi->id_data_sertifikasi_asesi]) }}" method="POST">
        @csrf

        {{-- HEADER / TITLE --}}
        {{-- HEADER --}}
        <x-header_form.header_form title="FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI" />

        {{-- SECTION 1: DATA DIRI --}}
        <div class="mb-8 border-2 border-gray-800 rounded-sm text-sm text-gray-900">
            
            {{-- Skema Sertifikasi --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50 flex items-center">
                    Skema Sertifikasi
                </div>
                <div>
                    <div class="grid grid-cols-[100px_10px_1fr] border-b border-gray-800 p-2 items-center">
                        <div class="font-semibold text-gray-600">Judul</div>
                        <div>:</div>
                        <div class="font-bold uppercase">{{ $skema->nama_skema ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-[100px_10px_1fr] p-2 items-center">
                        <div class="font-semibold text-gray-600">Tanggal</div>
                        <div>:</div>
                        <div>{{ now()->format('d-m-Y') }}</div>
                    </div>
                </div>
            </div>

            {{-- TUK --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800 items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    TUK
                </div>
                <div class="p-2 pl-4 flex flex-wrap gap-6">
                    @php $tuk_value = old('tuk', $data_sesi['tuk'] ?? ''); @endphp
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tuk" value="sewaktu" class="tuk-checkbox rounded text-blue-600 focus:ring-blue-500" onclick="selectOnlyThis(this)" {{ $tuk_value == 'sewaktu' ? 'checked' : '' }}>
                        <span class="ml-2">Sewaktu</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tuk" value="tempat_kerja" class="tuk-checkbox rounded text-blue-600 focus:ring-blue-500" onclick="selectOnlyThis(this)" {{ $tuk_value == 'tempat_kerja' ? 'checked' : '' }}>
                        <span class="ml-2">Tempat Kerja</span>
                    </label>
                    @error('tuk') <span class="text-red-600 text-xs ml-2">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Nama Asesor --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800 items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    Nama Asesor
                </div>
                <div class="p-2 pl-4 font-medium">
                    {{ $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Asesor' }}
                </div>
            </div>

            {{-- Nama Asesi --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] border-b border-gray-800 items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    Nama Asesi
                </div>
                <div class="p-2 pl-4 font-medium">
                    {{ $sertifikasi->asesi->nama_lengkap ?? 'Asesi' }}
                </div>
            </div>

            {{-- Tanggal Asesmen --}}
            <div class="grid grid-cols-1 md:grid-cols-[250px_1fr] items-center">
                <div class="p-3 font-bold border-b md:border-b-0 md:border-r border-gray-800 bg-gray-50">
                    Tanggal
                </div>
                <div class="p-2 pl-4">
                    <input type="date" name="tanggal_asesmen"
                           class="border-0 border-b border-gray-400 focus:ring-0 p-0 text-gray-900 bg-transparent w-full md:w-auto"
                           value="{{ old('tanggal_asesmen', $data_sesi['tanggal_asesmen'] ?? now()->format('Y-m-d')) }}">
                    @error('tanggal_asesmen') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- PANDUAN --}}
        <div class="border-2 border-gray-800 mb-8 text-gray-900 text-sm rounded-sm">
            <div class="p-2 bg-gray-50 border-b-2 border-gray-800 font-bold">
                PANDUAN BAGI ASESOR
            </div>
            <div class="p-3">
                <ul class="list-disc list-inside space-y-1">
                    <li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
                    <li>Isilah standar industri atau tempat kerja.</li>
                    <li>Beri tanda centang (✓) pada kolom YA jika sesuai, atau Tidak bila sebaliknya.</li>
                    <li>Penilaian Lanjut diisi jika hasil belum dapat disimpulkan.</li>
                </ul>
            </div>
        </div>

        {{-- SECTION 2: UNITS LOOP --}}
        <div class="space-y-10 mb-10">
            @foreach ($kelompok->unitKompetensi as $unit)
            <div class="border-2 border-gray-800 rounded-sm overflow-hidden">
                {{-- Unit Header --}}
                <div class="bg-blue-50 px-4 py-3 border-b-2 border-gray-800 flex flex-col md:flex-row justify-between md:items-center">
                    <div>
                        <span class="text-xs font-bold text-blue-800 uppercase tracking-wider block md:inline mb-1 md:mb-0">Unit {{ $unit->urutan }}</span>
                        <h3 class="text-lg font-bold text-gray-900 inline">{{ $unit->kode_unit }} - {{ $unit->judul_unit }}</h3>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-900">
                        <thead class="bg-gray-100 font-bold uppercase border-b-2 border-gray-800 text-xs">
                            <tr>
                                <th rowspan="2" class="p-3 border-r border-gray-800 w-10 text-center">No</th>
                                <th rowspan="2" class="p-3 border-r border-gray-800 w-1/4 min-w-[200px]">Elemen</th>
                                <th rowspan="2" class="p-3 border-r border-gray-800 w-1/3 min-w-[300px]">Kriteria Unjuk Kerja</th>
                                <th rowspan="2" class="p-3 border-r border-gray-800 w-1/5 min-w-[150px]">Standar Industri</th>
                                <th colspan="2" class="p-3 border-r border-gray-800 w-24 text-center min-w-[100px] border-b border-gray-800">Pencapaian</th>
                                <th rowspan="2" class="p-3 min-w-[150px]">Penilaian Lanjut</th>
                            </tr>
                            <tr>
                                <th class="p-3 border-r border-gray-800 text-center w-12">Ya</th>
                                <th class="p-3 border-r border-gray-800 text-center w-12">Tidak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300">
                             @foreach ($unit->elemen as $elemen)
                                @php $totalKuk = $elemen->kriteria->count(); @endphp
                                @foreach ($elemen->kriteria as $index => $kuk)
                                <tr class="hover:bg-gray-50">
                                    {{-- Elemen & No --}}
                                    @if ($index === 0)
                                    <td rowspan="{{ $totalKuk }}" class="p-3 border-r border-gray-800 bg-white align-top font-bold text-center">
                                        {{ $loop->parent->iteration }}
                                    </td>
                                    <td rowspan="{{ $totalKuk }}" class="p-3 border-r border-gray-800 bg-white align-top font-bold">
                                        {{ $elemen->elemen }}
                                    </td>
                                    @endif

                                    {{-- KUK --}}
                                    <td class="p-3 border-r border-gray-800 align-top">
                                        <div class="flex gap-2">
                                            <span class="font-bold text-gray-500">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                                            <span>{{ $kuk->kriteria }}</span>
                                        </div>
                                    </td>

                                    {{-- Standar Industri --}}
                                    <td class="p-2 border-r border-gray-800 align-top">
                                        <textarea name="standar_industri[{{ $kuk->id_kriteria }}]" rows="2" class="w-full text-xs border-gray-300 rounded bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Isi jika ada...">{{ old("standar_industri.{$kuk->id_kriteria}", $existingResponses[$kuk->id_kriteria]->standar_industri_ia01 ?? ($templateContent[$kuk->id_kriteria] ?? $kuk->standar_industri_kerja)) }}</textarea>
                                    </td>

                                    {{-- Checkbox Hasil: YA --}}
                                    <td class="p-0 border-r border-gray-800 align-top hover:bg-green-50 cursor-pointer transition-colors"
                                        onclick="triggerCheck('{{ $kuk->id_kriteria }}', 'kompeten', event)">
                                        <div class="flex justify-center items-center h-full py-4 px-2">
                                            <input type="checkbox"
                                                id="cb_ya_{{ $kuk->id_kriteria }}"
                                                name="hasil[{{ $kuk->id_kriteria }}]"
                                                value="kompeten"
                                                class="kuk-check-{{ $kuk->id_kriteria }} w-5 h-5 text-green-600 rounded focus:ring-green-500 border-gray-400 cursor-pointer"
                                                onclick="handleExclusiveCheckbox(this, '{{ $kuk->id_kriteria }}')"
                                                {{ (old("hasil.{$kuk->id_kriteria}") ?? ($existingResponses[$kuk->id_kriteria]->pencapaian_ia01 ?? -1)) == 1 || (old("hasil.{$kuk->id_kriteria}") == 'kompeten') ? 'checked' : '' }}>
                                        </div>
                                    </td>

                                    {{-- Checkbox Hasil: TIDAK --}}
                                    <td class="p-0 border-r border-gray-800 align-top hover:bg-red-50 cursor-pointer transition-colors"
                                        onclick="triggerCheck('{{ $kuk->id_kriteria }}', 'belum_kompeten', event)">
                                        <div class="flex justify-center items-center h-full py-4 px-2">
                                            <input type="checkbox"
                                                id="cb_tidak_{{ $kuk->id_kriteria }}"
                                                name="hasil[{{ $kuk->id_kriteria }}]"
                                                value="belum_kompeten"
                                                class="kuk-check-{{ $kuk->id_kriteria }} w-5 h-5 text-red-600 rounded focus:ring-red-500 border-gray-400 cursor-pointer"
                                                onclick="handleExclusiveCheckbox(this, '{{ $kuk->id_kriteria }}')"
                                                {{ (old("hasil.{$kuk->id_kriteria}") ?? ($existingResponses[$kuk->id_kriteria]->pencapaian_ia01 ?? -1)) === 0 || (old("hasil.{$kuk->id_kriteria}") == 'belum_kompeten') ? 'checked' : '' }}>
                                        </div>
                                    </td>

                                    {{-- Penilaian Lanjut --}}
                                    <td class="p-2 align-top">
                                         <textarea name="penilaian_lanjut[{{ $kuk->id_kriteria }}]" rows="2" class="w-full text-xs border-gray-300 rounded bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan...">{{ old("penilaian_lanjut.{$kuk->id_kriteria}", $existingResponses[$kuk->id_kriteria]->penilaian_lanjut_ia01 ?? '') }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                             @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        {{-- SECTION 3: REKOMENDASI (VERSI BARU - PROFESSIONAL) --}}
        <div class="mb-10 border-2 border-gray-800 rounded-sm text-gray-900">
            <div class="p-4 bg-gray-50 border-b-2 border-gray-800 font-bold text-lg">
                3. Rekomendasi & Umpan Balik
            </div>
            
            <div class="p-6">
                {{-- Umpan Balik --}}
                <div class="mb-6">
                    <label class="block font-bold text-gray-700 mb-2">Umpan Balik Asesor:</label>
                    <textarea name="umpan_balik" rows="4" class="w-full border-2 border-gray-300 rounded p-3 focus:ring-blue-500 focus:border-blue-500 bg-gray-50" placeholder="Masukkan umpan balik untuk asesi...">{{ old('umpan_balik', $sertifikasi->feedback_ia01) }}</textarea>
                    @error('umpan_balik') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- REKOMENDASI LOGIC --}}
                <div class="mb-5 p-6 rounded-lg border transition-colors duration-300
                    {{ $rekomendasiSistem == 'belum_kompeten' ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' }}">

                    <h2 class="font-bold text-xl mb-4 text-gray-800">Rekomendasi Asesor:</h2>

                    {{-- ALERT KALAU OTOMATIS BK --}}
                    @if($rekomendasiSistem == 'belum_kompeten')
                        <div class="flex items-start gap-3 text-red-800 bg-red-100 p-4 rounded-md mb-6 text-sm border border-red-200 shadow-sm">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div class="leading-relaxed">
                                <strong>Perhatian:</strong> Berdasarkan hasil checklist observasi, terdapat poin <strong>Belum Kompeten (BK)</strong>. <br>
                                Sesuai prosedur, rekomendasi akhir dikunci pada status <strong>Belum Kompeten</strong>.
                            </div>
                        </div>
                    @endif

                    {{-- PILIHAN KOMPETEN --}}
                    <label class="flex items-center gap-3 mb-3 p-2 rounded hover:bg-white/50 transition
                        {{ $rekomendasiSistem == 'belum_kompeten' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">

                        <input type="checkbox" name="rekomendasi" value="kompeten"
                            class="reco-check w-5 h-5 border-2 border-gray-400 rounded text-green-600 focus:ring-green-500"
                            onclick="toggleRekomendasi(this, 'kompeten')"
                            {{ (old('rekomendasi', $sertifikasi->rekomendasi_ia01) == 'kompeten' && $rekomendasiSistem != 'belum_kompeten') ? 'checked' : '' }}
                            {{ $rekomendasiSistem == 'belum_kompeten' ? 'disabled' : '' }}>

                        <span class="font-semibold text-gray-800">
                            Asesi <span class="text-green-700 font-bold">KOMPETEN</span> (Memenuhi seluruh kriteria unjuk kerja)
                        </span>
                    </label>

                    {{-- PILIHAN BELUM KOMPETEN --}}
                    <label class="flex items-center gap-3 p-2 rounded hover:bg-white/50 transition cursor-pointer">
                        <input type="checkbox" name="rekomendasi" value="belum_kompeten"
                            class="reco-check w-5 h-5 border-2 border-gray-400 rounded text-red-600 focus:ring-red-500"
                            onclick="toggleRekomendasi(this, 'belum_kompeten')"
                            {{ old('rekomendasi', $sertifikasi->rekomendasi_ia01) == 'belum_kompeten' || $rekomendasiSistem == 'belum_kompeten' ? 'checked' : '' }}>

                        <span class="font-semibold text-gray-800">
                            Asesi <span class="text-red-600 font-bold">BELUM KOMPETEN</span> (Belum memenuhi seluruh kriteria)
                        </span>
                    </label>

                    {{-- Detail BK (FORM INPUT) --}}
                    <div id="bk-details" class="ml-8 mt-4 space-y-4 {{ (old('rekomendasi', $sertifikasi->rekomendasi_ia01) == 'belum_kompeten' || $rekomendasiSistem == 'belum_kompeten') ? '' : 'hidden' }}">
                        
                        {{-- Note Kecil --}}
                        <div class="text-sm text-gray-600 italic border-l-4 border-gray-400 pl-3 py-1">
                            Mohon lengkapi rincian aspek yang belum terpenuhi di bawah ini sebagai bahan evaluasi:
                        </div>

                        {{-- Kelompok Pekerjaan --}}
                        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                            <label class="font-bold text-sm text-gray-700">Kelompok Pekerjaan</label>
                            <input type="text" name="bk_kelompok"
                                class="w-full border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100 text-gray-600 cursor-not-allowed shadow-sm"
                                value="{{ $kelompok->nama_kelompok_pekerjaan }}" readonly>
                        </div>

                        {{-- Unit Kompetensi --}}
                        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                            <label class="font-bold text-sm text-gray-700">Unit Kompetensi</label>
                            <input type="text" name="bk_unit"
                                class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:border-red-500 focus:ring-red-200 shadow-sm"
                                placeholder="Contoh: Menyiapkan Peralatan (Kode Unit)" value="{{ old('bk_unit') }}">
                        </div>

                        {{-- Elemen --}}
                        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                            <label class="font-bold text-sm text-gray-700">Elemen</label>
                            <input type="text" name="bk_elemen"
                                class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:border-red-500 focus:ring-red-200 shadow-sm"
                                placeholder="Sebutkan elemen kompetensi yang belum terpenuhi..." value="{{ old('bk_elemen') }}">
                        </div>

                        {{-- KUK --}}
                        <div class="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-2 items-center">
                            <label class="font-bold text-sm text-gray-700">No. KUK</label>
                            <input type="text" name="bk_kuk"
                                class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:border-red-500 focus:ring-red-200 shadow-sm"
                                placeholder="Contoh: 1.2, 2.1 (Pisahkan dengan koma)" value="{{ old('bk_kuk') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: TANDA TANGAN (From finish.blade.php) --}}
        <x-kolom_ttd.asesiasesor :sertifikasi="$sertifikasi" />

        {{-- SUBMIT BUTTON (NON-FIXED) --}}
        <div class="flex justify-end mt-8 mb-12">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-12 rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                Simpan & Selesai
            </button>
        </div>

    </form>
</div>

<script>
    // TUK Checkbox Logic (Only one)
    function selectOnlyThis(checkbox) {
        document.querySelectorAll('.tuk-checkbox').forEach(cb => {
            if (cb !== checkbox) cb.checked = false;
        });
    }

    // KUK Checkbox Logic (Exclusive K vs BK)
    // Updated to handle the DIV click (triggerCheck)
    function triggerCheck(id, value, event) {
        // Prevent double triggering if clicking directly on input
        if (event.target.tagName === 'INPUT') return;

        let checkbox = document.getElementById(value === 'kompeten' ? 'cb_ya_' + id : 'cb_tidak_' + id);
        checkbox.checked = !checkbox.checked;
        
        // Handle exclusivity (uncheck the other one)
        handleExclusiveCheckbox(checkbox, id);
    }

    function handleExclusiveCheckbox(checkbox, id) {
        if (!checkbox.checked) return; // If unchecking, do nothing special
        
        // Uncheck the other box for this KUK
        let boxes = document.querySelectorAll('.kuk-check-' + id);
        boxes.forEach((box) => {
            if (box !== checkbox) box.checked = false;
        });

        // Trigger System Recommendation Check
        updateSystemRecommendation();
    }

    // Rekomendasi Checkbox Logic (Only one)
    function toggleRekomendasi(checkbox, val) {
        document.querySelectorAll('.reco-check').forEach(el => {
            if(el !== checkbox) el.checked = false;
        });
        if(!checkbox.checked) checkbox.checked = true;

        // Toggle BK Details
        let bkDetails = document.getElementById('bk-details');
        if (bkDetails) {
            if (val === 'belum_kompeten') {
                bkDetails.classList.remove('hidden');
            } else {
                bkDetails.classList.add('hidden');
            }
        }
    }

    // Dynamic System Recommendation Logic
    function updateSystemRecommendation() {
        // Check if ANY "Belum Kompeten" is checked
        let allBkCheckboxes = document.querySelectorAll('input[value="belum_kompeten"]:checked');
        let hasBk = allBkCheckboxes.length > 0;

        let radioKompeten = document.querySelector('input[name="rekomendasi"][value="kompeten"]');
        let radioBelum = document.querySelector('input[name="rekomendasi"][value="belum_kompeten"]');
        let container = radioKompeten.closest('.border.transition-colors'); // The main container
        let bkDetails = document.getElementById('bk-details');
        
        // If has BK
        if (hasBk) {
            // Disable Kompeten
            radioKompeten.checked = false;
            radioKompeten.disabled = true;
            radioKompeten.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Auto Select Belum Kompeten
            radioBelum.checked = true;

            // Change Container Style
            if(container) {
                container.classList.remove('bg-green-50', 'border-green-200');
                container.classList.add('bg-red-50', 'border-red-200');
            }
            
            // Show BK Details
            if(bkDetails) bkDetails.classList.remove('hidden');
            
        } else {
            // Enable Kompeten
            radioKompeten.disabled = false;
            radioKompeten.parentElement.classList.remove('opacity-50', 'cursor-not-allowed');

            // Restore Container Style (Default to Greenish if neutral, or keep current)
            if(container) {
                container.classList.remove('bg-red-50', 'border-red-200');
                container.classList.add('bg-green-50', 'border-green-200');
            }
        }
    }
</script>
@endsection
