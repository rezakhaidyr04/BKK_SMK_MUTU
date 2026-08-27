@props([
    'label',
    'value',
    'color' => 'blue',
    'size' => 'md',
    'footnote' => null,
    'href' => null,
    'hrefLabel' => null,
])

@php
$borderColor = 'border-slate-200';
$iconBg = 'bg-blue-50';
$iconColor = 'text-blue-600';

$linkColor = match($color) {
    'green'  => 'text-green-600 dark:text-green-400',
    'purple' => 'text-violet-600 dark:text-violet-400',
    'orange' => 'text-amber-600 dark:text-amber-400',
    default  => 'text-blue-600 dark:text-blue-400',
};
@endphp

<div {{ $attributes->merge([
    'class' => "bg-white relative overflow-hidden rounded-lg border {$borderColor} p-4 shadow-sm sm:p-5"
]) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            @php
                $labelClass = $size === 'sm' ? 'mb-1 text-xs font-semibold text-slate-500 dark:text-neutral-400' : 'mb-1 text-sm font-semibold text-slate-500 dark:text-neutral-400';
                $valueClass = $size === 'sm' ? 'text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl dark:text-white' : 'text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white';
            @endphp
            <p class="{{ $labelClass }}">{{ $label }}</p>
            <p class="{{ $valueClass }}">{{ $value }}</p>
        </div>
        @isset($icon)
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $iconBg }} sm:h-14 sm:w-14" aria-hidden="true">
            <span class="{{ $iconColor }}">
                {{ $icon }}
            </span>
        </div>
        @endisset
    </div>
    <div class="mt-3 border-t border-slate-100 pt-3 text-xs dark:border-neutral-800 sm:mt-4">
        @isset($footer)
            {{ $footer }}
        @else
            @if($href && $hrefLabel)
                <a href="{{ $href }}" class="{{ $linkColor }} inline-flex items-center gap-1 font-bold hover:underline">{{ $hrefLabel }}</a>
            @elseif($footnote)
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $footnote }}</div>
            @endif
        @endisset
    </div>
</div>
