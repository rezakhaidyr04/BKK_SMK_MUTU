<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Lamaran Saya" subtitle="Lacak dan kelola semua lamaran pekerjaan Anda." eyebrow="Dashboard › Lamaran">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ $applications->total() ?? $stats['total'] }} Lamaran · Lacak Status
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Pencari Kerja
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('jobs.index') }}" variant="white" size="md" class="shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Lamar Lowongan Lain
                </x-ui.btn>
                <a href="#daftar-lamaran" class="page-banner__back !mb-0 font-semibold">
                    Lihat progres saya ↓
                </a>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            @php
                $activeFilter = request('status', '');
                $q = request('q', '');
                $activeCount = $stats['total'] - $stats['accepted'] - $stats['rejected'];
                $interviewRate = $stats['total'] > 0 ? round($stats['interviewed'] / $stats['total'] * 100) : 0;
                $progressPct = $stats['total'] > 0 ? round(($stats['accepted'] + $stats['rejected']) / $stats['total'] * 100) : 0;
                // Kartu statistik — selaras dashboard-stat-card: putih, ikon bg-*-100, aksen status standar
                $cards = [
                    ['key' => '', 'label' => 'Total', 'value' => $stats['total'], 'hint' => 'Semua lamaran', 'icon' => 'doc', 'color' => 'slate', 'iconBg' => 'bg-slate-100', 'iconClr' => 'text-slate-600'],
                    ['key' => 'submitted', 'label' => 'Terkirim', 'value' => $stats['submitted'], 'hint' => 'Menunggu dibaca HRD', 'icon' => 'mail', 'color' => 'blue', 'iconBg' => 'bg-blue-100', 'iconClr' => 'text-blue-600'],
                    ['key' => 'under_review', 'label' => 'Ditinjau', 'value' => $stats['under_review'], 'hint' => 'Berkas diperiksa', 'icon' => 'eye', 'color' => 'yellow', 'iconBg' => 'bg-amber-100', 'iconClr' => 'text-amber-600'],
                    ['key' => 'interviewed', 'label' => 'Wawancara', 'value' => $stats['interviewed'], 'hint' => 'Jadwal interview', 'icon' => 'cal', 'color' => 'purple', 'iconBg' => 'bg-violet-100', 'iconClr' => 'text-violet-600'],
                    ['key' => 'accepted', 'label' => 'Diterima', 'value' => $stats['accepted'], 'hint' => 'Lolos seleksi', 'icon' => 'check', 'color' => 'green', 'iconBg' => 'bg-green-100', 'iconClr' => 'text-green-600'],
                    ['key' => 'rejected', 'label' => 'Ditolak', 'value' => $stats['rejected'], 'hint' => 'Coba lowongan lain', 'icon' => 'x', 'color' => 'red', 'iconBg' => 'bg-red-100', 'iconClr' => 'text-red-500'],
                ];
                // Pill & dot status — sama seperti jobs/index (blue/violet/green/amber/slate) + badge (red)
                $statusMeta = [
                    'submitted' => ['label' => 'Terkirim', 'pill' => 'bg-blue-50 text-blue-700 border-blue-100', 'dot' => 'bg-blue-600', 'step' => 0, 'bar' => 'bg-blue-600'],
                    'under_review' => ['label' => 'Ditinjau', 'pill' => 'bg-amber-50 text-amber-700 border-amber-100', 'dot' => 'bg-amber-500', 'step' => 1, 'bar' => 'bg-amber-500'],
                    'interviewed' => ['label' => 'Wawancara', 'pill' => 'bg-violet-50 text-violet-700 border-violet-100', 'dot' => 'bg-violet-600', 'step' => 2, 'bar' => 'bg-violet-600'],
                    'accepted' => ['label' => 'Diterima', 'pill' => 'bg-green-50 text-green-700 border-green-100', 'dot' => 'bg-green-600', 'step' => 3, 'bar' => 'bg-green-600'],
                    'rejected' => ['label' => 'Ditolak', 'pill' => 'bg-red-50 text-red-600 border-red-100', 'dot' => 'bg-red-500', 'step' => 3, 'bar' => 'bg-red-500'],
                ];
                $steps = ['Terkirim', 'Ditinjau', 'Wawancara', 'Hasil'];
            @endphp

            {{-- RINGKASAN — kartu putih ala dashboard/jobs, bukan gradient neon --}}
            <div data-reveal class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">
                <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-slate-900 text-base sm:text-lg font-bold">{{ $stats['total'] }} lamaran terkirim</p>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs font-semibold">
                                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-60"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span></span>
                                    {{ $activeCount }} masih berproses
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">Perusahaan biasanya merespons dalam <span class="font-semibold text-slate-700">3–7 hari kerja</span>. Aktifkan notifikasi email agar tidak ketinggalan panggilan interview.</p>
                            <div class="mt-3 max-w-xl">
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <span class="text-slate-500 font-medium">Progres penyelesaian</span>
                                    <span class="text-slate-900 font-bold">{{ $progressPct }}% selesai</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="lamaran-progress bg-blue-600 h-2.5 rounded-full transition-all duration-1000" style="width: 0%" data-width="{{ $progressPct }}%"></div>
                                </div>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                    <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Peluang interview {{ $interviewRate }}%
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                    <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    Notifikasi HRD aktif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex sm:items-center gap-2.5 lg:flex-col lg:items-stretch lg:w-56 flex-shrink-0">
                        <a href="{{ route('jobs.index') }}" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Lamar Lowongan Lain
                        </a>
                        <a href="#daftar-lamaran" class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:border-slate-300 hover:bg-slate-50 transition text-center">
                            Lihat progres
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATISTIK — gaya dashboard-stat-card (putih, ikon bg-*-100) --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 mb-4" data-stagger>
                @foreach($cards as $c)
                    @php
                        $isActive = $activeFilter === $c['key'];
                        $pct = $stats['total'] > 0 ? round($c['value'] / $stats['total'] * 100) : 0;
                        $link = $c['key'] === '' ? route('applications.index', array_filter(['q' => $q])) : route('applications.index', array_filter(['status' => $c['key'], 'q' => $q]));
                    @endphp
                    <a data-reveal href="{{ $link }}"
                       class="ui-stat-card ui-stat-card-{{ $c['color'] }} !p-4 flex-col !items-stretch gap-0 transition-all duration-200 hover:-translate-y-0.5 {{ $isActive ? '!border-blue-600 ring-2 ring-blue-100' : '' }}">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-slate-400">{{ $c['label'] }}</p>
                                <p class="text-3xl font-extrabold tracking-tight text-slate-900"><span data-count="{{ $c['value'] }}">{{ $c['value'] }}</span></p>
                            </div>
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $c['iconBg'] }} {{ $c['iconClr'] }}">
                                @if($c['icon'] === 'doc')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @elseif($c['icon'] === 'mail')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                @elseif($c['icon'] === 'eye')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                @elseif($c['icon'] === 'cal')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @elseif($c['icon'] === 'check')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 border-t border-slate-100 pt-2.5">
                            <p class="text-xs text-slate-400">{{ $c['hint'] }} · <span class="font-semibold {{ $isActive ? 'text-blue-700' : 'text-slate-500' }}">{{ $pct }}%</span></p>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- FILTER + SEARCH — gaya jobs results header --}}
            <div data-reveal class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-4 overflow-hidden">
                <div class="p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center gap-3">
                    <form method="GET" action="{{ route('applications.index') }}" class="flex-1 flex items-center gap-2 min-w-0">
                        @if($activeFilter !== '')
                            <input type="hidden" name="status" value="{{ $activeFilter }}">
                        @endif
                        <div class="relative flex-1 min-w-0">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="q" value="{{ $q }}" placeholder="Cari posisi, perusahaan, atau lokasi…"
                                class="w-full pl-9 pr-9 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" />
                            @if($q !== '')
                                <a href="{{ $activeFilter !== '' ? route('applications.index', ['status' => $activeFilter]) : route('applications.index') }}"
                                   class="absolute right-2.5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors" title="Hapus pencarian">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span class="hidden sm:inline">Cari</span>
                        </button>
                    </form>
                    <div class="flex items-center gap-2 text-sm text-slate-500 flex-shrink-0">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span>Menampilkan <span class="font-semibold text-slate-700">{{ $applications->firstItem() ?? 0 }}–{{ $applications->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-700">{{ $applications->total() }}</span> lamaran</span>
                    </div>
                </div>
                <div class="border-t border-slate-100 px-4 sm:px-5 py-3 overflow-x-auto">
                    <div class="flex items-center gap-2 min-w-max">
                        @php
                            $tabs = [
                                ['key'=>'','label'=>'Semua','count'=>$stats['total']],
                                ['key'=>'submitted','label'=>'Terkirim','count'=>$stats['submitted']],
                                ['key'=>'under_review','label'=>'Ditinjau','count'=>$stats['under_review']],
                                ['key'=>'interviewed','label'=>'Wawancara','count'=>$stats['interviewed']],
                                ['key'=>'accepted','label'=>'Diterima','count'=>$stats['accepted']],
                                ['key'=>'rejected','label'=>'Ditolak','count'=>$stats['rejected']],
                            ];
                        @endphp
                        @foreach($tabs as $t)
                            @php
                                $on = $activeFilter === $t['key'];
                                $url = $t['key']==='' ? route('applications.index', array_filter(['q'=>$q])) : route('applications.index', array_filter(['status'=>$t['key'],'q'=>$q]));
                            @endphp
                            <a href="{{ $url }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[13px] font-medium border transition-colors {{ $on ? 'bg-blue-600 border-blue-600 text-white' : 'bg-slate-50 border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-800' }}">
                                {{ $t['label'] }}
                                <span class="px-1.5 py-0.5 rounded-full text-[11px] font-semibold {{ $on ? 'bg-white/20 text-white' : 'bg-white text-slate-500 border border-slate-200' }}">{{ $t['count'] }}</span>
                            </a>
                        @endforeach
                        @if($activeFilter !== '' || $q !== '')
                            <a href="{{ route('applications.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-slate-500 hover:text-slate-800 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M10 18h4"/></svg>
                                Hapus Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($applications->count() > 0)
            <div id="daftar-lamaran" class="space-y-4">
                @foreach($applications as $idx => $application)
                @php
                    $meta = $statusMeta[$application->status] ?? ['label'=>ucfirst($application->status),'pill'=>'bg-slate-100 text-slate-600 border-slate-200','dot'=>'bg-slate-400','step'=>0,'bar'=>'bg-slate-300'];
                    $job = $application->job;
                    $companyName = $job->company_name ?? $job->company->name ?? 'Perusahaan';
                    $initial = strtoupper(substr($companyName, 0, 1));
                    $logo = $job->company->logo ?? null;
                    $cur = $meta['step'];
                    $isRejected = $application->status === 'rejected';
                    $isAccepted = $application->status === 'accepted';
                    $progress = $isAccepted ? 100 : ($isRejected ? 100 : (int) round($cur / 3 * 100));
                    $jobTypeClasses = match($job->job_type ?? null) {
                        'full_time' => 'bg-blue-50 text-blue-700 border border-blue-100',
                        'part_time' => 'bg-violet-50 text-violet-700 border border-violet-100',
                        'internship' => 'bg-green-50 text-green-700 border border-green-100',
                        'contract' => 'bg-amber-50 text-amber-700 border border-amber-100',
                        default => 'bg-slate-100 text-slate-600 border border-slate-200',
                    };
                @endphp
                <article data-reveal style="transition-delay: {{ min($idx,5)*60 }}ms"
                    class="group relative bg-white rounded-2xl border border-slate-200 hover:border-slate-300 shadow-sm transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $meta['bar'] }}"></div>

                    <div class="relative p-3">
                        <div class="flex flex-col xl:flex-row gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[11px] font-medium border {{ $meta['pill'] }}">
                                        <span class="relative flex h-1.5 w-1.5">
                                            @if(!$isAccepted && !$isRejected)
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-60 {{ $meta['dot'] }}"></span>
                                            @endif
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 {{ $meta['dot'] }}"></span>
                                        </span>
                                        {{ $meta['label'] }}
                                    </span>
                                    @if($job->job_type)
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium {{ $jobTypeClasses }}">{{ \App\Support\Label::jobType($job->job_type) }}</span>
                                    @endif
                                    @if($job->location)
                                        <span class="inline-flex items-center rounded-full bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-600 border border-slate-200">{{ $job->location }}</span>
                                    @endif
                                    <span class="text-[11px] text-slate-400">· Dilamar {{ $application->created_at->format('d M Y') }} ({{ $application->created_at->diffForHumans() }})</span>
                                </div>
                                <div class="flex items-center gap-2.5 mb-2">
                                    @if($logo)
                                        <img src="{{ asset('storage/' . $logo) }}" alt="Logo {{ $companyName }}" class="shrink-0 w-9 h-9 rounded-full object-cover border border-slate-200 bg-white">
                                    @else
                                        <div class="shrink-0 w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 text-[13px] font-semibold border border-slate-200">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-slate-900 leading-tight">
                                            <a href="{{ route('applications.show', $application->id) }}" class="hover:text-blue-700 transition-colors">{{ $job->title ?? 'Lowongan sudah dihapus' }}</a>
                                        </h3>
                                        <p class="text-xs text-slate-600 font-medium mt-0.5">{{ $companyName }}</p>
                                    </div>
                                </div>

                                @if($job->salary_min || $job->salary_max)
                                <div class="flex items-center gap-1.5 mb-2 rounded-lg bg-slate-50 border border-slate-200 px-2.5 py-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-[13px] font-semibold text-slate-900">Rp {{ number_format($job->salary_min ?? 0,0,',','.') }}{{ $job->salary_max ? ' - '.number_format($job->salary_max,0,',','.') : '' }}</span>
                                </div>
                                @endif

                                {{-- TRACKER — satu garis utuh di belakang lingkaran biar lurus & simetris --}}
                                <div class="rounded-xl border border-slate-200 bg-slate-50/60 px-3 pt-2 pb-2">
                                    @php $fillPct = $isAccepted ? 100 : ($isRejected ? 100 : round($cur / 3 * 100, 1)); @endphp
                                    <div class="flex items-center justify-between mb-1.5">
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">Progres lamaran</p>
                                        <p class="text-[10px] font-medium text-slate-400">Langkah {{ min($cur+1,4) }} dari 4 · <span class="font-bold text-slate-600">{{ $isAccepted || $isRejected ? 100 : (int) $fillPct }}%</span></p>
                                    </div>
                                    <div class="relative px-4">
                                        <div class="absolute left-11 right-11 h-0.5 rounded-full bg-slate-200 overflow-hidden" style="top: 11px;" aria-hidden="true">
                                            <div class="h-full {{ $isRejected ? 'bg-red-500' : ($isAccepted ? 'bg-green-600' : 'bg-blue-600') }}" style="width: {{ $fillPct }}%"></div>
                                        </div>
                                        <div class="relative flex items-start justify-between">
                                            @foreach($steps as $idx2 => $label)
                                                @php
                                                    $done = $idx2 < $cur || $isAccepted;
                                                    $current = $idx2 === $cur && !$isAccepted && !$isRejected;
                                                    $finalRejected = $isRejected && $idx2 === 3;
                                                    $finalAccepted = $isAccepted && $idx2 === 3;
                                                @endphp
                                                <div class="flex flex-col items-center gap-0.5 w-14">
                                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold border-2 transition-all
                                                        {{ $finalRejected ? 'bg-red-500 border-red-500 text-white' : ($finalAccepted ? 'bg-green-600 border-green-600 text-white' : ($done ? 'bg-blue-600 border-blue-600 text-white' : ($current ? 'border-blue-600 text-blue-700 bg-blue-50' : 'bg-white border-slate-200 text-slate-400'))) }}">
                                                        @if(($done && !$current) || $finalAccepted)
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        @elseif($finalRejected)
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        @else
                                                            {{ $idx2 + 1 }}
                                                        @endif
                                                    </span>
                                                    <span class="text-[10px] leading-tight text-center {{ $done || $current || $finalRejected || $finalAccepted ? 'text-slate-700 font-semibold' : 'text-slate-400 font-medium' }}">{{ $isRejected && $idx2===3 ? 'Ditolak' : ($isAccepted && $idx2===3 ? 'Diterima' : $label) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                @if($application->status==='interviewed' && $application->interview_date)
                                    <div class="mt-2 rounded-xl border border-violet-100 bg-violet-50 px-2.5 py-2">
                                        <div class="flex items-start gap-2">
                                            <span class="w-7 h-7 rounded-lg bg-violet-600 text-white flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </span>
                                            <div class="min-w-0">
                                                <p class="text-xs text-slate-700">
                                                    <span class="font-semibold text-slate-900">Interview {{ $application->interview_date->format('d M Y, H:i') }} WIB</span>
                                                    @if($application->interview_location)<span class="text-slate-400"> • </span>{{ $application->interview_location }}@endif
                                                </p>
                                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                                    @if($application->interview_type === 'online' && $application->interview_link)
                                                        <a href="{{ $application->interview_link }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-600 text-white text-xs font-medium hover:bg-violet-700 transition-colors">
                                                            Gabung meeting
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        </a>
                                                    @endif
                                                    <span class="text-xs text-slate-500">Siapkan CV, pakaian rapi & datang 15 mnt lebih awal</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($isAccepted)
                                    <div class="mt-2 flex items-start gap-2 rounded-xl border border-green-100 bg-green-50 px-2.5 py-2">
                                        <span class="w-7 h-7 rounded-lg bg-green-600 text-white flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                        <p class="text-xs text-slate-700"><span class="font-semibold text-slate-900">Selamat, Anda lolos!</span> Tunggu hubungi HRD untuk tahap onboarding.</p>
                                    </div>
                                @elseif($isRejected)
                                    <div class="mt-2 flex items-start gap-2 rounded-xl border border-slate-200 bg-slate-50 px-2.5 py-2">
                                        <span class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                        <p class="text-xs text-slate-500">Belum lolos kali ini — perbaiki CV dan coba lowongan lain yang sesuai. <a href="{{ route('jobs.index') }}" class="font-medium text-blue-600 hover:underline">Cari lagi</a></p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-row lg:flex-col items-center lg:items-stretch lg:justify-end gap-1.5 flex-shrink-0 lg:w-36 lg:pl-4 lg:border-l lg:border-slate-100">
                                <a href="{{ route('applications.show', $application->id) }}"
                                   class="h-fit whitespace-nowrap inline-flex items-center justify-center gap-1 px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition">
                                    Detail lamaran
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                                <a href="{{ route('jobs.show', $application->job_id) }}"
                                   class="h-fit whitespace-nowrap inline-flex items-center justify-center px-3 py-1 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:border-slate-300 hover:bg-slate-50 transition">
                                    Lowongan
                                </a>
                                <p class="hidden lg:block text-[11px] text-slate-400 text-right">Update {{ $application->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm text-slate-500">Halaman <span class="font-semibold text-slate-700">{{ $applications->currentPage() }}</span> dari <span class="font-semibold text-slate-700">{{ $applications->lastPage() }}</span> · Total <span class="font-semibold text-slate-700">{{ $applications->total() }}</span> lamaran</p>
                <div>{{ $applications->links() }}</div>
            </div>
            @else
            <x-ui.panel>
                <x-ui.empty-state
                    title="{{ ($activeFilter !== '' || $q !== '') ? 'Tidak ada lamaran yang cocok' : 'Belum ada lamaran' }}"
                    description="{{ ($activeFilter !== '' || $q !== '') ? 'Coba ubah kata kunci atau ganti tab status di atas untuk melihat lamaran lain.' : 'Cari lowongan yang sesuai minat Anda, lalu kirim lamaran pertama dari halaman lowongan.' }}"
                >
                    <x-slot:action>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            @if($activeFilter !== '' || $q !== '')
                                <x-ui.btn variant="secondary" href="{{ route('applications.index') }}">Tampilkan semua</x-ui.btn>
                            @endif
                            <x-ui.btn href="{{ route('jobs.index') }}">Cari lowongan</x-ui.btn>
                        </div>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-ui.panel>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            document.querySelectorAll('.lamaran-progress').forEach(function(el) {
                requestAnimationFrame(function() {
                    setTimeout(function() { el.style.width = el.dataset.width || '0%'; }, 150);
                });
            });
            document.querySelectorAll('[data-count]').forEach(function(el) {
                var target = parseInt(el.dataset.count || '0', 10);
                if (!target || target <= 0) return;
                var t0 = null, dur = 600;
                function step(ts) {
                    if (!t0) t0 = ts;
                    var p = Math.min((ts - t0) / dur, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
        })();
    </script>
    @endpush
</x-app-layout>
