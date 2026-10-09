<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Daftar Perusahaan" subtitle="Kelola dan verifikasi akun perusahaan mitra BKK." eyebrow="Admin › Perusahaan">
            <x-slot:chips>
                @if(($pendingCount ?? 0) > 0)
                <span class="page-banner__chip">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    {{ $pendingCount }} Menunggu Verifikasi
                </span>
                @endif
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ $companies->total() }} Perusahaan · Kelola Perusahaan
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <a href="{{ route('admin.companies.create') }}" class="user-btn-primary !text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Perusahaan
                </a>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            {{-- ===== Filter Bar Premium ===== --}}
            <div class="user-filter-card" data-reveal>
                <form method="GET" action="{{ route('admin.companies.index') }}" class="user-filter-grid co-grid">
                    <div class="user-filter-field user-filter-search">
                        <label class="ui-label" for="c-search">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                            Cari Perusahaan
                        </label>
                        <div class="user-search-wrap">
                            <svg class="user-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                            <input id="c-search" type="text" name="search" value="{{ request('search') }}" class="ui-input user-search-input" placeholder="Nama, industri, alamat…" autocomplete="off" />
                            @if(request('search'))
                            <a href="{{ route('admin.companies.index', array_filter(['status' => request('status')])) }}" class="user-search-clear" title="Hapus pencarian">×</a>
                            @endif
                        </div>
                    </div>
                    <div class="user-filter-field">
                        <label class="ui-label" for="c-status">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Status Verifikasi
                        </label>
                        <select id="c-status" name="status" class="ui-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🟡 Menunggu</option>
                            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>🟢 Terverifikasi</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>🔴 Ditolak</option>
                        </select>
                    </div>
                    <div class="user-filter-actions">
                        <button type="submit" class="user-btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Saring
                        </button>
                        @if(request('search') || request('status'))
                        <a href="{{ route('admin.companies.index') }}" class="user-btn-ghost" title="Atur ulang filter">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M5 9a8 8 0 0114.9-3M19 15a8 8 0 01-14.9 3"/></svg>
                            <span class="hidden xl:inline">Reset</span>
                        </a>
                        @endif
                    </div>
                </form>
                @if(request('search') || request('status'))
                <div class="user-active-filters">
                    <span class="user-active-label">Filter aktif:</span>
                    @if(request('search'))<span class="user-chip">“{{ request('search') }}”</span>@endif
                    @if(request('status')==='pending')<span class="user-chip user-chip-amber">Menunggu</span>
                    @elseif(request('status')==='verified')<span class="user-chip user-chip-green">Terverifikasi</span>
                    @elseif(request('status')==='rejected')<span class="user-chip user-chip-red">Ditolak</span>
                    @endif
                    <span class="user-result-count">{{ $companies->total() }} hasil ditemukan</span>
                </div>
                @endif
            </div>

            {{-- ===== Table Card Premium ===== --}}
            <div class="user-table-card" data-reveal>
                <div class="user-table-head">
                    <div class="user-table-head-left">
                        <div class="user-table-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3>Semua Perusahaan <span class="user-count-badge">{{ $companies->total() }}</span>
                                @if(($pendingCount ?? 0) > 0)
                                <span class="user-count-badge badge-amber">{{ $pendingCount }} pending</span>
                                @endif
                            </h3>
                            <p>Klik baris untuk detail · Setujui / Tolak dari aksi cepat</p>
                        </div>
                    </div>
                    <div class="user-legend">
                        <span class="user-legend-item"><i class="dot dot-green"></i> Terverifikasi</span>
                        <span class="user-legend-item"><i class="dot dot-amber"></i> Menunggu</span>
                        <span class="user-legend-item"><i class="dot dot-red"></i> Ditolak</span>
                    </div>
                </div>

                <div class="user-table-wrap">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Perusahaan</th>
                                <th>Industri</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                            @php
                                $initials = collect(explode(' ', preg_replace('/^(PT|CV|UD|PD)\.? /i','', trim($company->name))))->map(fn($w)=>mb_substr($w,0,1))->take(2)->join('');
                                $gradients = ['g-blue','g-violet','g-emerald','g-amber','g-rose','g-cyan'];
                                $g = $gradients[crc32($company->name) % count($gradients)];
                                $email = optional($company->user)->email ?? $company->email ?? '-';
                            @endphp
                            <tr class="user-row {{ $company->verification_status === 'pending' ? 'row-pending' : '' }}" onclick="window.location='{{ route('admin.companies.show', $company) }}'">
                                <td>
                                    <div class="user-cell">
                                        @if($company->logo)
                                        <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="user-avatar-img" onclick="event.stopPropagation()">
                                        @else
                                        <div class="user-avatar {{ $g }}">{{ strtoupper($initials ?: 'P') }}</div>
                                        @endif
                                        <div class="user-meta">
                                            <span class="user-name">{{ $company->name }}
                                                @if(!$company->user_id)<span class="user-you warn">No Akun</span>@endif
                                            </span>
                                            <span class="user-email">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Daftar {{ $company->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($company->industry)
                                    <span class="company-pill">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.9 23.9 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ Str::limit($company->industry, 24) }}
                                    </span>
                                    @else
                                    <span class="company-dash">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="user-email !text-[.82rem] !text-slate-700">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ Str::limit($email, 30) }}
                                    </span>
                                </td>
                                <td onclick="event.stopPropagation()">
                                    @if($company->verification_status === 'verified')
                                    <span class="status-pill status-active"><span class="pulse"></span> Terverifikasi</span>
                                    @elseif($company->verification_status === 'pending')
                                    <span class="status-pill status-pending"><span class="pulse-amber"></span> Menunggu</span>
                                    @else
                                    <span class="status-pill status-rejected"><span class="dot-static-red"></span> Ditolak</span>
                                    @endif
                                    @if($company->mou_path)
                                    <a href="{{ route('admin.companies.mou.download', $company) }}" class="mou-link" onclick="event.stopPropagation()" title="Unduh / Lihat MoU">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        MoU ✓
                                    </a>
                                    @endif
                                    {{-- F3: flag kedaluwarsa MoU --}}
                                    @if($company->mou_expires_at && $company->mouExpired())
                                    <span class="mou-link" style="color:#b91c1c;background:#fef2f2;border-color:#fecaca;" title="MoU kedaluwarsa {{ $company->mou_expires_at->format('d M Y') }}">MoU kedaluwarsa!</span>
                                    @elseif($company->mou_expires_at && $company->mouExpiresSoon())
                                    <span class="mou-link" style="color:#92400e;background:#fffbeb;border-color:#fde68a;" title="MoU berakhir {{ $company->mou_expires_at->format('d M Y') }}">MoU ≤30 hari</span>
                                    @endif
                                    @if($company->verification_status === 'rejected' && $company->rejection_reason)
                                    <p class="reject-reason" title="{{ $company->rejection_reason }}">{{ Str::limit($company->rejection_reason, 45) }}</p>
                                    @endif
                                </td>
                                <td onclick="event.stopPropagation()">
                                    <div class="user-actions">
                                        @if($company->verification_status !== 'verified')
                                        <form method="POST" action="{{ route('admin.companies.approve', $company) }}" data-confirm="Setujui verifikasi {{ $company->name }}?" data-confirm-title="Setujui Perusahaan" data-confirm-ok="Ya, Setujui">
                                            @csrf
                                            <button type="submit" class="act-btn act-approve" title="Setujui {{ $company->name }}">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span>Setujui</span>
                                            </button>
                                        </form>
                                        @endif
                                        @if($company->verification_status !== 'rejected')
                                        <button type="button" class="act-btn act-delete" title="Tolak {{ $company->name }}" onclick="openRejectModal({{ $company->id }}, {{ Js::from($company->name) }})">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            <span>Tolak</span>
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.companies.show', $company) }}" class="act-btn act-view" title="Detail {{ $company->name }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Detail</span>
                                        </a>
                                        <a href="{{ route('admin.companies.edit', $company) }}" class="act-btn act-edit" title="Edit {{ $company->name }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="!border-0">
                                    <div class="user-empty">
                                        <div class="user-empty-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <h4>Tidak ada perusahaan ditemukan</h4>
                                        <p>Coba ubah kata kunci atau filter, atau tambah perusahaan baru.</p>
                                        <div class="user-empty-actions">
                                            <a href="{{ route('admin.companies.index') }}" class="user-btn-ghost">Atur Ulang Filter</a>
                                            <a href="{{ route('admin.companies.create') }}" class="user-btn-primary">+ Tambah Perusahaan</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($companies->hasPages() || $companies->total() > 0)
                <div class="user-table-foot">
                    <p class="user-page-info">
                        Menampilkan <strong>{{ $companies->firstItem() ?? 0 }}–{{ $companies->lastItem() ?? 0 }}</strong> dari <strong>{{ $companies->total() }}</strong> perusahaan
                    </p>
                    <div class="user-pagination">{{ $companies->links() }}</div>
                </div>
                @endif
            </div>

            {{-- ===== Reject Modal (tetap, dipoles) ===== --}}
            <x-ui.modal id="rejectModal" title="Tolak Verifikasi">
                <p class="text-sm text-slate-500 mb-4">Perusahaan: <span id="rejectCompanyName" class="font-semibold text-slate-800"></span></p>
                <form id="rejectForm" method="POST" class="ui-form-stack">
                    @csrf
                    <div>
                        <label class="ui-label">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" rows="4" required maxlength="500" class="ui-textarea"
                                  placeholder="Contoh: Profil perusahaan belum lengkap. Mohon isi industri, alamat, dan deskripsi perusahaan terlebih dahulu."></textarea>
                        <p class="text-xs text-slate-400 mt-1">Alasan ini akan ditampilkan ke perusahaan. Maks 500 karakter.</p>
                    </div>
                    <div class="flex gap-2 justify-end pt-2">
                        <button type="button" class="user-btn-ghost" onclick="closeRejectModal()">Batal</button>
                        <button type="submit" class="act-btn act-delete !px-4 !py-2.5">Kirim Penolakan</button>
                    </div>
                </form>
            </x-ui.modal>
        </div>
    </div>

    @push('styles')
    <style>
        .user-filter-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.25rem 1.4rem;margin-bottom:1.25rem;box-shadow:0 8px 24px rgba(15,23,42,.06),0 1px 3px rgba(15,23,42,.04)}
        .user-filter-grid{display:grid;gap:1rem;grid-template-columns:1fr}
        @media(min-width:900px){.user-filter-grid{grid-template-columns:1fr 1fr auto;align-items:end}.user-filter-grid.co-grid{grid-template-columns:1.6fr 1fr auto}}
        .user-search-wrap{position:relative}
        .user-search-icon{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:1.05rem;height:1.05rem;color:#94a3b8;pointer-events:none}
        .user-search-input{padding-left:2.6rem!important;background:#f8fafc!important}
        .user-search-input:focus{background:#fff!important}
        .user-search-clear{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);width:1.5rem;height:1.5rem;display:flex;align-items:center;justify-content:center;border-radius:9999px;background:#f1f5f9;color:#64748b;font-size:1.1rem;text-decoration:none}
        .user-search-clear:hover{background:#fee2e2;color:#dc2626}
        .user-filter-actions{display:flex;gap:.6rem;align-items:center}
        .user-btn-primary{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;font-weight:700;font-size:.85rem;padding:.65rem 1.15rem;border-radius:.8rem;box-shadow:0 4px 14px rgba(37,99,235,.28);border:1px solid transparent;transition:.18s;text-decoration:none;cursor:pointer}
        .user-btn-primary svg{width:1rem;height:1rem}
        .user-btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(37,99,235,.34)}
        .user-btn-ghost{display:inline-flex;align-items:center;gap:.45rem;background:#fff;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:.85rem;padding:.65rem .95rem;border-radius:.8rem;transition:.18s;text-decoration:none;cursor:pointer}
        .user-btn-ghost svg{width:1rem;height:1rem}
        .user-btn-ghost:hover{background:#f8fafc;border-color:#cbd5e1;color:#0f172a}
        .user-active-filters{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-top:1rem;padding-top:.9rem;border-top:1px dashed #e2e8f0;font-size:.8rem}
        .user-active-label{color:#64748b;font-weight:600}
        .user-chip{background:#f1f5f9;border:1px solid #e2e8f0;color:#334155;font-weight:600;padding:.2rem .65rem;border-radius:9999px}
        .user-chip-green{background:#dcfce7;border-color:#bbf7d0;color:#166534}
        .user-chip-amber{background:#fef3c7;border-color:#fde68a;color:#92400e}
        .user-chip-red{background:#fee2e2;border-color:#fecaca;color:#b91c1c}
        .user-result-count{margin-left:auto;color:#64748b}
        .user-table-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.06)}
        .user-table-head{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1.1rem 1.4rem;background:linear-gradient(90deg,#f8fafc,#fff 60%);border-bottom:1px solid #f1f5f9;flex-wrap:wrap}
        .user-table-head-left{display:flex;align-items:center;gap:.9rem}
        .user-table-icon{width:2.75rem;height:2.75rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#2563eb,#06b6d4);color:#fff;box-shadow:0 6px 14px rgba(37,99,235,.3)}
        .user-table-icon svg{width:1.35rem;height:1.35rem}
        .user-table-head h3{font-weight:800;font-size:1rem;color:#0f172a;display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
        .user-count-badge{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:.72rem;font-weight:800;padding:.1rem .55rem;border-radius:9999px}
        .badge-amber{background:#fef3c7!important;color:#92400e!important;border-color:#fde68a!important}
        .user-table-head p{font-size:.78rem;color:#64748b;margin-top:.1rem}
        .user-legend{display:flex;gap:.8rem;font-size:.75rem;color:#64748b;font-weight:600;flex-wrap:wrap}
        .user-legend-item{display:flex;align-items:center;gap:.35rem}
        .dot{width:.55rem;height:.55rem;border-radius:9999px;display:inline-block}
        .dot-green{background:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.18)}
        .dot-amber{background:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.18)}
        .dot-red{background:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.15)}
        .user-table-wrap{overflow-x:auto}
        .user-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.875rem;min-width:920px}
        .user-table thead th{background:#f8fafc;text-align:left;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;padding:.8rem 1.2rem;border-bottom:1px solid #e2e8f0;white-space:nowrap}
        .user-table td:last-child,.user-table thead th.text-right{text-align:right}
        .user-table tbody td{padding:.95rem 1.2rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
        .user-row{cursor:pointer;transition:background .15s}
        .user-row:hover{background:linear-gradient(90deg,#f8fbff,#fff)}
        .user-row:last-child td{border-bottom:0}
        .row-pending{background:linear-gradient(90deg,#fffbeb 0%,#fff 18%);box-shadow:inset 3px 0 0 #f59e0b}
        .row-pending:hover{background:linear-gradient(90deg,#fef3c7,#fff 30%)}
        .user-cell{display:flex;align-items:center;gap:.85rem}
        .user-avatar{width:2.6rem;height:2.6rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.82rem;flex-shrink:0;box-shadow:0 4px 10px rgba(15,23,42,.15)}
        .user-avatar-img{width:2.6rem;height:2.6rem;border-radius:.9rem;object-fit:cover;flex-shrink:0;border:2px solid #dbeafe}
        .g-blue{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}.g-violet{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
        .g-emerald{background:linear-gradient(135deg,#10b981,#047857)}.g-amber{background:linear-gradient(135deg,#f59e0b,#b45309)}
        .g-rose{background:linear-gradient(135deg,#f43f5e,#be123c)}.g-cyan{background:linear-gradient(135deg,#06b6d4,#0e7490)}
        .user-meta{display:flex;flex-direction:column;min-width:0}
        .user-name{font-weight:700;color:#0f172a;font-size:.9rem;display:flex;align-items:center;gap:.45rem}
        .user-you{font-size:.62rem;font-weight:800;padding:.1rem .45rem;border-radius:9999px;text-transform:uppercase;letter-spacing:.04em}
        .user-you.warn{background:#fef3c7;color:#92400e;border:1px solid #fde68a}
        .user-email{display:flex;align-items:center;gap:.3rem;color:#64748b;font-size:.78rem;white-space:nowrap}
        .user-email svg{width:.85rem;height:.85rem;flex-shrink:0;color:#94a3b8}
        .company-pill{display:inline-flex;align-items:center;gap:.35rem;background:#f8fafc;border:1px solid #e2e8f0;color:#334155;font-size:.78rem;font-weight:600;padding:.32rem .65rem;border-radius:.7rem}
        .company-pill svg{width:.9rem;height:.9rem;color:#64748b;flex-shrink:0}
        .company-dash{color:#cbd5e1;font-weight:700}
        .status-pill{display:inline-flex;align-items:center;gap:.45rem;font-size:.76rem;font-weight:700;padding:.32rem .75rem;border-radius:9999px;border:1px solid;white-space:nowrap}
        .status-active{background:#dcfce7;border-color:#86efac;color:#166534}
        .status-pending{background:#fef3c7;border-color:#fde68a;color:#92400e}
        .status-rejected{background:#fee2e2;border-color:#fecaca;color:#b91c1c}
        .pulse{width:.5rem;height:.5rem;border-radius:9999px;background:#22c55e;position:relative}
        .pulse::after{content:'';position:absolute;inset:-4px;border-radius:9999px;border:2px solid #22c55e;opacity:.4;animation:ping 1.6s infinite}
        .pulse-amber{width:.5rem;height:.5rem;border-radius:9999px;background:#f59e0b;position:relative}
        .pulse-amber::after{content:'';position:absolute;inset:-4px;border-radius:9999px;border:2px solid #f59e0b;opacity:.4;animation:ping 1.6s infinite}
        @keyframes ping{75%,100%{transform:scale(1.6);opacity:0}}
        .dot-static-red{width:.5rem;height:.5rem;border-radius:9999px;background:#ef4444}
        .mou-link{display:inline-flex;align-items:center;gap:.25rem;margin-top:.4rem;font-size:.72rem;font-weight:700;color:#1d4ed8;background:#eff6ff;border:1px solid #bfdbfe;padding:.18rem .55rem;border-radius:.6rem;text-decoration:none}
        .mou-link svg{width:.8rem;height:.8rem}
        .mou-link:hover{background:#1d4ed8;color:#fff}
        .reject-reason{font-size:.72rem;color:#b91c1c;background:#fef2f2;border:1px solid #fecaca;border-radius:.6rem;padding:.25rem .55rem;margin-top:.4rem;max-width:200px}
        .user-actions{display:flex;align-items:center;justify-content:flex-end;gap:.5rem;flex-wrap:wrap}
        .act-btn{display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;font-weight:700;padding:.5rem .8rem;border-radius:.75rem;border:1px solid;transition:.16s;text-decoration:none;cursor:pointer;white-space:nowrap}
        .act-btn svg{width:.95rem;height:.95rem}
        .act-view{background:#fff;border-color:#e2e8f0;color:#334155}
        .act-view:hover{background:#f8fafc;border-color:#94a3b8;transform:translateY(-1px);box-shadow:0 4px 10px rgba(15,23,42,.08)}
        .act-edit{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
        .act-edit:hover{background:#1d4ed8;border-color:#1d4ed8;color:#fff;transform:translateY(-1px);box-shadow:0 6px 14px rgba(37,99,235,.3)}
        .act-delete{background:#ef4444;border-color:#ef4444;color:#fff;box-shadow:0 4px 12px rgba(239,68,68,.25)}
        .act-delete:hover{background:#dc2626;transform:translateY(-1px);box-shadow:0 8px 18px rgba(239,68,68,.35)}
        .act-approve{background:linear-gradient(135deg,#22c55e,#16a34a);border-color:transparent;color:#fff;box-shadow:0 4px 12px rgba(34,197,94,.3)}
        .act-approve:hover{filter:brightness(1.05);transform:translateY(-1px);box-shadow:0 8px 18px rgba(34,197,94,.4)}
        .user-empty{text-align:center;padding:3rem 1.5rem!important}
        .user-empty-icon{width:4rem;height:4rem;margin:0 auto 1rem;border-radius:1.25rem;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8}
        .user-empty-icon svg{width:2rem;height:2rem}
        .user-empty h4{font-weight:800;color:#0f172a}
        .user-empty p{color:#64748b;font-size:.85rem;margin:.4rem 0 1.2rem}
        .user-empty-actions{display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap}
        .user-table-foot{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem 1.4rem;border-top:1px solid #f1f5f9;background:#fbfdff;flex-wrap:wrap}
        .user-page-info{font-size:.8rem;color:#64748b}
        .user-page-info strong{color:#0f172a}
        @media(max-width:640px){.act-btn span{display:none}.act-btn{padding:.55rem}}
    </style>
    @endpush

    @push('scripts')
    <script>
    function openRejectModal(companyId, companyName) {
        document.getElementById('rejectCompanyName').textContent = companyName;
        document.getElementById('rejectForm').action = `/admin/companies/${companyId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        const ta = document.getElementById('rejectForm').querySelector('textarea');
        if (ta) ta.value = '';
    }
    document.querySelectorAll('[data-modal-close]').forEach(el => {
        el.addEventListener('click', closeRejectModal);
    });
    </script>
    @endpush
</x-app-layout>
