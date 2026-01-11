<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.02 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Poppins', sans-serif; } 
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-[#f0f4f8] text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-20 px-6">
            <div class="max-w-4xl mx-auto">
                
                <!-- Header -->
                <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <a href="{{ route('admin.skema.template.list', [$skema->id_skema, 'FR.IA.02']) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold text-sm transition-all mb-4 group">
                            <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                            KEMBALI KE DAFTAR TEMPLATE
                        </a>
                        <h1 class="text-4xl font-black text-slate-900 leading-none">
                            MASTER TEMPLATE <br>
                            <span class="text-blue-600">FR.IA.02</span>
                        </h1>
                        <p class="text-slate-500 mt-4 font-medium max-w-lg">Atur skenario tugas praktik, daftar peralatan, dan alokasi waktu default untuk skema ini.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-5 bg-emerald-500 text-white rounded-3xl flex items-center shadow-lg shadow-emerald-200 animate-bounce">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-bold tracking-wide">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.skema.template.ia02.store', [$skema->id_skema, $id_jadwal]) }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        
                        <!-- Info Skema Card -->
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                            <div class="absolute right-0 bottom-0 opacity-10 translate-x-1/4 translate-y-1/4">
                                <i class="fas fa-file-alt text-[200px]"></i>
                            </div>
                            <div class="relative z-10">
                                <span class="bg-white/20 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">Informasi Skema</span>
                                <h2 class="text-2xl font-black mb-2">{{ $skema->nama_skema }}</h2>
                                <p class="text-blue-100 font-medium">Nomor Skema: {{ $skema->nomor_skema }}</p>
                            </div>
                        </div>

                        <!-- Editor Fields -->
                        <div class="grid grid-cols-1 gap-8">
                            
                            <!-- Skenario -->
                            <div class="glass-card rounded-[2.5rem] p-10 shadow-xl border border-white">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">
                                        <i class="fas fa-align-left"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Skenario Tugas</h3>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Langkah-langkah instruksi kerja</p>
                                    </div>
                                </div>
                                <textarea name="skenario" class="w-full p-8 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-8 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all font-medium text-slate-700 min-h-[250px] shadow-inner leading-relaxed" placeholder="Contoh: Peserta diminta untuk melakukan instalasi sistem operasi mulai dari partisi hingga selesai...">{{ $content['skenario'] ?? '' }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <!-- Peralatan -->
                                <div class="md:col-span-2 glass-card rounded-[2.5rem] p-10 shadow-xl border border-white">
                                    <div class="flex items-center gap-4 mb-8">
                                        <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-2xl">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Daftar Peralatan</h3>
                                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Alat & Bahan yang dibutuhkan</p>
                                        </div>
                                    </div>
                                    <textarea name="peralatan" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-8 focus:ring-amber-500/5 focus:border-amber-500 outline-none transition-all font-medium text-slate-700 min-h-[150px] shadow-inner leading-relaxed" placeholder="1. Laptop&#10;2. Flashdrive (Bootable OS)&#10;3. Koneksi Internet">{{ $content['peralatan'] ?? '' }}</textarea>
                                </div>

                                <!-- Waktu -->
                                <div class="glass-card rounded-[2.5rem] p-10 shadow-xl border border-white">
                                    <div class="flex items-center gap-4 mb-8">
                                        <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Alokasi Waktu</h3>
                                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Durasi pengerjaan</p>
                                        </div>
                                    </div>
                                    <input type="text" name="waktu" value="{{ $content['waktu'] ?? '02:00:00' }}" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:ring-8 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-black text-slate-800 text-center text-2xl shadow-inner" placeholder="HH:MM:SS">
                                    <p class="text-center text-[10px] text-slate-400 mt-4 font-bold uppercase tracking-[0.2em]">Format Jam:Menit:Detik</p>
                                </div>
                            </div>

                        </div>

                        <!-- Action Button -->
                        <div class="pt-8 flex justify-end">
                            <button type="submit" class="group relative px-12 py-6 bg-slate-900 text-white rounded-[2rem] font-black tracking-widest overflow-hidden hover:bg-blue-600 transition-all duration-500 shadow-2xl shadow-slate-300">
                                <span class="relative z-10 flex items-center gap-3">
                                    <i class="fas fa-cloud-upload-alt text-xl group-hover:scale-110 transition-transform"></i>
                                    SIMPAN PERUBAHAN TEMPLATE
                                </span>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
