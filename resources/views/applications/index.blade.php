<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero title="Lamaran Saya" subtitle="Lacak dan kelola semua lamaran pekerjaan Anda dalam satu tempat." />

        <div class="page-container page-section">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <x-ui.stat-card label="Total" :value="$stats['total']" color="gray" />
                <x-ui.stat-card label="Terkirim" :value="$stats['submitted']" color="blue" />
                <x-ui.stat-card label="Ditinjau" :value="$stats['under_review']" color="yellow" />
                <x-ui.stat-card label="Wawancara" :value="$stats['interviewed']" color="purple" />
                <x-ui.stat-card label="Diterima" :value="$stats['accepted']" color="green" />
                <x-ui.stat-card label="Ditolak" :value="$stats['rejected']" color="red" />
            </div>

            <div class="ui-filter-bar mb-6">
                <form action="{{ route('applications.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full">
                    <label class="ui-label mb-0">Filter Status</label>
                    <select name="status" class="ui-select max-w-xs" onchange="this.form.submit()">
                        <option value="">Semua Lamaran</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Terkirim</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Ditinjau</option>
                        <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>Wawancara</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @if(request('status'))
                    <x-ui.btn variant="ghost" href="{{ route('applications.index') }}" size="sm">Hapus Filter</x-ui.btn>
                    @endif
                </form>
            </div>

            @if($applications->count() > 0)
            <div class="space-y-4">
                @foreach($applications as $application)
                <div class="ui-panel hover:shadow-md transition-shadow">
                    <div class="ui-panel-body">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-slate-900">{{ $application->job->title }}</h3>
                                <p class="text-sm text-slate-600 mt-1">{{ $application->job->company->name ?? __('bkk.fallback.company') }}</p>
                                <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-slate-500">
                                    <span>{{ $application->created_at->diffForHumans() }}</span>
                                    @if($application->job->location)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $application->job->location }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-start sm:items-end gap-3">
                                <x-ui.status-badge :status="$application->status" />
                                <x-ui.btn variant="ghost" href="{{ route('applications.show', $application->id) }}" size="sm">
                                    Lihat Detail →
                                </x-ui.btn>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $applications->links() }}</div>
            @else
            <x-ui.panel>
                <x-ui.empty-state
                    title="Belum ada lamaran"
                    description="Mulai melamar lowongan yang sesuai dengan keahlian Anda."
                >
                    <x-slot:action>
                        <x-ui.btn href="{{ route('jobs.index') }}">Jelajahi Lowongan</x-ui.btn>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-ui.panel>
            @endif
        </div>
    </div>
</x-app-layout>
