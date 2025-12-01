<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.IA.04B - Penilaian Proyek Singkat</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        /* Custom Borders agar tabel terlihat tegas seperti dokumen resmi */
        .form-table, .form-table td, .form-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false,
                toggle() { this.open = ! this.open }
            })
        })
    </script>
</head>
<body class="bg-white text-gray-800 antialiased">

    <div class="flex h-screen overflow-hidden bg-white" x-data>

        <x-sidebar.sidebar />

        <main class="flex-1 h-full overflow-y-auto flex flex-col">
            
            <div class="lg:hidden p-4 bg-blue-600 text-white flex justify-between items-center shadow-md sticky top-0 z-30 flex-shrink-0">
                <span class="font-bold">Form Assessment</span>
                <button @click="$store.sidebar.open = true" class="p-2 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <div class="p-8 lg:p-12">
                    
                <div class="mb-10 text-center">
                    <div class="mb-4 text-gray-400 text-sm font-bold italic text-left">Logo BNSP</div> 
                    
                    <h1 class="text-2xl font-bold text-black uppercase tracking-wide border-b-2 border-gray-100 pb-4 mb-2 inline-block">
                        FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA
                    </h1>
                </div>

                <div class="grid grid-cols-[250px_auto] gap-y-3 text-sm mb-10 text-gray-700">
                    
                    <div class="font-bold text-black">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</div>
                    <div>
                        <div class="flex gap-2"><span class="font-semibold w-20">Judul</span> : Junior Web Programmer</div>
                        <div class="flex gap-2"><span class="font-semibold w-20">Nomor</span> : -</div>
                    </div>

                    <div class="font-bold text-black">TUK</div>
                    <div>: Sewaktu / Tempat Kerja / <span class="font-bold">Mandiri</span>*</div>

                    <div class="font-bold text-black">Judul Kegiatan Terstruktur</div>
                    <div>: <input type="text" class="border-b border-gray-300 focus:outline-none w-1/2" placeholder="Isi judul kegiatan..."></div>

                    <div class="font-bold text-black">Nama Asesor</div>
                    <div>: -</div>

                    <div class="font-bold text-black">Nama Asesi</div>
                    <div>: Tatang Sidartang</div>

                    <div class="font-bold text-black">Tanggal</div>
                    <div>: 18 November 2025</div>
                </div>

                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-8 text-sm text-gray-700">
                    <h3 class="font-bold text-orange-900 mb-2">PANDUAN BAGI ASESOR:</h3>
                    <ul class="list-disc pl-5 space-y-1 text-xs leading-relaxed">
                        <li>Lakukan penilaian pencapaian hasil proyek singkat atau kegiatan terstruktur lainnya melalui presentasi.</li>
                        <li>Penilaian dilakukan sesuai dengan <b>FR.IA.04A. DIT</b>.</li>
                        <li>Pertanyaan disampaikan oleh asesor setelah asesi melakukan presentasi.</li>
                        <li>Pertanyaan dapat dikembangkan oleh asesor berdasarkan dokumen presentasi dan atau hasil presentasi.</li>
                        <li>Pertanyaan yang disampaikan untuk pemenuhan pencapaian 5 dimensi kompetensi.</li>
                        <li>Isilah kolom lingkup penyajian proyek atau kegiatan terstruktur lainnya sesuai sektor/ sub-sektor/ profesi.</li>
                        <li>Berikan keputusan pencapaian berdasarkan kesimpulan jawaban asesi.</li>
                    </ul>
                </div>

                <div class="mb-8 overflow-x-auto">
                    <table class="w-full text-sm form-table min-w-[900px]">
                        <thead class="bg-gray-100 text-center font-bold">
                            <tr>
                                <th rowspan="2" class="p-2 w-10">No</th>
                                <th colspan="3" class="p-2">Aspek Penilaian</th>
                                <th colspan="2" class="p-2 w-24">Pencapaian</th>
                            </tr>
                            <tr>
                                <th class="p-2 w-1/4">Lingkup Penyajian Proyek / Kegiatan Terstruktur</th>
                                <th class="p-2 w-1/3">Daftar Pertanyaan</th>
                                <th class="p-2 w-1/4">Kesesuaian dengan Standar Kompetensi (Unit/Elemen/KUK)</th>
                                <th class="p-2 w-10">Ya</th>
                                <th class="p-2 w-10">Tdk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-2 text-center align-top font-bold">1.</td>
                                <td class="p-2 align-top">
                                    <textarea class="w-full h-32 border border-gray-300 p-2 rounded resize-none focus:border-blue-500 outline-none" placeholder="Tulis lingkup penyajian..."></textarea>
                                </td>
                                <td class="p-2 align-top">
                                    <div class="mb-2">
                                        <span class="font-bold block text-xs mb-1">Pertanyaan:</span>
                                        <textarea class="w-full h-20 border border-gray-300 p-2 rounded resize-none outline-none"></textarea>
                                    </div>
                                    <div>
                                        <span class="font-bold block text-xs mb-1">Tanggapan:</span>
                                        <textarea class="w-full h-20 border border-gray-300 p-2 rounded resize-none bg-gray-50 outline-none"></textarea>
                                    </div>
                                </td>
                                <td class="p-2 align-top">
                                    <textarea class="w-full h-full min-h-[150px] border border-gray-300 p-2 rounded resize-none outline-none" placeholder="KUK / Elemen..."></textarea>
                                </td>
                                <td class="p-2 text-center align-top pt-4"><input type="checkbox" class="w-5 h-5"></td>
                                <td class="p-2 text-center align-top pt-4"><input type="checkbox" class="w-5 h-5"></td>
                            </tr>

                            <tr>
                                <td class="p-2 text-center align-top font-bold">2.</td>
                                <td class="p-2 align-top">
                                    <textarea class="w-full h-32 border border-gray-300 p-2 rounded resize-none focus:border-blue-500 outline-none" placeholder="Tulis lingkup penyajian..."></textarea>
                                </td>
                                <td class="p-2 align-top">
                                    <div class="mb-2">
                                        <span class="font-bold block text-xs mb-1">Pertanyaan:</span>
                                        <textarea class="w-full h-20 border border-gray-300 p-2 rounded resize-none outline-none"></textarea>
                                    </div>
                                    <div>
                                        <span class="font-bold block text-xs mb-1">Tanggapan:</span>
                                        <textarea class="w-full h-20 border border-gray-300 p-2 rounded resize-none bg-gray-50 outline-none"></textarea>
                                    </div>
                                </td>
                                <td class="p-2 align-top">
                                    <textarea class="w-full h-full min-h-[150px] border border-gray-300 p-2 rounded resize-none outline-none"></textarea>
                                </td>
                                <td class="p-2 text-center align-top pt-4"><input type="checkbox" class="w-5 h-5"></td>
                                <td class="p-2 text-center align-top pt-4"><input type="checkbox" class="w-5 h-5"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border border-black mb-8 p-0">
                    <div class="flex border-b border-black bg-gray-50">
                        <div class="w-1/4 p-3 font-bold border-r border-black">Rekomendasi Asesor:</div>
                        <div class="w-3/4 p-3">
                            <p class="mb-2 text-sm">Asesi telah memenuhi/belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan:</p>
                            <div class="flex gap-8 font-bold">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-5 h-5 text-blue-600"> Kompeten
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" class="w-5 h-5 text-red-600"> Belum Kompeten
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-black">
                        <div class="p-4">
                            <div class="font-bold mb-4 underline">Asesi:</div>
                            <div class="flex mb-2 items-center">
                                <span class="w-32 text-sm font-semibold">Nama</span>
                                <span>: Tatang Sidartang</span>
                            </div>
                            <div class="flex items-start">
                                <span class="w-32 text-sm font-semibold">Tanda Tangan/ <br>Tanggal</span>
                                <div class="flex-1">
                                    <span class="mr-2">:</span>
                                    <div class="h-20 border-b border-gray-400 border-dashed inline-block w-3/4"></div> </div>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <div class="font-bold mb-4 underline">Asesor:</div>
                            <div class="flex mb-2 items-center">
                                <span class="w-32 text-sm font-semibold">Nama</span>
                                <span>: <input type="text" class="border-b border-gray-400 outline-none" placeholder="..."></span>
                            </div>
                            <div class="flex mb-2 items-center">
                                <span class="w-32 text-sm font-semibold">No. Reg</span>
                                <span>: <input type="text" class="border-b border-gray-400 outline-none" placeholder="..."></span>
                            </div>
                            <div class="flex items-start">
                                <span class="w-32 text-sm font-semibold">Tanda Tangan/ <br>Tanggal</span>
                                <div class="flex-1">
                                    <span class="mr-2">:</span>
                                    <div class="h-20 border-b border-gray-400 border-dashed inline-block w-3/4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <x-kolom_ttd.penyusunvalidator :sertifikasi="$sertifikasi ?? null" />
                </div>

                <div class="mt-12 flex justify-end gap-4">
                    <button class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg shadow hover:bg-gray-300 transition">Simpan Draft</button>
                    <button class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition">Simpan Permanen</button>
                </div>

            </div>
        </main>

        <div 
            x-show="$store.sidebar.open" 
            @click="$store.sidebar.open = false"
            x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        ></div>

    </div>

</body>
</html>