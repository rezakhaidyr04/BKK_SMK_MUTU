<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Pelamar" subtitle="Lihat pelamar yang sudah mengajukan lamaran untuk lowongan perusahaan Anda." />
    </x-slot>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Pelamar</th>
                        <th>Lowongan</th>
                        <th>Posisi</th>
                        <th>Tanggal Melamar</th>
                        <th>Status</th>
                        <th>Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr>
                        <td>
                            <p class="font-semibold text-slate-900">{{ $application->user->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $application->user->email }}</p>
                        </td>
                        <td class="font-medium text-slate-800">{{ optional($application->job)->title ?? '-' }}</td>
                        <td class="text-slate-600">{{ optional($application->job)->position ?? '-' }}</td>
                        <td class="text-sm text-slate-500">{{ $application->created_at->format('d M Y') }}</td>
                        <td>
                            <x-ui.status-badge :status="$application->status">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </x-ui.status-badge>
                        </td>
                        <td class="text-sm text-slate-500">
                            {{ $application->attachment_name ?? 'Tidak ada' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <x-ui.empty-state
                                icon="briefcase"
                                title="Belum ada pelamar"
                                description="Pelamar akan muncul setelah ada lowongan aktif dan kandidat mengajukan lamaran."
                                ctaLabel="Kelola Lowongan"
                                ctaHref="{{ route('company.jobs.index') }}"
                                ctaVariant="secondary"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $applications->links() }}
        </div>
        @endif
    </x-ui.panel>
</x-app-layout>
