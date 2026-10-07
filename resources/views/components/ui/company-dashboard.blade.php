@props(['company', 'stats', 'recentJobs', 'recentApplications'])

@php
    $statusColors = [
        'submitted' => 'st-slate',
        'under_review' => 'st-amber',
        'interviewed' => 'st-blue',
        'accepted' => 'st-green',
        'rejected' => 'st-red',
    ];
    $conversionRate = (($stats['total_applications'] ?? 0) > 0)
        ? round((($stats['accepted_applications'] ?? 0) / $stats['total_applications']) * 100)
        : 0;
@endphp

<style>
    .co-dash{--bd:#e2e8f0;--tx:#0f172a;--mut:#64748b}
    .co-card{background:#fff;border:1px solid var(--bd);border-radius:16px;box-shadow:0 1px 2px rgba(15,23,42,.05),0 8px 24px -12px rgba(37,99,235,.15);position:relative;overflow:hidden}
    .co-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#2563eb,#06b6d4,#8b5cf6);opacity:.85}
    .co-card-pad{padding:1.25rem}
    @media(min-width:1024px){.co-card-pad{padding:1.5rem}}
    .co-eyebrow{display:inline-flex;align-items:center;gap:.45rem;font-size:.68rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#fff;background:linear-gradient(135deg,#2563eb,#1d4ed8);border-radius:9999px;padding:.3rem .8rem;box-shadow:0 4px 12px -2px rgba(37,99,235,.4)}
    .co-eyebrow i{width:.4rem;height:.4rem;border-radius:9999px;background:#fff;box-shadow:0 0 0 3px rgba(255,255,255,.3);animation:blink 1.8s infinite}
    @keyframes blink{50%{opacity:.5}}
    .co-title{font-size:1.4rem;font-weight:800;color:var(--tx);letter-spacing:-.02em;margin:.85rem 0 0}
    .co-title .hl{background:linear-gradient(135deg,#2563eb,#06b6d4);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
    .co-desc{font-size:.87rem;color:var(--mut);line-height:1.6;margin:.5rem 0 0;max-width:34rem}
    .co-mini-grid{display:grid;gap:.75rem;margin-top:1.1rem}
    @media(min-width:640px){.co-mini-grid{grid-template-columns:repeat(3,1fr)}}
    .co-mini{border:1px solid var(--bd);border-radius:.9rem;padding:1rem;text-align:center;background:linear-gradient(180deg,#fff,#f8fafc);transition:transform .15s,box-shadow .15s}
    .co-mini:hover{transform:translateY(-2px);box-shadow:0 10px 20px -8px rgba(15,23,42,.18)}
    .co-mini:nth-child(1){border-top:3px solid #22c55e}
    .co-mini:nth-child(2){border-top:3px solid #2563eb}
    .co-mini:nth-child(3){border-top:3px solid #8b5cf6}
    .co-mini span{font-size:.66rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#94a3b8}
    .co-mini strong{display:block;font-size:1.7rem;font-weight:800;color:var(--tx);line-height:1.2;margin-top:.15rem}
    .co-mini:nth-child(1) strong{color:#15803d}
    .co-mini:nth-child(2) strong{color:#1d4ed8}
    .co-mini:nth-child(3) strong{color:#6d28d9}
    .co-cta{margin-top:1.1rem;display:flex;gap:1rem;align-items:center;justify-content:space-between;flex-wrap:wrap;background:linear-gradient(135deg,#1d4ed8,#2563eb 55%,#06b6d4);border-radius:.9rem;padding:1rem 1.2rem;color:#fff;box-shadow:0 10px 24px -8px rgba(37,99,235,.5);position:relative;overflow:hidden}
    .co-cta::after{content:'';position:absolute;right:-40px;top:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.12)}
    .co-cta strong{display:block;font-size:.92rem;color:#fff;position:relative;z-index:1}
    .co-cta p{font-size:.8rem;color:rgba(255,255,255,.85);margin:.15rem 0 0;position:relative;z-index:1}
    .co-sec-head{display:flex;align-items:flex-start;gap:.65rem;margin-bottom:1rem}
    .co-sec-head::before{content:'';width:4px;align-self:stretch;border-radius:9999px;background:linear-gradient(180deg,#2563eb,#06b6d4);flex-shrink:0}
    .co-sec-head h3{font-size:.95rem;font-weight:800;color:var(--tx);margin:0;letter-spacing:-.01em}
    .co-sec-head p{font-size:.78rem;color:var(--mut);margin:.15rem 0 0}
    .co-list{display:flex;flex-direction:column;gap:.7rem}
    .co-item{display:flex;align-items:center;justify-content:space-between;gap:1rem;border:1px solid var(--bd);border-radius:.9rem;padding:.8rem .95rem;background:#fff;transition:border-color .15s,box-shadow .15s,transform .15s}
    .co-item:hover{border-color:#bfdbfe;box-shadow:0 8px 18px -8px rgba(37,99,235,.25);transform:translateY(-1px)}
    .co-item-main{display:flex;align-items:center;gap:.8rem;min-width:0}
    .co-ava{width:2.5rem;height:2.5rem;border-radius:.8rem;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border:1px solid #bfdbfe;color:#1d4ed8;font-weight:800;font-size:.9rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 10px -4px rgba(37,99,235,.4)}
    .co-item-main strong{display:block;font-size:.88rem;color:var(--tx);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:16rem}
    .co-item-main small{font-size:.75rem;color:#94a3b8}
    .co-item-side{display:flex;align-items:center;gap:.6rem;flex-shrink:0}
    .co-count{display:inline-flex;align-items:center;gap:.3rem;font-size:.76rem;font-weight:700;color:#1d4ed8;background:linear-gradient(180deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe;border-radius:9999px;padding:.3rem .7rem;white-space:nowrap}
    .co-count svg{width:.85rem;height:.85rem}
    .co-btn{display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;padding:.52rem .95rem;border-radius:.65rem;border:1px solid var(--bd);background:#fff;color:#334155;text-decoration:none;white-space:nowrap;transition:.15s}
    .co-btn:hover{border-color:#94a3b8;background:#f8fafc;color:#0f172a;transform:translateY(-1px)}
    .co-btn-primary{background:linear-gradient(135deg,#2563eb,#1d4ed8);border-color:transparent;color:#fff;box-shadow:0 6px 16px -4px rgba(37,99,235,.5)}
    .co-btn-primary:hover{background:linear-gradient(135deg,#1d4ed8,#1e40af);color:#fff;box-shadow:0 10px 22px -6px rgba(37,99,235,.55)}
    .co-cta .co-btn-primary{background:#fff;border-color:#fff;color:#1d4ed8;box-shadow:0 4px 12px rgba(0,0,0,.2);position:relative;z-index:1}
    .co-cta .co-btn-primary:hover{background:#f0f7ff;transform:translateY(-1px)}
    .co-meta{text-align:right}
    .co-time{font-size:.75rem;color:#64748b;display:flex;align-items:center;gap:.3rem;justify-content:flex-end}
    .co-time svg{width:.8rem;height:.8rem}
    .st{display:inline-block;margin-top:.35rem;font-size:.66rem;font-weight:800;letter-spacing:.04em;text-transform:uppercase;padding:.24rem .6rem;border-radius:9999px;border:1px solid}
    .st-slate{background:#f1f5f9;border-color:#e2e8f0;color:#475569}
    .st-amber{background:#fef3c7;border-color:#fde68a;color:#92400e}
    .st-blue{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
    .st-green{background:#f0fdf4;border-color:#bbf7d0;color:#15803d}
    .st-red{background:#fef2f2;border-color:#fecaca;color:#b91c1c}
    .co-profile-top{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding-bottom:1rem;border-bottom:1px solid #f1f5f9}
    .co-profile-top small{display:block;font-size:.66rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8}
    .co-profile-top strong{display:block;font-size:1.05rem;color:var(--tx);margin-top:.2rem;letter-spacing:-.01em}
    .co-logo{width:3rem;height:3rem;border-radius:.85rem;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;font-weight:800;font-size:1.1rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;box-shadow:0 8px 18px -6px rgba(37,99,235,.55);border:2px solid #dbeafe}
    .co-logo img{width:100%;height:100%;object-fit:cover}
    .co-kv{display:grid;grid-template-columns:1fr 1fr;gap:.7rem;margin-top:1rem}
    .co-kv div{border:1px solid var(--bd);border-radius:.75rem;padding:.7rem .85rem;background:linear-gradient(180deg,#fff,#f8fafc)}
    .co-kv small{font-size:.64rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#64748b}
    .co-kv strong{display:block;font-size:.92rem;color:#1d4ed8;margin-top:.2rem}
    .co-progress{background:linear-gradient(180deg,#f0f7ff,#e6f1ff);border:1px solid #bfdbfe;border-radius:.85rem;padding:.9rem;margin-top:.9rem}
    .co-progress-row{display:flex;justify-content:space-between;align-items:center;font-size:.7rem;font-weight:800;color:#1d4ed8;text-transform:uppercase;letter-spacing:.05em}
    .co-progress-row b{background:#fff;border:1px solid #bfdbfe;border-radius:.45rem;padding:.12rem .5rem;box-shadow:0 2px 6px rgba(37,99,235,.15)}
    .co-track{height:.55rem;background:#e2e8f0;border-radius:9999px;margin-top:.6rem;overflow:hidden}
    .co-fill{height:100%;background:linear-gradient(90deg,#2563eb,#06b6d4);border-radius:9999px;box-shadow:0 0 10px rgba(37,99,235,.5)}
    .co-note{font-size:.76rem;color:#475569;margin:.6rem 0 0;line-height:1.5}
    .co-row{display:flex;align-items:center;justify-content:space-between;gap:1rem;border:1px solid var(--bd);border-radius:.85rem;padding:.8rem .95rem;background:#fff;transition:.15s}
    .co-row:hover{transform:translateY(-1px);box-shadow:0 8px 16px -8px rgba(15,23,42,.15)}
    .co-row:nth-child(1){background:linear-gradient(180deg,#f0fdf4,#fff 70%)}
    .co-row:nth-child(2){background:linear-gradient(180deg,#fffbeb,#fff 70%)}
    .co-row:nth-child(3){background:linear-gradient(180deg,#eff6ff,#fff 70%)}
    .co-row:nth-child(4){background:linear-gradient(180deg,#f8fafc,#fff 70%)}
    .co-row-main{display:flex;align-items:center;gap:.8rem;min-width:0}
    .co-ico{width:2.4rem;height:2.4rem;border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;box-shadow:0 6px 14px -4px rgba(15,23,42,.3)}
    .co-ico svg{width:1.15rem;height:1.15rem}
    .co-ico.g{background:linear-gradient(135deg,#22c55e,#15803d)}
    .co-ico.a{background:linear-gradient(135deg,#f59e0b,#b45309)}
    .co-ico.b{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}
    .co-ico.s{background:linear-gradient(135deg,#64748b,#334155)}
    .co-row-main span{display:block;font-size:.66rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b}
    .co-row-main small{font-size:.75rem;color:#94a3b8}
    .co-row b{font-size:1.35rem;color:var(--tx)}
    .co-duo{display:grid;grid-template-columns:1fr 1fr;gap:.7rem}
    .co-duo div{border:1px solid var(--bd);border-radius:.85rem;padding:.85rem;text-align:center}
    .co-duo.danger div:first-child{background:linear-gradient(180deg,#fef2f2,#fff);border-color:#fecaca}
    .co-duo.danger div:last-child{background:linear-gradient(180deg,#fffbeb,#fff);border-color:#fde68a}
    .co-duo small{font-size:.62rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b}
    .co-duo strong{display:block;font-size:1.25rem;margin-top:.2rem;color:var(--tx)}
    .co-quick{margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9}
    .co-quick small{display:block;font-size:.66rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#94a3b8;margin-bottom:.6rem}
    .co-quick-row{display:grid;grid-template-columns:1fr 1fr;gap:.6rem}
    .co-warn{background:linear-gradient(180deg,#fffbeb,#fff7ed);border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:14px;padding:1.1rem 1.25rem;display:flex;gap:1rem;align-items:center;justify-content:space-between;flex-wrap:wrap;box-shadow:0 8px 20px -10px rgba(245,158,11,.4)}
    .co-warn-main{display:flex;gap:.9rem;align-items:center;min-width:0}
    .co-warn-ico{width:2.5rem;height:2.5rem;border-radius:.8rem;background:linear-gradient(135deg,#f59e0b,#b45309);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 6px 14px -4px rgba(245,158,11,.5)}
    .co-warn-ico svg{width:1.3rem;height:1.3rem}
    .co-warn strong{display:block;font-size:.9rem;color:#7c2d12}
    .co-warn p{font-size:.8rem;color:#92400e;margin:.15rem 0 0}
    .co-main-grid{grid-template-columns:1fr}
    @media(min-width:1024px){
        .co-main-grid{grid-template-columns:1.65fr 1fr;align-items:start}
        .co-side{position:sticky;top:5.5rem}
    }
    a.co-mini{display:block;text-decoration:none;color:inherit}
    a.co-row{text-decoration:none;color:inherit}
    a.co-count{text-decoration:none}
</style>

<div class="page-shell company-dashboard-page min-h-screen co-dash">
    <x-ui.page-banner
        title="Dashboard Perusahaan"
        subtitle="Kelola data perusahaan, lowongan, dan pelamar dalam satu halaman."
        eyebrow="Perusahaan › Dashboard"
    >
        <x-slot:chips>
            <span class="page-banner__chip">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Dashboard Perusahaan
            </span>
            <span class="page-banner__chip">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                {{ $company->name ?? 'Perusahaan' }}
            </span>
        </x-slot:chips>
        <x-slot:actions>
            <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company" size="sm">Buat Lowongan</x-ui.btn>
            <x-ui.btn href="{{ route('company.jobs.index') }}" variant="white" size="sm">Daftar Lowongan</x-ui.btn>
        </x-slot:actions>
    </x-ui.page-banner>

    <div class="page-container py-6">
        <section class="space-y-5">
            @if($company && !($company->is_verified ?? false))
                <div class="co-warn">
                    <div class="co-warn-main">
                        <div class="co-warn-ico">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <strong>Lengkapi verifikasi perusahaan Anda</strong>
                            <p>Lowongan langsung tayang setelah profil dan dokumen legal lengkap.</p>
                        </div>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('company.profile.edit') }}#verification" class="co-btn co-btn-primary">Lengkapi Verifikasi</a>
                        <a href="{{ route('company.jobs.index') }}" class="co-btn">Lihat Lowongan</a>
                    </div>
                </div>
            @endif

            <div class="grid gap-5 lg:grid-cols-[1.65fr_1fr] lg:items-start co-main-grid">
                <div class="space-y-5 min-w-0">
                    {{-- Ringkasan --}}
                    <div class="co-card co-card-pad">
                        <span class="co-eyebrow"><i></i>Ringkasan</span>
                        <h2 class="co-title">Status: <span class="hl">{{ $stats['company_status'] }}</span></h2>
                        <p class="co-desc">Pantau lowongan aktif, pelamar baru, dan status verifikasi dalam satu layar.</p>
                        <div class="co-mini-grid">
                            <a href="{{ route('company.jobs.index') }}" class="co-mini"><span>Lowongan Aktif</span><strong>{{ $stats['active_jobs'] }}</strong></a>
                            <a href="{{ route('company.applicants.index') }}" class="co-mini"><span>Pelamar Baru</span><strong>{{ $stats['total_applications'] }}</strong></a>
                            <a href="{{ route('company.applicants.index') }}" class="co-mini"><span>Diterima</span><strong>{{ $stats['accepted_applications'] }}</strong></a>
                        </div>
                        <div class="co-cta">
                            <div>
                                <strong>Publikasikan lowongan baru</strong>
                                <p>Dapatkan kandidat terbaik dengan segera mempublikasikan lowongan.</p>
                            </div>
                            <a href="{{ route('company.jobs.create') }}" class="co-btn co-btn-primary">Buat Lowongan</a>
                        </div>
                    </div>

                    {{-- Lowongan terbaru --}}
                    <div class="co-card co-card-pad">
                        <div class="co-sec-head">
                            <div>
                                <h3>Lowongan Terbaru</h3>
                                <p>Daftar lowongan yang baru dipublikasikan.</p>
                            </div>
                        </div>
                        @if($recentJobs->isNotEmpty())
                            <div class="co-list">
                                @foreach($recentJobs as $job)
                                    <div class="co-item">
                                        <div class="co-item-main">
                                            <div class="co-ava">
                                                <svg style="width:1.15rem;height:1.15rem" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                <strong>{{ $job->title }}</strong>
                                                <small>{{ $job->position ?? 'Posisi Umum' }}</small>
                                            </div>
                                        </div>
                                        <div class="co-item-side">
                                            <a href="{{ route('company.applicants.index') }}" class="co-count" title="Lihat pelamar lowongan ini">
                                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                                                {{ $job->applications_count }} pelamar
                                            </a>
                                            <a href="{{ route('jobs.show', $job) }}" class="co-btn">Lihat</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <x-ui.empty-state title="Belum ada lowongan" description="Mulai buat lowongan pertama Anda sekarang." />
                        @endif
                    </div>

                    {{-- Aplikasi terbaru --}}
                    <div class="co-card co-card-pad">
                        <div class="co-sec-head">
                            <div>
                                <h3>Aplikasi Terbaru</h3>
                                <p>Pelamar terakhir yang mendaftar pada lowongan Anda.</p>
                            </div>
                        </div>
                        @if($recentApplications->isNotEmpty())
                            <div class="co-list">
                                @foreach($recentApplications as $application)
                                    <div class="co-item">
                                        <div class="co-item-main">
                                            <div class="co-ava">{{ strtoupper(substr($application->user->name ?? '-', 0, 1)) }}</div>
                                            <div>
                                                <strong>{{ $application->user->name }}</strong>
                                                <small>{{ optional($application->job)->title ?? 'Lowongan' }}</small>
                                            </div>
                                        </div>
                                        <div class="co-meta">
                                            <p class="co-time">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                {{ $application->created_at->diffForHumans() }}
                                            </p>
                                            <span class="st {{ $statusColors[$application->status] ?? 'st-slate' }}">{{ \App\Support\Label::applicationStatus($application->status) }}</span>
                                            <div class="mt-1.5"><a href="{{ route('company.applicants.show', $application) }}" class="co-btn" style="padding:.35rem .7rem;font-size:.72rem">Detail</a></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <x-ui.empty-state title="Belum ada pelamar" description="Pelamar akan muncul di sini ketika ada yang mendaftar." />
                        @endif
                    </div>
                </div>

                <div class="space-y-5 min-w-0 co-side">
                    {{-- Profil --}}
                    <div class="co-card co-card-pad">
                        <div class="co-profile-top">
                            <div>
                                <small>Profil Perusahaan</small>
                                <strong>{{ $company?->name ?? Auth::user()->name }}</strong>
                            </div>
                            <div class="co-logo">
                                @if($company?->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" />
                                @else
                                    {{ strtoupper(substr($company?->name ?? Auth::user()->name, 0, 1)) }}
                                @endif
                            </div>
                        </div>
                        <p class="co-note">Status verifikasi dan informasi rekrutmen perusahaan Anda.</p>
                        <div class="co-kv">
                            <div><small>Verifikasi</small><strong>{{ $stats['company_status'] }}</strong></div>
                            <div><small>Progress</small><strong>{{ $stats['verification_percent'] }}%</strong></div>
                        </div>
                        <div class="co-progress">
                            <div class="co-progress-row"><span>Kelengkapan Data</span><b>{{ $stats['verification_percent'] }}%</b></div>
                            <div class="co-track"><div class="co-fill" style="width: {{ $stats['verification_percent'] }}%;"></div></div>
                            <p class="co-note">{{ $stats['verification_note'] }}</p>
                        </div>
                        <a href="{{ route('company.profile.edit') }}#verification" class="co-btn co-btn-primary" style="width:100%;margin-top:.9rem">Lengkapi Verifikasi</a>
                    </div>

                    {{-- Statistik --}}
                    <div class="co-card co-card-pad">
                        <div class="co-sec-head">
                            <div>
                                <h3>Statistik Ringkas</h3>
                                <p>Ringkasan aktivitas perekrutan Anda.</p>
                            </div>
                        </div>
                        <div class="co-list">
                            <a href="{{ route('company.jobs.index') }}" class="co-row">
                                <div class="co-row-main">
                                    <div class="co-ico g"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></div>
                                    <div><span>Lowongan Aktif</span><small>Terbuka untuk pelamar</small></div>
                                </div>
                                <b>{{ $stats['active_jobs'] }}</b>
                            </a>
                            <a href="{{ route('company.applicants.index') }}" class="co-row">
                                <div class="co-row-main">
                                    <div class="co-ico a"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div><span>Menunggu Review</span><small>Sedang diproses</small></div>
                                </div>
                                <b>{{ $stats['pending_applications'] }}</b>
                            </a>
                            <a href="{{ route('company.applicants.index') }}" class="co-row">
                                <div class="co-row-main">
                                    <div class="co-ico b"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div><span>Diterima</span><small>Siap interview</small></div>
                                </div>
                                <b>{{ $stats['accepted_applications'] }}</b>
                            </a>
                            <a href="{{ route('company.applicants.index') }}" class="co-row">
                                <div class="co-row-main">
                                    <div class="co-ico s"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg></div>
                                    <div><span>Total Pelamar</span><small>Semua aplikasi</small></div>
                                </div>
                                <b>{{ $stats['total_applications'] }}</b>
                            </a>
                            <div class="co-duo danger">
                                <div><small>Konversi</small><strong>{{ $conversionRate }}%</strong></div>
                                <div><small>Meninjau</small><strong>{{ $stats['pending_applications'] ?? 0 }}</strong></div>
                            </div>
                        </div>
                        <div class="co-quick">
                            <small>Aksi Cepat</small>
                            <div class="co-quick-row">
                                <a href="{{ route('company.jobs.create') }}" class="co-btn co-btn-primary" style="justify-content:center">+ Lowongan</a>
                                <a href="{{ route('company.applicants.index') }}" class="co-btn" style="justify-content:center">Lihat Pelamar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
