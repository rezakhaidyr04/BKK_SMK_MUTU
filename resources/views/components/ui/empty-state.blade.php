{{--
    Modern Empty State Component
    @props:
      - title (required): Main heading text
      - description: Subtext below the title
      - icon: 'document' | 'search' | 'users' | 'briefcase' | 'bell' | 'inbox' | 'chart'
      - ctaLabel: Button label
      - ctaHref: Button URL
      - ctaVariant: 'primary' | 'secondary' | 'outline' (default: 'primary')
--}}
@props([
    'title',
    'description' => null,
    'icon'        => 'inbox',
    'ctaLabel'    => null,
    'ctaHref'     => null,
    'ctaVariant'  => 'primary',
])

@php
$icons = [
    'inbox'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>',
    'document' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    'search'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
    'users'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
    'briefcase'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    'bell'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
    'chart'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
];
$iconPath = $icons[$icon] ?? $icons['inbox'];
@endphp

<div {{ $attributes->merge(['class' => 'ui-empty-state']) }}>
    <div class="ui-empty-state-icon">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            {!! $iconPath !!}
        </svg>
    </div>

    <h3 class="ui-empty-state-title">{{ $title }}</h3>

    @if($description)
    <p class="ui-empty-state-text">{{ $description }}</p>
    @endif

    @isset($action)
    <div class="mt-4">{{ $action }}</div>
    @elseif($ctaLabel && $ctaHref)
    <div class="mt-4">
        <x-ui.btn href="{{ $ctaHref }}" variant="{{ $ctaVariant }}">{{ $ctaLabel }}</x-ui.btn>
    </div>
    @endisset
</div>
