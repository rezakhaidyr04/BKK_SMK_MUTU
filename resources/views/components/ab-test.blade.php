<?php

use App\Services\ABTestingService;

$abTest = app(ABTestingService::class);

$heroPrimary = $abTest->getCtaCopy('hero_primary');
$heroSecondary = $abTest->getCtaCopy('hero_secondary');
$ctaBanner = $abTest->getCtaCopy('cta_banner');
$heroHeading = $abTest->getHeroHeading();
?>

<div x-data="abTestTracker()" style="display: none;"></div>

<script>
    function abTestTracker() {
        return {
            variations: @json($abTest->getActiveVariations()),
            track(eventName, data = {}) {
                fetch('/ab-test/track', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        event: eventName,
                        variations: this.variations,
                        ...data
                    })
                }).catch(console.error);
            }
        }
    }
</script>

@props(['variant'])

@php
    $variant = $variant ?? 'control';
    $config = config("ab_testing.cta_copy.{$variant}");
@endphp

{{-- Component untuk render CTA dengan tracking --}}
@if (isset($slot))
    <button 
        {{ $attributes->merge([
            'wire:click' => "$dispatch('ab-test-track', { event: 'cta_click', variant: '$variant' })",
            'data-ab-variant' => $variant,
            'data-ab-test' => $variant
        ]) }}
    >
        {{ $slot }}
    </button>
@endif