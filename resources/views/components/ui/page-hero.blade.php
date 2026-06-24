@props(['title', 'subtitle' => null])

<section class="ui-page-hero">
    <div class="ui-page-hero-inner">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="ui-page-hero-title">{{ $title }}</h1>
                @if($subtitle)
                <p class="ui-page-hero-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
            <div class="flex flex-wrap items-center gap-3">
                {{ $actions }}
            </div>
            @endisset
        </div>
        @isset($extra)
        <div class="mt-8">
            {{ $extra }}
        </div>
        @endisset
    </div>
</section>
