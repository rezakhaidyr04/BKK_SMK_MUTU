@props([
    'href',
    'label',
    'accentColor' => 'blue',
])

@php
$hoverClasses = match($accentColor) {
    'purple' => 'hover:text-purple-600 hover:bg-purple-50 dark:hover:text-purple-400 dark:hover:bg-purple-900/20',
    'green'  => 'hover:text-green-600 hover:bg-green-50 dark:hover:text-green-400 dark:hover:bg-green-900/20',
    'orange' => 'hover:text-orange-600 hover:bg-orange-50 dark:hover:text-orange-400 dark:hover:bg-orange-900/20',
    default  => 'hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-400 dark:hover:bg-blue-900/20',
};
@endphp

<a href="{{ $href }}"
   class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300 {{ $hoverClasses }} px-4 py-3 rounded-xl transition-all duration-200 group">
    @isset($icon)
    <span class="flex-shrink-0 group-hover:scale-110 transition-transform duration-200" aria-hidden="true">
        {{ $icon }}
    </span>
    @endisset
    {{ $label }}
</a>
