<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.07 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
        [x-cloak] { display: none !important; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-[#fdf2f8] text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-24 px-6">
            <div class="max-w-5xl mx-auto" x-data="templateHandler()">
                
                <!-- Header -->
                <div class="mb-12">
                    <a href="{{ route('admin.skema.template.list', [$skema->id_skema, 'FR.IA.07']) }}" class="inline-flex items-center text-rose-600 hover:text-rose-800 font-bold mb-6 group tracking-[0.2em] text-[10px]">
                        <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        KEMBALI KE DAFTAR TEMPLATE
                    </a>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <h1 class="text-4xl font-black text-rose-950 tracking-tight leading-none">
                                LIST PERTANYAAN <br>
                                <span class="text-rose-600 uppercase">FR.IA.07</span>
                            </h1>
                            <p class="text-slate-500 mt-4 font-medium italic">"Pertanyaan Lisan Untuk Asesi"</p>
                        </div>
                        <div class="flex gap-4">
                            <button @click="save()" class="px-8 py-4 bg-rose-600 text-white rounded-3xl font-black shadow-xl shadow-rose-200 hover:bg-rose-700 transition-all flex items-center transform hover:scale-105">
                                <i class="fas fa-save mr-3"></i> SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-rose-500 text-white rounded-[2rem] flex items-center shadow-xl shadow-rose-100 border-4 border-rose-400">
                        <i class="fas fa-check-double mr-4 text-2xl"></i>
                        <span class="font-extrabold text-lg">{{ session('success') }}</span>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.ia07.store', [$skema->id_skema, $id_jadwal]) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-8">
                        @foreach($skema->unitKompetensi as $unit)
                        <div class="glass-card rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-300 border-2 border-rose-50/50 overflow-hidden" 
                             x-data="{ expanded: true }">
                            
                            <!-- Accordion Header -->
                            <div @click="expanded = !expanded" class="p-8 cursor-pointer flex justify-between items-center group bg-white/50 hover:bg-white transition-colors">
                                <div>
                                    <span class="text-xs font-black text-rose-500 uppercase tracking-widest mb-1 block">Unit Kompetensi</span>
                                    <h3 class="text-xl font-bold text-slate-800 group-hover:text-rose-700 transition-colors">{{ $unit->kode_unit }}</h3>
                                    <p class="text-slate-500 text-sm mt-1">{{ $unit->judul_unit }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all transform duration-300" :class="{ 'rotate-180': expanded }">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>

                            <!-- Accordion Body -->
                            <div x-show="expanded" x-collapse>
                                <div class="p-8 pt-0 border-t border-rose-50/50 bg-white/30">
                                    <div class="space-y-4 mt-6">
                                        <template x-for="(q, index) in groups[{{ $unit->id_unit_kompetensi }}]" :key="index">
                                            <div class="flex items-start gap-4 group/item">
                                                <div class="flex-shrink-0 w-8 h-8 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center font-bold text-sm" x-text="index + 1"></div>
                                                <div class="flex-grow">
                                                    <textarea 
                                                        :name="'questions[{{ $unit->id_unit_kompetensi }}]['+index+']'" 
                                                        x-model="groups[{{ $unit->id_unit_kompetensi }}][index]" 
                                                        class="w-full p-4 bg-white/80 border-2 border-rose-100/50 rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all font-medium text-slate-700 min-h-[80px]" 
                                                        placeholder="Masukkan pertanyaan lisan..."></textarea>
                                                </div>
                                                <button type="button" @click="removeQuestion({{ $unit->id_unit_kompetensi }}, index)" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center opacity-0 group-hover/item:opacity-100">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>

                                        <button type="button" @click="addQuestion({{ $unit->id_unit_kompetensi }})" class="mt-4 px-6 py-3 bg-white border-2 border-dashed border-rose-200 text-rose-500 rounded-xl font-bold hover:border-rose-500 hover:bg-rose-50 transition-all flex items-center text-sm w-full justification-center">
                                            <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan di Unit Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>

            </div>
        </main>
    </div>

    <script>
        function templateHandler() {
            // Initialize groups from PHP data
            // Structure: { unit_id: [q1, q2] }
            let initialGroups = @json($questions);
            
            // Ensure every unit has an array
            let units = @json($skema->unitKompetensi->pluck('id_unit_kompetensi'));
            
            let groups = {};
            units.forEach(id => {
                groups[id] = initialGroups[id] || [];
            });

            return {
                groups: groups,
                addQuestion(unitId) {
                    this.groups[unitId].push('');
                },
                removeQuestion(unitId, index) {
                    this.groups[unitId].splice(index, 1);
                },
                save() {
                    document.getElementById('templateForm').submit();
                }
            }
        }
    </script>
</body>
</html>
