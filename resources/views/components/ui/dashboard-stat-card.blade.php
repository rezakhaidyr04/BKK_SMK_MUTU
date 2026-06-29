@props([
    'label',
    'value',
    'color' => 'blue',
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
    'class' => "bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 {$borderColor}
                hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
]) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</p>
            <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
        @isset($icon)
        <div class="w-16 h-16 {{ $iconBg }} rounded-2xl flex items-center justify-center" aria-hidden="true">
            <span class="{{ $iconColor }}">
                {{ $icon }}
            </span>
        </div>
        @endisset
    </div>
    <div class="mt-4">
        @isset($footer)
            {{ $footer }}
        @else
            @if($href && $hrefLabel)
                <a href="{{ $href }}" class="text-sm {{ $linkColor }} font-semibold hover:underline">{{ $hrefLabel }}</a>
            @elseif($footnote)
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $footnote }}</div>
            @endif
        @endisset
    </div>
</div>
