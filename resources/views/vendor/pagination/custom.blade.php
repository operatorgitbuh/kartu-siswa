@if ($paginator->hasPages())
    <div class="flex items-center gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="p-1.5 text-slate-300 bg-white border border-slate-100 rounded-lg cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="p-1.5 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition shadow-sm active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="hidden md:flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-1 text-slate-400 text-xs">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="min-w-[32px] h-[32px] flex items-center justify-center rounded-lg text-[11px] font-bold bg-indigo-600 text-white shadow-md shadow-indigo-100">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="min-w-[32px] h-[32px] flex items-center justify-center rounded-lg text-[11px] font-bold bg-white text-slate-500 border border-slate-200 hover:border-indigo-300 hover:text-indigo-600 transition-all">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="p-1.5 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition shadow-sm active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
            </a>
        @else
            <span class="p-1.5 text-slate-300 bg-white border border-slate-100 rounded-lg cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
            </span>
        @endif
    </div>
@endif