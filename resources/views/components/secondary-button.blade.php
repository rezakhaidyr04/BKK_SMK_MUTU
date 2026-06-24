@props(['type' => 'button'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'ui-btn ui-btn-secondary']) }}>
    {{ $slot }}
</button>
