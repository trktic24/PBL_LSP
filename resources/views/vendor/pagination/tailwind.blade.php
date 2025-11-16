@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Ringkasan Jumlah Data (Bisa dihapus jika tidak dibutuhkan) --}}
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
            <div>
                {{-- PREVIOUS BUTTON (KUNING) --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-black bg-yellow-400 border border-yellow-500 rounded-l-md leading-5 hover:bg-yellow-500 transition duration-150 ease-in-out focus:outline-none focus:ring ring-yellow-300">
                        {!! __('pagination.previous') !!}
                    </a>
                @endif

                {{-- LOOP PAGES --}}
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
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-gray-700 bg-white border border-gray-300 leading-5 hover:text-blue-500 transition duration-150 ease-in-out focus:outline-none focus:ring ring-gray-300">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- NEXT BUTTON (KUNING) --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-black bg-yellow-400 border border-yellow-500 rounded-r-md leading-5 hover:bg-yellow-500 transition duration-150 ease-in-out focus:outline-none focus:ring ring-yellow-300">
                        {!! __('pagination.next') !!}
                    </a>
                @else
                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif