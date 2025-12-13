<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    
    <x-navbar /> 
    
    <aside class="fixed fixed-sidebar-icon bg-blue-600 shadow-xl rounded-2xl p-2 z-50">
        <nav class="flex flex-col space-y-4">
            
            <a href="{{ route('master_skema') }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl hover:bg-blue-700/70 transition duration-150 transform hover:scale-105" 
               title="Master Skema">
                <i class="fas fa-certificate text-lg"></i>
            </a>
            
            <a href="{{ route('detail_kelompokpekerjaan', $kelompok->id_kelompok_pekerjaan) }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl bg-blue-700 shadow-lg transition duration-150 transform hover:scale-105" 
               title="Kelompok Pekerjaan">
                <i class="fas fa-file-alt text-lg"></i>
            </a>
            
            <a href="{{ route('asesi_profile_tracker') }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl hover:bg-blue-700/70 transition duration-150 transform hover:scale-105" 
               title="Tracker Pendaftaran">
                <i class="fas fa-list-alt text-lg"></i>
            </a>
            
        </nav>
    </aside>

    <main class="content-area p-6">
        
        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">
                <a href="{{ route('master_skema') }}" class="hover:text-blue-600">Master Skema</a> 
                / <a href="{{ route('detail_skema', $kelompok->skema->id_skema) }}" class="hover:text-blue-600">{{ $kelompok->skema->nama_skema }}</a>
                / Detail Kelompok Pekerjaan
            </p>
            <h2 class="text-3xl font-bold text-gray-900">Kelompok Pekerjaan - Unit Kompetensi - Rencana Asesmen</h2>
        </div>

        <div class="relative rounded-xl overflow-hidden shadow-xl mb-8 border border-gray-200">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset($kelompok->skema->gambar ?? 'path/to/default/image.jpg') }}'); filter: brightness(0.3);"></div>
            
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
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Buat Unit Kompetensi Baru</h3>
            
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kode_unit" class="block text-sm font-medium text-gray-700">Kode Unit Kompetensi</label>
                        <input type="text" id="kode_unit" name="kode_unit" placeholder="Masukkan Unit Kompetensi misal: J.620100.004.02" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    </div>
                    <div>
                        <label for="judul_unit" class="block text-sm font-medium text-gray-700">Judul Unit Kompetensi</label>
                        <input type="text" id="judul_unit" name="judul_unit" placeholder="Masukkan Judul misal: Menggunakan struktur data" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    </div>
                </div>

                <div>
                    <label for="form_apl" class="block text-sm font-medium text-gray-700">Buat Form Rencana Asesmen</label>
                    <input type="text" id="form_apl" name="form_apl" placeholder="Masukkan Form misal : FR.APL.01" class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-150">
                        Simpan Unit Kompetensi
                    </button>
                </div>
            </form>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Daftar Unit Kompetensi di {{ $kelompok->nama_kelompok_pekerjaan }}</h3>
            
            <table class="min-w-full text-xs text-left border border-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr class="divide-x divide-gray-200 border-b border-gray-200">
                        <th class="px-4 py-3 font-semibold w-16">No</th>
                        <th class="px-4 py-3 font-semibold">Kode Unit</th>
                        <th class="px-4 py-3 font-semibold">Judul Unit</th>
                        <th class="px-4 py-3 font-semibold w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200">
                    @forelse ($kelompok->unitKompetensi->sortBy('urutan') as $index => $unit)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $unit->kode_unit }}</td>
                            <td class="px-4 py-3">{{ $unit->judul_unit }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="#" class="text-yellow-600 hover:text-yellow-800 mx-1" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="#" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 mx-1" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Kelompok Pekerjaan ini belum memiliki Unit Kompetensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </main>
    
</body>
</html>