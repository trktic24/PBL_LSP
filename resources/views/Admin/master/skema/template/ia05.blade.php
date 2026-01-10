<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.05 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
        [x-cloak] { display: none !important; }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-10 pb-12 px-6">
            <div class="max-w-5xl mx-auto" x-data="templateHandler()">
                
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" class="flex items-center text-slate-500 hover:text-blue-600 transition mb-2">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Skema
                        </a>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                            KELOLA BANK SOAL <span class="bg-blue-600 text-white px-3 py-1 rounded-lg ml-2">FR.IA.05</span>
                        </h1>
                        <p class="text-slate-500 mt-2 font-medium">Skema: {{ $skema->nama_skema }}</p>
                    </div>

                    <div class="flex gap-3">
                        <button @click="addQuestion()" class="px-5 py-3 bg-white border border-slate-200 text-blue-600 rounded-2xl font-bold shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-plus mr-2 text-sm"></i> Tambah Soal
                        </button>
                        <button @click="document.getElementById('formTemplate').submit()" class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan Template
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                        <i class="fas fa-check-circle mr-3 text-lg"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center shadow-sm">
                        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                @endif

                <form id="formTemplate" action="{{ route('admin.skema.template.ia05.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="glass-card rounded-[2rem] p-8 shadow-sm hover:shadow-xl transition-all duration-500 relative overflow-hidden group">
                                <!-- Background Decoration -->
                                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-50 rounded-full opacity-30 group-hover:scale-150 transition-transform duration-700"></div>
                                
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black shadow-lg shadow-blue-100" x-text="index + 1"></div>
                                            <h3 class="text-lg font-bold text-slate-700">Pertanyaan Pilihan Ganda</h3>
                                        </div>
                                        <button type="button" @click="removeQuestion(index)" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>

                                    <input type="hidden" :name="'soal['+index+'][id]'" x-model="q.id">

                                    <div class="mb-6">
                                        <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 px-1">Teks Pertanyaan</label>
                                        <textarea :name="'soal['+index+'][pertanyaan]'" x-model="q.pertanyaan" class="w-full p-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium min-h-[100px]" placeholder="Contoh: Apa yang dimaksud dengan normalisasi database?"></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Opsi A -->
                                        <div>
                                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 px-1">Opsi A</label>
                                            <input type="text" :name="'soal['+index+'][opsi_a]'" x-model="q.opsi_a" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium" placeholder="Pilihan A">
                                        </div>
                                        <!-- Opsi B -->
                                        <div>
                                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 px-1">Opsi B</label>
                                            <input type="text" :name="'soal['+index+'][opsi_b]'" x-model="q.opsi_b" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium" placeholder="Pilihan B">
                                        </div>
                                        <!-- Opsi C -->
                                        <div>
                                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 px-1">Opsi C</label>
                                            <input type="text" :name="'soal['+index+'][opsi_c]'" x-model="q.opsi_c" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium" placeholder="Pilihan C">
                                        </div>
                                        <!-- Opsi D -->
                                        <div>
                                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 px-1">Opsi D</label>
                                            <input type="text" :name="'soal['+index+'][opsi_d]'" x-model="q.opsi_d" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-medium" placeholder="Pilihan D">
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                                                <i class="fas fa-key text-sm"></i>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] font-black uppercase tracking-wider text-slate-400">Kunci Jawaban</span>
                                                <select :name="'soal['+index+'][kunci]'" x-model="q.kunci" class="bg-transparent font-bold text-slate-700 outline-none border-none focus:ring-0 p-0 text-sm cursor-pointer hover:text-blue-600 transition-colors">
                                                    <option value="A">Opsi A</option>
                                                    <option value="B">Opsi B</option>
                                                    <option value="C">Opsi C</option>
                                                    <option value="D">Opsi D</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="questions.length === 0" class="p-12 border-4 border-dashed border-slate-200 rounded-[2.5rem] flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-folder-open text-slate-300 text-3xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-400">Belum ada soal</h4>
                            <p class="text-slate-400 max-w-xs mt-2 font-medium">Klik tombol "Tambah Soal" untuk mulai membuat bank soal skema ini.</p>
                        </div>
                    </div>
                </form>

                <div class="mt-12 flex justify-center pb-20">
                     <button @click="addQuestion()" class="group flex items-center gap-3 px-8 py-4 bg-slate-200/50 hover:bg-blue-600 hover:text-white rounded-full font-bold transition-all duration-300">
                        <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i>
                        Tambah Pertanyaan Baru
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script>
        function templateHandler() {
            return {
                questions: @json($semua_soal).map(s => ({
                    id: s.id_soal_ia05,
                    pertanyaan: s.soal_ia05,
                    opsi_a: s.opsi_a_ia05,
                    opsi_b: s.opsi_b_ia05,
                    opsi_c: s.opsi_c_ia05,
                    opsi_d: s.opsi_d_ia05,
                    kunci: s.kunci_jawaban ? s.kunci_jawaban.jawaban_benar_ia05 : 'A'
                })),

                addQuestion() {
                    this.questions.push({
                        id: null,
                        pertanyaan: '',
                        opsi_a: '',
                        opsi_b: '',
                        opsi_c: '',
                        opsi_d: '',
                        kunci: 'A'
                    });
                    this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }); });
                },

                removeQuestion(index) {
                    if (confirm('Yakin ingin menghapus soal ini?')) {
                        const id = this.questions[index].id;
                        if (id) {
                            fetch(`/admin/skema/{{ $skema->id_skema }}/template/ia05/delete/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            }).then(() => {
                                this.questions.splice(index, 1);
                            });
                        } else {
                            this.questions.splice(index, 1);
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>
