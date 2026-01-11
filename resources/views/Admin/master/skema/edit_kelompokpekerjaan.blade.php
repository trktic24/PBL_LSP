<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Kelompok & Unit | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
        ::-webkit-scrollbar { width: 0; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col">
        
        <x-navbar.navbar-admin />
        
        <main class="flex-1 flex justify-center items-start pt-10 pb-12">
            <div class="w-full max-w-5xl bg-white border border-gray-200 rounded-xl shadow-lg p-10">
                
                <div class="flex justify-between items-start mb-10">
                    
                    <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" 
                       class="flex items-center text-gray-700 hover:text-blue-600 text-lg font-medium transition mt-1">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                    
                    <div class="text-center flex-1 px-4">
                        <h1 class="text-2xl font-bold text-gray-900 uppercase leading-tight">
                            EDIT KELOMPOK PEKERJAAN - UNIT KOMPETENSI
                        </h1>
                        <p class="text-sm text-gray-500 mt-1 font-medium">
                            Skema: {{ $skema->nama_skema }}
                        </p>
                    </div>
                    
                    <div class="w-[80px]"></div> 
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg text-sm">
                        <div class="font-bold mb-1 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> Terdapat Kesalahan:</div>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.skema.detail.update_kelompok', $kelompok->id_kelompok_pekerjaan) }}" method="POST" 
                      class="space-y-8" 
                      x-data="editUnitHandler()">
                    @csrf
                    @method('PUT')

                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full text-sm font-bold mr-3">1</span>
                            Kelompok Pekerjaan
                        </h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kelompok Pekerjaan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kelompok_pekerjaan" value="{{ old('nama_kelompok_pekerjaan', $kelompok->nama_kelompok_pekerjaan) }}" 
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition shadow-sm" 
                                   required>
                            <p class="text-xs text-gray-500 mt-2">Nama kelompok ini akan menjadi judul grup untuk unit-unit kompetensi di bawahnya.</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full text-sm font-bold mr-3">2</span>
                                Daftar Unit Kompetensi
                            </h3>
                            
                            <button type="button" @click="addUnit()" 
                                    class="px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-medium hover:bg-green-100 transition flex items-center shadow-sm">
                                <i class="fas fa-plus mr-2"></i> Tambah Baris
                            </button>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 space-y-4">
                            <div class="hidden md:flex gap-4 px-1 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <div class="w-1/3">Kode Unit <span class="text-red-500">*</span></div>
                                <div class="flex-1">Judul Unit Kompetensi <span class="text-red-500">*</span></div>
                                <div class="w-10 text-center">Aksi</div>
                            </div>

                            <template x-for="(unit, index) in units" :key="index">
                                <div class="flex flex-col md:flex-row gap-4 items-start animate-fade-in-down">
                                    
                                    <input type="hidden" :name="'units[' + index + '][id]'" x-model="unit.id_unit_kompetensi">

                                    <div class="w-full md:w-1/3">
                                        <label class="md:hidden block text-xs font-bold text-gray-500 mb-1">Kode Unit</label>
                                        <input type="text" 
                                               :name="'units[' + index + '][kode_unit]'" 
                                               x-model="unit.kode_unit" 
                                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono text-sm shadow-sm" 
                                               placeholder="Cth: J.620100.001.01" required>
                                    </div>

                                    <div class="flex-1 w-full">
                                        <label class="md:hidden block text-xs font-bold text-gray-500 mb-1">Judul Unit</label>
                                        <input type="text" 
                                               :name="'units[' + index + '][judul_unit]'" 
                                               x-model="unit.judul_unit" 
                                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm shadow-sm" 
                                               placeholder="Masukkan Judul Unit Kompetensi" required>
                                    </div>

                                    <div class="w-full md:w-auto flex justify-end">
                                        <button type="button" @click="removeUnit(index)" 
                                                class="w-[46px] h-[46px] flex items-center justify-center rounded-lg transition border shadow-sm"
                                                :class="units.length > 1 ? 'bg-white border-gray-300 text-red-500 hover:bg-red-50 hover:border-red-300' : 'bg-gray-100 border-gray-200 text-gray-300 cursor-not-allowed'"
                                                :disabled="units.length <= 1"
                                                title="Hapus Baris">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <div class="mt-3 flex justify-between items-center">
                            <p class="text-xs text-gray-500 italic">
                                <i class="fas fa-info-circle mr-1"></i> Pastikan kode unit unik dan sesuai standar SKKNI.
                            </p>
                            <div class="text-right text-sm text-gray-600 font-medium">
                                Total Unit: <span x-text="units.length" class="text-blue-600 font-bold"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Premium Edit Elemen & KUK (Format APL-02) -->
                    <div class="border-t-2 border-dashed border-gray-100 pt-10 mt-12">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
                            <div class="group">
                                <h3 class="text-2xl font-black text-slate-800 flex items-center tracking-tight">
                                    <span class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-2xl text-lg font-bold mr-4 shadow-lg shadow-purple-200 group-hover:rotate-6 transition-transform duration-300">3</span>
                                    Edit Elemen & KUK <span class="ml-2 text-indigo-500 opacity-60">(APL-02)</span>
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 ml-14 font-medium">Kustomisasi pertanyaan asesmen mandiri dan persyaratan bukti.</p>
                            </div>
                            
                            <div class="relative group max-w-sm">
                                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                                <div class="relative flex items-start p-4 bg-white/80 backdrop-blur-xl border border-blue-50/50 rounded-2xl shadow-sm">
                                    <div class="p-2 bg-blue-100 rounded-xl mr-3">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>
                                    <div class="text-[10px] leading-relaxed">
                                        <p class="font-bold text-gray-800 mb-1 uppercase tracking-widest text-[9px]">Panduan Cepat</p>
                                        <p class="text-gray-600 font-medium">Tentukan pernyataan kompetensi serta <span class="text-blue-600 font-bold">Instruksi Bukti</span> yang harus diunggah peserta saat mendaftar.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-12">
                            <template x-for="(unit, index) in units" :key="index">
                                <div class="bg-white/40 backdrop-blur-sm border border-slate-200/60 rounded-[2rem] shadow-xl shadow-slate-200/40 overflow-hidden group transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-100 hover:border-indigo-100">
                                    <!-- Header / Toggle Accordion -->
                                    <div @click="unit.expanded = !unit.expanded" 
                                         class="bg-gradient-to-r from-slate-50 via-white to-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center cursor-pointer select-none hover:bg-slate-100/50 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                                                <i class="fas" :class="unit.expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-0.5">Unit Kompetensi <span x-text="index + 1"></span></span>
                                                <div class="flex items-center space-x-3">
                                                    <span class="text-sm font-bold text-slate-700" x-text="unit.judul_unit || 'Unit Tanpa Judul'"></span>
                                                    <span class="px-2 py-0.5 bg-slate-200/50 text-slate-500 text-[8px] font-black rounded uppercase tracking-wider border border-slate-300/30" x-text="unit.kode_unit || 'N/A'"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-3">
                                            <div x-show="!unit.expanded" class="flex -space-x-2">
                                                <div class="w-6 h-6 rounded-full bg-green-100 border-2 border-white flex items-center justify-center text-green-600 text-[8px] font-black shadow-sm">K</div>
                                                <div class="w-6 h-6 rounded-full bg-rose-100 border-2 border-white flex items-center justify-center text-rose-600 text-[8px] font-black shadow-sm">BK</div>
                                            </div>
                                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest hidden md:block" x-text="unit.expanded ? 'Klik untuk Ciutkan' : 'Klik untuk Perluas'"></span>
                                        </div>
                                    </div>
                                    
                                    <div x-show="unit.expanded" 
                                         x-collapse
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="px-4 py-6 md:p-8 overflow-x-auto">
                                        <table class="w-full text-sm border-separate border-spacing-y-3">
                                            <thead>
                                                <tr class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.15em]">
                                                    <th class="px-6 py-2 text-center w-16">No</th>
                                                    <th class="px-6 py-2 text-left min-w-[300px]">Pernyataan Elemen / KUK</th>
                                                    <th class="px-4 py-2 text-center w-20">K</th>
                                                    <th class="px-4 py-2 text-center w-20">BK</th>
                                                    <th class="px-6 py-2 text-left min-w-[200px]">Instruksi Bukti</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Elemen Header Row -->
                                                <tr>
                                                    <td class="px-6 py-4 text-center bg-indigo-50/50 rounded-l-2xl border-l border-y border-indigo-100 font-black text-indigo-600">1</td>
                                                    <td class="px-6 py-4 bg-indigo-50/50 border-y border-indigo-100">
                                                        <div class="relative group/input">
                                                            <div class="absolute left-0 -top-2 px-2 py-0.5 bg-indigo-500 text-white text-[8px] font-black rounded uppercase tracking-widest z-10 scale-0 group-focus-within/input:scale-100 transition-transform origin-left">Nama Elemen Utama</div>
                                                            <input type="text" :name="'units[' + index + '][elemen_1_name]'" x-model="unit.elemen_1_name" 
                                                                   class="w-full p-4 bg-white/80 border border-indigo-200/50 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-bold text-slate-800 transition-all placeholder:text-slate-300 shadow-sm"
                                                                   placeholder="Masukkan pernyataan elemen pertama...">
                                                        </div>
                                                    </td>
                                                    <td colspan="3" class="px-6 py-4 bg-indigo-50/50 rounded-r-2xl border-r border-y border-indigo-100">
                                                        <div class="h-1 w-full bg-indigo-200/30 rounded-full"></div>
                                                    </td>
                                                </tr>

                                                <!-- KUK Row 1.1 -->
                                                <tr class="group/row">
                                                    <td class="px-6 py-8 text-center font-bold text-slate-400 group-hover/row:text-indigo-500 transition-colors">1.1</td>
                                                    <td class="px-6 py-4">
                                                        <input type="hidden" :name="'units[' + index + '][kriteria_1][id]'" x-model="unit.kriteria_1.id">
                                                        <textarea :name="'units[' + index + '][kriteria_1][text]'" x-model="unit.kriteria_1.text" 
                                                                  class="w-full p-4 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 focus:outline-none resize-none text-slate-600 leading-relaxed font-medium transition-all group-hover/row:bg-white"
                                                                  rows="2" placeholder="Tuliskan pernyataan Kriteria Unjuk Kerja 1.1..."></textarea>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                            <input type="radio" :name="'units[' + index + '][kriteria_1][is_kompeten]'" value="K" x-model="unit.kriteria_1.is_kompeten" class="sr-only peer">
                                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center peer-checked:bg-green-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-green-100 text-slate-300 transition-all hover:bg-slate-200">
                                                                <i class="fas fa-check font-black text-xs"></i>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                            <input type="radio" :name="'units[' + index + '][kriteria_1][is_kompeten]'" value="demonstrasi" x-model="unit.kriteria_1.is_kompeten" class="sr-only peer">
                                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-rose-100 text-slate-300 transition-all hover:bg-slate-200">
                                                                <i class="fas fa-times font-black text-xs"></i>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="space-y-3">
                                                            <div x-show="unit.kriteria_1.bukti && unit.kriteria_1.bukti.trim() !== '' && (unit.kriteria_1.bukti.includes('http') || unit.kriteria_1.bukti.includes('/storage'))" 
                                                                 class="group/link flex items-center justify-between px-4 py-2.5 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl shadow-sm hover:border-emerald-500 transition-all duration-300">
                                                                <div class="flex items-center">
                                                                    <div class="p-2 bg-emerald-500 text-white rounded-xl mr-3 shadow-md shadow-emerald-100">
                                                                        <i class="fas fa-link text-[10px]"></i>
                                                                    </div>
                                                                    <div class="flex flex-col overflow-hidden max-w-[150px]">
                                                                        <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">Link Terdeteksi</span>
                                                                        <span class="text-[9px] font-bold text-slate-600 truncate" x-text="unit.kriteria_1.bukti"></span>
                                                                    </div>
                                                                </div>
                                                                <a :href="unit.kriteria_1.bukti" target="_blank" class="p-2 bg-white text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                                </a>
                                                            </div>
                                                            <div class="relative">
                                                                <textarea :name="'units[' + index + '][kriteria_1][bukti]'" x-model="unit.kriteria_1.bukti" 
                                                                          class="w-full p-4 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:outline-none resize-none text-[10px] text-slate-500 font-bold transition-all placeholder:font-medium"
                                                                          rows="2" placeholder="Instruksi Bukti (SOP, Laporan, dll) atau Tempel Link"></textarea>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- KUK Row 1.2 -->
                                                <tr class="group/row">
                                                    <td class="px-6 py-8 text-center font-bold text-slate-400 group-hover/row:text-indigo-500 transition-colors">1.2</td>
                                                    <td class="px-6 py-4">
                                                        <input type="hidden" :name="'units[' + index + '][kriteria_2][id]'" x-model="unit.kriteria_2.id">
                                                        <textarea :name="'units[' + index + '][kriteria_2][text]'" x-model="unit.kriteria_2.text" 
                                                                  class="w-full p-4 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 focus:outline-none resize-none text-slate-600 leading-relaxed font-medium transition-all group-hover/row:bg-white"
                                                                  rows="2" placeholder="Tuliskan pernyataan Kriteria Unjuk Kerja 1.2..."></textarea>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                            <input type="radio" :name="'units[' + index + '][kriteria_2][is_kompeten]'" value="K" x-model="unit.kriteria_2.is_kompeten" class="sr-only peer">
                                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center peer-checked:bg-green-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-green-100 text-slate-300 transition-all hover:bg-slate-200">
                                                                <i class="fas fa-check font-black text-xs"></i>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                            <input type="radio" :name="'units[' + index + '][kriteria_2][is_kompeten]'" value="demonstrasi" x-model="unit.kriteria_2.is_kompeten" class="sr-only peer">
                                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-rose-100 text-slate-300 transition-all hover:bg-slate-200">
                                                                <i class="fas fa-times font-black text-xs"></i>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="space-y-3">
                                                            <div x-show="unit.kriteria_2.bukti && unit.kriteria_2.bukti.trim() !== '' && (unit.kriteria_2.bukti.includes('http') || unit.kriteria_2.bukti.includes('/storage'))" 
                                                                 class="group/link flex items-center justify-between px-4 py-2.5 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl shadow-sm hover:border-emerald-500 transition-all duration-300">
                                                                <div class="flex items-center">
                                                                    <div class="p-2 bg-emerald-500 text-white rounded-xl mr-3 shadow-md shadow-emerald-100">
                                                                        <i class="fas fa-link text-[10px]"></i>
                                                                    </div>
                                                                    <div class="flex flex-col overflow-hidden max-w-[150px]">
                                                                        <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">Link Terdeteksi</span>
                                                                        <span class="text-[9px] font-bold text-slate-600 truncate" x-text="unit.kriteria_2.bukti"></span>
                                                                    </div>
                                                                </div>
                                                                <a :href="unit.kriteria_2.bukti" target="_blank" class="p-2 bg-white text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                                </a>
                                                            </div>
                                                            <div class="relative">
                                                                <textarea :name="'units[' + index + '][kriteria_2][bukti]'" x-model="unit.kriteria_2.bukti" 
                                                                          class="w-full p-4 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:outline-none resize-none text-[10px] text-slate-500 font-bold transition-all placeholder:font-medium"
                                                                          rows="2" placeholder="Instruksi Bukti (SOP, Laporan, dll) atau Tempel Link"></textarea>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- KUK Row 1.3 -->
                                                <tr class="group/row">
                                                    <td class="px-6 py-8 text-center font-bold text-slate-400 group-hover/row:text-indigo-500 transition-colors">1.3</td>
                                                    <td class="px-6 py-4">
                                                        <input type="hidden" :name="'units[' + index + '][kriteria_3][id]'" x-model="unit.kriteria_3.id">
                                                        <textarea :name="'units[' + index + '][kriteria_3][text]'" x-model="unit.kriteria_3.text" 
                                                                  class="w-full p-4 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 focus:outline-none resize-none text-slate-600 leading-relaxed font-medium transition-all group-hover/row:bg-white"
                                                                  rows="2" placeholder="Tuliskan pernyataan Kriteria Unjuk Kerja 1.3..."></textarea>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                            <input type="radio" :name="'units[' + index + '][kriteria_3][is_kompeten]'" value="K" x-model="unit.kriteria_3.is_kompeten" class="sr-only peer">
                                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center peer-checked:bg-green-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-green-100 text-slate-300 transition-all hover:bg-slate-200">
                                                                <i class="fas fa-check font-black text-xs"></i>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                            <input type="radio" :name="'units[' + index + '][kriteria_3][is_kompeten]'" value="demonstrasi" x-model="unit.kriteria_3.is_kompeten" class="sr-only peer">
                                                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-rose-100 text-slate-300 transition-all hover:bg-slate-200">
                                                                <i class="fas fa-times font-black text-xs"></i>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="space-y-3">
                                                            <div x-show="unit.kriteria_3.bukti && unit.kriteria_3.bukti.trim() !== '' && (unit.kriteria_3.bukti.includes('http') || unit.kriteria_3.bukti.includes('/storage'))" 
                                                                 class="group/link flex items-center justify-between px-4 py-2.5 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl shadow-sm hover:border-emerald-500 transition-all duration-300">
                                                                <div class="flex items-center">
                                                                    <div class="p-2 bg-emerald-500 text-white rounded-xl mr-3 shadow-md shadow-emerald-100">
                                                                        <i class="fas fa-link text-[10px]"></i>
                                                                    </div>
                                                                    <div class="flex flex-col overflow-hidden max-w-[150px]">
                                                                        <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">Link Terdeteksi</span>
                                                                        <span class="text-[9px] font-bold text-slate-600 truncate" x-text="unit.kriteria_3.bukti"></span>
                                                                    </div>
                                                                </div>
                                                                <a :href="unit.kriteria_3.bukti" target="_blank" class="p-2 bg-white text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                                </a>
                                                            </div>
                                                            <div class="relative">
                                                                <textarea :name="'units[' + index + '][kriteria_3][bukti]'" x-model="unit.kriteria_3.bukti" 
                                                                          class="w-full p-4 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:outline-none resize-none text-[10px] text-slate-500 font-bold transition-all placeholder:font-medium"
                                                                          rows="2" placeholder="Instruksi Bukti (SOP, Laporan, dll) atau Tempel Link"></textarea>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sinkronisasi Real-time Aktif</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pt-8 border-t mt-8">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition flex justify-center items-center">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out forwards;
        }
    </style>

    <script>
        function editUnitHandler() {
            return {
                // Inject data existing dari database ke Alpine.js
                units: @json($mappedUnits),
                
                addUnit() {
                    // Tambah baris baru (ID null menandakan ini data baru)
                    this.units.push({ 
                        id_unit_kompetensi: null, 
                        kode_unit: '', 
                        judul_unit: '', 
                        expanded: true,
                        elemen_1_name: '',
                        kriteria_1: { id: null, text: '', bukti: '', is_kompeten: 'demonstrasi' },
                        kriteria_2: { id: null, text: '', bukti: '', is_kompeten: 'demonstrasi' },
                        kriteria_3: { id: null, text: '', bukti: '', is_kompeten: 'demonstrasi' }
                    });
                },
                
                removeUnit(index) {
                    if(this.units.length > 1) {
                        this.units.splice(index, 1);
                    }
                }
            }
        }
    </script>
</body>
</html>