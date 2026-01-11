<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.04 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
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
                    <a href="{{ route('admin.skema.template.list', [$skema->id_skema, 'FR.IA.04']) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold mb-4 group tracking-widest text-xs">
                        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        KEMBALI KE DAFTAR TEMPLATE
                    </a>
                    <h1 class="text-4xl font-black text-slate-900 leading-tight">
                        PENYUSUNAN <br>
                        <span class="text-blue-600">FR.IA.04</span>
                    </h1>
                    <p class="text-slate-500 mt-2 font-medium italic">"Instruksi Terstruktur / Ceklis Verifikasi"</p>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-blue-600 text-white rounded-3xl flex items-center shadow-xl shadow-blue-100 border-4 border-blue-400">
                        <i class="fas fa-magic mr-4 text-2xl animate-pulse"></i>
                        <span class="font-extrabold">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.skema.template.ia04.store', [$skema->id_skema, $id_jadwal]) }}" method="POST" x-data="{ points: @json($points) }">
                    @csrf
                    <div class="space-y-10">
                        
                        <!-- Main Instruction (IA.04A) -->
                        <div class="glass-card rounded-[2.5rem] p-10 shadow-2xl border-t-8 border-t-blue-500 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 text-blue-50 opacity-10">
                                <i class="fas fa-file-signature text-[120px]"></i>
                            </div>
                            
                            <div class="relative z-10 flex flex-col gap-10">
                                <!-- Hal yang disiapkan (Skenario Umum) -->
                                <div class="group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black">1</div>
                                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Instruksi / Hal yang Disiapkan</h3>
                                    </div>
                                    <textarea name="points[0][nama]" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-8 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all font-medium text-slate-700 min-h-[150px]" placeholder="Masukkan instruksi umum untuk asesi...">{{ $points[0]['nama'] ?? '' }}</textarea>
                                </div>

                                <!-- Hal yang didemonstrasikan (Hasil Umum) -->
                                <div class="group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-black">2</div>
                                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Output / Hal yang Didemonstrasikan</h3>
                                    </div>
                                    <textarea name="points[0][kriteria]" class="w-full p-6 bg-amber-50/20 border-2 border-amber-100/50 rounded-3xl focus:ring-8 focus:ring-amber-500/5 focus:border-amber-500 outline-none transition-all font-medium text-slate-700 min-h-[150px]" placeholder="Masukkan hasil yang diharapkan dari demonstrasi asesi...">{{ $points[0]['kriteria'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="p-8 bg-slate-900 rounded-[2.5rem] text-white flex items-center gap-8 shadow-2xl">
                            <div class="bg-white/20 p-6 rounded-3xl text-3xl">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-black mb-1">Catatan Template</h4>
                                <p class="text-slate-400 font-medium text-sm leading-relaxed">Template ini akan otomatis muncul sebagai instruksi default saat asesor melakukan penilaian pada form FR.IA.04 untuk skema ini.</p>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="flex justify-center pt-8">
                             <button type="submit" class="px-16 py-6 bg-blue-600 text-white rounded-full font-black tracking-widest hover:bg-slate-900 transition-all duration-500 shadow-2xl shadow-blue-200 flex items-center gap-4 group">
                                <i class="fas fa-save text-xl group-hover:scale-125 transition-transform"></i>
                                SIMPAN TEMPLATE IA.04
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
