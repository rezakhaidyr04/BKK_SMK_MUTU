<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Detail Laporan" subtitle="{{ \App\Models\JobReport::reasonLabel($report->reason) }}" eyebrow="Admin › Laporan Lowongan › Detail">
            <x-slot:actions>
                <a href="{{ route('admin.job-reports.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-white/20 text-sm font-semibold text-white hover:bg-white/10 transition">← Kembali</a>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-3xl mx-auto space-y-4">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">{{ session('success') }}</div>
            @endif
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[11px] px-2.5 py-1 rounded-full font-semibold {{ $report->status === 'menunggu' ? 'bg-amber-100 text-amber-700' : ($report->status === 'ditindak' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">{{ ucfirst($report->status) }}</span>
                    <span class="text-[11px] px-2.5 py-1 rounded-full font-semibold bg-red-50 text-red-700">{{ \App\Models\JobReport::reasonLabel($report->reason) }}</span>
                    @if($siblingCount > 0)
                    <span class="text-[11px] px-2.5 py-1 rounded-full font-semibold bg-orange-100 text-orange-700">+{{ $siblingCount }} laporan lain untuk lowongan ini</span>
                    @endif
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 text-lg">{{ $report->job?->title ?? 'Lowongan sudah dihapus' }}</h2>
                    <p class="text-sm text-gray-500">{{ $report->job?->company_name ?? '-' }} · status lowongan: <strong>{{ $report->job?->status ?? '-' }}</strong></p>
                    @if($report->job)
                    <a href="{{ route('admin.jobs.show', $report->job) }}" class="text-sm font-semibold text-blue-600 hover:underline">Buka detail lowongan →</a>
                    @endif
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-semibold text-slate-500 mb-1">DETAIL PELAPOR</p>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $report->detail ?: '— tidak ada detail —' }}</p>
                    <p class="text-xs text-gray-400 mt-2">Pelapor: {{ $report->user?->name ?? 'akun dihapus' }} ({{ $report->user?->email ?? '-' }}) · {{ $report->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            @if($report->status === 'menunggu')
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('admin.job-reports.close', $report) }}" method="POST" onsubmit="return confirm('Tutup lowongan ini? Lowongan tidak akan tampil publik lagi.')">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">Tutup lowongan + tandai ditindak</button>
                </form>
                <form action="{{ route('admin.job-reports.dismiss', $report) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Tolak laporan</button>
                </form>
                <form action="{{ route('admin.job-reports.destroy', $report) }}" method="POST" class="ml-auto" onsubmit="return confirm('Hapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition">Hapus</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
