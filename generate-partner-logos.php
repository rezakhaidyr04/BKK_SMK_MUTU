<?php

$partners = [
    'pt_maju_bersama' => ['name' => 'PT Maju Bersama', 'color' => '#1e3a8a'],
    'pt_teknologi_nusantara' => ['name' => 'PT Teknologi Nusantara', 'color' => '#0d9488'],
    'pt_ritel_karawang' => ['name' => 'PT Ritel Karawang', 'color' => '#dc2626'],
    'pt_karawang_hospitality' => ['name' => 'PT Karawang Hospitality', 'color' => '#7c2d12'],
    'pt_mitra_sejahtera' => ['name' => 'PT Mitra Sejahtera', 'color' => '#15803d'],
    'pt_jaya_abadi' => ['name' => 'PT Jaya Abadi', 'color' => '#b45309'],
    'pt_bersama_makmur' => ['name' => 'PT Bersama Makmur', 'color' => '#4338ca'],
    'pt_sukses_gemilang' => ['name' => 'PT Sukses Gemilang', 'color' => '#be185d'],
];

$outputDir = __DIR__ . '/public/images/partners/';

foreach ($partners as $key => $data) {
    $svg = <<<SVG
<svg viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="bg-{$key}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$data['color']}"/>
      <stop offset="100%" stop-color="{$data['color']}dd"/>
    </linearGradient>
  </defs>
  <rect width="200" height="60" rx="8" fill="url(#bg-{$key})"/>
  <text x="100" y="38" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="700" fill="white">
    {$data['name']}
  </text>
</svg>
SVG;

    file_put_contents($outputDir . $key . '.svg', $svg);
    echo "Generated: {$key}.svg\n";
}

echo "Done! Generated " . count($partners) . " partner logos.\n";