<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Pesan Kontak" subtitle="Inbox helpdesk publik — {{ $unreadCount }} belum dibaca." eyebrow="Admin › Kontak">
            <x-slot:chips>
                <span class="page-banner__chip">{{ $messages->total() }} pesan</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-5xl mx-auto">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">{{ session('success') }}</div>
            @endif
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @forelse($messages as $msg)
                <a href="{{ route('admin.contacts.show', $msg) }}" class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 last:border-0 hover:bg-slate-50 transition {{ $msg->status === 'baru' ? 'bg-blue-50/50' : '' }}">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold shrink-0">{{ strtoupper(substr($msg->name, 0, 1)) }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-gray-900 text-sm">{{ $msg->name }}</p>
                            <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold {{ $msg->status === 'baru' ? 'bg-blue-100 text-blue-700' : ($msg->status === 'dibalas' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">{{ ucfirst($msg->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-800 truncate mt-0.5">{{ $msg->subject }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $msg->email }} · {{ $msg->created_at->diffForHumans() }}</p>
                    </div>
                </a>
                @empty
                <div class="p-10 text-center text-sm text-gray-500">Belum ada pesan kontak.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $messages->links() }}</div>
        </div>
    </div>
</x-app-layout>
