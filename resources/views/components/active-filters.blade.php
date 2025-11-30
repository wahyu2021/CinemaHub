@props([
    'selectedGenre',
    'selectedYear',
    'selectedSort',
    'genres'
])

@if($selectedGenre || $selectedYear || $selectedSort !== 'popularity.desc')
<div class="flex flex-wrap gap-2 mb-8 animate-fade-in">
    @if($selectedGenre)
        @foreach(explode(',', $selectedGenre) as $gId)
            @php $gName = collect($genres)->firstWhere('id', $gId)['name'] ?? 'Genre'; @endphp
            <span class="px-3 py-1 rounded-lg bg-primary/20 border border-primary/30 text-white text-xs flex items-center gap-2">
                {{ $gName }}
            </span>
        @endforeach
    @endif

    @if($selectedYear)
        <span class="px-3 py-1 rounded-lg bg-purple-500/20 border border-purple-500/30 text-white text-xs flex items-center gap-2">
            Year: {{ $selectedYear }}
        </span>
    @endif

    <a href="{{ route('movies.index') }}"
        class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-red-500/20 hover:border-red-500/30 text-xs transition-all flex items-center gap-2">
        <i class="fas fa-times"></i> Reset All
    </a>
</div>
@endif
