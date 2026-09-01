@props(['title', 'subtitle' => null])

<section {{ $attributes->merge(['class' => 'dashboard-hero relative text-white bg-gradient-to-br from-[#1a3a8f] to-[#2563eb]']) }}>

    <div class="relative mx-auto max-w-7xl px-5 py-6 sm:px-8 sm:py-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-4">
                @isset($icon)
                    <div class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-500 bg-blue-800 text-white shadow-lg sm:flex">
                        <div class="scale-90">{{ $icon }}</div>
                    </div>
                @endisset
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-blue-100">BKK SMK MUTU</p>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ $title }}</h1>
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
            <div class="mt-6 border-t border-blue-400 pt-5">{{ $extra }}</div>
        @endisset
    </div>
</section>
