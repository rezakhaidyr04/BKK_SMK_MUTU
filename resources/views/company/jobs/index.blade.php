<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Lowongan Saya" subtitle="Kelola dan pantau lowongan pekerjaan perusahaan Anda." eyebrow="Perusahaan › Lowongan">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $jobs->total() }} Lowongan · Kelola Lowongan
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Dashboard Perusahaan
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Lowongan
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    @if(!auth()->user()->company?->isApproved())
    <x-ui.alert type="info" class="mb-6">
        <div class="space-y-1">
            <p class="font-semibold">Lowongan yang Anda buat menunggu persetujuan admin sebelum tayang.</p>
            <p class="text-sm">Verifikasi perusahaan diperlukan untuk memposting lowongan.</p>
            @if(auth()->user()->company?->verification_status === 'pending')
                <p class="text-sm">Permintaan verifikasi Anda sedang ditinjau.</p>
            @elseif(auth()->user()->company?->verification_status === 'rejected')
                <p class="text-sm">Permintaan verifikasi sebelumnya ditolak. Mohon perbarui profil dan ajukan kembali.</p>
            @else
                <p class="text-sm">Lengkapi verifikasi perusahaan di halaman profil.</p>
            @endif
        </div>
    </x-ui.alert>
    @endif

    <div class="ui-filter-bar">
        <form method="GET" action="{{ route('company.jobs.index') }}" class="grid gap-4 md:grid-cols-4 w-full">
            <div class="ui-filter-field">
                <label class="ui-label">Cari Lowongan</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, posisi, lokasi" class="ui-input" />
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Status</label>
                <select name="status" class="ui-select">
                    <option value="">Semua status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="closed"   {{ request('status') === 'closed'   ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            <div class="flex items-end gap-2 md:col-span-2">
                <x-ui.btn type="submit">Saring</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('company.jobs.index') }}">Atur Ulang</x-ui.btn>
            </div>
        </form>
    </div>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Lowongan</th>
                        <th>Tipe</th>
                        <th>Pelamar</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr>
                        <td>
                            <p class="font-semibold text-slate-900">{{ $job->title }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $job->position ?? '-' }}</p>
                        </td>
                        <td class="text-sm text-slate-600">
                            {{ \App\Support\Label::jobType($job->job_type) }}
                        </td>
                        <td>
                            <span class="text-lg font-bold text-slate-900">{{ $job->applications_count }}</span>
                            <span class="text-xs text-slate-400 ml-1">pelamar</span>
                        </td>
                        <td class="text-sm text-slate-500">
                            {{ optional($job->deadline)->format('d M Y') ?? '-' }}
                        </td>
                        <td>
                            <x-ui.status-badge :status="$job->status">
                                {{ \App\Support\Label::jobStatus($job->status) }}
                            </x-ui.status-badge>
                        </td>
                        <td>
                            <div class="ui-table-actions">
                                <x-ui.btn href="{{ route('jobs.show', $job->id) }}" variant="secondary" size="sm">Lihat</x-ui.btn>
                                @can('update', $job)
                                    <x-ui.btn href="{{ route('company.jobs.edit', $job->id) }}" variant="secondary" size="sm">Edit</x-ui.btn>
                                @endcan
                                @can('close', $job)
                                    @if($job->status === 'active')
                                        <form method="POST" action="{{ route('company.jobs.close', $job->id) }}" class="inline" onsubmit="return confirm('Tutup lowongan ini? Lamaran baru tidak lagi diterima.')">
                                            @csrf
                                            <x-ui.btn type="submit" variant="secondary" size="sm">Tutup</x-ui.btn>
                                        </form>
                                    @endif
                                @endcan
                                @can('delete', $job)
                                    <form method="POST" action="{{ route('company.jobs.destroy', $job->id) }}" class="inline" onsubmit="return confirm('Hapus lowongan ini? Hanya bisa jika belum ada lamaran.')">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.btn type="submit" variant="secondary" size="sm">Hapus</x-ui.btn>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <x-ui.empty-state
                                icon="briefcase"
                                title="Belum ada lowongan"
                                description="Anda belum memposting lowongan pekerjaan apapun."
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $jobs->links() }}
        </div>
        @endif
    </x-ui.panel>
        </div>
    </div>
</x-app-layout>
