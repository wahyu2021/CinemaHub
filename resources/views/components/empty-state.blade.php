@props([
    'icon' => 'fa-film',
    'title' => 'No Data Found',
    'description' => '',
    'actionUrl' => null,
    'actionText' => 'Go Back'
])

<div class="flex flex-col items-center justify-center py-32 text-center">
    <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 animate-pulse">
        <i class="fas {{ $icon }} text-4xl text-gray-600"></i>
    </div>
    <h3 class="text-2xl font-bold text-white mb-2">{{ $title }}</h3>
    @if($description)
        <p class="text-gray-400 mb-8 max-w-md">{{ $description }}</p>
    @endif
    @if($actionUrl)
        <a href="{{ $actionUrl }}"
            class="px-6 py-3 bg-white text-black font-bold rounded-full hover:bg-gray-200 transition-colors">
            {{ $actionText }}
        </a>
    @endif
</div>
