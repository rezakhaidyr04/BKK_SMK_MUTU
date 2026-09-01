@props([
    'title',
    'subtitle'  => null,
    'badge'     => null,
    'backUrl'   => null,
    'backLabel' => 'Kembali',
])

<div class="ui-page-hero page-banner">
    <div class="ui-page-hero-inner">

        {{-- Back Link --}}
        @if($backUrl)
        <div class="mb-2">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 text-blue-200/80 text-xs font-medium hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $backLabel }}
            </a>
        </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                {{-- Badge --}}
                @if($badge)
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 text-blue-100 text-xs font-semibold mb-2">
                    {{ $badge }}
                </div>
                @endif

                {{-- Title --}}
                <h1 class="ui-page-hero-title">{{ $title }}</h1>

                {{-- Subtitle --}}
                @if($subtitle)
                <p class="ui-page-hero-subtitle">{{ $subtitle }}</p>
                @endif
            </div>

            {{-- Actions slot --}}
            @isset($actions)
            <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                {{ $actions }}
            </div>
            @endisset
        </div>

        {{-- Extra slot (e.g. filter bar, tabs) --}}
        @isset($extra)
        <div class="mt-5">
            {{ $extra }}
        </div>
        @endisset
    </div>
</div>
