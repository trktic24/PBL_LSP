
@extends('layouts.app-profil')

@section('title', 'Jadwal Asesmen')
@section('content')

    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Asesmen</h1>
            <p class="text-gray-600">Lorem Ipsum dolor<br>sit amet</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Search Box Simple -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-2 flex justify-end">
        <div class="relative w-78">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                   id="searchInput"
                   placeholder="Search" 
                   class="block w-full pl-9 pr-4 py-2 text-sm border border-gray-700 rounded-full bg-white focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-600">
        </div>
    </div>

    <!-- Table Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="bg-white rounded-t-3xl shadow-lg overflow-hidden border border-gray-200">
            <!-- Table Header -->
            <div class="bg-yellow-50 border-b-2 border-gray-900">
                <div class="grid grid-cols-5 gap-4 px-6 py-4">
                    <div class="text-sm font-bold text-gray-900 text-left">Skema Sertifikasi</div>
                    <div class="text-sm font-bold text-gray-900 text-center">Pendaftaran</div>
                    <div class="text-sm font-bold text-gray-900 text-center">Tanggal Asesmen</div>
                    <div class="text-sm font-bold text-gray-900 text-center">TUK</div>
                    <div class="text-sm font-bold text-gray-900 text-center">Status</div>
                </div>
            </div>

            <!-- Table Body -->
            <div id="tableBody">
                @forelse($jadwal as $item)
                    @php
                        // Tentukan warna status badge
                        $statusClass = '';
                        $canRegister = false;
                        
                        switch($item->Status_jadwal) {
                            case 'Terjadwal':
                                $statusClass = 'bg-teal-200 text-teal-800'; 
                                $canRegister = true;
                                break;
                            case 'Full':
                                $statusClass = 'bg-yellow-200 text-yellow-800';
                                break;
                            case 'Selesai':
                                $statusClass = 'bg-gray-200 text-gray-700';
                                break;
                            case 'Dibatalkan':
                                $statusClass = 'bg-blue-200 text-blue-700';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-700';
                        }
                    @endphp
                    
                    <div class="jadwal-row bg-yellow-50 border-b border-gray-200 hover:bg-yellow-100 transition-colors duration-150"
                         data-search="{{ strtolower($item->skema->nama_skema ?? '') }} {{ strtolower($item->masterTuk->nama_lokasi ?? '') }} {{ strtolower($item->Status_jadwal) }}">
                        <div class="grid grid-cols-5 gap-4 px-6 py-4 items-center">
                            <!-- Skema Sertifikasi -->
                            <div class="text-sm text-gray-900 text-left">
                                {{ $item->skema->nama_skema ?? 'N/A' }}
                            </div>
                            
                            <!-- Pendaftaran -->
                            <div class="text-sm text-gray-900 text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d') }} - 
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                            </div>
                            
                            <!-- Tanggal Asesmen -->
                            <div class="text-sm text-gray-900 text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}
                            </div>
                            
                            <!-- TUK -->
                            <div class="text-sm text-gray-900 text-center">
                                {{ $item->masterTuk->nama_lokasi ?? 'N/A' }}
                            </div>
                            
                            <!-- Status -->
                            <div class="text-center">
                                 @if(in_array($item->Status_jadwal, ['Terjadwal']))
                                    <a href="{{ route('jadwal.show', $item->id_jadwal) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusClass }} transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer">
                                         {{ $item->Status_jadwal }} - Lihat Detail
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-semibold {{ $statusClass }} opacity-90 cursor-not-allowed">
                                         {{ $item->Status_jadwal }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-gray-500 text-lg">Belum ada jadwal asesmen tersedia.</p>
                    </div>
                @endforelse
            </div>

            <!-- No Result Message -->
            <div id="noResult" class="px-6 py-12 text-center bg-yellow-50 hidden">
                <p class="text-gray-500 text-lg">Tidak ada jadwal yang sesuai dengan pencarian.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Search -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('.jadwal-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const searchText = row.getAttribute('data-search');
                
                if (searchText.includes(searchValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show/hide no result message
            document.getElementById('noResult').classList.toggle('hidden', visibleCount > 0);
        });
    </script>
@endsection