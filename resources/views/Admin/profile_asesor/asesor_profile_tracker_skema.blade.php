@php
    // Helper Checkmark (modified to fit inside ball)
    if (!function_exists('renderCheckmark')) {
        function renderCheckmark() {
            return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>';
        }
    }

    // Unify data source: $schemeTimelineData (from showTrackerSkema) OR $timelineData (from showTracker)
    $finalTimelineData = $schemeTimelineData ?? $timelineData ?? [];
@endphp

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

  <x-navbar.navbar-admin />
  
  <div class="flex min-h-[calc(100vh-80px)]">
    
    <x-sidebar.sidebar_profile_asesor :asesor="$asesor" />

    <main class="ml-[22%] h-[calc(100vh-80px)] overflow-y-auto p-8 bg-gray-50 flex-1">
      
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] p-10 border border-gray-100 h-fit">
        
        {{-- Header Page --}}
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ route('admin.asesor.tracker', $asesor->id_asesor) }}" class="absolute left-0 top-1 text-gray-500 hover:text-blue-600 transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-800">Detail Aktivitas Skema</h1>
                <p class="text-sm text-gray-500 mt-1">Lacak progres asesmen untuk skema ini.</p>
            </div>
        </div>
        @if(isset($jadwal))
        <p class="text-sm text-gray-500 mb-8">
            Timeline aktivitas untuk Skema: 
            <span class="font-semibold text-gray-700">{{ $jadwal->skema->nama_skema ?? '-' }}</span> 
            (Jadwal: {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d M Y') }})
        </p>
        @endif

        @if(empty($finalTimelineData))
            <div class="text-center py-20 text-gray-500">
                <i class="fas fa-info-circle text-4xl mb-3 text-gray-300"></i>
                <p>Belum ada data aktivitas timeline.</p>
            </div>
        @else
            <div class="max-w-3xl mx-auto px-4 mt-8">
                <ol class="relative z-10 border-l-2 border-gray-200 ml-4 space-y-8">
                    @foreach($finalTimelineData as $item)
                        @php
                            // Handle variations in data structure
                            $isCompleted = $item['is_completed'] ?? false;
                            // Check status text for "Selesai" or "Kompeten" or "Diterima" as completion indicators if is_completed not set
                            if (!isset($item['is_completed'])) {
                                $statusLower = strtolower($item['status_text'] ?? '');
                                if (str_contains($statusLower, 'selesai') || str_contains($statusLower, 'diterima') || str_contains($statusLower, 'kompeten')) {
                                    $isCompleted = true;
                                }
                            }
                        @endphp

                        <li class="mb-10 ml-8 relative">
                            <div class="absolute -left-11 mt-1.5 w-6 h-6 bg-white rounded-full border-4 border-gray-200 flex items-center justify-center
                                        {{ $isCompleted ? '!border-green-500 bg-green-500' : '' }}">
                                @if($isCompleted) {!! renderCheckmark() !!} @endif
                            </div>
                            
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">
                                {{ $item['title'] ?? 'Aktivitas' }}
                                
                                @php
                                    $statusText = $item['status_text'] ?? '';
                                    $isShortStatus = in_array(strtolower($statusText), ['selesai', 'terjadwal', 'berlangsung', 'kompeten', 'belum kompeten', 'diterima', 'menunggu', 'siap dibuat', 'dapat dicetak']);
                                @endphp

                                @if($isCompleted)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">Selesai</span>
                                @elseif($isShortStatus && $statusText != '-')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded ml-3">{{ $statusText }}</span>
                                @endif
                            </h3>
                            
                            @if(!$isShortStatus && $statusText != '-' && $statusText != '')
                                <p class="mb-2 text-sm font-normal text-gray-500">
                                    {{ $statusText }}
                                </p>
                            @endif
                            
                            <p class="text-xs text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $item['date'] ?? '-' }}
                            </p>

                            @if(isset($item['action_url']))
                                <div class="mt-3">
                                    <a href="{{ $item['action_url'] }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-medium rounded-md hover:bg-blue-100 transition border border-blue-100">
                                        <i class="fas fa-eye mr-1.5"></i> Lihat Detail
                                    </a>
                                </div>
                            @endif

                            {{-- Sub Items (if any) --}}
                            @if(isset($item['sub_items']) && is_array($item['sub_items']))
                                <ul class="mt-2 space-y-1">
                                    @foreach($item['sub_items'] as $sub)
                                        <li class="text-xs text-gray-600 flex items-center">
                                            <i class="fas fa-angle-right mr-2 text-gray-400"></i>
                                            <span class="font-medium mr-1">{{ $sub['title'] }}:</span> {{ $sub['status'] }} ({{ $sub['date'] }})
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

      </div>
    </main>
  </div>
</body>
</html>