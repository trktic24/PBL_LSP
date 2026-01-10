<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.11 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-[#fafafa] text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-24 px-6">
            <div class="max-w-4xl mx-auto">
                
                <!-- Header -->
                <div class="mb-12">
                    <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" class="inline-flex items-center text-slate-600 hover:text-slate-900 font-bold mb-6 group text-xs uppercase tracking-widest">
                        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        Kembali
                    </a>
                    <h1 class="text-4xl font-black text-slate-900 leading-none">
                        TINJAU INSTRUMEN <br>
                        <span class="text-indigo-600 uppercase">FR.IA.11</span>
                    </h1>
                    <p class="text-slate-400 mt-4 font-medium italic">"Ceklis Meninjau Instrumen Asesmen"</p>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-indigo-600 text-white rounded-3xl flex items-center shadow-xl shadow-indigo-100 border-4 border-indigo-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.skema.template.ia11.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        
                        <div class="glass-card rounded-[2.5rem] p-10 shadow-xl border-2 border-indigo-50/50">
                            <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center uppercase tracking-tight">
                                <i class="fas fa-clipboard-check mr-4 text-indigo-500"></i>
                                Rekomendasi & Catatan Default
                            </h3>

                            <div class="grid grid-cols-1 gap-8">
                                <!-- Rekomendasi Kelompok -->
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1">Rekomendasi Kelompok Pekerjaan</label>
                                    <textarea name="rekomendasi_kelompok" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 min-h-[100px]" placeholder="Masukkan rekomendasi default untuk kelompok pekerjaan...">{{ $content['rekomendasi_kelompok'] ?? '' }}</textarea>
                                </div>

                                <!-- Rekomendasi Unit -->
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1">Rekomendasi Unit Kompetensi</label>
                                    <textarea name="rekomendasi_unit" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 min-h-[100px]" placeholder="Masukkan rekomendasi default untuk unit kompetensi...">{{ $content['rekomendasi_unit'] ?? '' }}</textarea>
                                </div>

                                <!-- Catatan Asesor -->
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1">Catatan Peninjau / Asesor</label>
                                    <textarea name="catatan_asesor" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 min-h-[100px]" placeholder="Masukkan catatan peninjau default...">{{ $content['catatan_asesor'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 bg-amber-50 rounded-[2rem] border-2 border-amber-100 text-amber-800 flex items-start gap-6">
                            <i class="fas fa-exclamation-triangle text-2xl mt-1"></i>
                            <div>
                                <h4 class="font-black text-lg">Perhatian</h4>
                                <p class="text-amber-700/80 font-medium">Kriteria teknis (Checklist Ya/Tidak) menggunakan standar LSP dan tidak dapat diubah dari sini untuk menjaga integritas instrumen.</p>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="flex justify-center pt-8">
                             <button type="submit" class="px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black shadow-xl shadow-indigo-200 hover:bg-slate-900 transition-all flex items-center gap-4 group">
                                <i class="fas fa-save group-hover:scale-125 transition-transform"></i>
                                SIMPAN TEMPLATE IA.11
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
