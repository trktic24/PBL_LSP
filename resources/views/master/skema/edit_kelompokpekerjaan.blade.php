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
                    
                    <a href="{{ route('skema.detail', $skema->id_skema) }}" 
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

                <form action="{{ route('skema.detail.update_kelompok', $kelompok->id_kelompok_pekerjaan) }}" method="POST" 
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
                units: @json($kelompok->unitKompetensi),
                
                addUnit() {
                    // Tambah baris baru (ID null menandakan ini data baru)
                    this.units.push({ id_unit_kompetensi: null, kode_unit: '', judul_unit: '' });
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