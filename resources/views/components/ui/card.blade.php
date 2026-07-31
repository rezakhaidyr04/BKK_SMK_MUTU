@props(['title' => null, 'subtitle' => null, 'footer' => null, 'hoverable' => false])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border border-neutral-100 dark:border-neutral-800 overflow-hidden transition-all duration-300 ease-out' . ($hoverable ? ' hover:shadow-md hover:-translate-y-1' : '')]) }}>
    @if($title || $subtitle)
    <div class="px-6 py-5 border-b border-neutral-100 dark:border-neutral-800">
        @if($title)
        <h3 class="text-h3 text-neutral-900 dark:text-neutral-50">{{ $title }}</h3>
        @endif
        @if($subtitle)
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @endif
    
    <div class="{{ $title || $subtitle ? 'p-6' : 'p-6' }}">
        {{ $slot }}
    </div>
    
    @if($footer)
    <div class="px-6 py-4 bg-neutral-50 dark:bg-neutral-900/50 border-t border-neutral-100 dark:border-neutral-800">
        {{ $footer }}
    </div>
    @endif
</div>
