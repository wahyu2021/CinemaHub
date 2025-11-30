@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'icon' => 'fa-pen',
    'required' => false,
    'autocomplete' => null
])

<div class="group">
    <label for="{{ $name }}" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">
        {{ $label }}
    </label>
    <div class="relative">
        <input 
            id="{{ $name }}" 
            type="{{ $type }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            {{ $attributes->merge(['class' => 'w-full bg-black/30 border border-white/10 rounded-xl px-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all']) }}
        >
        <i class="fas {{ $icon }} absolute right-5 top-4.5 text-gray-600 group-focus-within:text-primary transition-colors"></i>
    </div>
    @error($name)
        <span class="text-red-500 text-sm mt-2 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
        </span>
    @enderror
</div>
