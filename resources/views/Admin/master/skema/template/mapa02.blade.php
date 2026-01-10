<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.MAPA.02 | LSP Polines</title>
    
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
        .form-table, .form-table td, .form-table th {
            border: 1px solid #e2e8f0;
            border-collapse: collapse;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 uppercase">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 pt-12 pb-24 px-6">
            <div class="max-w-7xl mx-auto">
                
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
                                <span class="text-indigo-600">FR.MAPA.02</span>
                            </h1>
                            <p class="text-slate-500 mt-4 font-medium italic lowercase">"Peta Instrumen Asesmen Berdasarkan Kelompok Pekerjaan"</p>
                        </div>
                        <button onclick="document.getElementById('templateForm').submit()" class="px-10 py-5 bg-indigo-600 text-white rounded-3xl font-black shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all flex items-center">
                            <i class="fas fa-save mr-3"></i> SIMPAN PETA INSTRUMEN
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-emerald-500 text-white rounded-[2rem] flex items-center shadow-xl shadow-emerald-100 border-4 border-emerald-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold text-lg lowercase">{{ session('success') }}</span>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.mapa02.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-12">
                        
                        @php
                            $instruments = [
                                'FR.IA.01. CL - Ceklis Observasi',
                                'FR.IA.02. TPD - Tugas Praktik Demonstrasi',
                                'FR.IA.03. PMO - Pertanyaan Untuk Mendukung Observasi',
                                'FR.IA.04A. DIT - Daftar Instruksi Terstruktur (Proyek)',
                                'FR.IA.04B. DIT - Daftar Instruksi Terstruktur (Lainnya)',
                                'FR.IA.05. DPT - Daftar Pertanyaan Tertulis Pilihan Ganda',
                                'FR.IA.06. DPT - Daftar Pertanyaan Tertulis Pilihan Esai',
                                'FR.IA.07. DPL - Daftar Pertanyaan Lisan',
                                'FR.IA.08. CVP - Ceklis Verifikasi Portofolio',
                                'FR.IA.09. PW - Pertanyaan Wawancara',
                                'FR.IA.10. VPK - Verifikasi Pihak Ketiga',
                                'FR.IA.11. CRP - Ceklis Reviu Produk',
                            ];
                        @endphp

                        @foreach($skema->kelompokPekerjaan as $kp)
                            <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100 overflow-hidden">
                                <div class="flex items-center gap-6 mb-8 pb-6 border-b-2 border-slate-50">
                                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-[1.5rem] flex items-center justify-center font-black text-xl">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <h3 class="text-slate-400 font-black text-[10px] tracking-widest uppercase mb-1">KELOMPOK PEKERJAAN</h3>
                                        <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $kp->nama_kelompok_pekerjaan }}</h2>
                                    </div>
                                </div>

                                <div class="overflow-x-auto -mx-10 px-10">
                                    <table class="w-full text-sm form-table">
                                        <thead class="bg-slate-50/50">
                                            <tr class="text-[10px] font-black tracking-widest text-slate-400">
                                                <th class="p-6 text-left border-black uppercase bg-white">INSTRUMEN ASESMEN</th>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <th class="p-4 border-black text-center bg-indigo-50/50 text-indigo-600 w-16 italic">{{ $i }}</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            @foreach($instruments as $idx => $inst)
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="p-6 border-slate-100">
                                                        <div class="font-bold text-slate-700 lowercase leading-none">{{ $inst }}</div>
                                                    </td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <td class="p-2 border-slate-100 text-center bg-indigo-50/10 hover:bg-indigo-600 group transition-all">
                                                            <div class="flex items-center justify-center h-full w-full">
                                                                <input type="radio" 
                                                                    name="content[{{ $kp->id_kelompok_pekerjaan }}][{{ $inst }}]" 
                                                                    value="{{ $i }}" 
                                                                    class="w-6 h-6 text-indigo-600 focus:ring-indigo-500 border-slate-300"
                                                                    {{ ($content[$kp->id_kelompok_pekerjaan][$inst] ?? '') == $i ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                    @endfor
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-6 flex justify-between items-center text-[10px] font-bold text-slate-400 tracking-widest uppercase">
                                    <span>*) diisi berdasarkan hasil penentuan perencanaan asesmen</span>
                                    <span>**) 1:SANGAT BAIK ... 5:SANGAT KURANG</span>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="mt-16 flex justify-center pb-20">
                        <button type="submit" class="px-16 py-6 bg-slate-900 text-white rounded-full font-black shadow-2xl hover:bg-indigo-600 transition-all duration-500 hover:scale-105">
                            <i class="fas fa-save mr-3"></i> SIMPAN TEMPLATE PETA INSTRUMEN
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
