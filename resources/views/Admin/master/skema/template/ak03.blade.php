<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.AK.03 | LSP Polines</title>
    
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
                                <span class="text-indigo-600">FR.AK.03</span>
                            </h1>
                            <p class="text-slate-500 mt-4 font-medium italic lowercase">"Umpan Balik dan Catatan Asesmen"</p>
                        </div>
                        <button onclick="document.getElementById('templateForm').submit()" class="px-10 py-5 bg-indigo-600 text-white rounded-3xl font-black shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all flex items-center">
                            <i class="fas fa-save mr-3"></i> SIMPAN DEFAULT AK-03
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-emerald-500 text-white rounded-[2rem] flex items-center shadow-xl shadow-emerald-100 border-4 border-emerald-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold text-lg lowercase">{{ session('success') }}</span>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.ak03.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-12">
                        
                        <!-- BAGIAN 1: PILIH KOMPONEN -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-tasks"></i></span>
                                PILIH KOMPONEN UMPAN BALIK
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($allPoints as $point)
                                    <label class="flex items-start p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-indigo-100 transition-all cursor-pointer group has-[:checked]:bg-white has-[:checked]:border-indigo-500">
                                        <input type="checkbox" name="content[selected_points][]" value="{{ $point->id_poin_ak03 }}" class="w-6 h-6 mt-1 rounded-lg border-2 border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ in_array($point->id_poin_ak03, $content['selected_points'] ?? []) ? 'checked' : '' }}>
                                        <div class="ml-4">
                                            <span class="font-bold text-slate-600 group-hover:text-slate-900 lowercase leading-tight block">{{ $point->komponen }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- BAGIAN 2: CATATAN DEFAULT -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg"><i class="fas fa-comment-alt"></i></span>
                                CATATAN TAMBAHAN (DEFAULT)
                            </h2>

                            <div>
                                <textarea name="content[catatan_tambahan]" rows="5" class="w-full p-6 bg-slate-50 border-2 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 lowercase leading-relaxed" placeholder="Tuliskan catatan tambahan default yang akan muncul saat asesi mengisi umpan balik...">{{ $content['catatan_tambahan'] ?? '' }}</textarea>
                                <p class="text-[10px] font-bold text-slate-400 mt-4 ml-4 tracking-widest lowercase italic">* Asesi tetap dapat menghapus atau mengubah teks ini.</p>
                            </div>
                        </div>

                    </div>

                    <div class="mt-16 flex justify-center pb-20">
                        <button type="submit" class="px-16 py-6 bg-slate-900 text-white rounded-full font-black shadow-2xl hover:bg-indigo-600 transition-all duration-500 hover:scale-105">
                            <i class="fas fa-save mr-3"></i> SIMPAN TEMPLATE AK-03
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
