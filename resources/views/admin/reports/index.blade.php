<x-app-layout :full-bleed="true">
<x-slot name="header">
    <x-ui.page-header title="Laporan & Analitik" subtitle="Ringkasan performa sistem per {{ now()->format('d F Y') }}" />
</x-slot>
<div class="min-h-screen bg-slate-50">

    {{-- ===== HERO HEADER ===== --}}
    <div style="background: linear-gradient(135deg, #1e293b 0%, #1e3a8a 60%, #1e3a8a 100%); position:relative; overflow:hidden;">
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 20% 50%,rgba(99,102,241,0.3) 0%,transparent 60%),radial-gradient(circle at 80% 20%,rgba(59,130,246,0.2) 0%,transparent 50%);pointer-events:none;"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" style="position:relative;">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:rgba(165,180,252,1);">
                        &#9679; Admin Panel &mdash; BKK SMK MUTU
                    </p>
                    <h1 class="text-3xl font-black text-white tracking-tight">Laporan &amp; Analitik</h1>
                    <p class="mt-1 text-sm" style="color:rgba(148,163,184,1);">
                        Ringkasan performa sistem per <strong class="text-white">{{ now()->format('d F Y') }}</strong>
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('admin.reports.export') }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;transition:background 0.2s;"
                       onmouseover="this.style.background='rgba(255,255,255,0.18)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Ekspor CSV
                    </a>
                    <a href="{{ route('admin.reports.export-excel') }}"
                       style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#16a34a;border:1px solid #047857;color:#fff;border-radius:12px;font-size:14px;font-weight:600;text-decoration:none;box-shadow:0 4px 20px rgba(5,150,105,0.4);transition:background 0.2s;"
                       onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#16a34a'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Ekspor Excel
                    </a>
                </div>
            </div>

            {{-- Quick stats strip inside hero --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-8">
                @php
                    $heroStats = [
                        ['label'=>'Total Pengguna','value'=>$summary['total_umum'],'color'=>'rgba(129,140,248,1)'],
                        ['label'=>'Lowongan Aktif','value'=>$summary['active_jobs'],'color'=>'rgba(52,211,153,1)'],
                        ['label'=>'Total Lamaran','value'=>$summary['total_applications'],'color'=>'rgba(251,191,36,1)'],
                        ['label'=>'Lamaran Diterima','value'=>$summary['accepted_applications'],'color'=>'rgba(248,113,113,1)'],
                    ];
                @endphp
                @foreach($heroStats as $hs)
                <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:16px;padding:16px 20px;backdrop-filter:blur(8px);">
                    <p style="font-size:26px;font-weight:900;color:{{ $hs['color'] }};line-height:1;">{{ number_format($hs['value']) }}</p>
                    <p style="font-size:12px;color:rgba(203,213,225,0.85);margin-top:4px;font-weight:500;">{{ $hs['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- ===== ROW 1: Pengguna + Lamaran breakdown ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kartu Pengguna --}}
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 1px 8px rgba(15,23,42,0.06);padding:24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <h3 style="font-weight:700;font-size:15px;color:#0f172a;">Data Pengguna</h3>
                    <div style="width:36px;height:36px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <div style="space-y:12px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f8fafc;border-radius:12px;margin-bottom:10px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#3b82f6;flex-shrink:0;"></div>
                            <span style="font-size:14px;font-weight:500;color:#334155;">Pengguna Umum</span>
                        </div>
                        <span style="font-size:20px;font-weight:800;color:#1e3a8a;">{{ number_format($summary['total_umum']) }}</span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:linear-gradient(135deg,#2563eb,#2563eb);border-radius:12px;">
                        <span style="font-size:14px;font-weight:600;color:rgba(255,255,255,0.9);">Total</span>
                        <span style="font-size:22px;font-weight:900;color:#fff;">{{ number_format($summary['total_umum']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Kartu Lowongan --}}
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 1px 8px rgba(15,23,42,0.06);padding:24px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <h3 style="font-weight:700;font-size:15px;color:#0f172a;">Data Lowongan</h3>
                    <div style="width:36px;height:36px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#16a34a" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f8fafc;border-radius:12px;margin-bottom:10px;">
                        <span style="font-size:14px;font-weight:500;color:#334155;">Total</span>
                        <span style="font-size:20px;font-weight:800;color:#0f172a;">{{ number_format($summary['total_jobs']) }}</span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#f0fdf4;border-radius:12px;margin-bottom:10px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:pulse 2s infinite;"></div>
                            <span style="font-size:14px;font-weight:500;color:#065f46;">Aktif</span>
                        </div>
                        <span style="font-size:20px;font-weight:800;color:#16a34a;">{{ number_format($summary['active_jobs']) }}</span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:#fef2f2;border-radius:12px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:#ef4444;"></div>
                            <span style="font-size:14px;font-weight:500;color:#991b1b;">Ditutup</span>
                        </div>
                        <span style="font-size:20px;font-weight:800;color:#dc2626;">{{ number_format($summary['closed_jobs']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Tingkat keberhasilan --}}
            @php
                $rate = $summary['total_applications'] > 0
                    ? round(($summary['accepted_applications'] / $summary['total_applications']) * 100, 1)
                    : 0;
            @endphp
            <div style="background:linear-gradient(145deg,#1e3a8a,#1e3a8a);border-radius:20px;box-shadow:0 8px 32px rgba(30,58,138,0.35);padding:24px;color:#fff;display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                    <p style="font-size:11px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:rgba(165,180,252,0.9);margin-bottom:8px;">Tingkat Keberhasilan</p>
                    <p style="font-size:56px;font-weight:900;line-height:1;color:#fff;">{{ $rate }}<span style="font-size:28px;">%</span></p>
                    <p style="font-size:13px;color:rgba(203,213,225,0.8);margin-top:8px;">
                        <strong style="color:#fff;">{{ number_format($summary['accepted_applications']) }}</strong> diterima dari
                        <strong style="color:#fff;">{{ number_format($summary['total_applications']) }}</strong> lamaran
                    </p>
                </div>
                <div style="margin-top:24px;">
                    <div style="height:8px;background:rgba(255,255,255,0.15);border-radius:99px;overflow:hidden;">
                        <div style="height:100%;width:{{ min($rate, 100) }}%;background:linear-gradient(90deg,#60a5fa,#a5f3fc);border-radius:99px;transition:width 1s ease;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-top:6px;">
                        <span style="font-size:11px;color:rgba(203,213,225,0.6);">0%</span>
                        <span style="font-size:11px;color:rgba(203,213,252,0.6);">50%</span>
                        <span style="font-size:11px;color:rgba(203,213,225,0.6);">100%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== ROW 2: Tren lamaran + Status lamaran ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Bar Chart tren 6 bulan --}}
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 1px 8px rgba(15,23,42,0.06);padding:24px;" class="lg:col-span-3">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                    <div>
                        <h3 style="font-weight:700;font-size:15px;color:#0f172a;">Tren Lamaran</h3>
                        <p style="font-size:12px;color:#94a3b8;margin-top:2px;">6 bulan terakhir</p>
                    </div>
                    <span style="font-size:11px;font-weight:600;padding:4px 10px;background:#eff6ff;color:#2563eb;border-radius:99px;">Per Bulan</span>
                </div>
                @php $maxCount = max($months->pluck('count')->toArray() ?: [1]); @endphp
                <div style="display:flex;align-items:flex-end;gap:12px;height:160px;">
                    @foreach($months as $month)
                    @php $pct = $maxCount > 0 ? ($month['count'] / $maxCount * 100) : 0; @endphp
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;height:100%;">
                        <span style="font-size:13px;font-weight:700;color:#334155;">{{ $month['count'] }}</span>
                        <div style="flex:1;width:100%;display:flex;align-items:flex-end;">
                            <div style="width:100%;height:{{ max($pct, 3) }}%;background:linear-gradient(180deg,#3b82f6,#2563eb);border-radius:8px 8px 4px 4px;min-height:6px;transition:height 0.5s ease;box-shadow:0 4px 12px rgba(99,102,241,0.25);"></div>
                        </div>
                        <span style="font-size:10px;color:#94a3b8;text-align:center;white-space:nowrap;">{{ $month['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Status Lamaran donut-style --}}
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 1px 8px rgba(15,23,42,0.06);padding:24px;" class="lg:col-span-2">
                <h3 style="font-weight:700;font-size:15px;color:#0f172a;margin-bottom:20px;">Status Lamaran</h3>
                @php
                    $statuses = [
                        ['label'=>'Diajukan','value'=>$summary['submitted_applications'],'color'=>'#3b82f6','bg'=>'#eff6ff'],
                        ['label'=>'Diwawancara','value'=>$summary['interviewed_applications'],'color'=>'#8b5cf6','bg'=>'#f5f3ff'],
                        ['label'=>'Diterima','value'=>$summary['accepted_applications'],'color'=>'#16a34a','bg'=>'#f0fdf4'],
                        ['label'=>'Ditolak','value'=>$summary['rejected_applications'],'color'=>'#ef4444','bg'=>'#fef2f2'],
                    ];
                    $total = max($summary['total_applications'], 1);
                @endphp
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach($statuses as $st)
                    @php $w = round(($st['value'] / $total) * 100); @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                            <span style="font-size:13px;font-weight:500;color:#475569;">{{ $st['label'] }}</span>
                            <span style="font-size:14px;font-weight:700;color:{{ $st['color'] }};">{{ number_format($st['value']) }}</span>
                        </div>
                        <div style="height:8px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                            <div style="height:100%;width:{{ $w }}%;background:{{ $st['color'] }};border-radius:99px;min-width:{{ $st['value']>0?'6px':'0' }};transition:width 0.8s ease;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ===== ROW 3: Pengguna & Lowongan Terbaru ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Pengguna Terbaru --}}
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 1px 8px rgba(15,23,42,0.06);overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid #f1f5f9;">
                    <h3 style="font-weight:700;font-size:15px;color:#0f172a;">Pengguna Terbaru</h3>
                    <a href="{{ route('admin.users.index') }}" style="font-size:12px;font-weight:600;color:#2563eb;text-decoration:none;">Lihat Semua →</a>
                </div>
                @forelse($recentUsers as $user)
                @php
                    $colors = ['admin'=>['bg'=>'#f3e8ff','text'=>'#7c3aed'],'umum'=>['bg'=>'#dbeafe','text'=>'#1d4ed8'],'company'=>['bg'=>'#fef3c7','text'=>'#c2410c']];
                    $uc = $colors[$user->role] ?? ['bg'=>'#f1f5f9','text'=>'#475569'];
                @endphp
                <div style="display:flex;align-items:center;gap:12px;padding:14px 24px;border-bottom:1px solid #f8fafc;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:600;font-size:14px;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</p>
                        <p style="font-size:12px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->email }}</p>
                    </div>
                    <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;background:{{ $uc['bg'] }};color:{{ $uc['text'] }};flex-shrink:0;">
                        {{ \App\Support\Label::role($user->role) }}
                    </span>
                </div>
                @empty
                <div style="padding:40px;text-align:center;color:#94a3b8;font-size:14px;">Belum ada pengguna.</div>
                @endforelse
            </div>

            {{-- Lowongan Terbaru --}}
            <div style="background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 1px 8px rgba(15,23,42,0.06);overflow:hidden;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid #f1f5f9;">
                    <h3 style="font-weight:700;font-size:15px;color:#0f172a;">Lowongan Terbaru</h3>
                    <a href="{{ route('admin.jobs.index') }}" style="font-size:12px;font-weight:600;color:#2563eb;text-decoration:none;">Lihat Semua →</a>
                </div>
                @forelse($recentJobs as $job)
                @php
                    $sc = ['active'=>['bg'=>'#dcfce7','text'=>'#065f46'],'inactive'=>['bg'=>'#f1f5f9','text'=>'#475569'],'closed'=>['bg'=>'#fee2e2','text'=>'#991b1b'],'draft'=>['bg'=>'#fef9c3','text'=>'#854d0e']];
                    $jc = $sc[$job->status] ?? ['bg'=>'#f1f5f9','text'=>'#475569'];
                @endphp
                <div style="display:flex;align-items:center;gap:12px;padding:14px 24px;border-bottom:1px solid #f8fafc;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#3b82f6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0;">
                        {{ strtoupper(substr($job->company_name??'C',0,1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:600;font-size:14px;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $job->title }}</p>
                        <p style="font-size:12px;color:#94a3b8;">{{ $job->company_name ?? '-' }} &middot; {{ $job->applications_count }} pelamar</p>
                    </div>
                    <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;background:{{ $jc['bg'] }};color:{{ $jc['text'] }};flex-shrink:0;">
                        {{ \App\Support\Label::jobStatus($job->status) }}
                    </span>
                </div>
                @empty
                <div style="padding:40px;text-align:center;color:#94a3b8;font-size:14px;">Belum ada lowongan.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</x-app-layout>
