<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lacak Aktivitas Skema | LSP Polines</title> 
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    ::-webkit-scrollbar { width: 0; }
  </style>
</head>
<body class="text-gray-800">

  <x-navbar.navbar_admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 min-h-full">
        
        <div class="flex flex-col mb-8 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Lacak Aktivitas Skema</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih skema/jadwal untuk melihat daftar asesi dan melacak aktivitas mereka.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 overflow-x-auto">
            <table class="min-w-full text-xs text-left border border-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr class="divide-x divide-gray-200 border-b border-gray-200">
                        <th class="px-4 py-3 font-semibold w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-3 font-semibold whitespace-nowrap">Skema Sertifikasi & Jadwal</th>
                        <th class="px-6 py-3 font-semibold text-center w-1/4 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tracker_data ?? [] as $index => $item)
                    <tr class="hover:bg-gray-50 transition divide-x divide-gray-200">
                        <td class="px-4 py-4 align-top">{{ $index + 1 }}</td>
                        
                        <td class="px-6 py-4 text-gray-800 align-top">
                            <div class="font-bold text-gray-900">
                                {{ $item->skema->nama_skema ?? 'Nama Skema Tidak Tersedia' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $item->skema->nomor_skema ?? '-' }} 
                                <span class="block text-xs text-gray-500 mt-1">
                                    <i class="far fa-calendar-alt mr-1"></i> Jadwal: {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center align-top">
                            <div class="flex item-center justify-center space-x-3">
                                <a href="{{ route('admin.asesor.tracker_skema', ['id_asesor' => $asesor->id_asesor, 'id_jadwal' => $item->id_jadwal]) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-xs font-bold shadow-md transition-transform hover:scale-105 flex items-center">
                                    <i class="fas fa-route mr-1.5"></i> Tracker
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-clipboard-list text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada jadwal asesmen yang ditugaskan kepada asesor ini.</p>
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