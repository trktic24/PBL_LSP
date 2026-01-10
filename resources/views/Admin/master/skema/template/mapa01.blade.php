<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.MAPA.01 | LSP Polines</title>
    
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
                                <span class="text-indigo-600">FR.MAPA.01</span>
                            </h1>
                            <p class="text-slate-500 mt-4 font-medium italic lowercase">"Merencanakan Aktivitas dan Proses Asesmen"</p>
                        </div>
                        <button onclick="document.getElementById('templateForm').submit()" class="px-10 py-5 bg-indigo-600 text-white rounded-3xl font-black shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all flex items-center">
                            <i class="fas fa-save mr-3"></i> SIMPAN SETELAN DEFAULT
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-emerald-500 text-white rounded-[2rem] flex items-center shadow-xl shadow-emerald-100 border-4 border-emerald-400">
                        <i class="fas fa-check-circle mr-4 text-2xl"></i>
                        <span class="font-extrabold text-lg lowercase">{{ session('success') }}</span>
                    </div>
                @endif

                <form id="templateForm" action="{{ route('admin.skema.template.mapa01.store', $skema->id_skema) }}" method="POST">
                    @csrf
                    <div class="space-y-12">
                        
                        <!-- BAGIAN 1: PENDEKATAN -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg">1</span>
                                PENDEKATAN & KONTEKS
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-6">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full w-fit">1.1 Pendekatan Asesmen (Checklist Default)</label>
                                    @foreach([
                                        'Hasil pelatihan dan atau pendidikan, Kurikulum & fasilitas telusur',
                                        'Hasil pelatihan - belum berbasis kompetensi',
                                        'Pekerja berpengalaman - telusur',
                                        'Pekerja berpengalaman - belum berbasis kompetensi',
                                        'Pelatihan / belajar mandiri'
                                    ] as $opt)
                                        <label class="flex items-center p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-indigo-100 transition-all cursor-pointer group">
                                            <input type="checkbox" name="content[pendekatan_asesmen][]" value="{{ $opt }}" class="w-6 h-6 rounded-lg border-2 border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ (in_array($opt, $content['pendekatan_asesmen'] ?? [])) ? 'checked' : '' }}>
                                            <span class="ml-4 font-bold text-slate-600 group-hover:text-slate-900 lowercase">{{ $opt }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="space-y-8">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full w-fit mb-4">Tujuan Sertifikasi (Default)</label>
                                        <div class="flex flex-wrap gap-4">
                                            @foreach(['Sertifikasi', 'PKT', 'RPL', 'Lainnya'] as $opt)
                                                <label class="flex items-center px-6 py-3 bg-white border-2 border-slate-100 rounded-2xl hover:border-indigo-500 transition-all cursor-pointer">
                                                    <input type="radio" name="content[tujuan_sertifikasi]" value="{{ $opt }}" class="w-5 h-5 text-indigo-600" {{ ($content['tujuan_sertifikasi'] ?? 'Sertifikasi') == $opt ? 'checked' : '' }}>
                                                    <span class="ml-3 font-bold text-slate-700">{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full w-fit mb-4">Konteks Lingkungan</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach(['Tempat kerja nyata', 'Tempat kerja simulasi'] as $opt)
                                                <label class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-white border-2 border-transparent hover:border-indigo-500 transition-all cursor-pointer">
                                                    <input type="checkbox" name="content[konteks_lingkungan][]" value="{{ $opt }}" class="w-6 h-6 rounded-lg text-indigo-600" {{ in_array($opt, $content['konteks_lingkungan'] ?? []) ? 'checked' : '' }}>
                                                    <span class="ml-3 font-bold text-slate-600 lowercase">{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-10 border-slate-100">

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-full w-fit mb-6">1.2 Standar Default</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <span class="text-[10px] font-black text-slate-400 block mb-2 ml-1">STANDAR KOMPETENSI</span>
                                        <input type="text" name="content[standar_kompetensi]" value="{{ $content['standar_kompetensi'] ?? $skema->nama_skema }}" class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700">
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-black text-slate-400 block mb-2 ml-1">SPESIFIKASI PRODUK</span>
                                        <input type="text" name="content[spesifikasi_produk]" value="{{ $content['spesifikasi_produk'] ?? '' }}" class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700">
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-black text-slate-400 block mb-2 ml-1">PEDOMAN KHUSUS</span>
                                        <input type="text" name="content[pedoman_khusus]" value="{{ $content['pedoman_khusus'] ?? '' }}" class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BAGIAN 2: CHECKLIST UNIT -->
                        <div class="glass-card rounded-[3rem] p-10 shadow-sm border-2 border-slate-100">
                            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-4">
                                <span class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-lg">2</span>
                                BUKTI-BUKTI & METODE ASESMEN
                            </h2>

                            <div class="overflow-x-auto -mx-10 px-10">
                                <table class="w-full text-sm form-table">
                                    <thead class="bg-slate-50/50">
                                        <tr class="text-[10px] font-black tracking-widest text-slate-400">
                                            <th class="p-6 text-left border-black uppercase bg-white">UNIT KOMPETENSI</th>
                                            <th class="p-6 text-left border-black uppercase bg-white w-1/4">BUKTI-BUKTI (DEFAULT)</th>
                                            <th class="p-4 border-black text-center bg-indigo-50/50 text-indigo-600">L</th>
                                            <th class="p-4 border-black text-center bg-indigo-50/50 text-indigo-600">TL</th>
                                            <th class="p-4 border-black text-center bg-indigo-50/50 text-indigo-600">T</th>
                                            <th class="p-4 border-black text-center bg-slate-100/50">OBS</th>
                                            <th class="p-4 border-black text-center bg-slate-100/50">STR</th>
                                            <th class="p-4 border-black text-center bg-slate-100/50">TANYA</th>
                                            <th class="p-4 border-black text-center bg-slate-100/50">PORT</th>
                                            <th class="p-4 border-black text-center bg-slate-100/50">PROD</th>
                                            <th class="p-4 border-black text-center bg-slate-100/50">PIHAK</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @php $unitIdx = 0; @endphp
                                        @foreach($skema->kelompokPekerjaan as $kp)
                                            @foreach($kp->unitKompetensi as $unit)
                                                @php
                                                    $uTemplate = $content['unit_kompetensi'][$unitIdx] ?? [];
                                                @endphp
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="p-6 border-slate-100">
                                                        <div class="font-black text-slate-900">{{ $unit->kode_unit }}</div>
                                                        <div class="text-[10px] font-bold text-slate-400 mt-1 lowercase truncate w-48">{{ $unit->judul_unit }}</div>
                                                    </td>
                                                    <td class="p-6 border-slate-100">
                                                        <textarea name="content[unit_kompetensi][{{ $unitIdx }}][bukti]" class="w-full p-3 bg-white border-2 border-slate-100 rounded-xl focus:border-indigo-500 outline-none text-xs font-bold leading-relaxed lowercase" rows="3">{{ $uTemplate['bukti'] ?? '' }}</textarea>
                                                    </td>
                                                    <td class="p-2 border-slate-100 text-center bg-indigo-50/20"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][L]" value="1" {{ ($uTemplate['L'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600"></td>
                                                    <td class="p-2 border-slate-100 text-center bg-indigo-50/20"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][TL]" value="1" {{ ($uTemplate['TL'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600"></td>
                                                    <td class="p-2 border-slate-100 text-center bg-indigo-50/20"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][T]" value="1" {{ ($uTemplate['T'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600"></td>
                                                    
                                                    <td class="p-2 border-slate-100 text-center"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][observasi]" value="1" {{ ($uTemplate['observasi'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-slate-400"></td>
                                                    <td class="p-2 border-slate-100 text-center"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][kegiatan_terstruktur]" value="1" {{ ($uTemplate['kegiatan_terstruktur'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-slate-400"></td>
                                                    <td class="p-2 border-slate-100 text-center"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][tanya_jawab]" value="1" {{ ($uTemplate['tanya_jawab'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-slate-400"></td>
                                                    <td class="p-2 border-slate-100 text-center"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][verifikasi_portofolio]" value="1" {{ ($uTemplate['verifikasi_portofolio'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-slate-400"></td>
                                                    <td class="p-2 border-slate-100 text-center"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][reviu_produk]" value="1" {{ ($uTemplate['reviu_produk'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-slate-400"></td>
                                                    <td class="p-2 border-slate-100 text-center"><input type="checkbox" name="content[unit_kompetensi][{{ $unitIdx }}][verifikasi_pihak_ketiga]" value="1" {{ ($uTemplate['verifikasi_pihak_ketiga'] ?? '') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-slate-400"></td>
                                                </tr>
                                                @php $unitIdx++; @endphp
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="mt-16 flex justify-center pb-20">
                        <button type="submit" class="px-16 py-6 bg-slate-900 text-white rounded-full font-black shadow-2xl hover:bg-indigo-600 transition-all duration-500 hover:scale-105">
                            <i class="fas fa-save mr-3"></i> SIMPAN SEMUA TEMPLATE MAPA-01
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>
