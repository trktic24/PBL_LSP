<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.01 | LSP Polines</title>
    
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
            <div class="max-w-6xl mx-auto">
                
                <!-- Header -->
                <div class="mb-12">
                    <a href="{{ route('admin.skema.detail', $skema->id_skema) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-bold mb-6 group tracking-[0.2em] text-[10px]">
                        <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        KEMBALI KE DETAIL SKEMA
                    </a>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">
                                EDIT STANDAR INDUSTRI <br>
                                <span class="text-indigo-600">FR.IA.01</span>
                            </h1>
                            <p class="text-slate-500 mt-4 font-medium italic lowercase">"Ceklis Observasi Aktivitas Di Tempat Kerja"</p>
                        </div>
                        <button onclick="document.getElementById('templateForm').submit()" class="px-10 py-5 bg-indigo-600 text-white rounded-3xl font-black shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all flex items-center">
                            <i class="fas fa-save mr-3"></i> SIMPAN SEMUA STANDAR
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-emerald-500 text-white rounded-[2rem] flex items-center shadow-xl shadow-emerald-100 border-4 border-emerald-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold text-lg lowercase">{{ session('success') }}</span>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.ia01.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-12">
                        @foreach($skema->kelompokPekerjaan as $kp)
                            @foreach($kp->unitKompetensi as $unit)
                                <div class="glass-card rounded-[3rem] p-8 shadow-sm border-2 border-slate-100 overflow-hidden">
                                    <div class="flex items-center gap-6 mb-8 pb-6 border-b-2 border-slate-50">
                                        <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-[1.5rem] flex items-center justify-center font-black text-xl">
                                            {{ $unit->urutan }}
                                        </div>
                                        <div>
                                            <h3 class="text-slate-400 font-black text-[10px] tracking-widest uppercase mb-1">{{ $unit->kode_unit }}</h3>
                                            <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $unit->judul_unit }}</h2>
                                        </div>
                                    </div>

                                    <div class="space-y-8">
                                        @foreach($unit->elemen as $elemen)
                                            <div class="pl-4 border-l-4 border-indigo-100">
                                                <h4 class="text-xs font-black text-indigo-600 mb-6 bg-indigo-50/50 inline-block px-4 py-2 rounded-full">
                                                    ELEMEN {{ $loop->iteration }}: {{ $elemen->elemen }}
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 gap-6">
                                                    @foreach($elemen->kriteria as $kuk)
                                                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100/50 hover:bg-white hover:shadow-lg transition-all group">
                                                            <div class="flex flex-col md:flex-row gap-6">
                                                                <div class="md:w-1/2">
                                                                    <div class="flex items-start gap-4 mb-4">
                                                                        <span class="font-black text-slate-300 text-xl">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                                                                        <p class="font-bold text-slate-700 leading-relaxed lowercase">{{ $kuk->kriteria }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="md:w-1/2">
                                                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Standar Industri / Kerja</label>
                                                                    <textarea name="standar_industri[{{ $kuk->id_kriteria }}]" class="w-full p-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 min-h-[80px] lowercase" placeholder="Masukkan standar industri/kerja untuk KUK ini...">{{ $kuk->standar_industri_kerja }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    <div class="mt-16 flex justify-center pb-20">
                        <button type="submit" class="px-16 py-6 bg-slate-900 text-white rounded-full font-black shadow-2xl hover:bg-indigo-600 transition-all duration-500 hover:scale-105">
                            <i class="fas fa-save mr-3"></i> SIMPAN PERUBAHAN STANDAR IA.01
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
