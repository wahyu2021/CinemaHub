@props([
    'type' => 'success',
    'message' => ''
])

@php
$styles = [
    'success' => 'bg-green-500/20 border-green-500/30 text-green-400',
    'error' => 'bg-red-500/20 border-red-500/30 text-red-400',
    'warning' => 'bg-yellow-500/20 border-yellow-500/30 text-yellow-400',
    'info' => 'bg-blue-500/20 border-blue-500/30 text-blue-400',
];

$icons = [
    'success' => 'fa-check-circle',
    'error' => 'fa-times-circle',
    'warning' => 'fa-exclamation-triangle',
    'info' => 'fa-info-circle',
];
@endphp

<div class="mb-6 px-6 py-4 rounded-xl border flex items-center gap-3 {{ $styles[$type] ?? $styles['info'] }}">
    <i class="fas {{ $icons[$type] ?? $icons['info'] }}"></i>
    {{ $message ?: $slot }}
</div>
