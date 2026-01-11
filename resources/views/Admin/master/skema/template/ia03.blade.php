<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.03 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        [x-cloak] { display: none !important; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-indigo-50/40 text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-24 px-6 md:px-12">
            <div class="max-w-7xl mx-auto" x-data="templateHandler()">
                
                <!-- Header -->
                <div class="mb-10">
                    <a href="{{ route('admin.skema.template.list', [$skema->id_skema, 'FR.IA.03']) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-bold mb-6 group transition-colors">
                        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        KEMBALI KE DAFTAR TEMPLATE
                    </a>
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-black tracking-wider uppercase">Master Template</span>
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold tracking-wider uppercase">FR.IA.03</span>
                            </div>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                                Bank Pertanyaan Observasi
                            </h1>
                            <p class="text-slate-500 mt-2 font-medium">Kelola daftar pertanyaan pendukung observasi untuk Skema: <span class="text-indigo-600">{{ $skema->nama_skema }}</span></p>
                        </div>
                        
                        <button @click="save()" class="px-8 py-3.5 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transition-all flex items-center transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2.5"></i> SIMPAN PERUBAHAN
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl flex items-center shadow-sm">
                        <div class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-emerald-800">Berhasil Disimpan!</h4>
                            <p class="text-sm text-emerald-600">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                        <div class="flex items-start">
                             <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-4 flex-shrink-0 mt-0.5">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-red-800">Terdapat Kesalahan</h4>
                                <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.ia03.store', [$skema->id_skema, $id_jadwal]) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-12">
                        <!-- Loop per Kelompok Pekerjaan -->
                        @foreach($kelompokPekerjaan as $indexKelompok => $kelompok)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden group-container" :class="{'ring-2 ring-indigo-500/10': focusedGroup === {{ $kelompok->id_kelompok_pekerjaan }}}">
                            
                            <!-- Header Kelompok -->
                            <div class="bg-slate-50 border-b border-slate-200 p-6 md:p-8">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-blue-200">
                                            {{ $indexKelompok + 1 }}
                                        </div>
                                        <h2 class="text-xl font-bold text-slate-800">Kelompok Pekerjaan</h2>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $kelompok->kode_kelompok ?? 'NO CODE' }}</span>
                                </div>
                                <h3 class="text-lg text-blue-700 font-bold leading-relaxed">
                                    {{ $kelompok->nama_kelompok_pekerjaan }}
                                </h3>
                                
                                <!-- Unit Kompetensi Tags -->
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($kelompok->unitKompetensi as $unit)
                                        <div class="inline-flex items-center px-3 py-1 rounded-md bg-white border border-slate-200 text-xs font-semibold text-slate-600">
                                            <i class="fas fa-cube text-slate-400 mr-2"></i>
                                            {{ $unit->kode_unit }} - {{ \Illuminate\Support\Str::limit($unit->judul_unit, 40) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Questions List for this Group -->
                            <div class="p-6 md:p-8 bg-white min-h-[200px]">
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center">
                                    <i class="fas fa-list-ul mr-2"></i> Daftar Pertanyaan
                                </h4>

                                <div class="space-y-4" x-ref="list_{{ $kelompok->id_kelompok_pekerjaan }}">
                                    <!-- Loop Questions from Alpine Data -->
                                    <template x-for="(q, qIndex) in getQuestions({{ $kelompok->id_kelompok_pekerjaan }})" :key="qIndex">
                                        <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 bg-slate-50 hover:border-indigo-300 hover:bg-white transition-all group/item">
                                            <div class="flex-shrink-0 w-8 h-8 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center font-bold text-sm mt-1" x-text="qIndex + 1"></div>
                                            
                                            <div class="flex-grow">
                                                <textarea 
                                                    :name="'questions[' + {{ $kelompok->id_kelompok_pekerjaan }} + '][]'"
                                                    x-model="groups[{{ $kelompok->id_kelompok_pekerjaan }}][qIndex]"
                                                    rows="2"
                                                    @focus="focusedGroup = {{ $kelompok->id_kelompok_pekerjaan }}"
                                                    class="w-full bg-transparent border-0 focus:ring-0 p-0 text-slate-700 font-medium placeholder-slate-400 resize-y min-h-[60px]"
                                                    placeholder="Tuliskan pertanyaan observasi disini..."></textarea>
                                            </div>
                                            
                                            <button type="button" @click="removeQuestion({{ $kelompok->id_kelompok_pekerjaan }}, qIndex)" class="text-slate-400 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 transition-colors opacity-0 group-hover/item:opacity-100">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <!-- Empty State for Group -->
                                <div x-show="getQuestions({{ $kelompok->id_kelompok_pekerjaan }}).length === 0" class="text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                                    <p class="text-slate-400 text-sm font-medium italic">Belum ada pertanyaan untuk kelompok ini.</p>
                                </div>

                                <!-- Add Button -->
                                <button type="button" @click="addQuestion({{ $kelompok->id_kelompok_pekerjaan }})" class="mt-6 w-full py-3 border-2 border-dashed border-indigo-200 text-indigo-600 rounded-xl font-bold hover:bg-indigo-50 hover:border-indigo-400 transition-all flex items-center justify-center gap-2 group/btn">
                                    <i class="fas fa-plus group-hover/btn:scale-110 transition-transform"></i> TAMBAH PERTANYAAN
                                </button>
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
            // Data from controller is: [group_id => [q1, q2], ...]
            // Or flat array (old format)
            const initialData = @json($questions);
            const kelompokIds = @json($kelompokPekerjaan->pluck('id_kelompok_pekerjaan'));
            
            // Normalize data structure for Alpine
            let normalizedGroups = {};
            
            // Initialize all groups with empty array
            kelompokIds.forEach(id => {
                normalizedGroups[id] = [];
            });

            // If initialData is array (flat/old) or object (grouped)
            if (Array.isArray(initialData)) {
                // OLD FLAT FORMAT: Cannot map easily. Put all in first group or warn?
                // Let's put in the first group as failsafe
                if (initialData.length > 0 && kelompokIds.length > 0) {
                    normalizedGroups[kelompokIds[0]] = initialData;
                }
            } else if (typeof initialData === 'object' && initialData !== null) {
                // GROUPED FORMAT
                for (const [key, value] of Object.entries(initialData)) {
                    // Check if key exists in known groups (it should)
                    if (normalizedGroups.hasOwnProperty(key)) {
                        normalizedGroups[key] = value;
                    }
                }
            }

            return {
                groups: normalizedGroups,
                focusedGroup: null,

                getQuestions(groupId) {
                    if (!this.groups[groupId]) {
                        this.groups[groupId] = [];
                    }
                    return this.groups[groupId];
                },

                addQuestion(groupId) {
                    if (!this.groups[groupId]) {
                        this.groups[groupId] = []; // Safety init
                    }
                    this.groups[groupId].push('');
                    this.focusedGroup = groupId;
                },

                removeQuestion(groupId, index) {
                    this.groups[groupId].splice(index, 1);
                },

                save() {
                    document.getElementById('templateForm').submit();
                }
            }
        }
    </script>
</body>
</html>
