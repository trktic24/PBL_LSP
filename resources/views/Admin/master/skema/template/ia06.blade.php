<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.06 | LSP Polines</title>
    
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
<body class="bg-[#fcfdff] text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-10 pb-12 px-6">
            <div class="max-w-5xl mx-auto" x-data="templateHandler()">
                
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <a href="{{ route('admin.skema.template.list', [$skema->id_skema, 'FR.IA.06']) }}" class="flex items-center text-slate-500 hover:text-blue-600 transition mb-2">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Template
                        </a>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight lowercase">
                            KELOLA <span class="uppercase">ESSAY</span> <span class="bg-indigo-600 text-white px-3 py-1 rounded-lg ml-2 uppercase text-2xl">FR.IA.06</span>
                        </h1>
                        <p class="text-slate-500 mt-2 font-medium">Skema: {{ $skema->nama_skema }}</p>
                    </div>

                    <div class="flex gap-3">
                        <button @click="addQuestion()" class="px-5 py-3 bg-white border border-slate-200 text-indigo-600 rounded-2xl font-bold shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-plus mr-2 text-sm"></i> Tambah Soal
                        </button>
                        <button @click="document.getElementById('formTemplate').submit()" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center">
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

                <form id="formTemplate" action="{{ route('admin.skema.template.ia06.store', [$skema->id_skema, $id_jadwal]) }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="glass-card rounded-[2.5rem] p-8 shadow-sm hover:shadow-2xl transition-all duration-500 relative overflow-hidden group border-l-8 border-l-indigo-500">
                                
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-8">
                                        <div class="flex items-center gap-5">
                                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-[1.25rem] flex items-center justify-center font-black shadow-lg shadow-indigo-100 text-xl" x-text="index + 1"></div>
                                            <div>
                                                <h3 class="text-xl font-black text-slate-800 tracking-tight">Pertanyaan Essay</h3>
                                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-0.5">Konten Template Asesmen</p>
                                            </div>
                                        </div>
                                        <button type="button" @click="removeQuestion(index)" class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shadow-sm border border-rose-100/50">
                                            <i class="fas fa-trash-alt text-base"></i>
                                        </button>
                                    </div>

                                    <input type="hidden" :name="'soal['+index+'][id]'" x-model="q.id">

                                    <div class="grid grid-cols-1 gap-8">
                                        <!-- Pertanyaan -->
                                        <div class="relative group/field">
                                            <div class="absolute -left-2 top-0 bottom-0 w-1 bg-indigo-200 rounded-full scale-y-0 group-focus-within/field:scale-y-100 transition-transform origin-center"></div>
                                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3 px-1">Teks Pertanyaan Essay</label>
                                            <textarea :name="'soal['+index+'][pertanyaan]'" x-model="q.pertanyaan" class="w-full p-6 bg-white border border-slate-200 rounded-[1.5rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 min-h-[120px] shadow-inner" placeholder="Masukkan pertanyaan essay di sini..."></textarea>
                                        </div>

                                        <!-- Kunci/Contoh Jawaban -->
                                        <div class="relative group/field">
                                            <div class="absolute -left-2 top-0 bottom-0 w-1 bg-emerald-200 rounded-full scale-y-0 group-focus-within/field:scale-y-100 transition-transform origin-center"></div>
                                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-emerald-500 mb-3 px-1">Acuan / Contoh Jawaban Benar</label>
                                            <textarea :name="'soal['+index+'][kunci]'" x-model="q.kunci" class="w-full p-6 bg-emerald-50/30 border border-emerald-100 rounded-[1.5rem] focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium text-slate-600 min-h-[100px]" placeholder="Masukkan acuan jawaban untuk asesor..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="questions.length === 0" class="p-20 border-4 border-dashed border-slate-200 rounded-[3rem] flex flex-col items-center justify-center text-center bg-white/50">
                            <div class="w-24 h-24 bg-indigo-50 rounded-3xl flex items-center justify-center mb-6">
                                <i class="fas fa-edit text-indigo-200 text-4xl"></i>
                            </div>
                            <h4 class="text-2xl font-black text-slate-300">Bank Soal Kosong</h4>
                            <p class="text-slate-400 max-w-sm mt-3 font-medium">Belum ada pertanyaan essay untuk skema ini. Gunakan tombol di bawah untuk menambahkan soal pertama.</p>
                        </div>
                    </div>
                </form>

                <div class="mt-16 flex justify-center pb-32">
                     <button @click="addQuestion()" class="group flex items-center gap-4 px-10 py-5 bg-white border-2 border-slate-200 hover:border-indigo-600 hover:bg-indigo-600 hover:text-white rounded-3xl font-black transition-all duration-500 shadow-xl shadow-slate-100/50 hover:shadow-indigo-200">
                        <i class="fas fa-plus-circle text-xl group-hover:scale-125 transition-transform"></i>
                        TAMBAH SOAL ESSAY
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script>
        function templateHandler() {
            return {
                questions: @json($semua_soal).map(s => ({
                    id: s.id_soal_ia06,
                    pertanyaan: s.soal_ia06,
                    kunci: s.kunci_jawaban_ia06
                })),

                addQuestion() {
                    this.questions.push({
                        id: null,
                        pertanyaan: '',
                        kunci: ''
                    });
                    this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }); });
                },

                removeQuestion(index) {
                    if (confirm('Hapus soal essay ini?')) {
                        const id = this.questions[index].id;
                        if (id) {
                            fetch(`/admin/skema/{{ $skema->id_skema }}/template/ia06/delete/${id}`, {
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
