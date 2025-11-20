@use('App\Models\DataSertifikasiAsesi')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sertifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Meta CSRF Token wajib ada untuk request Ajax --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <x-sidebar :idAsesi="$asesi->id_asesi"></x-sidebar>

        {{-- Main Content --}}
        <main class="flex-1 p-12 bg-white overflow-y-auto" data-sertifikasi-id="{{ $id_sertifikasi_untuk_js }}">
            <div class="max-w-4xl mx-auto">

                {{-- Stepper --}}
                <div class="flex items-center justify-center mb-12">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">1</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">2</div>
                    </div>
                    <div class="w-24 h-0.5 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">3</div>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-4">Data Sertifikasi</h1>
                <p class="text-gray-600 mb-8">
                    Pilih Tujuan Asesmen serta Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi yang anda ajukan.
                </p>

                {{-- Section Info Skema --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Skema Sertifikasi / Klaster Asesmen</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1 text-sm font-medium text-gray-600">Judul</div>
                        <div class="col-span-2 text-sm text-gray-900" id="skema-judul">: ...Memuat data...</div>

                        <div class="col-span-1 text-sm font-medium text-gray-600">Nomor</div>
                        <div class="col-span-2 text-sm text-gray-900" id="skema-nomor">: ...Memuat data...</div>
                    </div>
                </div>

                {{-- Section Form Tujuan Asesmen --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Tujuan Asesmen</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- 1. Sertifikasi --}}
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="tujuan_asesmen" value="sertifikasi"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Sertifikasi</span>
                        </label>

                        {{-- 2. PKT --}}
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="tujuan_asesmen" value="PKT"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Pengakuan Kompetensi Terkini (PKT)</span>
                        </label>

                        {{-- 3. RPL --}}
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="tujuan_asesmen" value="rekognisi pembelajaran sebelumnya"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Rekognisi Pembelajaran Lampau (RPL)</span>
                        </label>

                        {{-- 4. Lainnya --}}
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer h-full hover:border-blue-500 transition-colors">
                            <input type="checkbox" name="tujuan_asesmen" value="lainnya"
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 tujuan-checkbox">
                            <span class="ml-3 text-sm font-medium text-gray-700">Lainnya</span>
                        </label>
                    </div>
                </div>

                {{-- Section Tabel Unit Kompetensi --}}
                <div class="mb-10">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Unit Kompetensi</h3>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Standar</th>
                                </tr>
                            </thead>
                            {{-- PENTING: Tambahkan ID di tbody agar JS bisa akses --}}
                            <tbody class="bg-white divide-y divide-gray-200" id="unit-kompetensi-body">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tombol Selanjutnya --}}
                <div class="flex justify-end items-center">
                    <button type="button" id="btn-selanjutnya" 
                        {{-- PENTING: data-next-url ini yang dipakai JS untuk redirect --}}
                        data-next-url="{{ route('bukti.pemohon', ['id_sertifikasi' => $id_sertifikasi_untuk_js]) }}"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Selanjutnya
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. INISIALISASI & VARIABEL
            const mainElement = document.querySelector('main[data-sertifikasi-id]');
            let sertifikasiId = null;

            const elJudulSkema = document.getElementById('skema-judul');
            const elNomorSkema = document.getElementById('skema-nomor');
            const tujuanCheckboxes = document.querySelectorAll('.tujuan-checkbox');
            const btnSelanjutnya = document.getElementById('btn-selanjutnya');
            
            // Pastikan ID ada
            if (mainElement && mainElement.dataset.sertifikasiId) {
                sertifikasiId = mainElement.dataset.sertifikasiId;
            } else {
                alert('Terjadi error: ID Sertifikasi tidak ditemukan.');
                return;
            }

            // 2. LOAD DATA DARI DATABASE (GET)
            fetch(`/api/data-sertifikasi/detail/${sertifikasiId}`)
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data) {
                        const data = response.data;
                        console.log("Data Loaded:", data); // Debugging

                        // A. Isi Data Skema (Judul & Nomor)
                        if (data.jadwal && data.jadwal.skema) {
                            elJudulSkema.textContent = ': ' + (data.jadwal.skema.nama_skema || '-');
                            elNomorSkema.textContent = ': ' + (data.jadwal.skema.nomor_skema || '-'); 

                            // B. Isi Tabel Unit Kompetensi (Panggil Fungsi Helper)
                            renderTabelUnit(data.jadwal.skema.unit_kompetensi);
                        } else {
                            elJudulSkema.textContent = ': Data tidak ditemukan';
                            elNomorSkema.textContent = ': -';
                        }

                        // C. Isi Pilihan Checkbox (Jika user pernah milih sebelumnya)
                        if (data.tujuan_asesmen) {
                            tujuanCheckboxes.forEach(cb => {
                                if (cb.value === data.tujuan_asesmen) {
                                    cb.checked = true;
                                }
                            });
                        }
                    }
                })
                .catch(err => {
                    console.error('Gagal load data:', err);
                    elJudulSkema.textContent = ': Gagal memuat';
                });


            // 3. LOGIKA INTERAKSI CHECKBOX (Pilih Satu Saja)
            tujuanCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', (e) => {
                    const clicked = e.target;
                    // Uncheck yang lain biar kayak radio button
                    tujuanCheckboxes.forEach(cb => {
                        if (cb !== clicked) cb.checked = false;
                    });
                });
            });


            // 4. LOGIKA TOMBOL "SELANJUTNYA" (SIMPAN & PINDAH)
            btnSelanjutnya.addEventListener('click', async function() {
                
                // A. Validasi: Pastikan ada yang dipilih
                const selected = Array.from(tujuanCheckboxes).find(cb => cb.checked);
                
                if (!selected) {
                    alert('Mohon pilih salah satu Tujuan Asesmen sebelum melanjutkan.');
                    return; // Stop
                }

                // B. Ubah Tampilan Tombol (Loading)
                const originalText = this.textContent;
                this.textContent = 'Menyimpan...';
                this.disabled = true;

                // C. Kirim Data ke API (Update Database)
                try {
                    // Ambil CSRF Token
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

                    const response = await fetch('/api/data-sertifikasi', {
                        method: 'POST', // Sesuai route API kamu
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            id_data_sertifikasi_asesi: sertifikasiId,
                            tujuan_asesmen: selected.value
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // D. Jika Sukses -> Pindah Halaman
                        console.log('Berhasil disimpan, pindah halaman...');
                        // Ambil URL tujuan dari atribut data-next-url
                        window.location.href = this.dataset.nextUrl; 
                    } else {
                        throw new Error(result.message || 'Gagal menyimpan data.');
                    }

                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan: ' + error.message);
                    
                    // Reset tombol biar bisa coba lagi
                    this.textContent = originalText;
                    this.disabled = false;
                }
            });

            // 5. HELPER: RENDER TABEL UNIT KOMPETENSI
            function renderTabelUnit(units) {
                const tbody = document.getElementById('unit-kompetensi-body');
                tbody.innerHTML = ''; // Kosongkan "Loading..."

                if (units && units.length > 0) {
                    units.forEach((unit, index) => {
                        const row = `
                            <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${unit.kode_unit || '-'}</td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-700">${unit.judul_unit || '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${unit.jenis_standar || '-'}</td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada unit kompetensi terdaftar untuk skema ini.
                            </td>
                        </tr>
                    `;
                }
            }

        });
    </script>

</body>
</html>