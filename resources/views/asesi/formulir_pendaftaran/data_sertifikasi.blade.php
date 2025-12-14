@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sertifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- FONT POPPINS --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Terapkan Poppins ke seluruh body */
        body { font-family: 'Poppins', sans-serif; }
        
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 md:bg-gray-100 font-sans">

    {{-- WRAPPER UTAMA --}}
    <div class="flex min-h-screen flex-col md:flex-row md:h-screen md:overflow-hidden">

        {{-- 1. SIDEBAR (Desktop Only - TIDAK DIUBAH) --}}
        <div class="hidden md:block md:w-80 flex-shrink-0">
            <x-sidebar :idAsesi="$asesi->id_asesi" :sertifikasi="$sertifikasi"></x-sidebar>
        </div>

        {{-- 2. HEADER MOBILE (Component Baru) --}}
        @php
            $gambarSkema = null;
            if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema && $sertifikasi->jadwal->skema->gambar) {
                $gambar = $sertifikasi->jadwal->skema->gambar;
                if (str_starts_with($gambar, 'images/')) {
                    $gambarSkema = asset($gambar);
                } elseif (file_exists(public_path('images/skema/foto_skema/' . $gambar))) {
                    $gambarSkema = asset('images/skema/foto_skema/' . $gambar);
                } else {
                    $gambarSkema = asset('images/skema/' . $gambar);
                }
            }
        @endphp

        <x-mobile_header
            :title="$sertifikasi->jadwal->skema->nama_skema ?? 'Skema Sertifikasi'"
            :code="$sertifikasi->jadwal->skema->kode_unit ?? $sertifikasi->jadwal->skema->nomor_skema ?? '-'"
            :name="$sertifikasi->asesi->nama_lengkap ?? 'Nama Peserta'"
            :image="$gambarSkema"
            :sertifikasi="$sertifikasi"
        />

        {{-- 3. MAIN CONTENT --}}
        <main class="flex-1 w-full relative md:p-12 md:overflow-y-auto bg-gray-50 md:bg-white z-0" 
              data-sertifikasi-id="{{ $id_sertifikasi_untuk_js }}">
            
            {{-- CONTAINER RESPONSIF --}}
            {{-- Mobile: Card naik ke atas (-mt-16) menutupi header. Desktop: Reset style jadi polos --}}
            <div class="max-w-4xl mx-auto mt-16 md:mt-0 p-6 md:p-0 transition-all bg-white rounded-t-[40px] md:rounded-none md:bg-transparent min-h-screen md:min-h-0 shadow-2xl md:shadow-none">

                {{-- A. STEPPER --}}
                
                {{-- 1. Desktop Stepper (Original Style: Yellow & Gray) --}}
                <div class="hidden md:flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center"><div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">1</div></div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center"><div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">2</div></div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center"><div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">3</div></div>
                </div>

                {{-- 2. Mobile Stepper (Kuning semua sesuai gambar) --}}
                <div class="flex items-center justify-center mb-8 md:hidden pt-8 md:pt-0">
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-black font-bold text-xl shadow-md">1</div></div>
                    <div class="w-16 h-1.5 bg-[#FFF4C3] mx-1"></div>
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFF4C3] rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">2</div></div>
                    <div class="w-16 h-1.5 bg-[#FFF4C3] mx-1"></div>
                    <div class="flex flex-col items-center"><div class="w-12 h-12 bg-[#FFF4C3] rounded-full flex items-center justify-center text-gray-600 font-bold text-xl">3</div></div>
                </div>

                {{-- CONTENT --}}
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 text-center md:text-left">Data Sertifikasi</h1>
                <p class="text-gray-600 mb-8 text-sm md:text-base text-center md:text-left">
                    Pilih Tujuan Asesmen serta Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi yang anda ajukan.
                </p>

                {{-- INFO SKEMA --}}
                {{-- Mobile: Beige (#F9F6E6) | Desktop: Amber-50 (Original) --}}
                <div class="bg-[#F9F6E6] border-[#EAE5C8] md:bg-amber-50 md:border-amber-200 border rounded-lg p-6 mb-8">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Skema Sertifikasi / Klaster Asesmen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4">
                        <div class="flex md:contents">
                            <div class="w-24 md:w-auto text-sm font-medium text-gray-600">Judul</div>
                            <div class="md:col-span-2 text-sm text-gray-900 font-medium md:font-normal" id="skema-judul">: ...Memuat data...</div>
                        </div>
                        <div class="flex md:contents">
                            <div class="w-24 md:w-auto text-sm font-medium text-gray-600">Nomor</div>
                            <div class="md:col-span-2 text-sm text-gray-900 font-medium md:font-normal" id="skema-nomor">: ...Memuat data...</div>
                        </div>
                    </div>
                </div>

                {{-- PILIH TUJUAN --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Tujuan Asesmen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach(['sertifikasi' => 'Sertifikasi', 'PKT' => 'Pengakuan Kompetensi Terkini (PKT)', 'RPL' => 'Rekognisi Pembelajaran Lampau (RPL)', 'lainnya' => 'Lainnya'] as $val => $label)
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="tujuan_asesmen" value="{{ $val }}" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- TABEL UNIT KOMPETENSI (FIXED & RESPONSIVE) --}}
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                    
                    <div class="border border-gray-200 rounded-lg shadow-sm overflow-x-auto">
                        {{-- PERUBAHAN DI SINI: Kolom jenis dihapus, width tetap full --}}
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-black text-white">
                                <tr>
                                    {{-- Kolom NO --}}
                                    <th class="px-4 py-3 text-center text-xs md:text-sm font-bold uppercase tracking-wider whitespace-nowrap w-16">No</th>
                                    {{-- Kolom KODE --}}
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-bold uppercase tracking-wider whitespace-nowrap w-1/4">Kode</th>
                                    {{-- Kolom JUDUL UNIT --}}
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-bold uppercase tracking-wider whitespace-nowrap">Judul Unit</th>
                                    {{-- Kolom JENIS SUDAH DIHAPUS DARI SINI --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="unit-kompetensi-body">
                                <tr>
                                    {{-- Colspan jadi 3 karena sisa 3 kolom --}}
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TOMBOL NAVIGASI --}}
                {{-- Flex row: Biar tombolnya jejer Kiri-Kanan --}}
                <div class="flex justify-between items-center pb-20 md:pb-0 gap-4 mt-8 border-t border-gray-100 pt-6">
                    <a href="{{ route('asesi.tracker', ['jadwal_id' => $sertifikasi->id_jadwal]) }}" 
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-300 transition-all shadow-sm text-sm md:text-base">
                        Kembali
                    </a>

                    <button type="button" id="btn-selanjutnya" 
                        data-next-url="{{ route('asesi.bukti.pemohon', ['id_sertifikasi' => $id_sertifikasi_untuk_js]) }}"
                        class="w-32 md:w-48 text-center px-4 md:px-8 py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm md:text-base shadow-blue-200">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- SCRIPT JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainElement = document.querySelector('main[data-sertifikasi-id]');
            let sertifikasiId = null;
            const elJudulSkema = document.getElementById('skema-judul');
            const elNomorSkema = document.getElementById('skema-nomor');
            const tujuanCheckboxes = document.querySelectorAll('.tujuan-checkbox');
            const btnSelanjutnya = document.getElementById('btn-selanjutnya');
            
            if (mainElement && mainElement.dataset.sertifikasiId) {
                sertifikasiId = mainElement.dataset.sertifikasiId;
            } else {
                return;
            }

            fetch(`/api/v1/data-sertifikasi/detail/${sertifikasiId}`)
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data) {
                        const data = response.data;
                        if (data.jadwal && data.jadwal.skema) {
                            elJudulSkema.textContent = ': ' + (data.jadwal.skema.nama_skema || '-');
                            elNomorSkema.textContent = ': ' + (data.jadwal.skema.nomor_skema || '-'); 
                            renderTabelUnit(data.jadwal.skema.unit_kompetensi);
                        }
                        if (data.tujuan_asesmen) {
                            tujuanCheckboxes.forEach(cb => {
                                if (cb.value === data.tujuan_asesmen) cb.checked = true;
                            });
                        }
                    }
                });

            tujuanCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', (e) => {
                    const clicked = e.target;
                    tujuanCheckboxes.forEach(cb => { if (cb !== clicked) cb.checked = false; });
                });
            });

            btnSelanjutnya.addEventListener('click', async function() {
                const selected = Array.from(tujuanCheckboxes).find(cb => cb.checked);
                if (!selected) { alert('Mohon pilih salah satu Tujuan Asesmen.'); return; }
                
                this.textContent = 'Menyimpan...';
                this.disabled = true;

                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                    const response = await fetch('/api/v1/data-sertifikasi', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ id_data_sertifikasi_asesi: sertifikasiId, tujuan_asesmen: selected.value })
                    });
                    const result = await response.json();
                    if (response.ok && result.success) {
                        window.location.href = this.dataset.nextUrl; 
                    } else { throw new Error(result.message); }
                } catch (error) {
                    alert('Gagal: ' + error.message);
                    this.textContent = 'Selanjutnya';
                    this.disabled = false;
                }
            });

            function renderTabelUnit(units) {
                const tbody = document.getElementById('unit-kompetensi-body');
                tbody.innerHTML = ''; 
                if (units && units.length > 0) {
                    units.forEach((unit, index) => {
                        // PERUBAHAN JS: TD Jenis dihapus
                        const row = `
                            <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} border-b border-gray-100 hover:bg-gray-100 transition-colors">
                                <td class="px-2 py-3 text-center text-xs md:text-sm text-gray-900 font-medium align-top">${index + 1}</td>
                                <td class="px-2 py-3 text-xs md:text-sm text-gray-700 font-mono align-top break-words leading-snug">${unit.kode_unit || '-'}</td>
                                <td class="px-2 py-3 text-xs md:text-sm text-gray-700 align-top whitespace-normal break-words leading-snug">${unit.judul_unit || '-'}</td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                } else {
                    // Update colspan jadi 3
                    tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada unit kompetensi terdaftar.</td></tr>`;
                }
            }
        });
    </script>

</body>
</html>