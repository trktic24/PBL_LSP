<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.AK.02 | LSP Polines</title>
    
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
                                <span class="text-indigo-600">FR.AK.02</span>
                            </h1>
                            <p class="text-slate-500 mt-4 font-medium italic lowercase">"Rekaman Asesmen Kompetensi (Rekomendasi & Tindak Lanjut)"</p>
                        </div>
                        <button onclick="document.getElementById('templateForm').submit()" class="px-10 py-5 bg-indigo-600 text-white rounded-3xl font-black shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all flex items-center">
                            <i class="fas fa-save mr-3"></i> SIMPAN DEFAULT AK-02
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-emerald-500 text-white rounded-[2rem] flex items-center shadow-xl shadow-emerald-100 border-4 border-emerald-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold text-lg lowercase">{{ session('success') }}</span>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.ak02.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-12">
                        
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-comment-dots"></i></span>
                                SETELAN DEFAULT ASESOR
                            </h2>

                            <div class="space-y-10">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full w-fit mb-4">Tindak Lanjut yang Dibutuhkan</label>
                                    <textarea name="content[tindak_lanjut]" rows="6" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 lowercase leading-relaxed" placeholder="Tuliskan tindak lanjut default untuk skema ini...">{{ $content['tindak_lanjut'] ?? '' }}</textarea>
                                    <p class="text-[10px] font-bold text-slate-400 mt-4 ml-4 tracking-widest lowercase italic">* Asesor dapat mengubah teks ini saat melakukan penilaian.</p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full w-fit mb-4">Komentar / Catatan Observasi</label>
                                    <textarea name="content[komentar]" rows="4" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 lowercase leading-relaxed" placeholder="Tuliskan catatan observasi default untuk skema ini...">{{ $content['komentar'] ?? '' }}</textarea>
                                    <p class="text-[10px] font-bold text-slate-400 mt-4 ml-4 tracking-widest lowercase italic">* Contoh: 'Asesi menunjukkan pemahaman yang baik terhadap standar industri.'</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-16 flex justify-center pb-20">
                        <button type="submit" class="px-16 py-6 bg-slate-900 text-white rounded-full font-black shadow-2xl hover:bg-indigo-600 transition-all duration-500 hover:scale-105">
                            <i class="fas fa-save mr-3"></i> SIMPAN SEMUA DEFAULT AK-02
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
