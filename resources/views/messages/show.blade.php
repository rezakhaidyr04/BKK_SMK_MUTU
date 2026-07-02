<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @php
                $otherUser = $conversation->users->firstWhere('id', '!=', Auth::id());
            @endphp

            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('messages.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke pesan</a>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $otherUser?->name ?? 'Percakapan' }}</h1>
                    <p class="text-sm text-gray-600">{{ $otherUser?->email ?? 'Detail percakapan' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <p class="text-sm text-gray-600">Riwayat pesan di percakapan ini.</p>
                </div>

                <div class="p-6 space-y-4 max-h-[32rem] overflow-y-auto">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-2xl rounded-2xl px-4 py-3 {{ $message->sender_id === Auth::id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                <div class="flex items-center justify-between gap-4 mb-1">
                                    <p class="text-xs font-semibold {{ $message->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-500' }}">{{ $message->sender->name ?? 'Pengguna' }}</p>
                                    <p class="text-xs {{ $message->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-500' }}">{{ $message->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <p class="text-sm leading-relaxed">{{ $message->body }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada pesan</h3>
                            <p class="text-sm text-gray-600">Mulai percakapan dengan mengirim pesan pertama.</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    <form method="POST" action="{{ route('messages.send', $conversation) }}" class="space-y-4">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700">Tulis pesan</label>
                        <textarea name="body" rows="4" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-200" placeholder="Ketik pesan Anda..." required></textarea>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">Pesan akan disimpan dan dikirim ke percakapan ini.</p>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors">Kirim Pesan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>