<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Detail Pesan" subtitle="{{ $message->subject }}" eyebrow="Admin › Kontak › Detail">
            <x-slot:actions>
                <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-white/20 text-sm font-semibold text-white hover:bg-white/10 transition">← Kembali</a>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-3xl mx-auto space-y-4">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">{{ session('success') }}</div>
            @endif
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold">{{ strtoupper(substr($message->name, 0, 1)) }}</div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $message->name }}</p>
                        <p class="text-xs text-gray-500">{{ $message->email }}{{ $message->user ? ' · akun #' . $message->user->id : ' · tamu' }} · {{ $message->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <span class="ml-auto text-[11px] px-2.5 py-1 rounded-full font-semibold {{ $message->status === 'baru' ? 'bg-blue-100 text-blue-700' : ($message->status === 'dibalas' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">{{ ucfirst($message->status) }}</span>
                </div>
                <h2 class="font-bold text-gray-900 mb-2">{{ $message->subject }}</h2>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $message->message }}</p>
                <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-400">
                    Balas via email ke <a href="mailto:{{ $message->email }}" class="text-blue-600 underline">{{ $message->email }}</a>, lalu tandai di bawah.
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                @if($message->status !== 'dibalas')
                <form action="{{ route('admin.contacts.replied', $message) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition">Tandai sudah dibalas</button>
                </form>
                @endif
                <form action="{{ route('admin.contacts.destroy', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 rounded-xl border border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50 transition">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
