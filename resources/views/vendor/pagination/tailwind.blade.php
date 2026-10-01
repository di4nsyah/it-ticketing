@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <p class="text-sm text-ink-faint">
            {!! __('Showing') !!}
            @if ($paginator->firstItem())
                <span class="font-medium text-ink-soft">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-medium text-ink-soft">{{ $paginator->lastItem() }}</span>
            @else
                <span class="font-medium text-ink-soft">{{ $paginator->count() }}</span>
            @endif
            {!! __('of') !!}
            <span class="font-medium text-ink-soft">{{ $paginator->total() }}</span>
            {!! __('results') !!}
        </p>

        <div class="inline-flex flex-wrap items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"
                      class="inline-flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-full border border-line bg-surface text-ink-faint opacity-40">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
                   class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border border-line bg-surface text-ink-soft transition duration-200 hover:border-line-strong hover:text-ink">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-full px-2 text-sm text-ink-faint">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="inline-flex h-9 min-w-9 cursor-default items-center justify-center rounded-full border border-ink bg-ink px-3 text-sm font-medium text-canvas">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                               class="inline-flex h-9 min-w-9 cursor-pointer items-center justify-center rounded-full border border-line bg-surface px-3 text-sm font-medium text-ink-soft transition duration-200 hover:border-line-strong hover:text-ink">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
                   class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border border-line bg-surface text-ink-soft transition duration-200 hover:border-line-strong hover:text-ink">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                      class="inline-flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-full border border-line bg-surface text-ink-faint opacity-40">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
