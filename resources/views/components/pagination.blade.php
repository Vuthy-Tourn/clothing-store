@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-20 flex justify-center">
        <div class="flex items-center gap-2" id="paginationContainer">
            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-item pagination-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-item pagination-arrow pagination-prev"
                    data-page="{{ $paginator->currentPage() - 1 }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $start = max(1, $current - 2);
                $end = min($last, $current + 2);
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="pagination-item pagination-number" data-page="1">1</a>
                @if ($start > 2)
                    <span class="pagination-item pagination-dots">...</span>
                @endif
            @endif

            {{-- Page Numbers Range --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <span class="pagination-item pagination-active">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="pagination-item pagination-number"
                        data-page="{{ $page }}">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <span class="pagination-item pagination-dots">...</span>
                @endif
                <a href="{{ $paginator->url($last) }}" class="pagination-item pagination-number"
                    data-page="{{ $last }}">{{ $last }}</a>
            @endif

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-item pagination-arrow pagination-next"
                    data-page="{{ $paginator->currentPage() + 1 }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="pagination-item pagination-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif

            {{-- Page Info --}}
            <div class="ml-6 text-sm text-gray-500">
                {{ __('messages.showing') }} {{ $paginator->firstItem() ?? 0 }} {{ __('messages.to') }}
                {{ $paginator->lastItem() ?? 0 }} {{ __('messages.of') }}
                {{ $paginator->total() }} {{ __('messages.products') }}
            </div>
        </div>
    </div>
@endif