<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Judul disesuaikan dengan data Kelompok Pekerjaan -->
    <title>{{ $kelompok->nama_kelompok_pekerjaan }} | Detail Kelompok Pekerjaan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
        [x-cloak] { display: none !important; }
        
        /* 1. Sidebar Ikonik (Dynamic Island Style) */
        .fixed-sidebar-icon {
            width: 60px; 
            top: 85px; 
            left: 5px; 
        }
        
        /* 2. Area Konten Utama - Padding DITINGKATKAN */
        .content-area {
            padding-left: 96px; 
            min-height: 100vh;
        }
        
        /* Style Ikon */
        .nav-icon {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Custom style untuk form asesmen, untuk visualisasi */
        .asesmen-form {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            padding: 0 12px;
            border-radius: 9999px; /* rounded-full */
            font-size: 0.875rem; /* text-sm */
            font-weight: 600; /* font-semibold */
            color: white;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    
    <!-- 1. Komponen Navbar Atas (Asumsi Anda punya x-navbar) -->
    <!-- Tidak perlu menyertakan kode x-navbar di sini karena sudah ada di file terpisah -->
    <nav class="bg-white shadow-md fixed top-0 w-full z-40 p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-blue-600">SertifikasiApp</div>
        <div class="flex items-center space-x-4">
            <button class="text-gray-600 hover:text-blue-600">
                <i class="fas fa-bell"></i>
            </button>
            <a href="#" class="flex items-center space-x-2">
                <img class="w-8 h-8 rounded-full object-cover" src="https://placehold.co/32x32/1e40af/ffffff?text=AD" alt="Admin Profile">
                <span class="text-sm font-medium">Admin</span>
            </a>
        </div>
    </nav>
    
    <!-- 2. Fixed Sidebar Navigasi Ikonik (Dynamic Island Style) -->
    <aside class="fixed fixed-sidebar-icon bg-blue-600 shadow-xl rounded-2xl p-2 z-50">
        <nav class="flex flex-col space-y-4">
            
            <!-- Tautan 1: Master Skema -->
            <a href="{{ route('master_skema') }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl hover:bg-blue-700/70 transition duration-150 transform hover:scale-105" 
               title="Master Skema">
                <i class="fas fa-certificate text-lg"></i>
            </a>
            
            <!-- Tautan 2: Kelompok Pekerjaan (Icon 2) -->
            <!-- ICON INI AKTIF: Terhubung ke halaman ini -->
            <a href="{{ route('detail_kelompokpekerjaan', $kelompok->id_kelompok_pekerjaan) }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl bg-blue-700 shadow-lg transition duration-150 transform hover:scale-105" 
               title="Kelompok Pekerjaan">
                <i class="fas fa-file-alt text-lg"></i>
            </a>
            
            <!-- Tautan 3: Tracker Asesi -->
            <a href="{{ route('asesi_profile_tracker') }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl hover:bg-blue-700/70 transition duration-150 transform hover:scale-105" 
               title="Tracker Pendaftaran">
                <i class="fas fa-list-alt text-lg"></i>
            </a>
            
        </nav>
    </aside>

    <!-- 3. Konten Utama -->
    <main class="content-area pt-20 p-6">
        
        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">
                <a href="{{ route('master_skema') }}" class="hover:text-blue-600">Master Skema</a> 
                / <a href="{{ route('detail_skema', $kelompok->skema->id_skema) }}" class="hover:text-blue-600">{{ $kelompok->skema->nama_skema }}</a>
                / Detail Kelompok Pekerjaan
            </p>
            <h2 class="text-3xl font-bold text-gray-900">Kelompok Pekerjaan - Unit Kompetensi - Rencana Asesmen</h2>
        </div>

        <!-- Header Kelompok Pekerjaan -->
        <div class="relative rounded-xl overflow-hidden shadow-xl mb-8 border border-gray-200">
            <!-- Background Image dengan Overlay -->
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://placehold.co/1200x200/2563eb/ffffff?text={{ urlencode('KELOMPOK PEKERJAAN') }}'); filter: brightness(0.3);"></div>
            
            <div class="relative p-6 md:p-10 text-white flex flex-col justify-end min-h-[200px]">
                <div class="absolute top-4 right-4 text-xs font-semibold px-3 py-1 rounded-full bg-blue-600/80 backdrop-blur-sm">
                    {{ $kelompok->skema->category?->nama_kategori ?? 'Tanpa Kategori' }}
                </div>
                
                <p class="text-lg font-medium opacity-80 mb-2">Skema: {{ $kelompok->skema->nama_skema }}</p>
                <h1 class="text-4xl font-extrabold mb-2 break-words max-w-3xl">
                    {{ $kelompok->nama_kelompok_pekerjaan }}
                </h1>
                
                <div class="text-base italic opacity-90 max-w-4xl">
                    Bagian dari Skema Nomor: {{ $kelompok->skema->nomor_skema }}
                </div>
            </div>
        </div>
        <!-- End Header Kelompok Pekerjaan -->

        <!-- Form Tambah Unit Kompetensi (Sesuai image_2f61fa.png) -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Buat Unit Kompetensi Baru</h3>
            
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kolom 1: Kode Unit -->
                    <div>
                        <label for="kode_unit" class="block text-sm font-medium text-gray-700">Kode Unit Kompetensi</label>
                        <input type="text" id="kode_unit" name="kode_unit" placeholder="Masukkan Unit Kompetensi misal: J.620100.004.02" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    </div>
                    <!-- Kolom 2: Judul Unit -->
                    <div class="md:col-span-1">
                        <label for="judul_unit" class="block text-sm font-medium text-gray-700">Judul Unit Kompetensi</label>
                        <input type="text" id="judul_unit" name="judul_unit" placeholder="Masukkan Judul misal: Menggunakan struktur data" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    </div>
                     <!-- Kolom 3: Form Rencana Asesmen -->
                    <div>
                        <label for="form_apl" class="block text-sm font-medium text-gray-700">Form Rencana Asesmen</label>
                        <select id="form_apl" name="form_apl" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border bg-white">
                            <option value="">Pilih Form Asesmen (Opsional)</option>
                            <!-- Loop data form asesmen dummy -->
                            @foreach ($formAsesmen as $form)
                                <option value="{{ $form['kode'] }}">{{ $form['kode'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-150 transform hover:scale-[1.01]">
                        <i class="fas fa-plus mr-2"></i> Simpan Unit Kompetensi
                    </button>
                </div>
            </form>
        </div>
        <!-- End Form Tambah Unit Kompetensi -->

        <!-- Unit Kompetensi Table -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom 1: Daftar Unit Kompetensi -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Daftar Unit Kompetensi</h3>
                
                <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr class="divide-x divide-gray-200 border-b border-gray-200">
                            <th class="px-4 py-3 font-semibold w-12">No</th>
                            <th class="px-4 py-3 font-semibold w-32">Kode Unit</th>
                            <th class="px-4 py-3 font-semibold">Judul Unit</th>
                            <th class="px-4 py-3 font-semibold w-20 text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($kelompok->unitKompetensi->sortBy('urutan') as $index => $unit)
                            <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-blue-700">{{ $unit->kode_unit }}</td>
                                <td class="px-4 py-3">{{ $unit->judul_unit }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <a href="#" class="text-yellow-600 hover:text-yellow-800 mx-1" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="#" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Unit Kompetensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 mx-1" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Kelompok Pekerjaan ini belum memiliki Unit Kompetensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Kolom 2: Form Rencana Asesmen -->
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Formulir Rencana Asesmen</h3>
                <p class="text-sm text-gray-600 mb-4">Daftar Formulir yang tersedia untuk proses Asesmen Skema ini:</p>
                
                <div class="space-y-3">
                    @foreach ($formAsesmen as $form)
                        <div class="flex items-center space-x-3 p-3 {{ $form['warna'] }} rounded-lg shadow-md hover:shadow-lg transition duration-150">
                            <i class="fas fa-file-pdf text-white text-lg"></i>
                            <span class="text-white font-medium text-sm">{{ $form['kode'] }}</span>
                            <span class="ml-auto text-white text-xs opacity-80">(Tersedia)</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t">
                    <button type="button" class="w-full px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition duration-150">
                        Kelola Formulir Asesmen
                    </button>
                </div>
            </div>
        </div>
        
    </main>
    
</body>
</html>