<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tinjauan Asesmen Asesor | LSP Polines</title> 

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  @php use Illuminate\Support\Facades\Storage; @endphp

  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>

<body class="text-gray-800">

  <x-navbar />
  
  {{-- Layout Utama Flex --}}
  <div class="flex min-h-[calc(100vh-80px)]">
    
    {{-- Sidebar dengan prop asesor --}}
    <x-sidebar_profile_asesor :asesor="$asesor" />

    {{-- Konten Utama --}}
    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full">
        
        <div class="flex flex-col mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Tinjauan Asesmen</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar asesmen yang perlu diverifikasi atau ditinjau.</p>
        </div>

        {{-- Search Bar --}}
        <div class="mb-6 relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" placeholder="Cari skema atau peserta..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 text-sm transition-all">
        </div>

        {{-- Tabel Tinjauan --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 px-6 text-left w-16">No</th>
                        <th class="py-4 px-6 text-left">Skema Sertifikasi</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center w-1/3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-medium">
                    @forelse($tinjauan_data ?? [] as $index => $item)
                    <tr class="border-b border-gray-100 hover:bg-blue-50/30 transition duration-150">
                        <td class="py-4 px-6 text-left">{{ $index + 1 }}</td>
                        <td class="py-4 px-6 text-left text-gray-800">{{ $item->skema ?? 'Nama Skema Tidak Tersedia' }}</td>
                        <td class="py-4 px-6 text-center">
                            @if(isset($item->status) && $item->status == 1)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    <i class="fas fa-check-circle mr-1.5"></i> Sudah Diverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    <i class="fas fa-clock mr-1.5"></i> Belum Diverifikasi
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex item-center justify-center space-x-3">
                                {{-- Tombol Aksi diubah menjadi Link 'a' --}}
                                @if(isset($item->status) && $item->status == 1)
                                    <button disabled class="bg-gray-100 text-gray-400 py-1.5 px-4 rounded-lg text-xs font-bold cursor-not-allowed border border-gray-200 shadow-sm">
                                        Terverifikasi
                                    </button>
                                    <a href="{{ route('asesor.jadwal.asesi', ['id_asesor' => $asesor->id_asesor, 'id_jadwal' => $item->id_jadwal ?? 0]) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-4 rounded-lg text-xs font-bold shadow-md transition-transform hover:scale-105 flex items-center">
                                        <i class="fas fa-file-alt mr-1.5"></i> Laporan
                                    </a>
                                @else
                                    <a href="{{ route('asesor.jadwal.asesi', ['id_asesor' => $asesor->id_asesor, 'id_jadwal' => $item->id_jadwal ?? 0]) }}" 
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white py-1.5 px-4 rounded-lg text-xs font-bold shadow-md transition-transform hover:scale-105 flex items-center">
                                        <i class="fas fa-edit mr-1.5"></i> Verifikasi
                                    </a>
                                    <button disabled class="bg-gray-100 text-gray-400 py-1.5 px-4 rounded-lg text-xs font-bold cursor-not-allowed border border-gray-200">
                                        Laporan
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-clipboard-list text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data tinjauan asesmen.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

      </div>
    </main>
  </div>
</body>
</html>