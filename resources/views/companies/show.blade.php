<x-app-layout :full-bleed="true" :hide-sidebar="!auth()->check()" :title="$company->name . ' — Profil Perusahaan BKKMu'" :description="'Profil ' . $company->name . ' beserta lowongan aktif di BKKMu.'">
    <div class="page-shell">
        @php
            $heroSubtitle = $company->industry ?? 'Perusahaan mitra BKKMu';
            if ($company->address) $heroSubtitle .= ' • ' . Str::limit($company->address, 60);
        @endphp
        <x-ui.page-banner
            :title="$company->name"
            :subtitle="$heroSubtitle"
            eyebrow="Perusahaan"
        >
            <x-slot:logo>
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo {{ $company->name }}">
                @else
                    <div class="page-banner__logo bg-gradient-to-br from-blue-600 to-cyan-500">{{ strtoupper(substr($company->name, 0, 1)) }}</div>
                @endif
            </x-slot:logo>
            <x-slot:chips>
                @if($company->isApproved())
                    <span class="page-banner__chip">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Terverifikasi BKK
                    </span>
                @endif
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $stats['active_jobs'] }} Lowongan Aktif
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="#lowongan" variant="white" size="sm">Lihat Lowongan</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            <div class="grid gap-5 lg:grid-cols-3">
                {{-- Konten utama (2/3) --}}
                <div class="lg:col-span-2 space-y-5 min-w-0">
                    <x-ui.panel title="Tentang Perusahaan" data-reveal>
                        @if($company->description)
                            <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $company->description }}</p>
                            @if($company->industry)
                                <div class="flex flex-wrap gap-2 mt-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">{{ $company->industry }}</span>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center text-center py-6">
                                <span class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </span>
                                <p class="text-sm font-semibold text-slate-700">Belum ada deskripsi</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs">Perusahaan belum menambahkan deskripsi profilnya.</p>
                                @if($company->industry)
                                    <div class="flex flex-wrap justify-center gap-2 mt-3">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">{{ $company->industry }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </x-ui.panel>

                    @if($company->address || $company->website || $company->maps_url)
                    <x-ui.panel title="Lokasi" data-reveal>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            @if($company->address)
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900">Alamat perusahaan</p>
                                    <p class="text-sm text-slate-600 mt-0.5">{{ $company->address }}</p>
                                    @php
                                        $coMapsLink = $company->maps_url ?? ($company->address ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($company->address) : null);
                                    @endphp
                                    @if($coMapsLink)
                                    <a href="{{ $coMapsLink }}" target="_blank" rel="noopener" class="mt-1.5 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Buka Rute di Google Maps
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($company->website)
                                <x-ui.btn href="{{ $company->website }}" target="_blank" rel="noopener" variant="secondary" size="sm" class="shrink-0">
                                    Kunjungi Website
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </x-ui.btn>
                            @endif
                        </div>
                    </x-ui.panel>
                    @endif

                    <x-ui.panel title="Ulasan Pencari Kerja" data-reveal>
                        @if($reviewStats['count'] > 0)
                            <div class="flex flex-wrap items-center gap-3 mb-5">
                                <p class="text-3xl font-extrabold text-slate-900">{{ number_format($reviewStats['average'], 1) }}</p>
                                <div>
                                    <div class="flex items-center gap-0.5" aria-label="Rating {{ $reviewStats['average'] }} dari 5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= round($reviewStats['average']) ? 'fill-amber-400' : 'fill-slate-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        @endfor
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Berdasarkan {{ $reviewStats['count'] }} ulasan</p>
                                </div>
                                <a href="{{ route('reviews.create', ['company' => $company->id]) }}" class="ml-auto inline-flex items-center gap-1.5 px-4 py-2 border border-blue-200 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-50 transition">Beri Ulasan</a>
                            </div>
                            <ul class="space-y-4">
                                @foreach($reviews as $review)
                                    <li class="border-t border-slate-100 pt-4 first:border-0 first:pt-0">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <p class="text-sm font-bold text-slate-900">{{ $review->display_name }}</p>
                                            <span class="flex items-center gap-0.5" aria-label="{{ $review->rating }} dari 5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-amber-400' : 'fill-slate-200' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                                @endfor
                                            </span>
                                        </div>
                                        @if($review->job_title)
                                            <p class="text-[11px] font-semibold text-blue-600 mb-1">{{ $review->job_title }}</p>
                                        @endif
                                        <p class="text-sm text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                                        <p class="text-[11px] text-slate-400 mt-1.5">{{ $review->created_at->diffForHumans() }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center text-center py-4">
                                <p class="text-sm font-semibold text-slate-700">Belum ada ulasan</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs">Jadilah yang pertama membagikan pengalaman dengan perusahaan ini.</p>
                                <a href="{{ route('reviews.create', ['company' => $company->id]) }}" class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">Beri Ulasan Pertama</a>
                            </div>
                        @endif
                    </x-ui.panel>

                    <div id="lowongan" class="scroll-mt-24">
                        <div class="flex items-center justify-between mb-3 px-1">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                Lowongan di Perusahaan Ini
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-blue-600 text-white">{{ $activeJobs->total() }}</span>
                            </h3>
                            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700">
                                Semua lowongan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                        @if($activeJobs->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            @foreach($activeJobs as $job)
                            @php
                                // Tanpa ungu: part_time memakai sky (keluarga biru) sesuai constraint halaman ini.
                                $jobTypeClasses = match($job->job_type ?? null) {
                                    'full_time' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                    'part_time' => 'bg-sky-50 text-sky-700 border border-sky-100',
                                    'internship' => 'bg-green-50 text-green-700 border border-green-100',
                                    'contract' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                    default => 'bg-slate-100 text-slate-600 border border-slate-200',
                                };
                            @endphp
                            <div data-reveal class="group h-full bg-white rounded-2xl shadow-sm transition-all duration-200 border border-slate-200 hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 overflow-hidden">
                                <div class="p-4 h-full flex flex-col">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-medium uppercase tracking-wide {{ $jobTypeClasses }}">{{ \App\Support\Label::jobType($job->job_type) }}</span>
                                        <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 border border-slate-200">{{ $job->location }}</span>
                                        @if($job->created_at && $job->created_at->gte(now()->subDays(7)))
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[11px] font-bold text-green-700 border border-green-100">Baru</span>
                                        @endif
                                    </div>
                                    <h4 class="font-semibold text-slate-900 leading-tight">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="group-hover:text-blue-700 transition-colors">{{ $job->title }}</a>
                                    </h4>
                                    @if($job->salary_min || $job->salary_max)
                                    <div class="flex items-center gap-2 mt-2 rounded-xl bg-slate-50 border border-slate-200 px-3 py-2">
                                        <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-sm font-bold text-slate-900">Rp {{ number_format($job->salary_min ?? 0, 0, ',', '.') }}{{ $job->salary_max ? ' - ' . number_format($job->salary_max, 0, ',', '.') : '' }}</span>
                                    </div>
                                    @endif
                                    <div class="flex items-center gap-2 mt-3">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition text-center">
                                            Lihat Detail
                                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                    @if($job->deadline)
                                    <p class="mt-2 pt-2 border-t border-slate-100 text-[11px] text-slate-400 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Batas lamaran {{ $job->deadline->diffForHumans() }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-slate-500">Halaman <span class="font-semibold text-slate-700">{{ $activeJobs->currentPage() }}</span> dari <span class="font-semibold text-slate-700">{{ $activeJobs->lastPage() }}</span> · Total <span class="font-semibold text-slate-700">{{ $activeJobs->total() }}</span> lowongan</p>
                            <div>{{ $activeJobs->links() }}</div>
                        </div>
                        @else
                        <x-ui.panel>
                            <x-ui.empty-state
                                title="Belum ada lowongan aktif"
                                description="Perusahaan ini sedang tidak membuka lowongan. Cek kembali nanti atau jelajahi lowongan lain."
                            >
                                <x-slot:action>
                                    <x-ui.btn href="{{ route('jobs.index') }}">Jelajahi Lowongan</x-ui.btn>
                                </x-slot:action>
                            </x-ui.empty-state>
                        </x-ui.panel>
                        @endif
                    </div>
                </div>

                {{-- Sidebar (1/3): kontak + info + bagikan --}}
                <div class="space-y-5">
                    <x-ui.panel title="Kontak Perusahaan" data-reveal>
                        <div class="space-y-3">
                            @if($company->email)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Email</p>
                                    <a href="mailto:{{ $company->email }}" class="text-slate-700 font-medium hover:text-blue-600 break-all">{{ $company->email }}</a>
                                </div>
                            </div>
                            @endif
                            @if($company->phone)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Telepon</p>
                                    <a href="tel:{{ $company->phone }}" class="text-slate-700 font-medium hover:text-blue-600">{{ $company->phone }}</a>
                                </div>
                            </div>
                            @endif
                            @if($company->address)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Lokasi</p>
                                    <p class="text-slate-700 font-medium">{{ $company->address }}</p>
                                </div>
                            </div>
                            @endif
                            @if($company->website)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Website</p>
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener" class="text-blue-600 font-medium hover:underline break-all">{{ str_replace(['http://', 'https://'], '', $company->website) }}</a>
                                </div>
                            </div>
                            @endif
                            @if(!$company->email && !$company->phone && !$company->address && !$company->website)
                            <p class="text-sm text-slate-400 italic">Perusahaan belum menambahkan info kontak.</p>
                            @endif
                        </div>

                    </x-ui.panel>

                    <x-ui.panel title="Informasi" data-reveal>
                        <dl class="space-y-3 text-sm">
                            @if($company->industry)
                            <div class="flex items-center justify-between gap-2">
                                <dt class="text-slate-400">Industri</dt>
                                <dd class="font-semibold text-slate-700 text-right">{{ $company->industry }}</dd>
                            </div>
                            @endif
                            @if($company->address)
                            <div class="flex items-start justify-between gap-2">
                                <dt class="text-slate-400 shrink-0">Lokasi</dt>
                                <dd class="font-semibold text-slate-700 text-right">{{ Str::limit($company->address, 60) }}</dd>
                            </div>
                            @endif
                            @if($company->website)
                            <div class="flex items-center justify-between gap-2">
                                <dt class="text-slate-400">Website</dt>
                                <dd class="text-right"><a href="{{ $company->website }}" target="_blank" rel="noopener" class="font-semibold text-blue-600 hover:underline">Kunjungi</a></dd>
                            </div>
                            @endif
                            <div class="flex items-center justify-between gap-2">
                                <dt class="text-slate-400">Status</dt>
                                <dd>
                                    @if($company->isApproved())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-100">Terverifikasi</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">Terdaftar</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </x-ui.panel>

                    <x-ui.panel title="Bagikan" data-reveal>
                        <p class="text-sm text-slate-500 mb-3">Sebarkan profil perusahaan ini ke teman yang membutuhkan.</p>
                        <button type="button" data-copy-link="{{ route('companies.show', $company) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span data-copy-label>Salin Tautan</span>
                        </button>
                    </x-ui.panel>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            document.querySelectorAll('[data-copy-link]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var url = btn.getAttribute('data-copy-link');
                    var label = btn.querySelector('[data-copy-label]');
                    function done() {
                        if (label) label.textContent = 'Tautan disalin!';
                        if (window.toast) window.toast.success('Tautan profil disalin.');
                        setTimeout(function () { if (label) label.textContent = 'Salin Tautan'; }, 2000);
                    }
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(url).then(done).catch(done);
                    } else {
                        var ta = document.createElement('textarea');
                        ta.value = url;
                        document.body.appendChild(ta);
                        ta.select();
                        try { document.execCommand('copy'); } catch (e) {}
                        document.body.removeChild(ta);
                        done();
                    }
                });
            });
        })();
    </script>
    @endpush
</x-app-layout>
