@props(['title', 'subtitle' => null])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">{{ $title }}</h2>
        @if($subtitle)
        <p class="text-sm text-blue-100 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
    <div class="flex flex-wrap items-center gap-3">
        {{ $actions }}
    </div>
    @endisset
</div>
