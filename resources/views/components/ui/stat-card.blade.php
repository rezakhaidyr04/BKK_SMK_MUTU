@props(['label', 'value', 'color' => 'blue', 'icon' => null, 'subtitle' => null])

@php
// Color maps
$colorMap = [
    'blue'   => ['bg' => '#eff6ff', 'icon' => '#2563eb', 'value' => '#1e40af', 'border' => '#bfdbfe', 'accent' => '#3b82f6'],
    'green'  => ['bg' => '#f0fdf4', 'icon' => '#16a34a', 'value' => '#166534', 'border' => '#bbf7d0', 'accent' => '#22c55e'],
    'purple' => ['bg' => '#faf5ff', 'icon' => '#7c3aed', 'value' => '#581c87', 'border' => '#e9d5ff', 'accent' => '#a855f7'],
    'yellow' => ['bg' => '#fefce8', 'icon' => '#ca8a04', 'value' => '#854d0e', 'border' => '#fde68a', 'accent' => '#eab308'],
    'red'    => ['bg' => '#fef2f2', 'icon' => '#dc2626', 'value' => '#991b1b', 'border' => '#fecaca', 'accent' => '#ef4444'],
    'orange' => ['bg' => '#fff7ed', 'icon' => '#ea580c', 'value' => '#9a3412', 'border' => '#fed7aa', 'accent' => '#f97316'],
    'indigo' => ['bg' => '#eef2ff', 'icon' => '#4f46e5', 'value' => '#3730a3', 'border' => '#c7d2fe', 'accent' => '#6366f1'],
    'slate'  => ['bg' => '#f8fafc', 'icon' => '#475569', 'value' => '#1e293b', 'border' => '#e2e8f0', 'accent' => '#64748b'],
    'gray'   => ['bg' => '#f9fafb', 'icon' => '#6b7280', 'value' => '#111827', 'border' => '#e5e7eb', 'accent' => '#9ca3af'],
];
$c = $colorMap[$color] ?? $colorMap['blue'];

// Icon SVG library
$icons = [
    'document-text' => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
    'document'      => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
    'check'         => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'check-circle'  => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'x'             => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'users'         => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
    'user'          => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
    'calendar'      => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
    'clock'         => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'briefcase'     => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
    'chart-bar'     => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
    'academic-cap'  => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>',
    'trending-up'   => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>',
    'star'          => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>',
    'mail'          => '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
];

// Resolve icon — if already an SVG string, use it; if a key name, look up; else use generic
$resolvedIcon = null;
if ($icon) {
    if (str_starts_with(trim($icon), '<svg')) {
        $resolvedIcon = $icon;
    } elseif (isset($icons[$icon])) {
        $resolvedIcon = $icons[$icon];
    } else {
        $resolvedIcon = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>';
    }
}
@endphp

<div {{ $attributes->merge(['class' => '']) }}
     style="
         position: relative;
         background: #ffffff;
         border: 1px solid {{ $c['border'] }};
         border-top: 3px solid {{ $c['accent'] }};
         border-radius: 14px;
         padding: 20px 22px;
         box-shadow: 0 2px 8px rgba(15,23,42,0.06);
         transition: box-shadow 0.2s ease, transform 0.2s ease;
         cursor: default;
         overflow: hidden;
     "
     onmouseover="this.style.boxShadow='0 8px 28px rgba(15,23,42,0.11)';this.style.transform='translateY(-2px)'"
     onmouseout="this.style.boxShadow='0 2px 8px rgba(15,23,42,0.06)';this.style.transform='translateY(0)'">

    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:14px;">

        {{-- Label & Value --}}
        <div style="min-width:0; flex:1;">
            <p style="
                font-size: 11px;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.07em;
                margin: 0 0 8px 0;
            ">{{ $label }}</p>

            <p style="
                font-size: 30px;
                font-weight: 800;
                color: {{ $c['value'] }};
                line-height: 1;
                margin: 0;
            ">{{ $value }}</p>

            @if($subtitle)
                <p style="
                    font-size: 12px;
                    color: #94a3b8;
                    margin: 6px 0 0 0;
                ">{{ $subtitle }}</p>
            @endif

            @isset($footer)
                <div style="margin-top: 10px; font-size: 12px;">{{ $footer }}</div>
            @endisset
        </div>

        {{-- Icon Badge --}}
        @if($resolvedIcon)
            <div style="
                width: 46px;
                height: 46px;
                border-radius: 12px;
                background: {{ $c['bg'] }};
                color: {{ $c['icon'] }};
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                border: 1px solid {{ $c['border'] }};
            ">
                {!! $resolvedIcon !!}
            </div>
        @endif

    </div>
</div>
