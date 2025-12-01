@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center space-x-1">
        {{-- Tombol Panah "Previous" --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-200 bg-gray-50 text-gray-400 cursor-default">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Tombol Angka --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator (...) --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-200 bg-gray-50 text-gray-400 cursor-default">
                    {{ $element }}
                </span>
            @endif

            {{-- Tombol Angka Aktif --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-blue-600 bg-blue-600 text-white text-xs font-semibold z-10">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Panah "Next" --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-200 bg-gray-50 text-gray-400 cursor-default">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        @endif
    </nav>
@endif