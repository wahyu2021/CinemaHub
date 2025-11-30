@props(['category', 'selectedGenre', 'genres', 'selectedSort', 'selectedYear'])

<div class="relative z-50 mb-12">
    <form method="GET" action="{{ route('movies.index') }}" class="relative" id="filter-form">
        <input type="hidden" name="category" value="{{ $category }}">
        <input type="hidden" name="genre" id="genre-input" value="{{ $selectedGenre }}">

        <div class="glass p-2 rounded-2xl border border-white/10 flex flex-col md:flex-row gap-2 shadow-2xl bg-black/40 backdrop-blur-xl">

            <div class="relative flex-1 group">
                <button type="button" onclick="toggleGenrePanel()"
                    class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-primary/50 rounded-xl px-5 flex items-center justify-between transition-all duration-300 group-focus-within:border-primary/50 group-focus-within:shadow-[0_0_15px_rgba(229,9,20,0.2)]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ __('messages.genre') }}</span>
                            <span class="text-sm font-medium text-white truncate max-w-[150px]" id="genre-display-text">{{ __('messages.all_genres') }}</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300" id="genre-arrow"></i>
                </button>

                <div id="genre-panel"
                    class="hidden absolute top-full left-0 w-full md:w-[600px] mt-4 p-6 bg-[#050505] rounded-2xl border border-white/20 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[100] transform origin-top transition-all duration-300 ring-1 ring-white/5">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 rounded-full blur-[50px] pointer-events-none"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-600/10 rounded-full blur-[50px] pointer-events-none"></div>

                    <div class="flex justify-between items-center mb-4 relative z-10">
                        <span class="text-sm text-gray-400 font-display uppercase tracking-widest font-bold">{{ __('messages.select_genres') }}</span>
                        <span class="text-xs text-gray-600 italic">{{ __('messages.multiple_selection') }}</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-64 overflow-y-auto pr-2 custom-scrollbar relative z-10">
                        @php $selectedGenres = !empty($selectedGenre) ? explode(',', $selectedGenre) : []; @endphp

                        <label class="cursor-pointer group/label col-span-2 md:col-span-3 mb-2">
                            <input type="checkbox" id="all-genres-checkbox" class="peer hidden" value=""
                                {{ empty($selectedGenre) ? 'checked' : '' }} onchange="handleAllGenresClick(this)">
                            <div class="w-full px-4 py-3 rounded-xl border transition-all duration-300 flex items-center justify-center gap-3
                                bg-white/5 border-white/10 text-gray-400 hover:bg-white/10 hover:border-white/30 hover:shadow-[0_0_15px_rgba(255,255,255,0.1)]
                                peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white peer-checked:shadow-[0_0_20px_rgba(229,9,20,0.5)]">
                                <i class="fas fa-globe text-sm"></i>
                                <span class="font-bold text-sm tracking-wide">{{ __('messages.all_genres') }}</span>
                            </div>
                        </label>

                        @foreach ($genres as $genre)
                            <label class="cursor-pointer group/label">
                                <input type="checkbox" class="peer hidden genre-checkbox"
                                    value="{{ $genre['id'] }}" data-name="{{ $genre['name'] }}"
                                    {{ in_array($genre['id'], $selectedGenres) ? 'checked' : '' }}
                                    onchange="handleGenreClick(this)">
                                <div class="px-4 py-2.5 rounded-lg border transition-all duration-200 text-sm flex items-center justify-between
                                    bg-[#0f0f0f] border-white/5 text-gray-400 hover:bg-[#1a1a1a] hover:text-white hover:border-white/20
                                    peer-checked:bg-primary/10 peer-checked:border-primary/50 peer-checked:text-primary">
                                    <span>{{ $genre['name'] }}</span>
                                    <i class="fas fa-check text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity transform scale-50 peer-checked:scale-100"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/10 flex justify-end gap-3 relative z-10">
                        <button type="button" onclick="clearGenres()" class="px-4 py-2 rounded-lg text-xs font-bold text-gray-500 hover:text-white hover:bg-white/5 transition-colors">
                            {{ __('messages.reset') }}
                        </button>
                        <button type="button" onclick="toggleGenrePanel()" class="px-6 py-2 rounded-lg bg-white text-black text-xs font-bold hover:bg-gray-200 transition-colors shadow-[0_0_10px_rgba(255,255,255,0.3)]">
                            {{ __('messages.done') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sort Filter --}}
            <div class="relative w-full md:w-64 group">
                <input type="hidden" name="sort_by" id="sort-input" value="{{ $selectedSort }}">
                <button type="button" onclick="toggleSortPanel()"
                    class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-blue-500/50 rounded-xl px-5 flex items-center justify-between transition-all duration-300 group-focus-within:border-blue-500/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                            <i class="fas fa-sort-amount-down"></i>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ __('messages.sort_by') }}</span>
                            <span class="text-sm font-medium text-white truncate max-w-[150px]" id="sort-display-text">
                                @switch($selectedSort)
                                    @case('popularity.asc') {{ __('messages.sort_popularity_asc') }} @break
                                    @case('vote_average.desc') {{ __('messages.sort_rating_desc') }} @break
                                    @case('release_date.desc') {{ __('messages.sort_date_desc') }} @break
                                    @default {{ __('messages.sort_popularity_desc') }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300" id="sort-arrow"></i>
                </button>

                <div id="sort-panel" class="hidden absolute top-full left-0 w-full mt-4 p-2 bg-[#050505] rounded-2xl border border-white/20 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[100] ring-1 ring-white/5 origin-top">
                    <div class="flex flex-col gap-1">
                        @foreach([
                            'popularity.desc' => __('messages.sort_popularity_desc'),
                            'popularity.asc' => __('messages.sort_popularity_asc'),
                            'vote_average.desc' => __('messages.sort_rating_desc'),
                            'release_date.desc' => __('messages.sort_date_desc')
                        ] as $val => $label)
                        <button type="button" onclick="handleSortClick('{{ $val }}')"
                            class="w-full px-4 py-3 rounded-xl text-left text-sm transition-all duration-200 flex items-center justify-between group/item
                            {{ $selectedSort == $val ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white border border-transparent' }}">
                            <span>{{ $label }}</span>
                            @if($selectedSort == $val) <i class="fas fa-check text-xs"></i> @endif
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Year Filter --}}
            <div class="relative w-full md:w-48 group">
                <input type="hidden" name="year" id="year-input" value="{{ $selectedYear }}">
                <button type="button" onclick="toggleYearPanel()"
                    class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-purple-500/50 rounded-xl px-5 flex items-center justify-between transition-all duration-300 group-focus-within:border-purple-500/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ __('messages.year') }}</span>
                            <span class="text-sm font-medium text-white truncate" id="year-display-text">
                                {{ $selectedYear ?: __('messages.all_years') }}
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300" id="year-arrow"></i>
                </button>

                <div id="year-panel" class="hidden absolute top-full right-0 md:left-auto md:right-0 w-64 mt-4 p-4 bg-[#050505] rounded-2xl border border-white/20 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[100] ring-1 ring-white/5 origin-top">
                    <div class="grid grid-cols-3 gap-2 max-h-64 overflow-y-auto custom-scrollbar pr-2">
                        <button type="button" onclick="handleYearClick('')"
                            class="col-span-3 px-3 py-2 rounded-lg text-xs font-bold transition-all duration-200 border
                            {{ !$selectedYear ? 'bg-purple-500 text-white border-purple-500 shadow-lg shadow-purple-500/20' : 'bg-white/5 text-gray-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                            {{ __('messages.all_years') }}
                        </button>
                        @for ($y = date('Y') + 1; $y >= 1990; $y--)
                            <button type="button" onclick="handleYearClick('{{ $y }}')"
                                class="px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 border
                                {{ $selectedYear == $y ? 'bg-purple-500/20 text-purple-400 border-purple-500/50' : 'bg-[#0f0f0f] text-gray-400 border-white/5 hover:bg-[#1a1a1a] hover:text-white hover:border-white/20' }}">
                                {{ $y }}
                            </button>
                        @endfor
                    </div>
                </div>
            </div>

            <button type="submit"
                class="h-14 px-8 bg-primary hover:bg-primary-glow text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40 flex items-center justify-center gap-2 group">
                <span>{{ __('messages.filter') }}</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const genreInput = document.getElementById('genre-input');
    const genreDisplayText = document.getElementById('genre-display-text');
    const allGenresCheckbox = document.getElementById('all-genres-checkbox');
    const genreCheckboxes = document.querySelectorAll('.genre-checkbox');
    const genrePanel = document.getElementById('genre-panel');
    const genreArrow = document.getElementById('genre-arrow');
    const sortPanel = document.getElementById('sort-panel');
    const sortArrow = document.getElementById('sort-arrow');
    const yearPanel = document.getElementById('year-panel');
    const yearArrow = document.getElementById('year-arrow');
    const filterForm = document.getElementById('filter-form');

    function handleAllGenresClick(el) {
        if (el.checked) {
            genreCheckboxes.forEach(cb => cb.checked = false);
        } else {
            const anyOtherChecked = Array.from(genreCheckboxes).some(cb => cb.checked);
            if (!anyOtherChecked) el.checked = true;
        }
        updateGenreState();
    }

    function handleGenreClick(el) {
        if (el.checked) {
            allGenresCheckbox.checked = false;
        } else {
            const anyOtherChecked = Array.from(genreCheckboxes).some(cb => cb.checked);
            if (!anyOtherChecked) allGenresCheckbox.checked = true;
        }
        updateGenreState();
    }

    function updateGenreState() {
        const checkedBoxes = Array.from(genreCheckboxes).filter(cb => cb.checked);
        const selectedValues = checkedBoxes.map(cb => cb.value);
        const selectedNames = checkedBoxes.map(cb => cb.dataset.name);

        genreInput.value = selectedValues.join(',');

        if (allGenresCheckbox.checked || selectedValues.length === 0) {
            genreDisplayText.textContent = "{{ __('messages.all_genres') }}";
            genreDisplayText.classList.remove('text-primary');
            genreDisplayText.classList.add('text-white');
        } else if (selectedValues.length === 1) {
            genreDisplayText.textContent = selectedNames[0];
            genreDisplayText.classList.add('text-primary');
        } else {
            genreDisplayText.textContent = `${selectedValues.length} {{ __('messages.genre') }}`;
            genreDisplayText.classList.add('text-primary');
        }
    }

    function toggleGenrePanel() {
        genrePanel.classList.toggle('hidden');
        genreArrow.classList.toggle('rotate-180');
        if (sortPanel && !sortPanel.classList.contains('hidden')) { sortPanel.classList.add('hidden'); sortArrow.classList.remove('rotate-180'); }
        if (yearPanel && !yearPanel.classList.contains('hidden')) { yearPanel.classList.add('hidden'); yearArrow.classList.remove('rotate-180'); }
    }

    function toggleSortPanel() {
        sortPanel.classList.toggle('hidden');
        sortArrow.classList.toggle('rotate-180');
        if (!genrePanel.classList.contains('hidden')) { genrePanel.classList.add('hidden'); genreArrow.classList.remove('rotate-180'); }
        if (yearPanel && !yearPanel.classList.contains('hidden')) { yearPanel.classList.add('hidden'); yearArrow.classList.remove('rotate-180'); }
    }

    function toggleYearPanel() {
        yearPanel.classList.toggle('hidden');
        yearArrow.classList.toggle('rotate-180');
        if (!genrePanel.classList.contains('hidden')) { genrePanel.classList.add('hidden'); genreArrow.classList.remove('rotate-180'); }
        if (sortPanel && !sortPanel.classList.contains('hidden')) { sortPanel.classList.add('hidden'); sortArrow.classList.remove('rotate-180'); }
    }

    function handleSortClick(value) {
        document.getElementById('sort-input').value = value;
        filterForm.submit();
    }

    function handleYearClick(value) {
        document.getElementById('year-input').value = value;
        filterForm.submit();
    }

    function clearGenres() {
        genreCheckboxes.forEach(cb => cb.checked = false);
        allGenresCheckbox.checked = true;
        updateGenreState();
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.group')) {
            if (!genrePanel.classList.contains('hidden')) { genrePanel.classList.add('hidden'); genreArrow.classList.remove('rotate-180'); }
            if (sortPanel && !sortPanel.classList.contains('hidden')) { sortPanel.classList.add('hidden'); sortArrow.classList.remove('rotate-180'); }
            if (yearPanel && !yearPanel.classList.contains('hidden')) { yearPanel.classList.add('hidden'); yearArrow.classList.remove('rotate-180'); }
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (!allGenresCheckbox.checked && Array.from(genreCheckboxes).every(cb => !cb.checked)) {
            allGenresCheckbox.checked = true;
        }
        updateGenreState();
    });
</script>
@endpush
