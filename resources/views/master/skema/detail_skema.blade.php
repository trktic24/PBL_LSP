<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $skema->nama_skema }} | Detail Skema</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 0; }
        [x-cloak] { display: none !important; }
        
        /* Sidebar & Layout Style */
        .fixed-sidebar-icon {
            width: 60px; 
            top: 85px; 
            left: 5px; 
        }
        .content-area {
            padding-left: 96px; 
            min-height: 100vh;
        }
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
            
            <a href="{{ route('skema.detail', $skema->id_skema) }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl {{ Request::routeIs('skema.detail') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/70' }} transition duration-150 transform hover:scale-105" 
               title="Informasi Skema">
                <i class="fas fa-certificate text-lg"></i>
            </a>
            
            <a href="{{ route('skema.detail.kelompok', $skema->id_skema) }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl {{ Request::routeIs('skema.detail.kelompok') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/70' }} transition duration-150 transform hover:scale-105" 
               title="Kelompok Pekerjaan & Unit">
                <i class="fas fa-layer-group text-lg"></i>
            </a>
            
            <a href="{{ route('skema.detail.bank_soal', $skema->id_skema) }}" 
               class="nav-icon w-11 h-11 text-white rounded-xl {{ Request::routeIs('skema.detail.bank_soal') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-700/70' }} transition duration-150 transform hover:scale-105" 
               title="Bank Soal & Form">
                <i class="fas fa-file-alt text-lg"></i>
            </a>
            
        </nav>
    </aside>

    <main class="content-area p-6">
        
        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">
                <a href="{{ route('master_skema') }}" class="hover:text-blue-600">Master Skema</a> 
                / Detail
            </p>
            <h2 class="text-3xl font-bold text-gray-900">Detail Skema Sertifikasi</h2>
        </div>

        <div class="relative rounded-xl overflow-hidden shadow-xl mb-8 border border-gray-200">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset($skema->gambar ?? 'path/to/default/image.jpg') }}'); filter: brightness(0.3);"></div>
            
            <div class="relative p-6 md:p-10 text-white flex flex-col justify-end min-h-[300px]">
                <div class="absolute top-4 right-4 text-xs font-semibold px-3 py-1 rounded-full bg-blue-600/80 backdrop-blur-sm">
                    {{ $skema->category?->nama_kategori ?? 'Tanpa Kategori' }}
                </div>
                
                <h1 class="text-4xl font-extrabold mb-2 break-words max-w-3xl">
                    {{ $skema->nama_skema }}
                </h1>
                <p class="text-lg font-medium opacity-80 mb-4">{{ $skema->nomor_skema }}</p>
                
                <p class="text-sm italic opacity-90 mb-4 max-w-4xl">
                    {{ $skema->deskripsi_skema }}
                </p>
                
                <div class="text-2xl font-bold text-white">
                    Rp {{ number_format($skema->harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-6 border-b pb-3">
                <h3 class="text-xl font-semibold text-gray-900">Formulir Pendaftaran Sertifikasi</h3>
                <a href="{{ route('edit_skema', $skema->id_skema) }}" class="flex items-center space-x-1 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-sm rounded-md transition">
                    <i class="fas fa-pen text-xs"></i> <span>Edit</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                @forelse ($formAsesmen ?? [] as $form)
                <a href="#" class="w-full text-center py-3 px-2 {{ $form['warna'] }} text-white rounded-lg font-semibold text-sm shadow-md transition-all duration-200 ease-in-out transform hover:scale-[1.03] active:scale-95">
                    {{ $form['kode'] }}
                </a>
                @empty
                <p class="col-span-full text-center text-gray-400 text-sm">Belum ada form asesmen.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 border-b pb-3">Unit Kompetensi</h3>
            
            <table class="min-w-full text-xs text-left border border-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr class="divide-x divide-gray-200 border-b border-gray-200">
                        <th class="px-4 py-3 font-semibold w-16">No</th>
                        <th class="px-4 py-3 font-semibold">Kelompok Pekerjaan</th>
                        <th class="px-4 py-3 font-semibold">Kode Unit</th>
                        <th class="px-4 py-3 font-semibold">Judul Unit</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200">
                    @php 
                        $globalNo = 1; 
                        $currentKelompok = '';
                    @endphp
                    
                    @forelse ($skema->kelompokPekerjaan as $kelompok)
                        @foreach ($kelompok->unitKompetensi->sortBy('urutan') as $unit)
                        <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                            <td class="px-4 py-3">{{ $globalNo++ }}</td>
                            
                            <td class="px-4 py-3 font-medium">
                                @if ($currentKelompok !== $kelompok->nama_kelompok_pekerjaan)
                                    {{ $kelompok->nama_kelompok_pekerjaan }}
                                    @php $currentKelompok = $kelompok->nama_kelompok_pekerjaan; @endphp
                                @endif
                            </td>
                            
                            <td class="px-4 py-3 whitespace-nowrap">{{ $unit->kode_unit }}</td>
                            <td class="px-4 py-3">{{ $unit->judul_unit }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Skema ini belum memiliki Kelompok Pekerjaan atau Unit Kompetensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </main>
    
</body>
</html>