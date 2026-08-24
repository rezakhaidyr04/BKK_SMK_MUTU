<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero title="Pesan" subtitle="Kelola percakapan dengan perusahaan dari satu tempat.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('jobs.index') }}" variant="secondary" size="sm">Cari Lowongan</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-hero>

        <div class="page-container page-section">
            @if($conversations->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($conversations as $conversation)
                        @php
                            $otherUser = $conversation->users->firstWhere('id', '!=', Auth::id());
                            $latestMessage = $conversation->messages->first();
                        @endphp
                        <a href="{{ route('messages.show', $conversation) }}"
                           class="block ui-panel hover:shadow-md hover:-translate-y-0.5 transition-all">
                            <div class="ui-panel-body">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold flex-shrink-0 text-lg">
                                        {{ substr($otherUser?->name ?? 'P', 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-3">
                                            <h3 class="font-bold text-slate-900 truncate">{{ $otherUser?->name ?? 'Percakapan' }}</h3>
                                            <span class="text-xs text-slate-500 flex-shrink-0">{{ $latestMessage?->created_at?->diffForHumans() ?? 'Baru' }}</span>
                                        </div>
                                        <p class="text-sm text-slate-600 mt-1 truncate">{{ $latestMessage?->body ?? 'Belum ada pesan' }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <x-ui.panel>
                    <x-ui.empty-state
                        icon="chat"
                        title="Belum ada percakapan"
                        description="Mulai percakapan dengan perusahaan setelah melamar lowongan."
                        ctaLabel="Cari Lowongan"
                        ctaHref="{{ route('jobs.index') }}"
                    />
                </x-ui.panel>
            @endif
        </div>
    </div>
</x-app-layout>
