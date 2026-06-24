@props(['id', 'title'])

<div id="{{ $id }}" class="ui-modal hidden fixed inset-0 z-50" aria-modal="true" role="dialog">
    <div class="ui-modal-backdrop fixed inset-0 bg-slate-900/50 backdrop-blur-sm" data-modal-close="{{ $id }}"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="ui-panel w-full max-w-md pointer-events-auto shadow-2xl">
            <div class="ui-panel-header">
                <h3 class="ui-panel-title">{{ $title }}</h3>
            </div>
            <div class="ui-panel-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
