@props([
    'variant' => 'primary',
    'size'    => 'md',
    'href'    => null,
    'type'    => 'button',
    'loading' => false,
    'disabled' => false,
])

@php
$sizeClass = match($size) {
    'xs' => 'ui-btn-xs',
    'sm' => 'ui-btn-sm',
    'md' => 'ui-btn-md',
    'lg' => 'ui-btn-lg',
    'xl' => 'ui-btn-xl',
    default => 'ui-btn-md',
};

$variantClass = match($variant) {
    'secondary' => 'ui-btn-secondary',
    'ghost'     => 'ui-btn-ghost',
    'danger'    => 'ui-btn-danger',
    'success'   => 'ui-btn-success',
    'outline'   => 'ui-btn-outline',
    'white'     => 'ui-btn-white',
    'company'   => 'ui-btn-primary',
    default     => 'ui-btn-primary',
};

$baseClass = 'ui-btn';
$disabledClass = ($loading || $disabled) ? 'opacity-60 cursor-not-allowed pointer-events-none' : 'ui-btn-hover';
$classes = implode(' ', [$baseClass, $variantClass, $sizeClass, $disabledClass]);
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($loading)
    <svg class="animate-spin -ml-0.5 w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    @endif
    {{ $slot }}
</a>
@else
<button type="{{ $type }}" {{ ($loading || $disabled) ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
    @if($loading)
    <svg class="animate-spin -ml-0.5 w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    @endif
    {{ $slot }}
</button>
@endif
