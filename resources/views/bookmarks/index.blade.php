<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Lowongan Tersimpan" subtitle="Lowongan yang Anda tandai untuk dilihat nanti." eyebrow="Dashboard › Tersimpan">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    {{ $bookmarks->total() ?? $bookmarks->count() }} Tersimpan · Shortlist
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Pencari Kerja
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('jobs.index') }}" variant="white" size="md" class="shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari Lowongan
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            {{-- RINGKASAN — kartu putih ala Lamaran, bukan hijau polos --}}
            <div data-reveal class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-slate-900 text-base font-bold">{{ $bookmarks->total() ?? $bookmarks->count() }} lowongan tersimpan</p>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs font-semibold">
                                    Shortlist pribadi
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">Simpan lowongan yang menarik, bandingkan gaji & lokasi, lalu kirim lamaran saat sudah yakin.</p>
                        </div>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Cari Lowongan
                    </a>
                </div>
            </div>

            @if($bookmarks->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8 items-stretch">
                @foreach($bookmarks as $bookmark)
                @php
                    $job = $bookmark->job;
                    $jobIsAvailable = $job && !$job->trashed();
                    $jobTitle = $jobIsAvailable ? $job->title : 'Lowongan yang sudah dihapus';
                    $jobCompany = $jobIsAvailable ? ($job->company_name ?? $job->company->name ?? 'Perusahaan') : 'Perusahaan';
                    $initial = strtoupper(substr($jobCompany, 0, 1));
                    $logo = $jobIsAvailable ? ($job->company->logo ?? null) : null;
                    $jobTypeClasses = match($job->job_type ?? null) {
                        'full_time' => 'bg-blue-50 text-blue-700 border border-blue-100',
                        'part_time' => 'bg-violet-50 text-violet-700 border border-violet-100',
                        'internship' => 'bg-green-50 text-green-700 border border-green-100',
                        'contract' => 'bg-amber-50 text-amber-700 border border-amber-100',
                        default => 'bg-slate-100 text-slate-600 border border-slate-200',
                    };
                @endphp
                <div data-reveal class="h-full bg-white rounded-2xl shadow-sm transition-all duration-200 border border-slate-200 hover:border-slate-300 overflow-hidden group {{ $jobIsAvailable ? '' : 'opacity-75' }}">
                    <div class="p-4 sm:p-5 h-full flex flex-col">
                        <div class="flex items-start justify-between gap-3 sm:gap-4 mb-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    @if($jobIsAvailable)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-medium uppercase tracking-wide {{ $jobTypeClasses }}">{{ \App\Support\Label::jobType($job->job_type) }}</span>
                                        <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 border border-slate-200">{{ $job->location }}</span>
                                        @if($job->created_at && $job->created_at->gte(now()->subDays(7)))
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[11px] font-medium text-green-700 border border-green-100">Baru</span>
                                        @endif
                                        @if($job->deadline && $job->deadline->lte(now()->addDays(3)) && $job->deadline->gte(now()->startOfDay()))
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-medium text-amber-700 border border-amber-100">Deadline dekat</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-500 border border-slate-200">Tidak tersedia</span>
                                    @endif
                                </div>
                                <h3 class="text-lg sm:text-[1.15rem] font-semibold text-slate-900 transition-colors leading-tight">
                                    @if($jobIsAvailable)
                                        <a href="{{ route('jobs.show', $job->id) }}" class="hover:text-blue-700">{{ $jobTitle }}</a>
                                    @else
                                        {{ $jobTitle }}
                                    @endif
                                </h3>
                                <p class="text-sm text-slate-600 font-medium mt-1.5">{{ $jobCompany }}</p>
                            </div>
                            @if($logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="Logo {{ $jobCompany }}" class="shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border border-slate-200 bg-white">
                            @else
                                <div class="shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 text-sm sm:text-base font-semibold border border-slate-200">
                                    {{ $initial }}
                                </div>
                            @endif
                        </div>

                        @if($jobIsAvailable && $job->deadline)
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $job->isExpired() ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Deadline {{ $job->deadline->diffForHumans() }}
                            </span>
                        </div>
                        @endif

                        @if($jobIsAvailable && ($job->salary_min || $job->salary_max))
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 mb-3 rounded-xl bg-slate-50 border border-slate-200 px-3.5 py-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-[0.14em]">Estimasi gaji</span>
                            </div>
                            <span class="text-base sm:text-lg font-semibold text-slate-900">
                                Rp {{ number_format($job->salary_min ?? 0, 0, ',', '.') }} - {{ number_format($job->salary_max ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif

                        @if($jobIsAvailable && $job->description)
                        <p class="text-sm text-slate-600 line-clamp-2 mb-4 leading-relaxed min-h-[2.75rem]">
                            {{ Str::limit(strip_tags($job->description), 120) }}
                        </p>
                        @endif

                        <div class="flex items-center gap-2.5 mt-auto">
                            @if($jobIsAvailable)
                                <a href="{{ route('jobs.show', $job->id) }}" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition text-center">
                                    Lihat Lowongan
                                </a>
                            @else
                                <span class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-400 rounded-lg text-sm font-medium text-center cursor-not-allowed">
                                    Lowongan Tidak Tersedia
                                </span>
                            @endif
                            <form action="{{ route('bookmarks.destroy', $bookmark->id) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini dari simpanan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Hapus dari simpanan" title="Hapus dari simpanan" class="p-2.5 border border-slate-200 rounded-lg text-slate-500 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-100 text-sm">
                            <span class="text-slate-500 text-xs">
                                Disimpan {{ $bookmark->created_at->diffForHumans() }}
                            </span>
                            @if($jobIsAvailable)
                                <span class="font-medium text-slate-500 text-xs">
                                    Diposting {{ $job->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm text-slate-500">Halaman <span class="font-semibold text-slate-700">{{ $bookmarks->currentPage() }}</span> dari <span class="font-semibold text-slate-700">{{ $bookmarks->lastPage() }}</span> · Total <span class="font-semibold text-slate-700">{{ $bookmarks->total() }}</span> tersimpan</p>
                <div>{{ $bookmarks->links() }}</div>
            </div>
            @else
            <x-ui.panel>
                <x-ui.empty-state
                    title="Belum ada lowongan tersimpan"
                    description="Belum ada shortlist. Simpan lowongan yang cocok agar mudah dibandingkan dan dibuka kembali nanti."
                >
                    <x-slot:action>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <x-ui.btn href="{{ route('jobs.index') }}">Jelajahi Lowongan</x-ui.btn>
                            <x-ui.btn href="{{ route('dashboard') }}" variant="secondary">Kembali ke Dasbor</x-ui.btn>
                        </div>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-ui.panel>
            @endif
        </div>
    </div>
</x-app-layout>
