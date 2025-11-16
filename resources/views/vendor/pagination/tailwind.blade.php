@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
        {{-- Ringkasan Jumlah Data (dihilangkan untuk fokus pada tombol) --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>
        </div>

        {{-- NAVIGATION LINKS (TOMBOL NEXT/PREVIOUS DAN ANGKA) --}}
        <div class="flex items-center">
            <div class="relative z-0 inline-flex shadow-sm rounded-md"> 
                
                {{-- TOMBOL FIRST PAGE (PANAH GANDA KIRI: << - PUTIH) --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-md leading-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                    </span>
                @else
                    <a href="{{ $paginator->url(1) }}" class="relative inline-flex items-center px-2 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-l-md leading-5 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none focus:ring ring-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                    </a>
                @endif
                
                {{-- PREVIOUS BUTTON (PANAH TUNGGAL KIRI: < - PUTIH) --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 -ml-px">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none focus:ring ring-gray-300 -ml-px">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @endif

                {{-- LOOP PAGES (TOMBOL ANGKA) --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">
                                {{ $element }}
                            </span>
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            {{-- ACTIVE PAGE (WARNA BIRU) --}}
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-white bg-blue-600 border border-blue-600 cursor-default leading-5">
                                        {{ $page }}
                                    </span>
                                </span>
                            {{-- INACTIVE PAGE (WARNA PUTIH/GRAY) --}}
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none focus:ring ring-gray-300">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- NEXT BUTTON (PANAH TUNGGAL KANAN: > - PUTIH) --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-semibold text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none focus:ring ring-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                @else
                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                @endif
                
                {{-- TOMBOL LAST PAGE (PANAH GANDA KANAN: >> - PUTIH) --}}
                @php
                    $lastPage = $paginator->lastPage();
                @endphp
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->url($lastPage) }}" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-r-md leading-5 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none focus:ring ring-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7m-8 0l7-7-7-7"></path></svg>
                    </a>
                @else
                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-md leading-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7m-8 0l7-7-7-7"></path></svg>
                    </span>
                @endif

            </div>
        </div>
    </nav>
@endif