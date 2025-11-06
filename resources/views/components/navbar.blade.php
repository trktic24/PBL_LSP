<nav class="flex items-center justify-between px-10 bg-white shadow-md sticky top-0 z-10 border-b border-gray-200 h-[80px] relative">
  <div class="flex items-center space-x-4">
    <a href="{{ route('dashboard') }}">
      <img src="{{ asset('images/logo_lsp.jpg') }}" alt="LSP Polines" class="h-16 w-auto">
    </a>
  </div>

  <div class="flex items-center space-x-20 text-base md:text-lg font-semibold relative h-full">
    
    <a href="{{ route('dashboard') }}" class="relative h-full flex items-center transition
       {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
      Dashboard
      @if (request()->routeIs('dashboard'))
      <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
      @endif
    </a>

    <div x-data="{ open: false }" class="relative h-full flex items-center">
      <button @click="open = !open" class="flex items-center transition
        {{ request()->is('master_*') || request()->is('add_skema') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
        <span>Master</span>
        <i :class="open ? 'fas fa-caret-up ml-2.5 text-sm' : 'fas fa-caret-down ml-2.5 text-sm'"></i>
      </button>

      <div x-show="open" @click.away="open = false"
        class="absolute left-0 top-full mt-2 w-44 bg-white shadow-lg rounded-md border border-gray-100 z-20"
        x-transition>
        <a href="{{ route('master_skema') }}" 
           class="block px-4 py-2 
                  {{ request()->routeIs('master_skema') || request()->routeIs('add_skema') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
           Skema
        </a>
        <a href="{{ route('master_asesor') }}" 
           class="block px-4 py-2 
                  {{ request()->routeIs('master_asesor') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
           Asesor
        </a>
        <a href="{{ route('master_asesi') }}" 
           class="block px-4 py-2 
                  {{ request()->routeIs('master_asesi') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
           Asesi
        </a>
        <a href="{{ route('master_schedule') }}" 
           class="block px-4 py-2 
                  {{ request()->routeIs('master_schedule') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
           Schedule
        </a>
      </div>
      @if (request()->is('master_*') || request()->is('add_skema'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
      @endif
    </div>

    <a href="{{ route('schedule_admin') }}" class="relative h-full flex items-center transition
       {{ request()->is('schedule_admin') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
       Schedule
       @if (request()->is('schedule_admin'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
       @endif
    </a>
    <a href="{{ route('tuk_all') }}" class="relative h-full flex items-center transition
       {{ request()->is('tuk_*') || request()->is('add_tuk') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
       TUK
       @if (request()->is('tuk_*') || request()->is('add_tuk'))
        <span class="absolute bottom-[-1px] left-0 w-full h-[3px] bg-blue-600"></span>
       @endif
    </a>
  </div>

  <div class="flex items-center space-x-6">
    <a href="{{ route('notifications') }}" 
       class="relative w-12 h-12 flex items-center justify-center rounded-full bg-white border border-gray-200 shadow-[0_4px_8px_rgba(0,0,0,0.15)] 
              hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),inset-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
      <i class="fas fa-bell text-xl {{ request()->routeIs('notifications') ? 'text-blue-600' : 'text-gray-600' }}"></i>
      <span class="absolute top-2 right-2">
        <span class="relative flex w-2 h-2">
          <span class="absolute inline-flex w-full h-full animate-ping rounded-full bg-red-400 opacity-75"></span>
          <span class="relative inline-flex w-2 h-2 rounded-full bg-red-500"></span>
        </span>
      </span>
    </a>

    <a href="{{ route('profile_admin') }}" 
       class="flex items-center space-x-3 bg-white border border-gray-200 rounded-full pl-5 pr-2 py-1 shadow-[0_4px_8px_rgba(0,0,0,0.1)] 
              hover:shadow-[inset_2px_2px_5px_rgba(0,0,0,0.1),_inset_-2px_-2px_5px_rgba(255,255,255,0.8)] transition-all">
      <span class="{{ request()->routeIs('profile_admin') ? 'text-blue-600' : 'text-gray-800' }} font-semibold text-base mr-2">Admin LSP</span>
      <div class="h-10 w-10 rounded-full border-2 border-gray-300 overflow-hidden shadow-inner">
        <img src="{{ asset('images/profile.jpg') }}" alt="Profil" class="w-full h-full object-cover">
      </div>
    </a>
  </div>
</nav>