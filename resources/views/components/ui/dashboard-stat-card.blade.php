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
$borderColor = match($color) {
    'green'  => 'border-green-500',
    'purple' => 'border-purple-500',
    'yellow' => 'border-yellow-500',
    'red'    => 'border-red-500',
    'orange' => 'border-orange-500',
    'indigo' => 'border-indigo-500',
    default  => 'border-blue-500',
};

$iconBg = match($color) {
    'green'  => 'bg-green-100 dark:bg-green-900/30',
    'purple' => 'bg-purple-100 dark:bg-purple-900/30',
    'yellow' => 'bg-yellow-100 dark:bg-yellow-900/30',
    'red'    => 'bg-red-100 dark:bg-red-900/30',
    'orange' => 'bg-orange-100 dark:bg-orange-900/30',
    'indigo' => 'bg-indigo-100 dark:bg-indigo-900/30',
    default  => 'bg-blue-100 dark:bg-blue-900/30',
};

$iconColor = match($color) {
    'green'  => 'text-green-600 dark:text-green-400',
    'purple' => 'text-purple-600 dark:text-purple-400',
    'yellow' => 'text-yellow-600 dark:text-yellow-400',
    'red'    => 'text-red-600 dark:text-red-400',
    'orange' => 'text-orange-600 dark:text-orange-400',
    'indigo' => 'text-indigo-600 dark:text-indigo-400',
    default  => 'text-blue-600 dark:text-blue-400',
};

$linkColor = match($color) {
    'green'  => 'text-green-600 dark:text-green-400',
    'purple' => 'text-purple-600 dark:text-purple-400',
    'orange' => 'text-orange-600 dark:text-orange-400',
    default  => 'text-blue-600 dark:text-blue-400',
};
@endphp

<div {{ $attributes->merge([
    'class' => "dashboard-surface relative overflow-hidden border-l-4 {$borderColor} p-4 sm:p-5"
]) }}>
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-current to-transparent opacity-20" aria-hidden="true"></div>
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
