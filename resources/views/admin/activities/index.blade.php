<x-app-layout title="Log Aktivitas — BKK SMK MUTU" description="Riwayat aktivitas admin pada sistem BKK SMK MUTU.">
    <div class="page-shell">
        <div class="page-container py-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">Log Aktivitas</h1>
                    <p class="text-sm text-slate-500 mt-1">Riwayat tindakan mutasi yang dilakukan oleh admin.</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">Kembali ke Laporan</a>
            </div>

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
