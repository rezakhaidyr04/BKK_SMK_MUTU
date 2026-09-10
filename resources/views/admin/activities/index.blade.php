<x-app-layout title="Log Aktivitas — BKK SMK MUTU" description="Riwayat aktivitas admin pada sistem BKK SMK MUTU." :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Log Aktivitas" subtitle="Riwayat tindakan mutasi yang dilakukan oleh admin." eyebrow="Admin › Aktivitas">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Audit Trail
                </span>
                <span class="page-banner__chip">Admin Area · {{ $activities->total() }} Log</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn variant="white" size="sm" href="{{ route('admin.reports.index') }}">Kembali ke Laporan</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="text-left font-semibold px-4 py-3">Waktu</th>
                            <th class="text-left font-semibold px-4 py-3">Admin</th>
                            <th class="text-left font-semibold px-4 py-3">Tindakan</th>
                            <th class="text-left font-semibold px-4 py-3">Subjek</th>
                            <th class="text-left font-semibold px-4 py-3">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($activities as $activity)
                            <tr>
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-slate-800">{{ $activity->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-800">{{ $activity->description }}</td>
                                <td class="px-4 py-3 text-slate-500 text-xs">
                                    @if ($activity->subject_type)
                                        {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ $activity->ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada aktivitas tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
