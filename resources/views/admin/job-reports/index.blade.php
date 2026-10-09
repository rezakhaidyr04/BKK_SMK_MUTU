<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Laporan Lowongan" subtitle="Tindaklanjuti laporan lowongan mencurigakan — {{ $pendingCount }} menunggu." eyebrow="Admin › Moderasi">
            <x-slot:chips>
                <span class="page-banner__chip">{{ $reports->total() }} laporan</span>
            </x-slot:chips>
            <x-slot:actions>
                <a href="{{ route('admin.job-reports.index', ['status' => 'menunggu']) }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 border border-white/20 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20 transition">Hanya menunggu</a>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-5xl mx-auto">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
            @endif
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @forelse($reports as $report)
                <a href="{{ route('admin.job-reports.show', $report) }}" class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 last:border-0 hover:bg-slate-50 transition {{ $report->status === 'menunggu' ? 'bg-amber-50/40' : '' }}">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $report->job?->title ?? 'Lowongan dihapus' }}</p>
                            <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold {{ $report->status === 'menunggu' ? 'bg-amber-100 text-amber-700' : ($report->status === 'ditindak' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">{{ ucfirst($report->status) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ \App\Models\JobReport::reasonLabel($report->reason) }} · {{ $report->job?->company_name ?? '-' }} · oleh {{ $report->user?->name ?? 'anonim' }} · {{ $report->created_at->diffForHumans() }}</p>
                    </div>
                </a>
                @empty
                <div class="p-10 text-center text-sm text-gray-500">Belum ada laporan. Bagus — tidak ada lowongan mencurigakan.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $reports->links() }}</div>
        </div>
    </div>
</x-app-layout>
