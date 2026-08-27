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
$palette = match($color) {
    'green'  => ['iconBg' => 'bg-green-100',  'iconClr' => 'text-green-600',  'linkClr' => 'text-green-600'],
    'purple' => ['iconBg' => 'bg-violet-100', 'iconClr' => 'text-violet-600', 'linkClr' => 'text-violet-600'],
    'orange' => ['iconBg' => 'bg-amber-100',  'iconClr' => 'text-amber-600',  'linkClr' => 'text-amber-600'],
    'red'    => ['iconBg' => 'bg-red-100',    'iconClr' => 'text-red-500',    'linkClr' => 'text-red-500'],
    'yellow' => ['iconBg' => 'bg-yellow-100', 'iconClr' => 'text-yellow-600', 'linkClr' => 'text-yellow-600'],
    'indigo' => ['iconBg' => 'bg-blue-100',   'iconClr' => 'text-blue-600',   'linkClr' => 'text-blue-600'],
    default  => ['iconBg' => 'bg-blue-100',   'iconClr' => 'text-blue-600',   'linkClr' => 'text-blue-600'],
};

$labelClass = 'mb-1 text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-neutral-500';
$valueClass = $size === 'sm'
    ? 'text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl'
    : 'text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl';
@endphp

<div {{ $attributes->merge([
    'class' => 'bg-white relative overflow-hidden rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all duration-200 dark:bg-neutral-900 dark:border-neutral-800 flex flex-col flex-1 min-w-[160px]'
]) }}>
    <div class="flex items-start justify-between gap-3 flex-1">
        <div class="min-w-0 flex-1">
            <p class="{{ $labelClass }}">{{ $label }}</p>
            <p class="{{ $valueClass }}">{{ $value }}</p>
        </div>
        @isset($icon)
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $palette['iconBg'] }}" aria-hidden="true">
            <span class="{{ $palette['iconClr'] }}">
                {{ $icon }}
            </span>
        </div>
        @endisset
    </div>
    <div class="mt-4 border-t border-slate-100 pt-3 dark:border-neutral-800">
        @isset($footer)
            {{ $footer }}
        @else
            @if($href && $hrefLabel)
                <a href="{{ $href }}" class="{{ $palette['linkClr'] }} text-xs font-semibold inline-flex items-center gap-1 hover:underline">{{ $hrefLabel }}</a>
            @elseif($footnote)
                <p class="text-xs text-slate-400 dark:text-gray-500">{{ $footnote }}</p>
            @endif
        @endisset
    </div>
</div>
