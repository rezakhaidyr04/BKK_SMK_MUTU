@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'ui-panel']) }}>
    @if($title || isset($header))
    <div class="ui-panel-header">
        <div>
            @if($title)
            <h2 class="ui-panel-title">{{ $title }}</h2>
            @endif
            @if($subtitle)
            <p class="ui-panel-subtitle">{{ $subtitle }}</p>
            @endif
            @isset($header)
            {{ $header }}
            @endisset
        </div>
        @isset($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
        @endisset
    </div>
    @endif
    <div class="ui-panel-body">
        {{ $slot }}
    </div>
</div>
