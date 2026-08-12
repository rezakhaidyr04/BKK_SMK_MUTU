<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Lowongan Saya" subtitle="Kelola dan pantau lowongan pekerjaan perusahaan Anda.">
            <x-slot:actions>
                @if(auth()->user()->company?->is_verified)
                    <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Lowongan
                    </x-ui.btn>
                @else
                    <x-ui.status-badge status="pending">Perusahaan belum terverifikasi</x-ui.status-badge>
                @endif
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    @if(!auth()->user()->company?->is_verified)
    <x-ui.alert type="warning" class="mb-6">
        <div class="space-y-1">
            <p class="font-semibold">Perusahaan Anda belum terverifikasi.</p>
            <p class="text-sm">Verifikasi akan membantu meningkatkan kepercayaan pelamar dan memungkinkan Anda mengelola lowongan dengan lebih baik.</p>
            @if(auth()->user()->company?->verification_status === 'pending')
                <p class="text-sm">Permintaan verifikasi Anda sedang ditinjau. Silakan tunggu konfirmasi admin.</p>
            @elseif(auth()->user()->company?->verification_status === 'rejected')
                <p class="text-sm">Permintaan verifikasi sebelumnya ditolak. Mohon perbarui profil dan ajukan kembali.</p>
            @else
                <p class="text-sm">Lengkapi verifikasi perusahaan di halaman profil agar akun Anda bisa diverifikasi.</p>
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
                                <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
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
</x-app-layout>
