<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Lowongan Saya" subtitle="Kelola dan pantau lowongan pekerjaan perusahaan Anda." eyebrow="Perusahaan › Lowongan">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $jobs->total() }} Lowongan
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company" size="sm">+ Buat Lowongan</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            @if(!auth()->user()->company?->isApproved())
            <x-ui.alert type="info" class="mb-4">
                Lowongan menunggu persetujuan admin.
                @if(auth()->user()->company?->verification_status === 'pending')
                    Verifikasi Anda sedang ditinjau.
                @elseif(auth()->user()->company?->verification_status === 'rejected')
                    Verifikasi ditolak. Perbarui profil dan ajukan kembali.
                @else
                    Lengkapi verifikasi di halaman profil.
                @endif
            </x-ui.alert>
            @endif

            {{-- Statistik seimbang: ikon lembut + angka --}}
            <div class="bal-stats">
                <div class="bal-stat">
                    <div class="bal-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div><span>Total</span><strong>{{ $stats['total'] ?? 0 }}</strong></div>
                </div>
                <div class="bal-stat">
                    <div class="bal-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div><span>Aktif</span><strong>{{ $stats['active'] ?? 0 }}</strong></div>
                </div>
                <div class="bal-stat">
                    <div class="bal-icon amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div><span>Pelamar</span><strong>{{ $stats['applicants'] ?? 0 }}</strong></div>
                </div>
                <div class="bal-stat">
                    <div class="bal-icon slate">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v12a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    </div>
                    <div><span>Ditutup</span><strong>{{ $stats['closed'] ?? 0 }}</strong></div>
                </div>
            </div>

            {{-- Filter seimbang --}}
            <div class="bal-filter">
                <form method="GET" action="{{ route('company.jobs.index') }}" class="bal-filter-row">
                    <div class="bal-search">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, posisi, lokasi…" autocomplete="off" />
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}" />
                        @endif
                    </div>
                    <button type="submit" class="bal-btn-primary">Cari</button>
                    @if(request('search') || request('status'))
                    <a href="{{ route('company.jobs.index') }}" class="bal-btn">Reset</a>
                    @endif
                </form>
                @php
                    $curStatus = request('status');
                    $curSearch = request('search');
                    $pills = [
                        ['Semua', '', $stats['total'] ?? 0],
                        ['Aktif', 'active', $stats['active'] ?? 0],
                        ['Menunggu', 'pending', $stats['pending'] ?? 0],
                        ['Draf', 'draft', $stats['draft'] ?? 0],
                        ['Ditutup', 'closed', $stats['closed'] ?? 0],
                    ];
                @endphp
                <div class="bal-pills">
                    @foreach($pills as [$label, $value, $count])
                        <a href="{{ route('company.jobs.index', array_filter(['search' => $curSearch, 'status' => $value])) }}"
                           class="bal-pill {{ $curStatus === $value || (!$curStatus && $value === '') ? 'on' : '' }}">
                            {{ $label }} <span>{{ $count }}</span>
                        </a>
                    @endforeach
                </div>
                <p class="bal-count">Menampilkan {{ $jobs->firstItem() ?? 0 }}–{{ $jobs->lastItem() ?? 0 }} dari {{ $jobs->total() }} lowongan</p>
            </div>

            {{-- Tabel lowongan --}}
            <x-ui.panel>
                <div class="ui-table-wrap -mx-6 -mt-6">
                    <table class="ui-table bal-table">
                        <colgroup>
                            <col style="width:27%">
                            <col style="width:12%">
                            <col style="width:9%">
                            <col style="width:15%">
                            <col style="width:9%">
                            <col style="width:310px">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="col-lowongan">Lowongan</th>
                                <th>Tipe</th>
                                <th>Pelamar</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                            @php
                                $isExpired = $job->deadline && $job->deadline->lt(now()->startOfDay());
                                $isNear = !$isExpired && $job->deadline && $job->deadline->lte(now()->addDays(7));
                                $appCount = $job->applications_count ?? 0;
                            @endphp
                            <tr class="bal-row">
                                <td class="cell-lowongan">
                                    <div class="bal-cell-main">
                                        <div class="bal-ava">{{ strtoupper(substr($job->title, 0, 1)) }}</div>
                                        <div class="bal-title-wrap">
                                            <p class="bal-title">
                                                <a href="{{ route('jobs.show', $job->id) }}" class="hover:text-blue-700">{{ $job->title }}</a>
                                            </p>
                                            <p class="bal-sub">{{ $job->position ?? '-' }}{{ $job->location ? ' · '.$job->location : '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="bal-type">{{ \App\Support\Label::jobType($job->job_type) }}</span></td>
                                <td>
                                    <a href="{{ route('company.applicants.index', ['job_id' => $job->id]) }}" class="bal-apply {{ $appCount > 0 ? 'has' : '' }}" title="Lihat pelamar">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>{{ $appCount }}</span>
                                    </a>
                                </td>
                                <td>
                                    <p class="bal-date {{ $isExpired ? 'is-expired' : '' }}">{{ $job->deadline ? $job->deadline->format('d M Y') : '–' }}</p>
                                    <p class="bal-date-sub {{ $isExpired ? 'is-expired' : ($isNear ? 'is-near' : '') }}">{{ $job->deadline ? ($isExpired ? 'Kadaluarsa' : $job->deadline->diffForHumans()) : '' }}</p>
                                </td>
                                <td>
                                    @if($job->status === 'active')
                                        <span class="bal-status green">Aktif</span>
                                    @elseif($job->status === 'closed')
                                        <span class="bal-status red">Ditutup</span>
                                    @elseif($job->status === 'pending')
                                        <span class="bal-status amber">Menunggu</span>
                                    @elseif($job->status === 'draft')
                                        <span class="bal-status gray">Draf</span>
                                    @else
                                        <span class="bal-status gray">{{ \App\Support\Label::jobStatus($job->status) }}</span>
                                    @endif
                                </td>
                                <td class="cell-aksi">
                                    <div class="bal-actions">
                                        <a href="{{ route('jobs.show', $job->id) }}" class="bal-act" title="Lihat">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat</span>
                                        </a>
                                        @can('update', $job)
                                            <a href="{{ route('company.jobs.edit', $job->id) }}" class="bal-act blue" title="Ubah">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                <span>Ubah</span>
                                            </a>
                                        @endcan
                                        @can('publish', $job)
                                            @if(in_array($job->status, ['draft', 'pending'], true))
                                            <form method="POST" action="{{ route('company.jobs.publish', $job->id) }}" class="inline" data-confirm="Publikasikan lowongan {{ $job->title }}? Lowongan akan langsung tayang." data-confirm-title="Publikasikan" data-confirm-ok="Ya, Publikasikan">
                                                @csrf
                                                <button type="submit" class="bal-publish" title="Publikasikan sekarang">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Tayangkan
                                                </button>
                                            </form>
                                            @endif
                                        @endcan
                                        @can('close', $job)
                                            @if($job->status === 'active')
                                            <form method="POST" action="{{ route('company.jobs.close', $job->id) }}" class="inline" data-confirm="Tutup lowongan {{ $job->title }}?" data-confirm-title="Tutup Lowongan" data-confirm-ok="Ya, Tutup">
                                                @csrf
                                                <button type="submit" class="bal-act amber" title="Tutup">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    <span>Tutup</span>
                                                </button>
                                            </form>
                                            @endif
                                        @endcan
                                        @can('delete', $job)
                                        <form method="POST" action="{{ route('company.jobs.destroy', $job->id) }}" class="inline" data-confirm="Hapus lowongan {{ $job->title }}?" data-confirm-title="Hapus Lowongan" data-confirm-ok="Ya, Hapus" data-confirm-variant="danger">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bal-act red" title="Hapus">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
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
                                        description="Buat lowongan pertama untuk mulai menerima lamaran."
                                    >
                                        <x-slot:action>
                                            <x-ui.btn href="{{ route('company.jobs.create') }}" variant="company">Buat Lowongan</x-ui.btn>
                                        </x-slot:action>
                                    </x-ui.empty-state>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <p class="text-sm text-slate-500">Halaman {{ $jobs->currentPage() }} dari {{ $jobs->lastPage() }} · Total {{ $jobs->total() }} lowongan</p>
                    <div>{{ $jobs->links() }}</div>
                </div>
            </x-ui.panel>
        </div>
    </div>

    @push('styles')
    <style>
        .bal-stats{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem;margin-bottom:1rem}
        @media(min-width:900px){.bal-stats{grid-template-columns:repeat(4,1fr)}}
        .bal-stat{display:flex;align-items:center;gap:.75rem;background:#fff;border:1px solid #e2e8f0;border-radius:.9rem;padding:.85rem 1rem;transition:transform .15s,box-shadow .15s}
        .bal-stat:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(37,99,235,.10);border-color:#bfdbfe}
        .bal-icon{width:2.4rem;height:2.4rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .bal-icon svg{width:1.2rem;height:1.2rem}
        .bal-icon.blue{background:#eff6ff;color:#1d4ed8}
        .bal-icon.green{background:#f0fdf4;color:#15803d}
        .bal-icon.amber{background:#fffbeb;color:#b45309}
        .bal-icon.slate{background:#f8fafc;color:#64748b}
        .bal-stat span{font-size:.72rem;color:#64748b}
        .bal-stat strong{display:block;font-size:1.35rem;color:#0f172a;line-height:1.15}
        .bal-filter{background:#fff;border:1px solid #e2e8f0;border-radius:.9rem;padding:.9rem 1rem;margin-bottom:1rem}
        .bal-filter-row{display:flex;gap:.5rem;flex-wrap:wrap}
        .bal-search{position:relative;flex:2;min-width:200px}
        .bal-search svg{position:absolute;left:.7rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#94a3b8}
        .bal-search input{width:100%;padding:.55rem .8rem .55rem 2.3rem;border:1px solid #e2e8f0;border-radius:.65rem;font-size:.85rem;color:#0f172a;background:#f8fafc}
        .bal-search input:focus{outline:none;border-color:#3b82f6;background:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.12)}
        .bal-filter-row .ui-select{flex:1;min-width:150px}
        .bal-btn-primary{background:#2563eb;color:#fff;font-weight:600;font-size:.83rem;padding:.55rem 1.1rem;border-radius:.65rem;border:0;cursor:pointer}
        .bal-btn-primary:hover{background:#1d4ed8}
        .bal-btn{background:#fff;border:1px solid #e2e8f0;color:#475569;font-size:.83rem;padding:.55rem 1rem;border-radius:.65rem;text-decoration:none}
        .bal-btn:hover{background:#f8fafc}
        .bal-count{font-size:.78rem;color:#64748b;margin-top:.7rem;padding-top:.6rem;border-top:1px dashed #e2e8f0}
        .bal-pills{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.7rem}
        .bal-pill{display:inline-flex;align-items:center;gap:.4rem;font-size:.78rem;font-weight:600;color:#475569;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9999px;padding:.4rem .85rem;text-decoration:none;transition:.15s}
        .bal-pill span{display:inline-flex;align-items:center;justify-content:center;min-width:1.35rem;height:1.35rem;padding:0 .3rem;border-radius:9999px;background:#e2e8f0;color:#475569;font-size:.68rem;font-weight:800}
        .bal-pill:hover{border-color:#93c5fd;color:#1d4ed8;background:#eff6ff}
        .bal-pill.on{background:#2563eb;border-color:#2563eb;color:#fff;box-shadow:0 4px 12px rgba(37,99,235,.25)}
        .bal-pill.on span{background:rgba(255,255,255,.25);color:#fff}
        .bal-publish{display:inline-flex;align-items:center;gap:.35rem;height:2rem;padding:0 .8rem;border-radius:.55rem;border:0;background:#16a34a;color:#fff;font-size:.74rem;font-weight:700;cursor:pointer;transition:.15s;white-space:nowrap}
        .bal-publish svg{width:.9rem;height:.9rem}
        .bal-publish:hover{background:#15803d;box-shadow:0 4px 12px rgba(22,163,74,.3)}
        .bal-table{table-layout:fixed;width:100%;min-width:880px;border-collapse:collapse}
        /* Wrapper -mx-6 hanya bisa meregang kanan bila width:auto
           (width:100% bawaan .ui-table-wrap mengunci lebar sehingga
           margin kanan negatif tidak berefek → header tampak kurang). */
        .ui-table-wrap{width:auto}
        .bal-table thead{background:#eff6ff}
        .bal-table thead th{background:#eff6ff;text-align:left;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#1d4ed8;padding:.75rem 1rem;border-bottom:1px solid #bfdbfe;white-space:nowrap;vertical-align:middle}
        .bal-table thead th.text-right{text-align:right}
        .bal-table tbody td{padding:.9rem 1rem;border-bottom:1px solid #f1f5f9;vertical-align:middle;text-align:left}
        .bal-table tbody tr:last-child td{border-bottom:0}
        .bal-row{height:68px}
        .bal-row:hover{background:#eff6ff}
        .cell-lowongan{max-width:0}
        .cell-aksi{text-align:right}
        .bal-cell-main{display:flex;align-items:center;gap:.75rem;min-height:44px}
        .bal-ava{width:2.375rem;height:2.375rem;border-radius:9999px;background:#eff6ff;border:1px solid #dbeafe;color:#1d4ed8;font-weight:700;font-size:.85rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .bal-title-wrap{min-width:0;flex:1}
        .bal-title{font-size:.875rem;font-weight:600;color:#0f172a;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:0}
        .bal-title a{color:inherit;text-decoration:none}
        .bal-sub{font-size:.75rem;color:#94a3b8;line-height:1.35;margin:.15rem 0 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .bal-type{display:inline-flex;align-items:center;height:1.625rem;background:#f1f5f9;border:1px solid #e2e8f0;color:#334155;font-size:.74rem;font-weight:600;padding:0 .65rem;border-radius:9999px;white-space:nowrap;line-height:1}
        .bal-apply{display:inline-flex;align-items:center;justify-content:center;gap:.3rem;min-width:3rem;height:1.625rem;background:#f8fafc;border:1px solid #e2e8f0;color:#64748b;font-size:.78rem;font-weight:700;padding:0 .6rem;border-radius:9999px;text-decoration:none;line-height:1}
        .bal-apply svg{width:.85rem;height:.85rem}
        .bal-apply.has{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
        .bal-apply.has:hover{background:#dbeafe}
        .bal-date{font-size:.83rem;color:#334155;line-height:1.3;margin:0;white-space:nowrap}
        .bal-date.is-expired{color:#dc2626;font-weight:600}
        .bal-date-sub{font-size:.7rem;color:#94a3b8;line-height:1.35;margin:.15rem 0 0;white-space:nowrap;min-height:1rem}
        .bal-date-sub.is-near{color:#b45309;font-weight:600}
        .bal-date-sub.is-expired{color:#dc2626}
        .bal-status{display:inline-flex;align-items:center;height:1.625rem;font-size:.74rem;font-weight:700;padding:0 .65rem;border-radius:9999px;border:1px solid;line-height:1;white-space:nowrap}
        .bal-status.green{background:#f0fdf4;border-color:#bbf7d0;color:#15803d}
        .bal-status.red{background:#fef2f2;border-color:#fecaca;color:#b91c1c}
        .bal-status.gray{background:#f8fafc;border-color:#e2e8f0;color:#475569}
        .bal-status.amber{background:#fffbeb;border-color:#fde68a;color:#92400e}
        .bal-actions{display:flex;gap:.375rem;justify-content:flex-end;align-items:center;flex-wrap:nowrap}
        .bal-act{display:inline-flex;align-items:center;justify-content:center;gap:.3rem;min-width:2rem;height:2rem;padding:0 .55rem;border-radius:.55rem;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer;transition:.15s;flex-shrink:0;font-size:.72rem;font-weight:600;white-space:nowrap;text-decoration:none}
        .bal-act svg{width:1rem;height:1rem;flex-shrink:0}
        .bal-act:hover{background:#f8fafc;color:#0f172a;border-color:#cbd5e1}
        .bal-act.blue:hover{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
        .bal-act.amber:hover{background:#fffbeb;color:#92400e;border-color:#fde68a}
        .bal-act.green:hover{background:#f0fdf4;color:#15803d;border-color:#bbf7d0}
        .bal-act.red:hover{background:#fef2f2;color:#dc2626;border-color:#fecaca}
        .bal-table tbody tr{transition:background .12s}
        .bal-table tbody tr:hover{background:#eff6ff}
    </style>
    @endpush
</x-app-layout>
