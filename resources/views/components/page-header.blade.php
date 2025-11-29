@props([
    'title',
    'subtitle' => ''
])

<div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
    <div>
        <h1 class="text-4xl md:text-5xl font-display font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
            {{ $title }}
        </h1>
        @if($subtitle)
            <p class="text-gray-400 font-light flex items-center gap-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                {{ $subtitle }}
            </p>
        @endif
    </div>
    {{ $slot }}
</div>
