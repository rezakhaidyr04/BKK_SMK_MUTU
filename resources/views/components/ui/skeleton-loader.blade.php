{{--
    Skeleton Loader Component
    @props:
      - type: 'card' | 'table' | 'stat' | 'profile' | 'list' (default: 'card')
      - count: number of skeleton items to repeat (default: 1)
      - lines: number of text lines in card type (default: 3)
--}}
@props([
    'type'  => 'card',
    'count' => 1,
    'lines' => 3,
])

@for($i = 0; $i < $count; $i++)
    @if($type === 'stat')
    <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-100 dark:border-neutral-800 p-6 animate-pulse">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-neutral-200 dark:bg-neutral-700 flex-shrink-0"></div>
            <div class="flex-1 space-y-2">
                <div class="h-7 bg-neutral-200 dark:bg-neutral-700 rounded-lg w-1/2"></div>
                <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-3/4"></div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800">
            <div class="h-3 bg-neutral-200 dark:bg-neutral-700 rounded w-1/3"></div>
        </div>
    </div>

    @elseif($type === 'table')
    <div class="animate-pulse">
        <div class="bg-neutral-100 dark:bg-neutral-800 rounded-xl p-4 mb-2 flex gap-4">
            <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-8"></div>
            <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded flex-1"></div>
            <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-24"></div>
            <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-20"></div>
        </div>
        @for($r = 0; $r < ($lines ?? 5); $r++)
        <div class="flex gap-4 px-4 py-3 border-b border-neutral-100 dark:border-neutral-800">
            <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-8"></div>
            <div class="flex items-center gap-3 flex-1">
                <div class="w-8 h-8 rounded-full bg-neutral-200 dark:bg-neutral-700 flex-shrink-0"></div>
                <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded flex-1"></div>
            </div>
            <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-24"></div>
            <div class="h-6 bg-neutral-200 dark:bg-neutral-700 rounded-full w-20"></div>
        </div>
        @endfor
    </div>

    @elseif($type === 'profile')
    <div class="animate-pulse space-y-6">
        <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-full bg-neutral-200 dark:bg-neutral-700 flex-shrink-0"></div>
            <div class="flex-1 space-y-2">
                <div class="h-6 bg-neutral-200 dark:bg-neutral-700 rounded-lg w-1/3"></div>
                <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-1/2"></div>
                <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-1/4"></div>
            </div>
        </div>
        <div class="space-y-3">
            @for($l = 0; $l < 5; $l++)
            <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded {{ $l % 3 === 2 ? 'w-3/4' : 'w-full' }}"></div>
            @endfor
        </div>
    </div>

    @elseif($type === 'list')
    <div class="animate-pulse space-y-3">
        @for($l = 0; $l < ($lines ?? 4); $l++)
        <div class="flex items-center gap-3 p-3 rounded-xl border border-neutral-100 dark:border-neutral-800">
            <div class="w-10 h-10 rounded-xl bg-neutral-200 dark:bg-neutral-700 flex-shrink-0"></div>
            <div class="flex-1 space-y-1.5">
                <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded w-3/4"></div>
                <div class="h-3 bg-neutral-200 dark:bg-neutral-700 rounded w-1/2"></div>
            </div>
            <div class="h-6 bg-neutral-200 dark:bg-neutral-700 rounded-full w-16"></div>
        </div>
        @endfor
    </div>

    @else {{-- default: card --}}
    <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-100 dark:border-neutral-800 p-6 animate-pulse">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-neutral-200 dark:bg-neutral-700 rounded-xl flex-shrink-0"></div>
            <div class="flex-1 min-w-0 space-y-2">
                <div class="h-4 bg-neutral-200 dark:bg-neutral-700 rounded-lg w-3/4"></div>
                <div class="h-3 bg-neutral-200 dark:bg-neutral-700 rounded w-1/2"></div>
            </div>
        </div>
        @if($lines > 1)
        <div class="mt-5 space-y-2.5">
            <div class="h-3 bg-neutral-200 dark:bg-neutral-700 rounded"></div>
            <div class="h-3 bg-neutral-200 dark:bg-neutral-700 rounded w-5/6"></div>
            @if($lines > 2)
            <div class="h-3 bg-neutral-200 dark:bg-neutral-700 rounded w-4/6"></div>
            @endif
        </div>
        @endif
    </div>
    @endif
@endfor
