<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Pesan</h1>
                    <p class="text-gray-600 mt-2">Kelola percakapan dengan perusahaan dari satu tempat.</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">Cari Lowongan</a>
            </div>

            @if($conversations->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($conversations as $conversation)
                        @php
                            $otherUser = $conversation->users->firstWhere('id', '!=', Auth::id());
                            $latestMessage = $conversation->messages->first();
                        @endphp
                        <a href="{{ route('messages.show', $conversation) }}" class="block bg-white rounded-2xl shadow-lg border border-gray-100 p-5 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold flex-shrink-0">
                                    {{ substr($otherUser?->name ?? 'P', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <h3 class="font-bold text-gray-900 truncate">{{ $otherUser?->name ?? 'Percakapan' }}</h3>
                                        <span class="text-xs text-gray-500">{{ $latestMessage?->created_at?->diffForHumans() ?? 'Baru' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1 truncate">{{ $latestMessage?->body ?? 'Belum ada pesan' }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada percakapan</h3>
                    <p class="text-gray-600">Mulai percakapan dengan perusahaan setelah melamar lowongan.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
