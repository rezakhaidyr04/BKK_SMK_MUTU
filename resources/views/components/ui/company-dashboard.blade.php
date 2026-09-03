@props(['company', 'stats', 'recentJobs', 'recentApplications'])

<style>
    /* ─── Modern Card Styling ─── */
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        border: 1px solid #e2e8f0;
        transition: box-shadow 0.2s ease;
    }
    .premium-card:hover {
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.1);
        border-color: #bfdbfe;
    }

    /* ─── Warning Card with Modern Design ─── */
    .warning-card {
        background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%);
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(127, 29, 29, 0.16);
        position: relative;
        overflow: hidden;
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .warning-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: none;
    }
    @keyframes drift {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }
    .warning-card:hover {
        box-shadow: 0 12px 24px rgba(127, 29, 29, 0.2);
    }
    .warning-bg {
        display: none;
    }
    .warning-icon-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b91c1c;
        border: 1px solid #fed7aa;
        border-radius: 14px;
        width: 2.75rem; 
        height: 2.75rem;
        display: flex; 
        align-items: center; 
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px -2px rgba(181, 28, 28, 0.15);
    }
    .warning-card:hover .warning-icon-box {
        transform: scale(1.1) rotateZ(-5deg);
        box-shadow: 0 6px 16px -2px rgba(181, 28, 28, 0.2);
    }

    /* ─── Stat List Items ─── */
    .stat-list-item {
        border-radius: 12px;
        border: 1px solid rgba(203, 213, 225, 0.6);
        background: #ffffff;
        padding: 1rem 1.25rem;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-list-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        transition: left 0.5s ease;
    }
    .stat-list-item:hover::before {
        left: 100%;
    }
    .stat-list-item:hover {
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.08);
        border-color: rgba(59, 130, 246, 0.3);
        background: #ffffff;
    }

    /* ─── Modern Buttons ─── */
    .glass-btn-warning {
        background: #ffffff;
        color: #b91c1c;
        border: 1px solid rgba(185, 28, 28, 0.2);
        box-shadow: 0 4px 12px -2px rgba(185, 28, 28, 0.1);
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-btn-warning:hover { 
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -2px rgba(185, 28, 28, 0.2);
    }
    .glass-btn-outline {
        background: #ffffff;
        color: #b91c1c;
        border: 1px solid rgba(185, 28, 28, 0.15);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-btn-outline:hover { 
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        transform: translateY(-2px);
    }

    /* ─── Modern Badge ─── */
    .stat-badge-light {
        padding: 0.35rem 0.75rem; 
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 12px; 
        border: 1px solid rgba(59, 130, 246, 0.2);
        display: inline-flex;
        align-items: center;
        font-weight: 600;
        font-size: 0.8rem;
        color: #0369a1;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px -1px rgba(59, 130, 246, 0.1);
    }
    .stat-badge-light:hover {
        background: linear-gradient(135deg, #e0f2fe 0%, #cffafe 100%);
        box-shadow: 0 4px 8px -2px rgba(59, 130, 246, 0.15);
    }

    /* ─── Enhanced Stats Cards ─── */
    .modern-stat-box {
        border-radius: 12px;
        border: 1px solid rgba(203, 213, 225, 0.6);
        background: #ffffff;
        padding: 1rem;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .modern-stat-box:hover {
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.08);
        border-color: rgba(59, 130, 246, 0.2);
    }

    /* ─── Profile Card ─── */
    .profile-avatar {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1e40af 100%);
        box-shadow: 0 8px 16px -4px rgba(59, 130, 246, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .profile-avatar:hover {
        transform: scale(1.05) rotateZ(2deg);
        box-shadow: 0 12px 24px -4px rgba(59, 130, 246, 0.4);
    }

    /* ─── Progress Bar Enhancement ─── */
    .progress-bar-modern {
        background: linear-gradient(90deg, #e0e7ff 0%, #c7d2fe 100%);
        border-radius: 12px;
        overflow: hidden;
        height: 0.5rem;
    }
    .progress-bar-modern .progress-fill {
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 12px;
        height: 100%;
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 8px rgba(59, 130, 246, 0.4);
    }
    .progress-bar-modern .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* ─── Section Transitions ─── */
    .section-fade-in {
        animation: none;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="page-shell company-dashboard-page min-h-screen">
            <x-ui.dashboard-hero
                class="company-dashboard-hero"
                title="Dashboard Perusahaan"
                subtitle="Kelola data perusahaan, lowongan, dan pelamar dalam satu halaman."
            >
                <x-slot:icon>
                    <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </x-slot:icon>

                <x-slot:actions>
                    <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company" size="sm">Buat Lowongan</x-ui.btn>
                    <x-ui.btn href="{{ route('company.jobs.index') }}" variant="white" size="sm">Daftar Lowongan</x-ui.btn>
                </x-slot:actions>
            </x-ui.dashboard-hero>

    <div class="page-container py-6 section-fade-in">
        <section class="space-y-6">
            @if($company && !($company->is_verified ?? false))
                <div class="warning-card p-5 lg:p-6">
                    <div class="warning-bg"></div>
                    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between" style="z-index: 10;">
                        <div class="flex items-center gap-6 flex-1">
                            <div class="warning-icon-box flex-shrink-0">
                                <svg style="width: 1.75rem; height: 1.75rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: rgba(255, 255, 255, 0.8); margin:0;">⚠ Verifikasi Diperlukan</p>
                                <h2 style="margin-top: 0.25rem; margin-bottom: 0.5rem; font-size: 1.25rem; font-weight: 700; color: white;">Lengkapi Verifikasi Perusahaan Anda</h2>
                                <p style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.9); font-weight: 500; margin:0; line-height: 1.6;">Dokumen legal dan profil lengkap diperlukan untuk membuka akses penuh ke semua fitur perekrutan dan meningkatkan kepercayaan kandidat.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3 shrink-0 lg:flex-col">
                            <x-ui.btn href="{{ route('company.profile.edit') }}#verification" class="glass-btn-warning" size="sm" style="font-size: 0.9rem; padding: 0.6rem 1.2rem;">✓ Lengkapi Verifikasi</x-ui.btn>
                            <x-ui.btn href="{{ route('company.jobs.index') }}" class="glass-btn-outline" size="sm" style="font-size: 0.9rem; padding: 0.6rem 1.2rem;">Lihat Lowongan</x-ui.btn>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid gap-8 xl:grid-cols-[1.7fr_1fr]">
                <div class="space-y-8">
                    <div class="premium-card p-6 lg:p-7 relative">
                        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between" style="z-index: 10;">
                            <div style="max-width: 24rem;">
                                <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.8rem; border-radius: 12px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 1px solid #bfdbfe; margin-bottom: 1rem;">
                                    <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #2563eb); box-shadow: 0 0 4px rgba(59, 130, 246, 0.4);"></span>
                                    <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #0369a1; margin:0;">📊 Ringkasan</p>
                                </div>
                                <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin:0; line-height: 1.3;">Status: <span style="background: linear-gradient(135deg, #3b82f6, #2563eb); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $stats['company_status'] }}</span></h2>
                                <p style="margin-top: 0.75rem; font-size: 0.95rem; color: #475569; line-height: 1.6; margin-bottom:0; font-weight: 500;">Pantau aktivitas perekrutan Anda secara real-time. Total lowongan aktif, pelamar baru, dan status verifikasi dalam satu layar.</p>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1 w-full xl:w-auto shrink-0">
                                <x-ui.dashboard-stat-card label="Lowongan Aktif" :value="$stats['active_jobs']" color="green" size="sm" />
                                <x-ui.dashboard-stat-card label="Pelamar Baru" :value="$stats['total_applications']" color="indigo" size="sm" />
                                <x-ui.dashboard-stat-card label="Diterima" :value="$stats['accepted_applications']" color="purple" size="sm" />
                            </div>
                        </div>

                        <div class="company-publish-callout">
                            <div style="position: absolute; inset: 0; background: radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.05), transparent 50%);"></div>
                            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <h3 style="font-size: 1rem; font-weight: 700; color: #0369a1; margin:0;">🚀 Publikasikan Lowongan Baru</h3>
                                    <p style="margin-top: 0.25rem; font-size: 0.85rem; color: #0c4a6e; margin-bottom:0; font-weight: 500;">Dapatkan kandidat terbaik dengan segera mempublikasikan lowongan Anda.</p>
                                </div>
                                <div class="flex flex-wrap gap-3 shrink-0">
                                    <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company" size="sm" style="font-size: 0.9rem;">Buat Lowongan</x-ui.btn>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-ui.panel title="Lowongan Terbaru" subtitle="Daftar lowongan yang baru dipublikasikan." class="company-dashboard-panel">
                        @if($recentJobs->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($recentJobs as $job)
                                    <div class="stat-list-item sm:flex sm:items-center sm:justify-between group">
                                        <div class="flex items-center gap-4">
                                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 12px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #0369a1; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 700; font-size: 1rem; box-shadow: 0 4px 8px -2px rgba(59, 130, 246, 0.15);" class="group-hover:shadow-lg group-hover:scale-110">
                                                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                <p style="font-weight: 700; color: #0f172a; font-size: 0.95rem; margin:0; transition: color 0.3s ease;" class="group-hover:text-blue-600">{{ $job->title }}</p>
                                                <p style="font-size: 0.8rem; color: #64748b; font-weight: 500; margin:0; margin-top:0.25rem;">{{ $job->position ?? 'Posisi Umum' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex items-center gap-4 sm:mt-0">
                                            <div class="stat-badge-light">
                                                <svg style="width: 0.875rem; height: 0.875rem; margin-right: 0.25rem;" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                                                <span style="font-size: 0.8rem; font-weight: 600;">{{ $job->applications_count }} <span style="font-weight: 400;">pelamar</span></span>
                                            </div>
                                            <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary" size="sm" class="px-3 py-1.5 text-xs font-semibold">Kelola</x-ui.btn>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-12 text-center">
                                <x-ui.empty-state title="Belum ada lowongan" description="Mulai buat lowongan pertama Anda sekarang." />
                            </div>
                        @endif
                    </x-ui.panel>

                    <x-ui.panel title="Aplikasi Terbaru" subtitle="Pelamar terakhir yang mendaftar pada lowongan Anda." class="company-dashboard-panel">
                        @if($recentApplications->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($recentApplications as $application)
                                    <div class="stat-list-item">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex items-center gap-4">
                                                <div style="display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 10px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #0369a1; font-weight: 700; font-size: 0.95rem; box-shadow: 0 4px 8px -2px rgba(59, 130, 246, 0.15);">
                                                    {{ strtoupper(substr($application->user->name ?? '-', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p style="font-weight: 700; color: #0f172a; font-size: 0.95rem; margin:0;">{{ $application->user->name }}</p>
                                                    <p style="font-size: 0.75rem; font-weight: 600; color: #fff; background: linear-gradient(135deg, #3b82f6, #2563eb); display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; margin-top: 0.25rem; margin-bottom:0;">{{ optional($application->job)->title ?? 'Lowongan' }}</p>
                                                </div>
                                            </div>
                                            <div class="sm:text-right flex flex-row sm:flex-col justify-between items-center sm:items-end sm:border-t-0 border-t border-slate-100 pt-3 sm:pt-0">
                                                <p style="font-size: 0.8rem; font-weight: 600; color: #0369a1; display: flex; align-items: center; gap: 0.35rem; margin:0;">
                                                    <svg style="width: 0.85rem; height: 0.85rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    {{ $application->created_at->diffForHumans() }}
                                                </p>
                                                <span style="margin: 0.35rem 0 0 0; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.65rem; font-weight: 700; padding: 0.25rem 0.6rem; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); color: #0369a1; border: 1px solid #cffafe; border-radius: 6px; display: inline-block;">{{ \App\Support\Label::applicationStatus($application->status) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-12 text-center">
                                <x-ui.empty-state title="Belum ada pelamar" description="Pelamar akan muncul di sini ketika ada yang mendaftar." />
                            </div>
                        @endif
                    </x-ui.panel>
                </div>

                <div class="space-y-8">
                    <div class="premium-card p-6 relative overflow-hidden">
                        <div style="position: absolute; inset: 0; background: radial-gradient(circle at 100% 0%, rgba(59, 130, 246, 0.03), transparent 70%);"></div>
                        <div class="relative flex flex-col gap-6">
                            <div class="flex items-center justify-between gap-4" style="border-bottom: 1px solid rgba(203, 213, 225, 0.6); padding-bottom: 1.25rem;">
                                <div class="flex-1">
                                    <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #64748b; margin:0;">👤 Profil Perusahaan</p>
                                    <h2 style="margin-top: 0.5rem; font-size: 1.25rem; font-weight: 800; color: #0f172a; margin:0;">{{ $company?->name ?? Auth::user()->name }}</h2>
                                </div>
                                <div class="profile-avatar flex-shrink-0" style="display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 12px; color: white; font-weight: 800; font-size: 1.125rem;">
                                    {{ strtoupper(substr($company?->name ?? Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                            
                            <div>
                                <p style="font-size: 0.85rem; line-height: 1.6; color: #475569; margin-bottom: 1rem; margin-top:0; font-weight: 500;">Status verifikasi dan informasi rekrutmen perusahaan Anda.</p>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="modern-stat-box">
                                        <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #0369a1; margin:0;">✓ Verifikasi</p>
                                        <p style="margin-top: 0.5rem; font-size: 1rem; font-weight: 800; color: #0f172a; margin:0;">{{ $stats['company_status'] }}</p>
                                    </div>
                                    <div class="modern-stat-box">
                                        <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #0369a1; margin:0;">📈 Progress</p>
                                        <p style="margin-top: 0.5rem; font-size: 1rem; font-weight: 800; color: #0f172a; margin:0;">{{ $stats['verification_percent'] }}%</p>
                                    </div>
                                </div>
                            </div>

                            <div style="border-radius: 16px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #cffafe; padding: 1rem; position: relative; overflow: hidden;">
                                <div style="position: absolute; inset: 0; background: radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.08), transparent 50%);"></div>
                                <div class="relative">
                                    <div class="flex justify-between items-center mb-2">
                                        <span style="font-size: 0.7rem; font-weight: 700; color: #0369a1; text-transform: uppercase; letter-spacing: 0.05em;">📋 Kelengkapan Data</span>
                                        <span style="font-size: 0.75rem; font-weight: 700; color: #0369a1; background: white; padding: 0.25rem 0.5rem; border-radius: 6px;">{{ $stats['verification_percent'] }}%</span>
                                    </div>
                                    <div class="progress-bar-modern">
                                        <div class="progress-fill" style="width: {{ $stats['verification_percent'] }}%;"></div>
                                    </div>
                                    <p style="margin-top: 0.75rem; font-size: 0.8rem; font-weight: 500; color: #0369a1; line-height: 1.5; margin-bottom:0;">{{ $stats['verification_note'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-3">
                                <x-ui.btn href="{{ route('company.profile.edit') }}#verification" variant="company" class="w-full justify-center text-sm py-2 font-semibold" style="box-shadow: 0 6px 12px -3px rgba(16, 185, 129, 0.2);">✓ Lengkapi Verifikasi</x-ui.btn>
                            </div>
                        </div>
                    </div>

                    <div class="premium-card p-6">
                        <div class="flex items-center justify-between gap-3 mb-6">
                            <div>
                                <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin:0;">📊 Statistik Ringkas</h3>
                                <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.35rem; margin-bottom: 0; font-weight: 500;">Ringkasan lengkap aktivitas perekrutan Anda</p>
                            </div>
                            <x-ui.btn href="{{ route('applications.index') }}" variant="secondary" size="sm" class="px-2.5 py-1 text-xs font-semibold">Lihat Detail</x-ui.btn>
                        </div>

                        <div class="grid gap-3">
                            <!-- Row 1: Active Jobs & Pending -->
                            <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 14px; border: 1px solid rgba(203, 213, 225, 0.6); background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 1rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" class="hover:shadow-md hover:border-emerald-300">
                                <div class="flex items-center gap-4">
                                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 10px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.3);">
                                        <svg style="width: 1.125rem; height: 1.125rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #047857; margin:0;">Lowongan Aktif</p>
                                        <p style="font-size: 0.8rem; color: #10b981; margin-top: 0.1rem; margin-bottom: 0; font-weight: 600;">Terbuka untuk pelamar</p>
                                    </div>
                                </div>
                                <p style="font-size: 1.5rem; font-weight: 900; color: #047857; margin:0;">{{ $stats['active_jobs'] }}</p>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 14px; border: 1px solid rgba(203, 213, 225, 0.6); background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 1rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" class="hover:shadow-md hover:border-amber-300">
                                <div class="flex items-center gap-4">
                                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 10px; background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px -2px rgba(217, 119, 6, 0.3);">
                                        <svg style="width: 1.125rem; height: 1.125rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #92400e; margin:0;">Menunggu Review</p>
                                        <p style="font-size: 0.8rem; color: #d97706; margin-top: 0.1rem; margin-bottom: 0; font-weight: 600;">Sedang diproses</p>
                                    </div>
                                </div>
                                <p style="font-size: 1.5rem; font-weight: 900; color: #92400e; margin:0;">{{ $stats['pending_applications'] }}</p>
                            </div>

                            <!-- Row 2: Accepted & Total Pelamar -->
                            <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 14px; border: 1px solid rgba(203, 213, 225, 0.6); background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); padding: 1rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" class="hover:shadow-md hover:border-blue-300">
                                <div class="flex items-center gap-4">
                                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px -2px rgba(59, 130, 246, 0.3);">
                                        <svg style="width: 1.125rem; height: 1.125rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #0369a1; margin:0;">✓ Diterima</p>
                                        <p style="font-size: 0.8rem; color: #0ea5e9; margin-top: 0.1rem; margin-bottom: 0; font-weight: 600;">Siap interview</p>
                                    </div>
                                </div>
                                <p style="font-size: 1.5rem; font-weight: 900; color: #0369a1; margin:0;">{{ $stats['accepted_applications'] }}</p>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 14px; border: 1px solid #bfdbfe; background: #eff6ff; padding: 1rem; transition: box-shadow 0.2s ease, border-color 0.2s ease;" class="hover:shadow-md hover:border-blue-300">
                                <div class="flex items-center gap-4">
                                    <div style="width: 2.25rem; height: 2.25rem; border-radius: 10px; background: #2563eb; color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px -2px rgba(37, 99, 235, 0.25);">
                                        <svg style="width: 1.125rem; height: 1.125rem;" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                                    </div>
                                    <div>
                                        <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #1d4ed8; margin:0;">Total Pelamar</p>
                                        <p style="font-size: 0.8rem; color: #2563eb; margin-top: 0.1rem; margin-bottom: 0; font-weight: 600;">Semua aplikasi</p>
                                    </div>
                                </div>
                                <p style="font-size: 1.5rem; font-weight: 900; color: #1d4ed8; margin:0;">{{ $stats['total_applications'] }}</p>
                            </div>

                            <!-- Row 3: Additional Stats -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3px; border-radius: 14px; overflow: hidden; border: 1px solid rgba(203, 213, 225, 0.6);">
                                <!-- Conversion Rate -->
                                <div style="border-radius: 12px; border: 1px solid rgba(203, 213, 225, 0.6); background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); padding: 0.875rem; text-align: center;">
                                    <p style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #7f1d1d; margin:0;">Konversi</p>
                                    <p style="font-size: 1.25rem; font-weight: 900; color: #b91c1c; margin-top: 0.35rem; margin-bottom: 0;">
                                        @php
                                            $conversionRate = ($stats['total_applications'] > 0) 
                                                ? round(($stats['accepted_applications'] / $stats['total_applications']) * 100) 
                                                : 0;
                                        @endphp
                                        {{ $conversionRate }}%
                                    </p>
                                </div>
                                <!-- Rejection Rate -->
                                    <div style="border-radius: 12px; border: 1px solid #fde68a; background: #fffbeb; padding: 0.875rem; text-align: center;">
                                        <p style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #92400e; margin:0;">Meninjau</p>
                                        <p style="font-size: 1.25rem; font-weight: 900; color: #b45309; margin-top: 0.35rem; margin-bottom: 0;">
                                        @php
                                            $reviewCount = $stats['pending_applications'] ?? 0;
                                        @endphp
                                        {{ $reviewCount }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(203, 213, 225, 0.6);">
                            <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin:0 0 0.75rem 0;">Quick Actions</p>
                            <div class="grid grid-cols-2 gap-2">
                                <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company" size="sm" class="w-full justify-center text-xs py-1.5 font-semibold" style="font-size: 0.8rem;">+ Lowongan</x-ui.btn>
                                <x-ui.btn href="{{ route('applications.index') }}" variant="secondary" size="sm" class="w-full justify-center text-xs py-1.5 font-semibold" style="font-size: 0.8rem;">Lihat Aplikasi</x-ui.btn>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
