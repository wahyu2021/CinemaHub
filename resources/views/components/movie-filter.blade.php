@props([
    'category',
    'genres',
    'selectedGenre',
    'selectedSort',
    'selectedYear'
])

<div class="relative z-50 mb-12">
    <form method="GET" action="{{ route('movies.index') }}" class="relative" id="filter-form">
        <input type="hidden" name="category" value="{{ $category }}">
        <input type="hidden" name="genre" id="genre-input" value="{{ $selectedGenre }}">

        <div class="glass p-2 rounded-2xl border border-white/10 flex flex-col md:flex-row gap-2 shadow-2xl bg-black/40 backdrop-blur-xl">
            {{-- Genre Filter --}}
            <div class="relative flex-1 group">
                <button type="button" onclick="toggleGenrePanel()"
                    class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-primary/50 rounded-xl px-5 flex items-center justify-between transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="flex flex-col items-start">
                            <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Genre</span>
                            <span class="text-sm font-medium text-white truncate max-w-[150px]" id="genre-display-text">All Genres</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300" id="genre-arrow"></i>
                </button>

                <div id="genre-panel" class="hidden absolute top-full left-0 w-full md:w-[600px] mt-4 p-6 bg-[#050505] rounded-2xl border border-white/20 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[100] ring-1 ring-white/5">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 rounded-full blur-[50px] pointer-events-none"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-600/10 rounded-full blur-[50px] pointer-events-none"></div>

                    <div class="flex justify-between items-center mb-4 relative z-10">
                        <span class="text-sm text-gray-400 font-display uppercase tracking-widest font-bold">Select Genres</span>
                        <span class="text-xs text-gray-600 italic">Multiple selection allowed</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-64 overflow-y-auto pr-2 custom-scrollbar relative z-10">
                        @php $selectedGenres = !empty($selectedGenre) ? explode(',', $selectedGenre) : []; @endphp

                        <label class="cursor-pointer col-span-2 md:col-span-3 mb-2">
                            <input type="checkbox" id="all-genres-checkbox" class="peer hidden" value=""
                                {{ empty($selectedGenre) ? 'checked' : '' }} onchange="handleAllGenresClick(this)">
                            <div class="w-full px-4 py-3 rounded-xl border transition-all duration-300 flex items-center justify-center gap-3
                                bg-white/5 border-white/10 text-gray-400 hover:bg-white/10 hover:border-white/30
                                peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white peer-checked:shadow-[0_0_20px_rgba(229,9,20,0.5)]">
                                <i class="fas fa-globe text-sm"></i>
                                <span class="font-bold text-sm tracking-wide">ALL GENRES</span>
                            </div>
                        </label>

                        @foreach($genres as $genre)
                            <label class="cursor-pointer">
                                <input type="checkbox" class="peer hidden genre-checkbox"
                                    value="{{ $genre['id'] }}" data-name="{{ $genre['name'] }}"
                                    {{ in_array($genre['id'], $selectedGenres) ? 'checked' : '' }}
                                    onchange="handleGenreClick(this)">
                                <div class="px-4 py-2.5 rounded-lg border transition-all duration-200 text-sm flex items-center justify-between
                                    bg-[#0f0f0f] border-white/5 text-gray-400 hover:bg-[#1a1a1a] hover:text-white hover:border-white/20
                                    peer-checked:bg-primary/10 peer-checked:border-primary/50 peer-checked:text-primary">
                                    <span>{{ $genre['name'] }}</span>
                                    <i class="fas fa-check text-[10px] opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/10 flex justify-end gap-3 relative z-10">
                        <button type="button" onclick="clearGenres()"
                            class="px-4 py-2 rounded-lg text-xs font-bold text-gray-500 hover:text-white hover:bg-white/5 transition-colors">
                            RESET
                        </button>
                        <button type="button" onclick="toggleGenrePanel()"
                            class="px-6 py-2 rounded-lg bg-white text-black text-xs font-bold hover:bg-gray-200 transition-colors shadow-[0_0_10px_rgba(255,255,255,0.3)]">
                            DONE
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sort Filter --}}
            <div class="relative w-full md:w-64 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                        <i class="fas fa-sort-amount-down"></i>
                    </div>
                </div>
                <select name="sort_by" onchange="this.form.submit()"
                    class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-blue-500/50 rounded-xl pl-16 pr-10 text-white appearance-none cursor-pointer focus:outline-none transition-all">
                    <option value="popularity.desc" {{ $selectedSort == 'popularity.desc' ? 'selected' : '' }} class="bg-dark">Popularity (High to Low)</option>
                    <option value="popularity.asc" {{ $selectedSort == 'popularity.asc' ? 'selected' : '' }} class="bg-dark">Popularity (Low to High)</option>
                    <option value="vote_average.desc" {{ $selectedSort == 'vote_average.desc' ? 'selected' : '' }} class="bg-dark">Rating (High to Low)</option>
                    <option value="release_date.desc" {{ $selectedSort == 'release_date.desc' ? 'selected' : '' }} class="bg-dark">Release Date (Newest)</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                </div>
                <span class="absolute top-2 left-16 text-[10px] uppercase font-bold text-gray-500 tracking-wider pointer-events-none">Sort By</span>
            </div>

            {{-- Year Filter --}}
            <div class="relative w-full md:w-48 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <select name="year" onchange="this.form.submit()"
                    class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-purple-500/50 rounded-xl pl-16 pr-10 text-white appearance-none cursor-pointer focus:outline-none transition-all">
                    <option value="" class="bg-dark">All Years</option>
                    @for($y = date('Y') + 1; $y >= 1990; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }} class="bg-dark">{{ $y }}</option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                </div>
                <span class="absolute top-2 left-16 text-[10px] uppercase font-bold text-gray-500 tracking-wider pointer-events-none">Year</span>
            </div>

            <button type="submit"
                class="h-14 px-8 bg-primary hover:bg-primary-glow text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40 flex items-center justify-center gap-2 group">
                <span>Filter</span>
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
        genreDisplayText.textContent = 'All Genres';
        genreDisplayText.classList.remove('text-primary');
        genreDisplayText.classList.add('text-white');
    } else if (selectedValues.length === 1) {
        genreDisplayText.textContent = selectedNames[0];
        genreDisplayText.classList.add('text-primary');
    } else {
        genreDisplayText.textContent = `${selectedValues.length} Genres`;
        genreDisplayText.classList.add('text-primary');
    }
}

function toggleGenrePanel() {
    genrePanel.classList.toggle('hidden');
    genreArrow.classList.toggle('rotate-180');
}

function clearGenres() {
    genreCheckboxes.forEach(cb => cb.checked = false);
    allGenresCheckbox.checked = true;
    updateGenreState();
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.group') && !genrePanel.classList.contains('hidden')) {
        toggleGenrePanel();
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
