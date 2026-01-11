<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Template FR.IA.02 | LSP Polines</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style> 
        body { font-family: 'Quicksand', sans-serif; background-color: #f8fafc; } 
        .glass-panel {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .form-input {
            width: 100%;
            border-radius: 1rem;
            border: 2px solid #e2e8f0;
            padding: 1rem;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="text-slate-800">
    <div class="min-h-screen flex flex-col">
        <x-navbar.navbar-admin />
        
        <main class="flex-1 py-12 px-6">
            <div class="max-w-5xl mx-auto">
                
                <!-- Breadcrumb & Header -->
                <div class="mb-8">
                    <a href="{{ route('admin.skema.template.list', [$skema->id_skema, 'FR.IA.02']) }}" class="inline-flex items-center text-slate-500 hover:text-blue-600 font-semibold mb-4 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Template
                    </a>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">Master Template FR.IA.02</h1>
                            <p class="text-slate-500 mt-1">Atur Skenario Tugas Praktik & Observasi Demonstrasi</p>
                        </div>
                        <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold border border-blue-100">
                            <i class="fas fa-layer-group mr-2"></i> {{ $skema->nama_skema }}
                        </span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100/50 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.skema.template.ia02.store', [$skema->id_skema, $id_jadwal]) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Left Column: Main Editor -->
                        <div class="lg:col-span-2 space-y-8">
                            
                            <!-- Section: Skenario -->
                            <div class="glass-panel p-8">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-800">Instruksi / Skenario <span class="text-red-500">*</span></h3>
                                        <p class="text-slate-400 text-sm">Instruksi kerja yang harus dilakukan Asesi</p>
                                    </div>
                                </div>
                                <textarea name="skenario" rows="8" class="form-input font-medium text-slate-700 leading-relaxed" placeholder="Contoh: Peserta diminta untuk melakukan instalasi sistem operasi...">{{ $content['skenario'] ?? '' }}</textarea>
                            </div>

                            <!-- Section: Peralatan -->
                            <div class="glass-panel p-8">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-800">Perlengkapan & Peralatan <span class="text-red-500">*</span></h3>
                                        <p class="text-slate-400 text-sm">Alat dan bahan yang perlu disiapkan</p>
                                    </div>
                                </div>
                                <textarea name="peralatan" rows="5" class="form-input font-medium text-slate-700 leading-relaxed" placeholder="Contoh: 1. Laptop, 2. Koneksi Internet...">{{ $content['peralatan'] ?? '' }}</textarea>
                            </div>

                        </div>

                        <!-- Right Column: Settings & Save -->
                        <div class="space-y-8">
                            
                            <!-- Section: Waktu -->
                            <div class="glass-panel p-8">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Waktu Pengerjaan <span class="text-red-500">*</span></h3>
                                </div>
                                
                                <label class="block text-sm font-semibold text-slate-600 mb-2">Durasi Pengerjaan</label>
                                <div class="relative">
                                    <input type="text" name="waktu" value="{{ $content['waktu'] ?? '02:00:00' }}" class="form-input text-center font-bold text-xl tracking-wider" placeholder="HH:MM:SS">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-sm font-semibold">
                                        JAM
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-2 text-center">Format: Jam:Menit:Detik (Contoh: 02:00:00)</p>
                            </div>

                            <!-- Info Box -->
                            <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-lg shadow-blue-200">
                                <h4 class="font-bold flex items-center gap-2 mb-3">
                                    <i class="fas fa-info-circle"></i> Sinkronisasi
                                </h4>
                                <p class="text-sm text-blue-100 leading-relaxed mb-4">
                                    Perubahan template ini akan otomatis diterapkan ke Asesi yang <strong>belum mengisi</strong> formulir IA.02.
                                </p>
                                <p class="text-sm text-blue-100 leading-relaxed">
                                    Data Asesi yang sudah tersimpan <strong>tidak akan berubah</strong> (aman).
                                </p>
                            </div>

                            <!-- Save Button -->
                            <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                                <i class="fas fa-save"></i> Simpan Template
                            </button>

                        </div>
                    </div>

                </form>
            </div>
        </main>
    </div>
</body>
</html>
