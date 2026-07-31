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

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-16 px-6 text-center']) }}>
    {{-- Illustrated icon ring --}}
    <div class="relative mb-6">
        <div class="w-24 h-24 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center ring-8 ring-primary-50/50 dark:ring-primary-900/10">
            <svg class="w-10 h-10 text-primary-400 dark:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                {!! $iconPath !!}
            </svg>
        </div>
        {{-- Decorative dots --}}
        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-primary-200 dark:bg-primary-800 opacity-60"></div>
        <div class="absolute -bottom-2 -left-2 w-3 h-3 rounded-full bg-primary-300 dark:bg-primary-700 opacity-40"></div>
    </div>

    <h3 class="text-lg font-bold text-neutral-900 dark:text-neutral-50 mb-2">{{ $title }}</h3>

    @if($description)
    <p class="text-sm text-neutral-500 dark:text-neutral-400 max-w-xs leading-relaxed mb-6">{{ $description }}</p>
    @endif

    @isset($action)
    <div class="mt-2">{{ $action }}</div>
    @endisset

    @if($ctaLabel && $ctaHref && !isset($action))
    <a
        href="{{ $ctaHref }}"
        class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 ease-out hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900
        {{ $ctaVariant === 'secondary' ? 'bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200 border border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 shadow-sm' : ($ctaVariant === 'outline' ? 'bg-transparent text-primary-600 dark:text-primary-400 border border-primary-300 dark:border-primary-700 hover:bg-primary-50' : 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm') }}"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        {{ $ctaLabel }}
    </a>
    @endif
</div>
