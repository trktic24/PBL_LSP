<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.08 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
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
<body class="bg-[#f0f9ff] text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-24 px-6">
            <div class="max-w-4xl mx-auto">
                
                <!-- Header -->
                <div class="mb-12">
                    <a href="{{ route('admin.skema.template.list', [$skema->id_skema, 'FR.IA.08']) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 font-bold mb-6 group text-xs uppercase tracking-widest">
                        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        Kembali Ke Daftar Template
                    </a>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none uppercase">
                        INSTRUKSI VERIFIKASI <br>
                        <span class="text-blue-600">FR.IA.08</span>
                    </h1>
                    <p class="text-slate-400 mt-4 font-medium italic">"Ceklis Verifikasi Pihak Ketiga"</p>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-blue-600 text-white rounded-3xl flex items-center shadow-xl shadow-blue-100 border-4 border-blue-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.skema.template.ia08.store', [$skema->id_skema, $id_jadwal]) }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        
                        <div class="glass-card rounded-[2.5rem] p-10 shadow-xl border-2 border-blue-50/50">
                            <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center uppercase tracking-tight">
                                <i class="fas fa-info-circle mr-4 text-blue-500"></i>
                                Panduan Verifikasi Default
                            </h3>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1">Instruksi Untuk Pihak Ketiga (Supervisor/Atasan)</label>
                                <textarea name="instructions" class="w-full p-8 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-slate-700 min-h-[300px]" placeholder="Masukkan instruksi verifikasi untuk pihak ketiga di sini...">{{ $instructions }}</textarea>
                            </div>
                        </div>

                        <div class="p-8 bg-blue-50 rounded-[2rem] border-2 border-blue-100 text-blue-800 flex items-start gap-6">
                            <i class="fas fa-lightbulb text-2xl mt-1"></i>
                            <div>
                                <h4 class="font-black text-lg">Tips Penulisan</h4>
                                <p class="text-blue-700/80 font-medium">Berikan instruksi yang jelas kepada pihak ketiga mengenai apa yang harus mereka verifikasi berdasarkan pekerjaan asesi sehari-hari.</p>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="flex justify-center pt-8">
                             <button type="submit" class="px-16 py-6 bg-blue-600 text-white rounded-full font-black shadow-xl shadow-blue-200 hover:bg-slate-900 transition-all flex items-center gap-4 group">
                                <i class="fas fa-save group-hover:scale-125 transition-transform"></i>
                                SIMPAN TEMPLATE IA.08
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
