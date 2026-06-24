@props(['label', 'value', 'color' => 'blue', 'icon' => null])

@php
$iconBg = match($color) {
    'green' => 'bg-green-100 text-green-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
    'red' => 'bg-red-100 text-red-600',
    'orange' => 'bg-orange-100 text-orange-600',
    'indigo' => 'bg-indigo-100 text-indigo-600',
    default => 'bg-blue-100 text-blue-600',
};
@endphp

<div {{ $attributes->merge(['class' => 'ui-stat-card']) }}>
    <div class="flex items-center justify-between gap-4">
        <div class="min-w-0">
            <p class="ui-stat-card-label">{{ $label }}</p>
            <p class="ui-stat-card-value">{{ $value }}</p>
            @isset($footer)
            <div class="mt-2 text-sm">{{ $footer }}</div>
            @endisset
        </div>
        @if($icon)
        <div class="ui-stat-card-icon {{ $iconBg }}">
            {!! $icon !!}
        </div>
        @endif
    </div>
</div>
