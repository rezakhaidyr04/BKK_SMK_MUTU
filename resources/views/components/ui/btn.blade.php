@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
$classes = 'ui-btn ' . match($variant) {
    'secondary' => 'ui-btn-secondary',
    'ghost' => 'ui-btn-ghost',
    'danger' => 'ui-btn-danger',
    'white' => 'ui-btn-white',
    default => 'ui-btn-primary',
} . ($size === 'sm' ? ' ui-btn-sm' : ($size === 'lg' ? ' ui-btn-lg' : ''));
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
@endif
