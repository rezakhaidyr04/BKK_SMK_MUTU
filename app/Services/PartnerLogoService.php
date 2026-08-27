<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class PartnerLogoService
{
    protected array $partners = [];

    public function __construct()
    {
        $this->loadPartners();
    }

    /**
     * Load partner configuration
     */
    protected function loadPartners(): void
    {
        $this->partners = Config::get('partners.list', []);
    }

    /**
     * Get all active partners
     */
    public function getActivePartners(): array
    {
        return array_filter($this->partners, fn($p) => $p['active'] ?? true);
    }

    /**
     * Get partner by key
     */
    public function getPartner(string $key): ?array
    {
        return $this->partners[$key] ?? null;
    }

    /**
     * Get partner logo HTML
     */
    public function getLogoHtml(string $key, array $attributes = []): string
    {
        $partner = $this->getPartner($key);
        
        if (!$partner) {
            return $this->getPlaceholderLogo($attributes);
        }

        $logoPath = $partner['logo_path'] ?? null;
        $alt = $partner['name'] ?? 'Partner';

        if ($logoPath && File::exists(public_path($logoPath))) {
            $ext = pathinfo($logoPath, PATHINFO_EXTENSION);
            $webpPath = str_replace(".{$ext}", '.webp', $logoPath);
            
            $attrs = $this->buildAttributes($attributes, [
                'alt' => $alt,
                'class' => 'partner-logo',
                'loading' => 'lazy',
                'decoding' => 'async',
            ]);

            if (File::exists(public_path($webpPath))) {
                return "<picture>
    <source srcset='" . asset($webpPath) . "' type='image/webp'>
    <img src='" . asset($logoPath) . "' {$attrs}>
</picture>";
            }

            return "<img src='" . asset($logoPath) . "' {$attrs}>";
        }

        return $this->getPlaceholderLogo($attributes + ['alt' => $alt]);
    }

    /**
     * Get placeholder logo SVG
     */
    protected function getPlaceholderLogo(array $attributes = []): string
    {
        $attrs = $this->buildAttributes($attributes, [
            'class' => 'partner-logo placeholder',
            'style' => 'height: 40px; width: auto; opacity: 0.5;',
        ]);

        return "<svg {$attrs} viewBox='0 0 120 40' fill='none' xmlns='http://www.w3.org/2000/svg' aria-hidden='true'>
    <rect width='120' height='40' rx='8' fill='currentColor' opacity='0.1'/>
    <text x='60' y='26' text-anchor='middle' font-size='11' font-weight='600' fill='currentColor' opacity='0.5'>LOGO</text>
</svg>";
    }

    /**
     * Build HTML attributes string
     */
    protected function buildAttributes(array $userAttrs, array $defaults = []): string
    {
        $attrs = array_merge($defaults, $userAttrs);
        return collect($attrs)->map(fn($v, $k) => "{$k}=\"{$v}\"")->implode(' ');
    }

    /**
     * Get logos for marquee display
     */
    public function getMarqueeLogos(int $count = 10): array
    {
        $partners = $this->getActivePartners();
        $keys = array_keys($partners);
        
        // Repeat to fill count
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $key = $keys[$i % count($keys)];
            $result[] = [
                'key' => $key,
                'name' => $partners[$key]['name'] ?? $key,
                'html' => $this->getLogoHtml($key, ['class' => 'marquee-logo']),
            ];
        }

        return $result;
    }

    /**
     * Add new partner (runtime)
     */
    public function addPartner(string $key, array $data): void
    {
        $this->partners[$key] = array_merge([
            'name' => $key,
            'logo_path' => null,
            'website' => null,
            'active' => true,
            'order' => 999,
        ], $data);

        // Sort by order
        uasort($this->partners, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
    }
}