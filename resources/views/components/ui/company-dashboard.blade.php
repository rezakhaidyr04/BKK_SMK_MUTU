@props(['company', 'stats', 'recentJobs', 'recentApplications'])

<style>
    .premium-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        transition: all 0.25s ease;
    }
    .premium-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        border-color: #e2e8f0;
    }
    .warning-card {
        background: linear-gradient(135deg, #f43f5e, #be123c);
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(190, 18, 60, 0.2);
        position: relative;
        overflow: hidden;
        color: white;
        transition: all 0.25s ease;
    }
    .warning-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(190, 18, 60, 0.3);
    }
    .warning-bg {
        position: absolute; inset: 0;
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIi8+PC9zdmc+');
        opacity: 0.15; pointer-events: none;
    }
    .warning-icon-box {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        width: 2.75rem; height: 2.75rem;
        display: flex; align-items: center; justify-content: center;
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.1);
        transition: transform 0.25s ease;
    }
    .warning-card:hover .warning-icon-box {
        transform: scale(1.05);
    }
    .stat-list-item {
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        background: #ffffff;
        padding: 1rem 1.25rem;
        transition: all 0.25s ease;
    }
    .stat-list-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
    }
    .glass-btn-warning {
        background: #ffffff !important;
        color: #be123c !important;
        border: none !important;
        box-shadow: 0 2px 4px -1px rgba(0,0,0,0.1) !important;
        font-weight: 600 !important;
    }
    .glass-btn-warning:hover { background: #ffe4e6 !important; }
    .glass-btn-outline {
        background: rgba(255,255,255,0.15) !important;
        color: white !important;
        border: 1px solid rgba(255,255,255,0.3) !important;
        backdrop-filter: blur(4px) !important;
    }
    .glass-btn-outline:hover { background: rgba(255,255,255,0.25) !important; }
    .stat-badge-light {
        padding: 0.25rem 0.6rem; 
        background: #f8fafc; 
        border-radius: 9999px; 
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
    }
</style>

<div class="page-shell bg-slate-50 min-h-screen">
    <div class="page-container py-6">
        <section class="space-y-6">
            <x-ui.dashboard-hero
                title="Dashboard Perusahaan"
                subtitle="Kelola data perusahaan, lowongan, dan pelamar dalam satu halaman."
            >
                <x-slot:icon>
                    <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </x-slot:icon>

                <x-slot:actions>
                    <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company" size="sm" style="box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">Buat Lowongan</x-ui.btn>
                    <x-ui.btn href="{{ route('company.jobs.index') }}" variant="white" size="sm" style="color: #1e3a8a; box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05);">Daftar Lowongan</x-ui.btn>
                </x-slot:actions>
            </x-ui.dashboard-hero>

            @if($company && !($company->is_verified ?? false))
                <div class="warning-card p-6">
                    <div class="warning-bg"></div>
                    <div class="relative flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between" style="z-index: 10;">
                        <div class="flex items-center gap-4">
                            <div class="warning-icon-box flex-shrink-0">
                                <svg style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #ffe4e6; margin:0;">Perhatian Verifikasi</p>
                                <h2 style="margin-top: 0.125rem; margin-bottom: 0.25rem; font-size: 1.125rem; font-weight: 700; color: white;">Perusahaan belum terverifikasi</h2>
                                <p style="font-size: 0.8rem; color: #ffe4e6; font-weight: 500; margin:0;">Lengkapi dokumen legal dan profil untuk membuka semua fitur perekrutan.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 shrink-0">
                            <x-ui.btn href="{{ route('company.profile.edit') }}#verification" class="glass-btn-warning" size="sm">Lengkapi Verifikasi</x-ui.btn>
                            <x-ui.btn href="{{ route('company.jobs.index') }}" class="glass-btn-outline" size="sm">Lihat Lowongan</x-ui.btn>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 xl:grid-cols-[1.7fr_1fr]">
                <div class="space-y-6">
                    <div class="premium-card p-6 lg:p-8 relative">
                        <div style="position: absolute; top: 0; right: 0; padding: 1.5rem; opacity: 0.03; pointer-events: none;">
                            <svg style="width: 6rem; height: 6rem; color: #2563eb;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        </div>
                        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between" style="z-index: 10;">
                            <div style="max-width: 24rem;">
                                <div style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.625rem; border-radius: 9999px; background: #f8fafc; border: 1px solid #e2e8f0; margin-bottom: 0.75rem;">
                                    <span style="width: 0.375rem; height: 0.375rem; border-radius: 50%; background: #0ea5e9;"></span>
                                    <p style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #475569; margin:0;">Ringkasan</p>
                                </div>
                                <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin:0;">Status: <span style="color: #0284c7;">{{ $stats['company_status'] }}</span></h2>
                                <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #64748b; line-height: 1.5; margin-bottom:0;">Pantau aktivitas perekrutan Anda. Total lowongan aktif, pelamar baru, dan status verifikasi Anda.</p>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1 w-full xl:w-auto shrink-0">
                                <x-ui.dashboard-stat-card label="Lowongan Aktif" :value="$stats['active_jobs']" color="green" size="sm" />
                                <x-ui.dashboard-stat-card label="Pelamar Baru" :value="$stats['total_applications']" color="indigo" size="sm" />
                                <x-ui.dashboard-stat-card label="Diterima" :value="$stats['accepted_applications']" color="purple" size="sm" />
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; padding: 1rem 1.25rem; border-radius: 16px; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1px solid #e2e8f0;">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <h3 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin:0;">Publikasikan lowongan baru</h3>
                                    <p style="margin-top: 0.125rem; font-size: 0.8rem; color: #475569; margin-bottom:0;">Segera dapatkan kandidat terbaik dengan publikasi sekarang.</p>
                                </div>
                                <div class="flex flex-wrap gap-2 shrink-0">
                                    <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company" size="sm">Buat Lowongan Baru</x-ui.btn>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-ui.panel title="Lowongan Terbaru" subtitle="Daftar lowongan yang baru dipublikasikan.">
                        @if($recentJobs->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($recentJobs as $job)
                                    <div class="stat-list-item sm:flex sm:items-center sm:justify-between group">
                                        <div class="flex items-center gap-3">
                                            <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.75rem; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" class="group-hover:bg-blue-600 group-hover:text-white">
                                                <svg style="width: 1.125rem; height: 1.125rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                <p style="font-weight: 600; color: #0f172a; font-size: 0.95rem; margin:0;" class="group-hover:text-blue-700 transition-colors">{{ $job->title }}</p>
                                                <p style="font-size: 0.75rem; color: #64748b; font-weight: 500; margin:0; margin-top:0.125rem;">{{ $job->position ?? 'Posisi umum' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex items-center gap-3 sm:mt-0">
                                            <div class="stat-badge-light">
                                                <span style="font-size: 0.75rem; font-weight: 600; color: #334155;">{{ $job->applications_count }} <span style="font-weight: 400; color: #64748b;">pelamar</span></span>
                                            </div>
                                            <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary" size="sm" class="px-2.5 py-1 text-xs">Kelola</x-ui.btn>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center text-slate-500">
                                <x-ui.empty-state title="Belum ada lowongan" description="Buat lowongan pertama Anda." />
                            </div>
                        @endif
                    </x-ui.panel>

                    <x-ui.panel title="Aplikasi Terbaru" subtitle="Pelamar terakhir yang mendaftar pada lowongan Anda.">
                        @if($recentApplications->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($recentApplications as $application)
                                    <div class="stat-list-item">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex items-center gap-3">
                                                <div style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4338ca; font-weight: 700; font-size: 0.95rem; box-shadow: inset 0 1px 2px rgba(255,255,255,0.5);">
                                                    {{ strtoupper(substr($application->user->name ?? '-', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p style="font-weight: 600; color: #0f172a; font-size: 0.95rem; margin:0;">{{ $application->user->name }}</p>
                                                    <p style="font-size: 0.7rem; font-weight: 600; color: #4f46e5; background: #eef2ff; display: inline-block; padding: 0.125rem 0.375rem; border-radius: 0.25rem; margin-top: 0.125rem; margin-bottom:0;">{{ optional($application->job)->title ?? 'Lowongan' }}</p>
                                                </div>
                                            </div>
                                            <div class="sm:text-right flex flex-row sm:flex-col justify-between items-center sm:items-end sm:border-t-0 border-t border-slate-100 pt-3 sm:pt-0">
                                                <p style="font-size: 0.75rem; font-weight: 500; color: #64748b; display: flex; align-items: center; gap: 0.25rem; margin:0;">
                                                    <svg style="width: 0.75rem; height: 0.75rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ $application->created_at->diffForHumans() }}
                                                </p>
                                                <p style="margin: 0; sm:margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.65rem; font-weight: 700; padding: 0.125rem 0.375rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.25rem; display: inline-block;">{{ ucfirst($application->status) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center text-slate-500">
                                <x-ui.empty-state title="Belum ada pelamar" description="Pelamar akan muncul di sini." />
                            </div>
                        @endif
                    </x-ui.panel>
                </div>

                <div class="space-y-6">
                    <div class="premium-card p-6">
                        <div class="flex flex-col gap-5">
                            <div class="flex items-center justify-between gap-3" style="border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                                <div>
                                    <p style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #94a3b8; margin:0;">Profil</p>
                                    <h2 style="margin-top: 0.125rem; font-size: 1.125rem; font-weight: 700; color: #1e293b; margin:0;">{{ $company?->name ?? Auth::user()->name }}</h2>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: center; width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; font-weight: 700; font-size: 1.125rem; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);">
                                    {{ strtoupper(substr($company?->name ?? Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                            
                            <div>
                                <p style="font-size: 0.8rem; line-height: 1.5; color: #64748b; margin-bottom: 0.75rem; margin-top:0;">Status verifikasi dan rekrutmen.</p>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <div style="border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #f8fafc; padding: 0.75rem;">
                                        <p style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin:0;">Verifikasi</p>
                                        <p style="margin-top: 0.125rem; font-size: 0.95rem; font-weight: 700; color: #0f172a; margin:0;">{{ $stats['company_status'] }}</p>
                                    </div>
                                    <div style="border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #f8fafc; padding: 0.75rem;">
                                        <p style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin:0;">Progress</p>
                                        <p style="margin-top: 0.125rem; font-size: 0.95rem; font-weight: 700; color: #0f172a; margin:0;">{{ $stats['verification_percent'] }}%</p>
                                    </div>
                                </div>
                            </div>

                            <div style="border-radius: 0.75rem; background: #eff6ff; border: 1px solid #dbeafe; padding: 0.875rem;">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span style="font-size: 0.65rem; font-weight: 700; color: #1e40af; text-transform: uppercase; letter-spacing: 0.05em;">Kelengkapan Data</span>
                                    <span style="font-size: 0.65rem; font-weight: 700; color: #1d4ed8;">{{ $stats['verification_percent'] }}%</span>
                                </div>
                                <div style="height: 0.375rem; border-radius: 9999px; background: rgba(59,130,246,0.2); overflow: hidden;">
                                    <div style="height: 100%; border-radius: 9999px; background: linear-gradient(90deg, #3b82f6, #6366f1); width: {{ $stats['verification_percent'] }}%;"></div>
                                </div>
                                <p style="margin-top: 0.5rem; font-size: 0.7rem; font-weight: 500; color: #1e40af; line-height: 1.4; margin-bottom:0;">{{ $stats['verification_note'] }}</p>
                            </div>
                            
                            <div class="flex flex-col gap-2">
                                <x-ui.btn href="{{ route('company.profile.edit') }}#verification" variant="company" class="w-full justify-center text-sm py-1.5">Lengkapi Verifikasi</x-ui.btn>
                            </div>
                        </div>
                    </div>

                    <div class="premium-card p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <h3 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin:0;">Statistik Ringkas</h3>
                            </div>
                            <x-ui.btn href="{{ route('applications.index') }}" variant="secondary" size="sm" class="px-2 py-1 text-xs">Lihat semua</x-ui.btn>
                        </div>
                        <div class="grid gap-3">
                            <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 0.75rem; border: 1px solid #f1f5f9; background: linear-gradient(135deg, #f8fafc, #ffffff); padding: 0.875rem;">
                                <div class="flex items-center gap-3">
                                    <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <p style="font-size: 0.8rem; font-weight: 600; color: #475569; margin:0;">Lowongan Aktif</p>
                                </div>
                                <p style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin:0;">{{ $stats['active_jobs'] }}</p>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 0.75rem; border: 1px solid #f1f5f9; background: linear-gradient(135deg, #f8fafc, #ffffff); padding: 0.875rem;">
                                <div class="flex items-center gap-3">
                                    <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <p style="font-size: 0.8rem; font-weight: 600; color: #475569; margin:0;">Menunggu</p>
                                </div>
                                <p style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin:0;">{{ $stats['pending_applications'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
