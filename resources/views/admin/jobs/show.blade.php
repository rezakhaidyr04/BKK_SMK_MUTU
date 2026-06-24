<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Detail Lowongan" subtitle="Detail lengkap lowongan dan pelamar.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.jobs.edit', $job) }}" size="sm">Ubah</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.panel title="Informasi Lowongan" class="mb-6">
        <div class="grid gap-6 md:grid-cols-2 text-sm">
            <div class="space-y-3">
                <p><span class="font-semibold text-slate-700">Judul:</span> {{ $job->title }}</p>
                <p><span class="font-semibold text-slate-700">Posisi:</span> {{ $job->position ?? '-' }}</p>
                <p><span class="font-semibold text-slate-700">Perusahaan:</span> {{ optional($job->company)->name ?? '-' }}</p>
                <p><span class="font-semibold text-slate-700">Lokasi:</span> {{ $job->location }}</p>
                <p><span class="font-semibold text-slate-700">Tipe:</span> {{ ucwords(str_replace('_', ' ', $job->job_type ?? '-')) }}</p>
                <p><span class="font-semibold text-slate-700">Status:</span>
                    <x-ui.status-badge :status="$job->status">{{ \App\Support\Label::jobStatus($job->status) }}</x-ui.status-badge>
                </p>
            </div>
            <div class="space-y-3">
                <p><span class="font-semibold text-slate-700">Deadline:</span> {{ optional($job->deadline)->format('d M Y') ?? '-' }}</p>
                <p><span class="font-semibold text-slate-700">Gaji:</span>
                    {{ $job->salary_min ? 'Rp ' . number_format($job->salary_min, 0, ',', '.') : '-' }}
                    –
                    {{ $job->salary_max ? 'Rp ' . number_format($job->salary_max, 0, ',', '.') : '-' }}
                </p>
                <p><span class="font-semibold text-slate-700">Diposting:</span> {{ $job->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </x-ui.panel>

    <x-ui.panel title="Deskripsi" class="mb-6">
        <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $job->description }}</div>
    </x-ui.panel>

    <x-ui.panel title="Pelamar">
        @if($job->applications->count() > 0)
        <div class="space-y-4">
            @foreach($job->applications as $application)
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="font-semibold text-slate-900">{{ optional($application->user)->name ?? 'Pengguna tidak tersedia' }}</p>
                <p class="text-sm text-slate-600">
                    {{ \App\Support\Label::applicationStatus($application->status) }} · {{ $application->created_at->diffForHumans() }}
                </p>
            </div>
            @endforeach
        </div>
        @else
        <x-ui.empty-state title="Belum ada pelamar" description="Lowongan ini belum menerima lamaran." />
        @endif
    </x-ui.panel>
</x-app-layout>
