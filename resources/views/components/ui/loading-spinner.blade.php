@props(['size' => 'md', 'color' => 'brand'])

@php
    $sizes = [
        'sm' => 'w-4 h-4 border-2',
        'md' => 'w-6 h-6 border-3',
        'lg' => 'w-8 h-8 border-4',
        'xl' => 'w-12 h-12 border-4',
    ];
    
    $colors = [
        'brand' => 'border-brand-600 border-t-transparent',
        'white' => 'border-white border-t-transparent',
        'gray' => 'border-gray-400 border-t-transparent',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $colorClass = $colors[$color] ?? $colors['brand'];
@endphp

<div class="inline-block {{ $sizeClass }} {{ $colorClass }} rounded-full animate-spin" role="status" aria-label="Loading">
    <span class="sr-only">Loading...</span>
</div>
