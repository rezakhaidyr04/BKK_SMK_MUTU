@props(['title', 'subtitle' => null])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="page-title text-xl sm:text-2xl">{{ $title }}</h2>
        @if($subtitle)
        <p class="page-subtitle text-sm">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
    <div class="flex flex-wrap items-center gap-3">
        {{ $actions }}
    </div>
    @endisset
</div>
