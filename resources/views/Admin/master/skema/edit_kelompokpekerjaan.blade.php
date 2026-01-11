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

                    <div class="border-t-2 border-dashed border-gray-100 pt-10 mt-12">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
                            <div class="group">
                                <h3 class="text-2xl font-black text-slate-800 flex items-center tracking-tight">
                                    <span class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-2xl text-lg font-bold mr-4 shadow-lg shadow-purple-200 group-hover:rotate-6 transition-transform duration-300">3</span>
                                    Edit Elemen & KUK <span class="ml-2 text-indigo-500 opacity-60">(APL-02)</span>
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 ml-14 font-medium">Kustomisasi pertanyaan asesmen mandiri dan persyaratan bukti per Elemen.</p>
                            </div>
                        </div>

                        <div class="space-y-12">
                            <template x-for="(unit, index) in units" :key="index">
                                <div class="bg-white/40 backdrop-blur-sm border border-slate-200/60 rounded-[2rem] shadow-xl shadow-slate-200/40 overflow-hidden group transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-100 hover:border-indigo-100 mb-8">
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
                                    </div>
                                    
                                    <div x-show="unit.expanded" 
                                         x-collapse
                                         class="px-4 py-6 md:p-8 overflow-x-auto">
                                        
                                        <!-- Elements Loop -->
                                        <template x-for="(element, elemIndex) in unit.elements" :key="elemIndex">
                                            <div class="mb-8 border border-indigo-100 rounded-2xl bg-white shadow-sm overflow-hidden">
                                                <div class="p-4 bg-indigo-50/30 border-b border-indigo-100 flex items-center justify-between">
                                                    <div class="flex-1 mr-4">
                                                        <input type="hidden" :name="'units[' + index + '][elements][' + elemIndex + '][id]'" x-model="element.id">
                                                        <div class="relative group/input">
                                                            <div class="absolute left-0 -top-2 px-2 py-0.5 bg-indigo-500 text-white text-[8px] font-black rounded uppercase tracking-widest z-10 scale-0 group-focus-within/input:scale-100 transition-transform origin-left">Elemen Kompetensi</div>
                                                            <input type="text" :name="'units[' + index + '][elements][' + elemIndex + '][name]'" x-model="element.name" 
                                                                   class="w-full p-3 bg-white border border-indigo-200/50 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none font-bold text-slate-800 transition-all placeholder:text-slate-300 shadow-sm"
                                                                   placeholder="Masukkan nama elemen...">
                                                        </div>
                                                    </div>
                                                    <button type="button" @click="removeElement(index, elemIndex)" class="text-xs text-rose-500 hover:text-rose-700 font-bold uppercase tracking-wider" title="Hapus Elemen">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>

                                                <table class="w-full text-sm border-separate border-spacing-y-2 px-4 pb-4">
                                                    <thead>
                                                        <tr class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.15em]">
                                                            <th class="px-2 py-2 text-center w-12">No</th>
                                                            <th class="px-2 py-2 text-left">Kriteria Unjuk Kerja</th>
                                                            <th class="px-2 py-2 text-center w-16">K</th>
                                                            <th class="px-2 py-2 text-center w-16">BK</th>
                                                            <th class="px-2 py-2 text-left min-w-[200px]">Bukti</th>
                                                            <th class="px-2 py-2 text-center w-10"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Kriteria Loop -->
                                                        <template x-for="(kuk, kukIndex) in element.kriteria" :key="kukIndex">
                                                            <tr class="group/row">
                                                                <td class="px-2 py-2 text-center font-bold text-slate-400">
                                                                    <span x-text="(elemIndex+1) + '.' + (kukIndex+1)"></span>
                                                                    <input type="hidden" :name="'units[' + index + '][elements][' + elemIndex + '][kriteria][' + kukIndex + '][no_kriteria]'" :value="(elemIndex+1) + '.' + (kukIndex+1)">
                                                                </td>
                                                                <td class="px-2 py-2">
                                                                    <input type="hidden" :name="'units[' + index + '][elements][' + elemIndex + '][kriteria][' + kukIndex + '][id]'" x-model="kuk.id">
                                                                    <textarea :name="'units[' + index + '][elements][' + elemIndex + '][kriteria][' + kukIndex + '][text]'" x-model="kuk.text" 
                                                                              class="w-full p-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 focus:outline-none resize-none text-slate-600 text-xs leading-relaxed font-medium transition-all group-hover/row:bg-white"
                                                                              rows="2" placeholder="Kriteria Unjuk Kerja..."></textarea>
                                                                </td>
                                                                <td class="px-2 py-2 text-center align-top pt-4">
                                                                    <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                                        <input type="radio" :name="'units[' + index + '][elements][' + elemIndex + '][kriteria][' + kukIndex + '][is_kompeten]'" value="K" x-model="kuk.is_kompeten" class="sr-only peer">
                                                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center peer-checked:bg-green-500 peer-checked:text-white text-slate-300 hover:bg-slate-200">
                                                                            <i class="fas fa-check text-[10px]"></i>
                                                                        </div>
                                                                    </label>
                                                                </td>
                                                                <td class="px-2 py-2 text-center align-top pt-4">
                                                                    <label class="relative inline-flex items-center cursor-pointer group/radio">
                                                                        <input type="radio" :name="'units[' + index + '][elements][' + elemIndex + '][kriteria][' + kukIndex + '][is_kompeten]'" value="demonstrasi" x-model="kuk.is_kompeten" class="sr-only peer">
                                                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center peer-checked:bg-rose-500 peer-checked:text-white text-slate-300 hover:bg-slate-200">
                                                                            <i class="fas fa-times text-[10px]"></i>
                                                                        </div>
                                                                    </label>
                                                                </td>
                                                                <td class="px-2 py-2">
                                                                    <textarea :name="'units[' + index + '][elements][' + elemIndex + '][kriteria][' + kukIndex + '][bukti]'" x-model="kuk.bukti" 
                                                                              class="w-full p-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:outline-none resize-none text-[10px] text-slate-500 font-bold transition-all placeholder:font-medium"
                                                                              rows="2" placeholder="Instruksi Bukti..."></textarea>
                                                                </td>
                                                                <td class="px-2 py-2 text-center align-top pt-4">
                                                                    <button type="button" @click="removeKriteria(index, elemIndex, kukIndex)" class="text-slate-300 hover:text-rose-500 transition" title="Hapus Kriteria">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <tr>
                                                            <td colspan="6" class="px-2 py-2 text-center">
                                                                <button type="button" @click="addKriteria(index, elemIndex)" class="w-full py-2 border-2 border-dashed border-slate-200 rounded-lg text-slate-400 text-xs font-bold hover:border-indigo-300 hover:text-indigo-500 hover:bg-indigo-50 transition">
                                                                    <i class="fas fa-plus mr-1"></i> Tambah Kriteria
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </template>

                                        <button type="button" @click="addElement(index)" class="w-full py-3 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-100 transition shadow-sm">
                                            <i class="fas fa-plus-circle mr-2"></i> Tambah Elemen Baru
                                        </button>

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
                // Inject data existing embedded from Controller
                units: @json($mappedUnits),
                
                addUnit() {
                    // Tambah Unit Baru (default 1 elemen, 1 kriteria)
                    this.units.push({ 
                        id_unit_kompetensi: null, 
                        kode_unit: '', 
                        judul_unit: '', 
                        expanded: true,
                        elements: [
                            {
                                id: null,
                                name: '',
                                kriteria: [
                                    { id: null, text: '', bukti: '', is_kompeten: 'demonstrasi', no_kriteria: '1.1' }
                                ]
                            }
                        ]
                    });
                },
                
                removeUnit(index) {
                    if(this.units.length > 1) {
                        this.units.splice(index, 1);
                    }
                },

                addElement(unitIndex) {
                    this.units[unitIndex].elements.push({
                        id: null,
                        name: '',
                        kriteria: [
                            { id: null, text: '', bukti: '', is_kompeten: 'demonstrasi', no_kriteria: (this.units[unitIndex].elements.length + 1) + '.1' }
                        ]
                    });
                },

                removeElement(unitIndex, elementIndex) {
                    // Prevent removing if only 1 element exists? Optional logic.
                    if (this.units[unitIndex].elements.length > 1) {
                        this.units[unitIndex].elements.splice(elementIndex, 1);
                    } else {
                        alert("Minimal satu elemen harus ada.");
                    }
                },

                addKriteria(unitIndex, elementIndex) {
                    let elemCount = elementIndex + 1;
                    let kriteriaCount = this.units[unitIndex].elements[elementIndex].kriteria.length + 1;
                    this.units[unitIndex].elements[elementIndex].kriteria.push({
                        id: null,
                        text: '',
                        bukti: '',
                        is_kompeten: 'demonstrasi',
                        no_kriteria: elemCount + '.' + kriteriaCount
                    });
                },

                removeKriteria(unitIndex, elementIndex, kriteriaIndex) {
                    if (this.units[unitIndex].elements[elementIndex].kriteria.length > 1) {
                        this.units[unitIndex].elements[elementIndex].kriteria.splice(kriteriaIndex, 1);
                    } else {
                        alert("Minimal satu kriteria harus ada.");
                    }
                }
            }
        }
    </script>
</body>
</html>