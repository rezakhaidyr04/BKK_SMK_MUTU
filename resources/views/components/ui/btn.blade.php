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
    'xs' => 'px-3 py-1.5 text-xs gap-1.5',
    'sm' => 'px-4 py-2 text-sm gap-2',
    'lg' => 'px-6 py-3 text-base gap-2.5',
    'xl' => 'px-8 py-4 text-lg gap-3',
    default => 'px-5 py-2.5 text-sm gap-2',
};

$variantClass = match($variant) {
    'secondary' => 'bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200 border border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-700 shadow-sm',
    'ghost'     => 'bg-transparent text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800',
    'danger'    => 'bg-danger-600 text-white hover:bg-danger-700 dark:hover:bg-danger-500 shadow-sm',
    'success'   => 'bg-success-600 text-white hover:bg-success-700 dark:hover:bg-success-500 shadow-sm',
    'outline'   => 'bg-transparent text-primary-600 dark:text-primary-400 border border-primary-300 dark:border-primary-700 hover:bg-primary-50 dark:hover:bg-primary-900/30',
    'white'     => 'bg-white text-primary-700 hover:bg-primary-50 shadow-sm',
    'company'   => 'bg-emerald-600 text-white hover:bg-emerald-700 dark:hover:bg-emerald-500 shadow-lg',
    default     => 'bg-primary-600 text-white hover:bg-primary-700 dark:hover:bg-primary-500 shadow-sm',
};

$baseClass = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 ease-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900 active:scale-[0.97] select-none';
$disabledClass = ($loading || $disabled) ? 'opacity-60 cursor-not-allowed pointer-events-none' : 'hover:-translate-y-0.5';
$classes = implode(' ', [$baseClass, $variantClass, $sizeClass, $disabledClass]);
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($loading)
    <svg class="animate-spin -ml-0.5 w-4 h-4" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    @endif
    {{ $slot }}
</a>
@else
<button type="{{ $type }}" {{ ($loading || $disabled) ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
    @if($loading)
    <svg class="animate-spin -ml-0.5 w-4 h-4" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    @endif
    {{ $slot }}
</button>
@endif
