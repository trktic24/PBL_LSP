<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lacak Aktivitas Asesor | LSP Polines</title> 

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

  <x-navbar />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-12 border border-gray-100 min-h-full">
        
        <h2 class="text-2xl font-bold text-gray-800 mb-8">Lacak Aktivitas Asesor</h2>

        {{-- Jika timelineData kosong, tampilkan pesan --}}
        @if(empty($timelineData))
            <div class="text-center py-10 text-gray-500">
                <i class="fas fa-info-circle text-4xl mb-3 text-gray-300"></i>
                <p>Belum ada data aktivitas timeline.</p>
            </div>
        @else
            <div class="max-w-4xl mx-auto">
                <div class="relative">
                    
                    {{-- Garis Timeline Vertical --}}
                    <div class="absolute left-8 top-8 bottom-8 w-0.5 bg-gray-300" aria-hidden="true"></div>

                    {{-- Loop Timeline Data --}}
                    @foreach($timelineData as $item)
                    <div class="relative pl-24 pb-12 group last:pb-0">
                        
                        {{-- Ikon Bulat Besar --}}
                        <div class="absolute left-0 top-0 w-16 h-16 rounded-full bg-gray-100 border-4 border-white shadow-sm flex items-center justify-center z-10">
                            <i class="{{ $item['icon'] ?? 'fas fa-circle' }} text-gray-500 text-2xl"></i>
                        </div>

                        {{-- Indikator Status Kecil (Hijau Checklist) --}}
                        <div class="absolute left-[2.2rem] top-20 w-6 h-6 rounded-full bg-green-500 border-2 border-white z-20 flex items-center justify-center">
                            <i class="fas fa-check text-white text-[10px]"></i>
                        </div>

                        {{-- Konten --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $item['title'] ?? 'Aktivitas' }}</h3>
                            
                            <div class="mt-1">
                                <p class="text-sm font-semibold text-gray-700">{{ $item['date'] ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $item['status_text'] ?? '' }}</p>
                            </div>

                            {{-- Sub-items (jika ada) --}}
                            @if(!empty($item['sub_items']))
                                <div class="mt-4 space-y-4">
                                    @foreach($item['sub_items'] as $sub)
                                        <div>
                                            <h4 class="text-md font-bold text-gray-800">{{ $sub['title'] }}</h4>
                                            <p class="text-sm font-semibold text-gray-700">{{ $sub['date'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $sub['status'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Footer Text (jika ada) --}}
                            @if(!empty($item['footer_text']))
                                <div class="mt-2 text-sm text-gray-600">
                                    {{ $item['footer_text'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        @endif

      </div>
    </main>
  </div>
</body>
</html>