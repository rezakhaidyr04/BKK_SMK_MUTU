@props(['title', 'subtitle' => null])

<section {{ $attributes->merge(['class' => 'dashboard-hero relative text-white bg-[#0a1633]']) }}>

    {{-- Lapisan dekorasi yang sama persis dengan page-banner --}}
    <div class="page-banner__decor" aria-hidden="true">
        <span class="page-banner__orb page-banner__orb--1"></span>
        <span class="page-banner__orb page-banner__orb--2"></span>
        <span class="page-banner__orb page-banner__orb--3"></span>
        <span class="page-banner__ring"></span>
    </div>

    <div class="dashboard-hero__content relative mx-auto max-w-7xl px-6 py-5 sm:px-8 sm:py-6 lg:px-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                @isset($icon)
                    <div class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-500 bg-blue-800 text-white shadow-lg sm:flex">
                        <div class="scale-90">{{ $icon }}</div>
                    </div>
                @endisset
                <div>
                    <p class="mb-2"><span class="page-banner__eyebrow">BKKMu</span></p>
                    <h1 class="text-[1.35rem] font-bold leading-[1.2] text-white sm:text-[1.5rem] lg:text-[1.6rem]">{{ $title }}</h1>
                    @if($subtitle)
                        <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-blue-100 sm:text-base">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                <div class="hidden rounded-xl border border-blue-500 bg-blue-800 px-4 py-2 text-right shadow-sm lg:block">
                    <p class="mb-0.5 text-[0.65rem] font-bold uppercase tracking-[0.16em] text-blue-100">Hari Ini</p>
                    <p class="text-sm font-bold text-white">{{ now()->translatedFormat('d M Y') }}</p>
                </div>

                @isset($actions)
                    <div class="flex flex-wrap gap-2">{{ $actions }}</div>
                @endisset
            </div>
        </div>

        @isset($extra)
            <div class="mt-4 border-t border-blue-400 pt-4">{{ $extra }}</div>
        @endisset
    </div>
</section>
