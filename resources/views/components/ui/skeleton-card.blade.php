@props(['lines' => 3])

<div class="bg-white rounded-2xl shadow-card p-5 animate-pulse">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 bg-gray-200 rounded-xl flex-shrink-0"></div>
        <div class="flex-1 min-w-0 space-y-2">
            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
        </div>
    </div>
    @if($lines > 1)
    <div class="mt-4 space-y-2">
        <div class="h-3 bg-gray-200 rounded"></div>
        <div class="h-3 bg-gray-200 rounded w-5/6"></div>
        @if($lines > 2)
        <div class="h-3 bg-gray-200 rounded w-4/6"></div>
        @endif
    </div>
    @endif
</div>
