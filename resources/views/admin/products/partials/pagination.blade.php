@if ($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-white">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Page Info -->
            <div class="text-sm text-gray-700">
                {{ __('Showing') }}
                <span class="font-medium">{{ $products->firstItem() }}</span>
                {{ __('to') }}
                <span class="font-medium">{{ $products->lastItem() }}</span>
                {{ __('of') }}
                <span class="font-medium">{{ $products->total() }}</span>
                {{ __('results') }}
            </div>

            <!-- Pagination Links -->
            <nav class="flex items-center space-x-1">
                <!-- Previous Page Link -->
                @if ($products->onFirstPage())
                    <span class="px-3 py-2 rounded-lg text-gray-400 bg-gray-100 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" 
                       onclick="event.preventDefault(); loadPage('{{ $products->previousPageUrl() }}')"
                       class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                @endif

                <!-- Page Numbers -->
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if ($page == $products->currentPage())
                        <span class="px-3 py-2 rounded-lg bg-gray-800 text-white font-medium">
                            {{ $page }}
                        </span>
                    @elseif ($page >= $products->currentPage() - 2 && $page <= $products->currentPage() + 2)
                        <a href="{{ $url }}" 
                           onclick="event.preventDefault(); loadPage('{{ $url }}')"
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-600 transition-colors duration-200">
                            {{ $page }}
                        </a>
                    @elseif ($page == 1 || $page == $products->lastPage())
                        <a href="{{ $url }}" 
                           onclick="event.preventDefault(); loadPage('{{ $url }}')"
                           class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-600 transition-colors duration-200">
                            {{ $page }}
                        </a>
                    @elseif ($page == $products->currentPage() - 3 || $page == $products->currentPage() + 3)
                        <span class="px-3 py-2 text-gray-400">...</span>
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" 
                       onclick="event.preventDefault(); loadPage('{{ $products->nextPageUrl() }}')"
                       class="px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="px-3 py-2 rounded-lg text-gray-400 bg-gray-100 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </nav>

            <!-- Items Per Page Selector -->
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span>{{ __('Items per page:') }}</span>
                <select id="perPageSelect" 
                        onchange="changePerPage(this.value)"
                        class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-gray-500">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>
    </div>
@endif