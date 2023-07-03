@php
    $previousClasses = 'inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50';
    $nextClasses = 'inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50';
    $currentClasses = 'inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-sm text-gray-700 bg-indigo-100';
@endphp

<div class="flex items-center justify-between mt-4">
    <div>
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-sm text-gray-500 bg-white cursor-default">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="{{ $previousClasses }}" rel="prev">
                {!! __('pagination.previous') !!}
            </a>
        @endif
    </div>

    <div>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="{{ $nextClasses }}" rel="next">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-sm text-gray-500 bg-white cursor-default">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </div>
</div>
