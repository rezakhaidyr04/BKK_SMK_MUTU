@props([
    'title',
    'subtitle' => null,
    'gradient' => 'from-blue-600 via-teal-600 to-green-600',
])

<div class="relative overflow-hidden bg-gradient-to-r {{ $gradient }} shadow-2xl">
    {{-- Decorative overlays --}}
    <div class="absolute inset-0 bg-black opacity-10" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent" aria-hidden="true"></div>

    {{-- Animated floating circles for visual depth --}}
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-2xl animate-pulse" aria-hidden="true"></div>
    <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/5 rounded-full blur-xl animate-pulse ui-hero-pulse-delay-1" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $title }}</h1>
                @if($subtitle)
                <p class="text-white/80">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                {{-- Date badge --}}
                <div class="hidden md:block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-white">
                    <p class="text-sm" aria-label="Tanggal hari ini">Hari Ini</p>
                    <p class="font-semibold">{{ now()->format('d M Y') }}</p>
                </div>
                {{-- Extra actions slot --}}
                @isset($actions)
                    {{ $actions }}
                @endisset
            </div>
        </div>
        {{-- Extra content below (like profile completion bar) --}}
        @isset($extra)
            <div class="mt-6">
                {{ $extra }}
            </div>
        @endisset
    </div>
</div>
