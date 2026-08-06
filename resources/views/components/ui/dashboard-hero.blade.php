@props([
    'title',
    'subtitle' => null,
])

<style>
    .premium-hero {
        background: linear-gradient(135deg, #1e3a8a, #2563eb, #0ea5e9);
        border-radius: 24px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
        color: white;
    }
    .premium-hero-bg-1 {
        position: absolute; top: -2rem; right: 20%; width: 16rem; height: 16rem;
        background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(40px); pointer-events: none;
    }
    .premium-hero-bg-2 {
        position: absolute; bottom: -2rem; left: -5%; width: 12rem; height: 12rem;
        background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(30px); pointer-events: none;
    }
    .glass-badge {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        border-radius: 16px;
        padding: 0.5rem 1rem;
        transition: background 0.2s ease;
    }
    .glass-badge:hover {
        background: rgba(255,255,255,0.15);
    }
    .glass-icon-box {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 16px;
        width: 3rem; height: 3rem;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    .glass-icon-box:hover {
        transform: scale(1.05);
    }
</style>

<div class="premium-hero">
    <div class="premium-hero-bg-1"></div>
    <div class="premium-hero-bg-2"></div>
    
    <div class="relative max-w-7xl mx-auto px-5 sm:px-8 py-6" style="z-index: 10;">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                @isset($icon)
                    <div class="hidden sm:flex glass-icon-box text-white">
                        <div style="transform: scale(0.8)">
                            {{ $icon }}
                        </div>
                    </div>
                @endisset
                <div>
                    <h1 class="font-extrabold text-white tracking-tight" style="font-size: 1.75rem; line-height: 1.2; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        {{ $title }}
                    </h1>
                    @if($subtitle)
                        <p class="mt-1.5 font-medium" style="font-size: 0.95rem; color: #e0f2fe; text-shadow: 0 1px 2px rgba(0,0,0,0.1); max-width: 32rem;">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                {{-- Date badge --}}
                <div class="hidden lg:flex flex-col items-end glass-badge text-right">
                    <p style="font-size: 0.65rem; letter-spacing: 0.15em; font-weight: 600; color: #bae6fd; text-transform: uppercase; margin-bottom: 0.15rem;">
                        Hari Ini
                    </p>
                    <p style="font-size: 0.95rem; font-weight: 700; color: white;">
                        {{ now()->translatedFormat('d M Y') }}
                    </p>
                </div>

                {{-- Extra actions slot --}}
                @isset($actions)
                    <div class="flex flex-wrap gap-2">
                        {{ $actions }}
                    </div>
                @endisset
            </div>
        </div>

        {{-- Extra content below --}}
        @isset($extra)
            <div class="mt-5 pt-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
                {{ $extra }}
            </div>
        @endisset
    </div>
</div>