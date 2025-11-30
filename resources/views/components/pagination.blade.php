@props([
    'currentPage' => 1,
    'totalPages' => 1,
    'routeName',
    'routeParams' => []
])

@if($totalPages > 1)
<div class="flex justify-center items-center gap-2 mt-12">
    @if($currentPage > 1)
        <a href="{{ route($routeName, array_merge($routeParams, ['page' => $currentPage - 1])) }}"
            class="w-10 h-10 flex items-center justify-center rounded-full glass hover:bg-primary hover:border-primary transition-all text-white group">
            <i class="fas fa-chevron-left group-hover:-translate-x-0.5 transition-transform"></i>
        </a>
    @endif

    <div class="flex gap-2 bg-black/30 p-1 rounded-full backdrop-blur-md border border-white/5">
        @php
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);
        @endphp

        @if($start > 1)
            <a href="{{ route($routeName, array_merge($routeParams, ['page' => 1])) }}"
                class="w-8 h-8 flex items-center justify-center rounded-full text-sm text-gray-400 hover:text-white hover:bg-white/10 transition-all">1</a>
            @if($start > 2)
                <span class="w-8 h-8 flex items-center justify-center text-gray-600">...</span>
            @endif
        @endif

        @for($i = $start; $i <= $end; $i++)
            <a href="{{ route($routeName, array_merge($routeParams, ['page' => $i])) }}"
                class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold transition-all {{ $i == $currentPage ? 'bg-primary text-white shadow-lg shadow-primary/40 scale-110' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                {{ $i }}
            </a>
        @endfor

        @if($end < $totalPages)
            @if($end < $totalPages - 1)
                <span class="w-8 h-8 flex items-center justify-center text-gray-600">...</span>
            @endif
            <a href="{{ route($routeName, array_merge($routeParams, ['page' => $totalPages])) }}"
                class="w-8 h-8 flex items-center justify-center rounded-full text-sm text-gray-400 hover:text-white hover:bg-white/10 transition-all">{{ $totalPages }}</a>
        @endif
    </div>

    @if($currentPage < $totalPages)
        <a href="{{ route($routeName, array_merge($routeParams, ['page' => $currentPage + 1])) }}"
            class="w-10 h-10 flex items-center justify-center rounded-full glass hover:bg-primary hover:border-primary transition-all text-white group">
            <i class="fas fa-chevron-right group-hover:translate-x-0.5 transition-transform"></i>
        </a>
    @endif
</div>
@endif
