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
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="bg-[#fdf2f8] text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-24 px-6">
            <div class="max-w-5xl mx-auto" x-data="singleListHandler()">
                
                <div class="mb-12 sticky top-4 z-50 bg-[#fdf2f8]/90 backdrop-blur-sm py-4 border-b border-rose-200/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="#" onclick="history.back()" class="inline-flex items-center text-rose-600 hover:text-rose-800 font-bold mb-2 text-xs tracking-widest">
                            <i class="fas fa-arrow-left mr-2"></i> KEMBALI
                        </a>
                        <h1 class="text-3xl font-black text-rose-950 tracking-tight leading-none">
                            KELOLA SOAL LISAN <span class="text-rose-600">FR.IA.07</span>
                        </h1>
                    </div>
                    <button @click="save()" class="px-8 py-3 bg-rose-600 text-white rounded-full font-bold shadow-lg shadow-rose-200 hover:bg-rose-700 transition-transform hover:scale-105 flex items-center">
                        <i class="fas fa-save mr-2"></i> SIMPAN & SEBARKAN
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-emerald-100 text-emerald-800 rounded-2xl flex items-center border border-emerald-200">
                        <i class="fas fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="mb-10">
                    <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center">
                        <i class="fas fa-book-open mr-2 text-rose-500"></i> Referensi Unit Kompetensi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($skema->unitKompetensi as $unit)
                        <div class="bg-white p-4 rounded-2xl border border-rose-100 shadow-sm flex items-start gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="bg-rose-50 text-rose-600 text-[10px] font-black px-2 py-1 rounded border border-rose-100">
                                {{ $unit->kode_unit }}
                            </span>
                            <p class="text-xs font-bold text-slate-600 leading-snug">{{ $unit->judul_unit }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <form id="templateForm" action="{{ route('admin.skema.template.ia07.store', ['id_skema' => $skema->id_skema, 'id_jadwal' => $id_jadwal]) }}" method="POST">
                    @csrf
                    
                    <div class="glass-card rounded-[2.5rem] shadow-sm border-2 border-rose-50 p-8">
                        <h3 class="text-xl font-bold text-rose-950 mb-6 flex items-center">
                            <i class="fas fa-list-ul mr-3 text-rose-500"></i> Daftar Pertanyaan
                        </h3>

                        <div class="space-y-6">
                            <template x-for="(item, index) in questions" :key="index">
                                <div class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                                    
                                    <div class="absolute -left-3 -top-3 w-8 h-8 bg-slate-800 text-white rounded-lg flex items-center justify-center font-bold text-sm shadow-lg" x-text="index + 1"></div>
                                    
                                    <button type="button" @click="removeQuestion(index)" class="absolute top-4 right-4 text-slate-300 hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>

                                    <div class="space-y-4 pt-2">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pertanyaan</label>
                                            <textarea 
                                                :name="'questions[' + index + '][pertanyaan]'" 
                                                x-model="item.pertanyaan"
                                                class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all font-medium text-slate-700 min-h-[80px]" 
                                                placeholder="Masukkan pertanyaan lisan..."></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2">
                                                <i class="fas fa-key mr-1"></i> Kunci Jawaban (Opsional)
                                            </label>
                                            <textarea 
                                                :name="'questions[' + index + '][kunci]'" 
                                                x-model="item.kunci"
                                                class="w-full p-3 bg-emerald-50/50 border-2 border-emerald-100/50 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-medium text-slate-700 min-h-[50px] text-sm" 
                                                placeholder="Jawaban yang diharapkan..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="questions.length === 0" class="text-center py-12 border-2 border-dashed border-rose-200 rounded-3xl bg-rose-50/30 mb-6">
                            <p class="text-rose-400 font-medium">Belum ada pertanyaan dibuat.</p>
                        </div>

                        <button type="button" @click="addQuestion()" class="mt-8 w-full py-4 bg-white border-2 border-dashed border-rose-300 text-rose-500 rounded-2xl font-bold hover:bg-rose-50 hover:border-rose-500 transition-all flex items-center justify-center group">
                            <span class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-plus"></i>
                            </span>
                            TAMBAH PERTANYAAN BARU
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

    <script>
        function singleListHandler() {
            // Ambil data dari PHP
            let savedQuestions = @json($questions);
            
            // Validasi data (karena dulu formatnya beda, kita amanin disini)
            // Kalau data kosong atau bukan array, inisialisasi array kosong
            if (!Array.isArray(savedQuestions)) {
                savedQuestions = [];
            }

            return {
                questions: savedQuestions.length ? savedQuestions : [{ pertanyaan: '', kunci: '' }],
                
                addQuestion() {
                    this.questions.push({ pertanyaan: '', kunci: '' });
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