<x-app-layout>
    <div class="page-shell">
        <div class="page-container page-section">
            <x-ui.page-header title="Lowongan Saya" subtitle="Kelola dan pantau lowongan pekerjaan perusahaan Anda.">
                <x-slot:actions>
                @if(auth()->user()->company?->is_verified)
                    <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company">Buat Lowongan</x-ui.btn>
                @else
                    <span class="inline-flex items-center rounded-md bg-amber-100 px-3 py-2 text-sm font-medium text-amber-700">
                        Perusahaan belum terverifikasi
                    </span>
                @endif
            </x-slot:actions>
            </x-ui.page-header>

            @if(!auth()->user()->company?->is_verified)
                <x-ui.alert type="warning" class="mt-6">
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

            <div class="grid gap-6 mt-6">
                <x-ui.panel title="Filter Lowongan" subtitle="Temukan lowongan berdasarkan kata kunci dan status.">
                    <form method="GET" action="{{ route('company.jobs.index') }}" class="ui-panel-body grid gap-6 lg:grid-cols-[1.5fr_auto] items-end">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Cari lowongan</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, posisi, lokasi" class="ui-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                                <select name="status" class="ui-input">
                                    <option value="">Semua status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 justify-end">
                            <button type="submit" class="ui-btn ui-btn-secondary">Filter</button>
                            <a href="{{ route('company.jobs.index') }}" class="ui-btn ui-btn-ghost">Reset</a>
                        </div>
                    </form>
                </x-ui.panel>

                <x-ui.panel title="Daftar Lowongan" subtitle="{{ $jobs->total() }} lowongan ditemukan.">
                    <div class="ui-panel-body p-0">
                        <div class="p-4 space-y-3">
                            @forelse($jobs as $job)
                                <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all duration-150 border border-transparent hover:border-neutral-100">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-900 truncate">{{ $job->title }}</p>
                                        <p class="text-sm text-slate-500 truncate">{{ $job->position ?? '-' }} • {{ $job->job_type === 'full_time' ? 'Penuh Waktu' : ($job->job_type === 'part_time' ? 'Paruh Waktu' : ($job->job_type === 'internship' ? 'Magang' : 'Kontrak')) }}</p>
                                    </div>
                                    <div class="hidden sm:flex sm:items-center sm:gap-4">
                                        <div class="text-sm text-slate-500 text-center">
                                            <div class="text-lg font-bold text-slate-900">{{ $job->applications_count }}</div>
                                            <div class="text-xs">pelamar</div>
                                        </div>
                                        <div>
                                            <x-ui.status-badge :status="$job->status">{{ \App\Support\Label::jobStatus($job->status) }}</x-ui.status-badge>
                                        </div>
                                        <div class="text-sm text-slate-500 text-right">
                                            <div>{{ optional($job->deadline)->format('d M Y') ?? '-' }}</div>
                                        </div>
                                        <div class="pl-3">
                                            <x-ui.btn href="{{ route('jobs.show', $job->id) }}" variant="secondary" size="sm">Lihat</x-ui.btn>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12">
                                    <x-ui.empty-state title="Belum ada lowongan" description="Anda belum memposting lowongan pekerjaan apapun." />
                                </div>
                            @endforelse
                        </div>

                        @if($jobs->hasPages())
                            <div class="mt-6 pt-4 border-t border-slate-100">
                                {{ $jobs->links() }}
                            </div>
                        @endif
                    </div>
                </x-ui.panel>
            </div>
        </div>
    </div>
</x-app-layout>
