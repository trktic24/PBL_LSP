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
            <div class="max-w-4xl mx-auto" x-data="templateHandler()">
                
                <!-- Header -->
                <div class="mb-12">
                    <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" class="inline-flex items-center text-rose-600 hover:text-rose-800 font-bold mb-6 group tracking-[0.2em] text-[10px]">
                        <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        KEMBALI KE DETAIL SKEMA
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
                            <button @click="addQuestion()" class="px-6 py-4 bg-white border-2 border-rose-100 text-rose-600 rounded-3xl font-black shadow-xl shadow-rose-100 hover:border-rose-600 transition-all flex items-center group">
                                <i class="fas fa-plus-circle mr-3 group-hover:scale-110"></i> TAMBAH
                            </button>
                            <button @click="save()" class="px-8 py-4 bg-rose-600 text-white rounded-3xl font-black shadow-xl shadow-rose-200 hover:bg-rose-700 transition-all flex items-center">
                                <i class="fas fa-save mr-3"></i> SIMPAN BARU
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

                <form id="templateForm" action="{{ route('admin.skema.template.ia07.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="glass-card rounded-[2.5rem] p-8 shadow-sm hover:shadow-xl transition-all duration-300 relative group border-2 border-rose-50/50">
                                <div class="flex items-start gap-6">
                                    <div class="flex-shrink-0 w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center font-black" x-text="index + 1"></div>
                                    <div class="flex-grow">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1">Teks Pertanyaan Lisan</label>
                                        <textarea :name="'questions['+index+']'" x-model="questions[index]" class="w-full p-6 bg-rose-50/10 border-2 border-rose-100/50 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all font-bold text-slate-700 min-h-[100px]" placeholder="Masukkan pertanyaan lisan di sini..."></textarea>
                                    </div>
                                    <button type="button" @click="removeQuestion(index)" class="flex-shrink-0 w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center opacity-0 group-hover:opacity-100 italic font-black text-xs">
                                        HAPUS
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="questions.length === 0" class="p-24 border-4 border-dashed border-rose-100 rounded-[3rem] flex flex-col items-center justify-center text-center bg-white/30">
                            <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center mb-8 text-rose-200 text-4xl">
                                <i class="fas fa-microphone-slash"></i>
                            </div>
                            <h4 class="text-2xl font-black text-rose-900/30 uppercase tracking-tighter">Bank Lisan Kosong</h4>
                            <p class="text-slate-400 max-w-xs mt-4 font-semibold">Gunakan tombol "Tambah" untuk mengisi bank pertanyaan lisan (FR.IA.07) untuk skema ini.</p>
                        </div>
                    </div>
                </form>

                <div class="mt-16 flex justify-center pb-20">
                    <button @click="addQuestion()" class="px-12 py-5 bg-white border-4 border-slate-100 hover:border-rose-600 hover:bg-rose-600 hover:text-white rounded-full font-black transition-all duration-500 shadow-2xl shadow-rose-200 group">
                        <i class="fas fa-plus mr-3 group-hover:rotate-180 transition-transform duration-500"></i>
                        TAMBAH PERTANYAAN LISAN
                    </button>
                </div>

            </div>
        </main>
    </div>

    <script>
        function templateHandler() {
            return {
                questions: @json($questions),
                addQuestion() {
                    this.questions.push('');
                    this.$nextTick(() => { window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }); });
                },
                removeQuestion(index) {
                    this.questions.splice(index, 1);
                },
                save() {
                    document.getElementById('templateForm').submit();
                }
            }
        }
    </script>
</body>
</html>
