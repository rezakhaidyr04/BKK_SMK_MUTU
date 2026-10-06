<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Notifikasi" subtitle="Semua pemberitahuan lowongan dan lamaran Anda." eyebrow="Akun › Notifikasi">
            <x-slot:chips>
                <span class="page-banner__chip">
                    {{ auth()->user()->unreadNotifications->count() }} belum dibaca
                </span>
            </x-slot:chips>
            <x-slot:actions>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                           class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/25 transition hover:bg-white/25">
                            Tandai semua dibaca
                        </button>
                    </form>
                @endif
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            <x-ui.panel>
                @forelse($notifications as $notification)
                    @php
                        $type = $notification->data['type'] ?? 'general';
                        $icon = match($type) {
                            'new_job' => 'briefcase',
                            'interview_scheduled' => 'calendar',
                            'application_status' => 'clipboard',
                            'application_received' => 'inbox',
                            default => 'bell',
                        };
                        $target = route('notifications.go', $notification->id);
                    @endphp
                    <form action="{{ $target }}" method="POST" class="flex items-start gap-3 px-5 py-4 border-b border-slate-100 last:border-0">
                        @csrf
                        <button type="submit" class="flex items-start gap-3 w-full text-left transition hover:bg-slate-50 {{ $notification->unread() ? 'bg-blue-50/50' : '' }}">
                        <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $notification->unread() ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                            @if($icon === 'briefcase')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @elseif($icon === 'calendar')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @elseif($icon === 'clipboard')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            @elseif($icon === 'inbox')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @endif
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm leading-snug {{ $notification->unread() ? 'font-semibold text-slate-900' : 'text-slate-700' }}">
                                {{ $notification->data['message'] ?? 'Ada pembaruan untuk Anda.' }}
                            </span>
                            <span class="mt-1 block text-xs text-slate-400">
                                {{ $notification->created_at->diffForHumans() }}
                                @if($notification->unread())
                                    · <span class="font-semibold text-blue-600">Baru</span>
                                @endif
                            </span>
                        </span>
                    </button>
                    </form>
                @empty
                    <x-ui.empty-state
                        icon="bell"
                        title="Belum ada notifikasi"
                        description="Lowongan baru dan kabar lamaran (termasuk undangan wawancara) akan muncul di sini dan dikirim ke email Anda."
                    />
                @endforelse
            </x-ui.panel>

            @if($notifications->hasPages())
                <div class="mt-4">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
