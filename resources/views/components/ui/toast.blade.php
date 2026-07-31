{{-- 
    Toast Notification Component
    Usage: <x-ui.toast /> (include once in layout)
    Trigger via: window.toast.success('msg') | window.toast.error('msg') | window.toast.warning('msg') | window.toast.info('msg')
--}}
<div
    x-data="{
        toasts: [],
        add(type, message, duration = 4000) {
            const id = Date.now();
            this.toasts.push({ id, type, message, visible: false });
            this.$nextTick(() => {
                const t = this.toasts.find(t => t.id === id);
                if (t) t.visible = true;
            });
            setTimeout(() => this.remove(id), duration);
        },
        remove(id) {
            const t = this.toasts.find(t => t.id === id);
            if (t) { t.visible = false; setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 400); }
        },
        icon(type) {
            const icons = {
                success: '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg>',
                error:   '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M6 18L18 6M6 6l12 12\'/></svg>',
                warning: '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z\'/></svg>',
                info:    '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>',
            };
            return icons[type] || icons.info;
        },
        colors(type) {
            const map = {
                success: 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300',
                error:   'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300',
                warning: 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-300',
                info:    'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300',
            };
            return map[type] || map.info;
        },
        iconColors(type) {
            const map = {
                success: 'text-green-500', error: 'text-red-500',
                warning: 'text-yellow-500', info: 'text-blue-500',
            };
            return map[type] || map.info;
        }
    }"
    x-init="
        window.toast = {
            success: (msg, d) => $data.add('success', msg, d),
            error:   (msg, d) => $data.add('error', msg, d),
            warning: (msg, d) => $data.add('warning', msg, d),
            info:    (msg, d) => $data.add('info', msg, d),
        };
        @if(session('success'))
            $nextTick(() => add('success', '{{ addslashes(session('success')) }}'));
        @endif
        @if(session('error'))
            $nextTick(() => add('error', '{{ addslashes(session('error')) }}'));
        @endif
        @if(session('warning'))
            $nextTick(() => add('warning', '{{ addslashes(session('warning')) }}'));
        @endif
        @if(session('info'))
            $nextTick(() => add('info', '{{ addslashes(session('info')) }}'));
        @endif
    "
    class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"
    aria-live="assertive"
    aria-atomic="true"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8 scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-8 scale-95"
            :class="colors(toast.type)"
            class="pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-2xl border shadow-lg w-80 max-w-sm"
        >
            <div :class="iconColors(toast.type)" class="flex-shrink-0 mt-0.5" x-html="icon(toast.type)"></div>
            <p class="flex-1 text-sm font-medium leading-snug" x-text="toast.message"></p>
            <button @click="remove(toast.id)" class="flex-shrink-0 opacity-60 hover:opacity-100 transition-opacity ml-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>
