@php
    // --- Logika Status Aktif ---
    
    // PENTING: Menggunakan '/admin/' di request()->is() untuk mencocokkan URL path yang benar
    $isSkemaActive = request()->is('admin/master/skema*');
    $isAsesorActive = request()->is('admin/master_asesor') || 
                      request()->is('admin/add_asesor*') || 
                      request()->is('admin/edit_asesor*');
    $isAsesiActive = request()->is('admin/master/asesi*');
    $isMasterScheduleActive = request()->is('admin/master/jadwal*');
    $isCategoryActive = request()->is('admin/master/category*'); 
    $isBeritaActive = request()->is('admin/master/berita*');
    $isTukActive = request()->is('admin/master/tuk*'); // Koreksi: Pastikan TUK juga punya /admin
    $isMitraActive = request()->is('admin/master/mitra*');

    $isMasterActive = $isSkemaActive || $isAsesorActive || $isAsesiActive || $isMasterScheduleActive || $isCategoryActive || $isBeritaActive || $isMitraActive;
    
    // PENTING: Menggunakan 'admin.' pada request()->routeIs() karena ini membandingkan NAMA route
    $isScheduleActive = request()->routeIs('admin.schedule_admin');
    $isNotifActive = request()->routeIs('admin.notifications');
    $isProfileActive = request()->routeIs('admin.profile_admin');
    
    // Cek Dashboard secara terpisah karena routeis('dashboard') di kode Anda tidak konsisten
    $isDashboardActive = request()->routeIs('admin.dashboard');

@endphp

<style>
    [x-cloak] { display: none !important; }
</style>

<nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
        </a>
    </div>

    <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
        
        <a href="{{ route('admin.dashboard') }}" class="relative h-full flex items-center transition
            {{ $isDashboardActive ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            Dashboard
            @if ($isDashboardActive)
            <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
            @endif
        </a>

        <div x-data="{ open: false }" class="relative h-full flex items-center">
            <button @click="open = !open" class="flex items-center transition {{ $isMasterActive ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                <span>Master</span>
                <i :class="open ? 'fas fa-caret-up ml-2.5 text-sm' : 'fas fa-caret-down ml-2.5 text-sm'"></i>
            </button>

            <div 
                x-show="open" @click.away="open = false"
                class="absolute left-0 top-full mt-2 w-96 bg-white shadow-lg rounded-xl border border-gray-100 z-20 p-2"
                x-transition x-cloak>
                
                <div class="grid grid-cols-2 gap-x-8">
                    
                    <div class="space-y-1">
                        <a href="{{ route('admin.master_category') }}" 
                            class="block px-4 py-2 {{ $isCategoryActive ? 'text-blue-600 bg-blue-50 font-semibold rounded-lg' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg' }}">
                            Categories
                        </a>
                        <a href="{{ route('admin.master_skema') }}" 
                            class="block px-4 py-2 {{ $isSkemaActive ? 'text-blue-600 bg-blue-50 font-semibold rounded-lg' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg' }}">
                            Skema
                        </a>
                            <a href="{{ route('admin.master_schedule') }}" 
                            class="block px-4 py-2 {{ $isMasterScheduleActive ? 'text-blue-600 bg-blue-50 font-semibold rounded-lg' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg' }}">
                            Schedule
                        </a>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('admin.master_asesor') }}" 
                            class="block px-4 py-2 {{ $isAsesorActive ? 'text-blue-600 bg-blue-50 font-semibold rounded-lg' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg' }}">
                            Asesor
                        </a>
                        <a href="{{ route('admin.master_asesi') }}" 
                            class="block px-4 py-2 {{ $isAsesiActive ? 'text-blue-600 bg-blue-50 font-semibold rounded-lg' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg' }}">
                            Asesi
                        </a>
                        <a href="{{ route('admin.master_berita') }}" 
                            class="block px-4 py-2 {{ $isBeritaActive ? 'text-blue-600 bg-blue-50 font-semibold rounded-lg' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg' }}">
                            Berita
                        </a>
                        <a href="{{ route('admin.master_mitra') }}" 
                            class="block px-4 py-2 {{ $isMitraActive ? 'text-blue-600 bg-blue-50 font-semibold rounded-lg' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg' }}">
                            Mitra
                        </a>
                        </div>
                </div>
            </div>
            @if ($isMasterActive)
                <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
            @endif
        </div>

        <a href="{{ route('admin.schedule_admin') }}" class="relative h-full flex items-center transition {{ $isScheduleActive ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            Jadwal
            @if ($isScheduleActive)
                <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
            @endif
        </a>
        
        <a href="{{ route('admin.master_tuk') }}" class="relative h-full flex items-center transition {{ $isTukActive ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
            TUK
            @if ($isTukActive)
                <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
            @endif
        </a>
    </div>

    <div class="flex items-center space-x-6">
        <a href="{{ route('admin.notifications') }}" 
            class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)] 
                    hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),inset-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
            <i class="fas fa-bell text-xl {{ $isNotifActive ? 'text-blue-600' : 'text-gray-600' }}"></i>
            <span class="absolute top-2 right-2">
                <span class="relative flex w-2 h-2">
                    <span class="absolute inline-flex w-full h-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex w-2 h-2 rounded-full bg-red-500"></span>
                </span>
            </span>
        </a>

        <div x-data="{ open: false }" class="relative">
            
            <button @click="open = !open" 
                    class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
                            hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all z-20 relative">
                <span class="{{ $isProfileActive ? 'text-blue-600' : 'text-gray-800' }} font-semibold text-base mr-5 whitespace-nowrap">
                    {{ Auth::check() ? (in_array(Auth::user()->role->nama_role, ['admin', 'superadmin']) ? 'Admin LSP' : Auth::user()->username) : 'Guest' }}
                </span>
                
                <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner flex-shrink-0 flex items-center justify-center bg-blue-600 text-white font-bold text-sm select-none">
                    @php
                        $initials = 'AD'; // Default: Admin Default
                        
                        if (Auth::check()) {
                            $email = Auth::user()->email ?? 'admin.default@lsp.com';
                            
                            // 1. Ambil bagian email sebelum '@'
                            $prefix = explode('@', $email)[0];
                            
                            // 2. Ganti titik atau underscore dengan spasi, lalu pisahkan kata
                            $words = explode(' ', str_replace(['.', '_'], ' ', $prefix));
                            
                            // 3. Ambil inisial: Huruf pertama dari 2 kata pertama
                            if (count($words) >= 2) {
                                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            } else {
                                // Jika hanya satu kata, ambil 2 huruf pertama
                                $initials = strtoupper(substr($prefix, 0, 2));
                            }
                        }
                    @endphp
                    {{ $initials }}
                </div>
            </button>
            
            <div x-show="open" 
                    @click.away="open = false"
                    class="absolute right-0 top-[25px] min-w-full bg-white shadow-lg rounded-b-3xl border border-gray-100 z-10"
                    x-transition 
                    x-cloak>

                <div class="pt-10 px-2 pb-2"> 
                    
                    <a href="{{ route('admin.profile_admin') }}" 
                        class="block px-4 py-3 text-sm flex items-center rounded-lg
                                {{ $isProfileActive ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600 hover:font-semibold' }}">
                        <i class="fas fa-user-circle w-5 mr-2 opacity-70"></i>
                        Profile Account
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        
                        <a href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); if(confirm('Anda yakin ingin logout dari Website Admin LSP Polines?')) { this.closest('form').submit(); }"
                            class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:font-semibold flex items-center rounded-lg">
                            <i class="fas fa-sign-out-alt w-5 mr-2 opacity-70"></i>
                            Logout
                        </a>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</nav>