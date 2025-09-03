@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-8">
        <ul class="inline-flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 text-gray-400 bg-gray-200 dark:bg-gray-800 rounded-lg cursor-not-allowed">
                        ←
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       class="px-3 py-2 bg-[#e50914] text-white rounded-lg shadow hover:bg-[#b20710] transition">
                        ←
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Dots --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-2 text-gray-500">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-4 py-2 bg-[#e50914] text-white font-semibold rounded-lg shadow">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   class="px-4 py-2 bg-white dark:bg-black/90 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-[#e50914] hover:text-white transition shadow">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                       class="px-3 py-2 bg-[#e50914] text-white rounded-lg shadow hover:bg-[#b20710] transition">
                        →
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 text-gray-400 bg-gray-200 dark:bg-gray-800 rounded-lg cursor-not-allowed">
                        →
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
