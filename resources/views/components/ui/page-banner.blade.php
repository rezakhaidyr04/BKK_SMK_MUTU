@props([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
    'backUrl' => null,
    'backLabel' => 'Kembali',
    'variant' => 'default', // default, compact, accent
    'actions' => null,
])

<div class="page-banner page-banner--{{ $variant }}">
    <div class="page-banner__content">
        {{-- Back Link --}}
        @if($backUrl)
        <a href="{{ $backUrl }}" class="page-banner__back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ $backLabel }}
        </a>
        @endif

        <div class="page-banner__header">
            {{-- Eyebrow Badge --}}
            @if($eyebrow)
            <div class="page-banner__eyebrow">
                {{ $eyebrow }}
            </div>
            @endif

            {{-- Title --}}
            <h1 class="page-banner__title">{{ $title }}</h1>

            {{-- Subtitle --}}
            @if($subtitle)
            <p class="page-banner__subtitle">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Slot untuk konten tambahan jika diperlukan --}}
        @isset($extra)
        <div class="page-banner__extra">
            {{ $extra }}
        </div>
        @endisset
    </div>

    {{-- Actions --}}
    @if($actions)
    <div class="page-banner__actions">
        {{ $actions }}
    </div>
    @endif
</div>
