<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.AK.05 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 uppercase">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-24 px-6">
            <div class="max-w-5xl mx-auto">
                
                <!-- Header -->
                <div class="mb-12">
                    <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-bold mb-6 group tracking-[0.2em] text-[10px]">
                        <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        KEMBALI KE DETAIL SKEMA
                    </a>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">
                                KELOLA TEMPLATE <br>
                                <span class="text-indigo-600">FR.AK.05</span>
                            </h1>
                            <p class="text-slate-500 mt-4 font-medium italic lowercase">"Laporan Asesmen"</p>
                        </div>
                        <button onclick="document.getElementById('templateForm').submit()" class="px-10 py-5 bg-indigo-600 text-white rounded-3xl font-black shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all flex items-center">
                            <i class="fas fa-save mr-3"></i> SIMPAN DEFAULT AK-05
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-emerald-500 text-white rounded-[2rem] flex items-center shadow-xl shadow-emerald-100 border-4 border-emerald-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold text-lg lowercase">{{ session('success') }}</span>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.ak05.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-12">
                        
                        <!-- BAGIAN 1: ASPEK ASESMEN -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-balance-scale"></i></span>
                                ASPEK NEGATIF & POSITIF
                            </h2>

                            <div>
                                <textarea name="content[aspek_asesmen]" rows="4" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 lowercase leading-relaxed" placeholder="Contoh: Asesi menunjukkan penguasaan alat kerja yang sangat baik, namun perlu peningkatan dalam manajemen waktu pelaksanaan tugas.">{{ $content['aspek_asesmen'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- BAGIAN 2: PENOLAKAN HASIL -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-times-circle"></i></span>
                                PENCATATAN PENOLAKAN
                            </h2>

                            <div>
                                <textarea name="content[catatan_penolakan]" rows="3" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 lowercase leading-relaxed" placeholder="Tuliskan catatan default jika ada penolakan hasil asesmen oleh asesi...">{{ $content['catatan_penolakan'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- BAGIAN 3: SARAN PERBAIKAN -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-lightbulb"></i></span>
                                SARAN PERBAIKAN
                            </h2>

                            <div>
                                <textarea name="content[saran_perbaikan]" rows="3" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 lowercase leading-relaxed" placeholder="Contoh: Disarankan untuk asesi mengikuti workshop lanjutan tentang teknologi terbaru di bidang ini.">{{ $content['saran_perbaikan'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- BAGIAN 4: CATATAN AKHIR -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-comment-dots"></i></span>
                                CATATAN AKHIR (PENGESAHAN)
                            </h2>

                            <div>
                                <textarea name="content[catatan_akhir]" rows="2" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 lowercase leading-relaxed" placeholder="Catatan singkat untuk bagian pengesahan laporan...">{{ $content['catatan_akhir'] ?? '' }}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="mt-16 flex justify-center pb-20">
                        <button type="submit" class="px-16 py-6 bg-slate-900 text-white rounded-full font-black shadow-2xl hover:bg-blue-600 transition-all duration-500 hover:scale-105">
                            <i class="fas fa-save mr-3"></i> SIMPAN SEMUA DEFAULT AK-05
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
