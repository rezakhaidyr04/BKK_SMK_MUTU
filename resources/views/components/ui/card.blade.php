@props(['title' => null, 'subtitle' => null, 'footer' => null, 'hoverable' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden' . ($hoverable ? ' hover:shadow-card-hover transition-shadow duration-300' : '')]) }}>
    @if($title || $subtitle)
    <div class="p-5 border-b border-gray-100">
        @if($title)
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        @endif
        @if($subtitle)
        <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @endif
    
    <div class="{{ $title || $subtitle ? 'p-5' : 'p-5' }}">
        {{ $slot }}
    </div>
    
    @if($footer)
    <div class="px-5 py-4 bg-gray-50 border-t border-gray-100">
        {{ $footer }}
    </div>
    @endif
</div>
