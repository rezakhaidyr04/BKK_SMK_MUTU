<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Laporan Admin" subtitle="Ringkasan performa sistem dan ekspor data.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.reports.export') }}" size="sm" class="!bg-emerald-600 hover:!bg-emerald-700 !shadow-emerald-600/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Ekspor CSV
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <x-ui.stat-card label="Total Siswa" :value="$summary['total_students']" color="blue" />
        <x-ui.stat-card label="Total Alumni" :value="$summary['total_alumni']" color="green" />
        <x-ui.stat-card label="Total Perusahaan" :value="$summary['total_companies']" color="purple" />
    </div>

    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <x-ui.stat-card label="Total Lowongan" :value="$summary['total_jobs']" color="indigo" />
        <x-ui.stat-card label="Lowongan Aktif" :value="$summary['active_jobs']" color="green" />
        <x-ui.stat-card label="Total Aplikasi" :value="$summary['total_applications']" color="blue" />
    </div>

    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        <x-ui.stat-card label="Aplikasi Diajukan" :value="$summary['submitted_applications']" color="yellow" />
        <x-ui.stat-card label="Aplikasi Diterima" :value="$summary['accepted_applications']" color="green" />
    </div>

    <x-ui.panel title="Pengguna Terbaru" class="mb-6">
        <div class="grid gap-4 md:grid-cols-2">
            @forelse($recentUsers as $user)
            <div class="rounded-xl border border-slate-200 p-4 hover:border-blue-200 transition-colors">
                <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                <p class="text-sm text-slate-500">{{ $user->email }} · {{ \App\Support\Label::role($user->role) }}</p>
            </div>
            @empty
            <p class="text-sm text-slate-500">Belum ada data pengguna.</p>
            @endforelse
        </div>
    </x-ui.panel>

    <div class="grid gap-6 lg:grid-cols-2">
        <x-ui.panel title="Perusahaan Terbaru">
            <div class="space-y-3">
                @forelse($recentCompanies as $company)
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="font-semibold text-slate-900">{{ $company->name }}</p>
                    <p class="text-sm text-slate-500">{{ $company->industry }} · {{ $company->created_at->format('d M Y') }}</p>
                </div>
                @empty
                <p class="text-sm text-slate-500">Belum ada perusahaan terdaftar.</p>
                @endforelse
            </div>
        </x-ui.panel>

        <x-ui.panel title="Lowongan Terbaru">
            <div class="space-y-3">
                @forelse($recentJobs as $job)
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="font-semibold text-slate-900">{{ $job->title }}</p>
                    <p class="text-sm text-slate-500">{{ optional($job->company)->name ?? '-' }} · {{ \App\Support\Label::jobStatus($job->status) }}</p>
                </div>
                @empty
                <p class="text-sm text-slate-500">Belum ada lowongan.</p>
                @endforelse
            </div>
        </x-ui.panel>
    </div>
</x-app-layout>
