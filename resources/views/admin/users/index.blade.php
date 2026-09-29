<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Daftar Pengguna" subtitle="Kelola semua akun pengguna di sistem." eyebrow="Admin › Pengguna">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ $users->total() }} Pengguna · Manajemen Akun
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.create') }}" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Tambah Pengguna
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            {{-- ===== Filter Bar Premium ===== --}}
            <div class="user-filter-card" data-reveal>
                <form method="GET" action="{{ route('admin.users.index') }}" class="user-filter-grid">
                    <div class="user-filter-field user-filter-search">
                        <label class="ui-label" for="f-search">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                            Cari Pengguna
                        </label>
                        <div class="user-search-wrap">
                            <svg class="user-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                            <input id="f-search" type="text" name="search" value="{{ request('search') }}" class="ui-input user-search-input" placeholder="Nama, email, role…" autocomplete="off" />
                            @if(request('search'))
                            <a href="{{ route('admin.users.index', array_filter(['role' => request('role'), 'status' => request('status')])) }}" class="user-search-clear" title="Hapus pencarian">×</a>
                            @endif
                        </div>
                    </div>
                    <div class="user-filter-field">
                        <label class="ui-label" for="f-role">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Role
                        </label>
                        <select id="f-role" name="role" class="ui-select" onchange="this.form.submit()">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                            <option value="company" {{ request('role') == 'company' ? 'selected' : '' }}>🏢 Perusahaan</option>
                            <option value="umum" {{ request('role') == 'umum' ? 'selected' : '' }}>👤 Pengguna Umum</option>
                        </select>
                    </div>
                    <div class="user-filter-field">
                        <label class="ui-label" for="f-status">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Status
                        </label>
                        <select id="f-status" name="status" class="ui-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>● Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>○ Nonaktif</option>
                        </select>
                    </div>
                    <div class="user-filter-actions">
                        <button type="submit" class="user-btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Saring
                        </button>
                        @if(request()->hasAny(['search','role','status']) && (request('search') || request('role') || request('status')))
                        <a href="{{ route('admin.users.index') }}" class="user-btn-ghost" title="Atur ulang filter">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M5 9a8 8 0 0114.9-3M19 15a8 8 0 01-14.9 3"/></svg>
                            <span class="hidden xl:inline">Reset</span>
                        </a>
                        @endif
                    </div>
                </form>
                @if(request('search') || request('role') || request('status'))
                <div class="user-active-filters">
                    <span class="user-active-label">Filter aktif:</span>
                    @if(request('search'))<span class="user-chip">“{{ request('search') }}”</span>@endif
                    @if(request('role'))<span class="user-chip user-chip-blue">{{ ucfirst(request('role')) }}</span>@endif
                    @if(request('status'))<span class="user-chip {{ request('status')=='active' ? 'user-chip-green' : 'user-chip-gray' }}">{{ request('status')=='active' ? 'Aktif' : 'Nonaktif' }}</span>@endif
                    <span class="user-result-count">{{ $users->total() }} hasil ditemukan</span>
                </div>
                @endif
            </div>

            {{-- ===== Table Card Premium ===== --}}
            <div class="user-table-card" data-reveal>
                <div class="user-table-head">
                    <div class="user-table-head-left">
                        <div class="user-table-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a3 3 0 11-2.83-4H16a3 3 0 01-1 2.83V9z"/></svg>
                        </div>
                        <div>
                            <h3>Semua Pengguna <span class="user-count-badge">{{ $users->total() }}</span></h3>
                            <p>Klik baris untuk melihat detail · Aksi cepat di kanan</p>
                        </div>
                    </div>
                    <div class="user-legend">
                        <span class="user-legend-item"><i class="dot dot-green"></i> Aktif</span>
                        <span class="user-legend-item"><i class="dot dot-gray"></i> Nonaktif</span>
                    </div>
                </div>

                <div class="user-table-wrap">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Role</th>
                                <th>Perusahaan</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            @php
                                $initials = collect(explode(' ', trim($user->name)))->map(fn($w) => mb_substr($w,0,1))->take(2)->join('');
                                $gradients = ['g-blue','g-violet','g-emerald','g-amber','g-rose','g-cyan'];
                                $g = $gradients[crc32($user->email) % count($gradients)];
                                $roleMap = [
                                    'admin' => ['cls' => 'role-admin', 'icon' => 'M9 12l2 2 4-4m5.6 2A9 9 0 1112 3a9 9 0 015.6 17z', 'label' => 'Admin'],
                                    'company' => ['cls' => 'role-company', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5h1v5h-1zm6 0v-5h1v5h-1z', 'label' => 'Perusahaan'],
                                    'umum' => ['cls' => 'role-umum', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Umum'],
                                ];
                                $r = $roleMap[$user->role] ?? $roleMap['umum'];
                            @endphp
                            <tr class="user-row" onclick="window.location='{{ route('admin.users.show', $user) }}'">
                                <td>
                                    <div class="user-cell">
                                        @if($user->avatar)
                                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="user-avatar-img" onclick="event.stopPropagation()">
                                        @else
                                        <div class="user-avatar {{ $g }}">{{ strtoupper($initials) }}</div>
                                        @endif
                                        <div class="user-meta">
                                            <span class="user-name">{{ $user->name }}
                                                @if($user->id === auth()->id())<span class="user-you">Anda</span>@endif
                                            </span>
                                            <span class="user-email">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                {{ $user->email }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge {{ $r['cls'] }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $r['icon'] }}"/></svg>
                                        {{ $r['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if(optional($user->company)->name)
                                    <span class="company-pill">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5h1v5h-1zm6 0v-5h1v5h-1z"/></svg>
                                        {{ Str::limit(optional($user->company)->name, 22) }}
                                    </span>
                                    @else
                                    <span class="company-dash">—</span>
                                    @endif
                                </td>
                                <td onclick="event.stopPropagation()">
                                    @if($user->is_active)
                                    <span class="status-pill status-active"><span class="pulse"></span> Aktif</span>
                                    @else
                                    <span class="status-pill status-inactive"><span class="dot-static"></span> Nonaktif</span>
                                    @endif
                                </td>
                                <td onclick="event.stopPropagation()">
                                    <div class="user-actions">
                                        <a href="{{ route('admin.users.show', $user) }}" class="act-btn act-view" title="Lihat detail {{ $user->name }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat</span>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="act-btn act-edit" title="Ubah {{ $user->name }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Ubah</span>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" data-confirm="Hapus pengguna {{ $user->name }}?" data-confirm-title="Hapus Pengguna" data-confirm-ok="Ya, Hapus" data-confirm-variant="danger">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="act-btn act-delete" title="Hapus {{ $user->name }}">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="!border-0">
                                    <div class="user-empty">
                                        <div class="user-empty-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172A4 4 0 0112 14h.5M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                                        </div>
                                        <h4>Tidak ada pengguna ditemukan</h4>
                                        <p>Coba ubah kata kunci atau filter, atau tambahkan pengguna baru.</p>
                                        <div class="user-empty-actions">
                                            <a href="{{ route('admin.users.index') }}" class="user-btn-ghost">Atur Ulang Filter</a>
                                            <a href="{{ route('admin.users.create') }}" class="user-btn-primary">+ Tambah Pengguna</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages() || $users->total() > 0)
                <div class="user-table-foot">
                    <p class="user-page-info">
                        Menampilkan <strong>{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
                    </p>
                    <div class="user-pagination">
                        {{ $users->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* ===== Filter Card ===== */
        .user-filter-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.25rem 1.4rem;margin-bottom:1.25rem;box-shadow:0 8px 24px rgba(15,23,42,.06),0 1px 3px rgba(15,23,42,.04)}
        .user-filter-grid{display:grid;gap:1rem;grid-template-columns:1fr}
        @media(min-width:900px){.user-filter-grid{grid-template-columns:1.6fr 1fr 1fr auto;align-items:end}}
        .user-filter-field .ui-label svg{width:.9rem;height:.9rem}
        .user-search-wrap{position:relative}
        .user-search-icon{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:1.05rem;height:1.05rem;color:#94a3b8;pointer-events:none}
        .user-search-input{padding-left:2.6rem!important;background:#f8fafc!important}
        .user-search-input:focus{background:#fff!important}
        .user-search-clear{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);width:1.5rem;height:1.5rem;display:flex;align-items:center;justify-content:center;border-radius:9999px;background:#f1f5f9;color:#64748b;font-size:1.1rem;line-height:1;text-decoration:none}
        .user-search-clear:hover{background:#fee2e2;color:#dc2626}
        .user-filter-actions{display:flex;gap:.6rem;align-items:center}
        .user-btn-primary{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;font-weight:700;font-size:.85rem;padding:.65rem 1.15rem;border-radius:.8rem;box-shadow:0 4px 14px rgba(37,99,235,.28);border:1px solid transparent;transition:.18s}
        .user-btn-primary svg{width:1rem;height:1rem}
        .user-btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(37,99,235,.34);filter:brightness(1.03)}
        .user-btn-ghost{display:inline-flex;align-items:center;gap:.45rem;background:#fff;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:.85rem;padding:.65rem .95rem;border-radius:.8rem;transition:.18s;text-decoration:none}
        .user-btn-ghost svg{width:1rem;height:1rem}
        .user-btn-ghost:hover{background:#f8fafc;border-color:#cbd5e1;color:#0f172a}
        .user-active-filters{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-top:1rem;padding-top:.9rem;border-top:1px dashed #e2e8f0;font-size:.8rem}
        .user-active-label{color:#64748b;font-weight:600}
        .user-chip{background:#f1f5f9;border:1px solid #e2e8f0;color:#334155;font-weight:600;padding:.2rem .65rem;border-radius:9999px}
        .user-chip-blue{background:#dbeafe;border-color:#bfdbfe;color:#1d4ed8}
        .user-chip-green{background:#dcfce7;border-color:#bbf7d0;color:#166534}
        .user-chip-gray{background:#f1f5f9;color:#475569}
        .user-result-count{margin-left:auto;color:#64748b}
        /* ===== Table Card ===== */
        .user-table-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.06),0 1px 3px rgba(15,23,42,.04)}
        .user-table-head{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1.1rem 1.4rem;background:linear-gradient(90deg,#f8fafc,#fff 60%);border-bottom:1px solid #f1f5f9;flex-wrap:wrap}
        .user-table-head-left{display:flex;align-items:center;gap:.9rem}
        .user-table-icon{width:2.75rem;height:2.75rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#2563eb,#06b6d4);color:#fff;box-shadow:0 6px 14px rgba(37,99,235,.3)}
        .user-table-icon svg{width:1.35rem;height:1.35rem}
        .user-table-head h3{font-weight:800;font-size:1rem;color:#0f172a;letter-spacing:-.01em;display:flex;align-items:center;gap:.5rem}
        .user-count-badge{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:.72rem;font-weight:800;padding:.1rem .55rem;border-radius:9999px}
        .user-table-head p{font-size:.78rem;color:#64748b;margin-top:.1rem}
        .user-legend{display:flex;gap:.8rem;font-size:.75rem;color:#64748b;font-weight:600}
        .user-legend-item{display:flex;align-items:center;gap:.35rem}
        .dot{width:.55rem;height:.55rem;border-radius:9999px;display:inline-block}
        .dot-green{background:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.18)}
        .dot-gray{background:#cbd5e1}
        .user-table-wrap{overflow-x:auto}
        .user-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.875rem;min-width:860px}
        .user-table thead th{background:#f8fafc;text-align:left;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;padding:.8rem 1.2rem;border-bottom:1px solid #e2e8f0;white-space:nowrap}
        .user-table thead th.text-right,.user-table td:last-child{text-align:right}
        .user-table tbody td{padding:.95rem 1.2rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
        .user-row{cursor:pointer;transition:background .15s}
        .user-row:hover{background:linear-gradient(90deg,#f8fbff,#fff)}
        .user-row:last-child td{border-bottom:0}
        .user-cell{display:flex;align-items:center;gap:.85rem;min-width:0}
        .user-avatar{width:2.6rem;height:2.6rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.82rem;flex-shrink:0;letter-spacing:.02em;box-shadow:0 4px 10px rgba(15,23,42,.15)}
        .user-avatar-img{width:2.6rem;height:2.6rem;border-radius:.9rem;object-fit:cover;flex-shrink:0;border:2px solid #dbeafe}
        .g-blue{background:linear-gradient(135deg,#3b82f6,#1d4ed8)} .g-violet{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
        .g-emerald{background:linear-gradient(135deg,#10b981,#047857)} .g-amber{background:linear-gradient(135deg,#f59e0b,#b45309)}
        .g-rose{background:linear-gradient(135deg,#f43f5e,#be123c)} .g-cyan{background:linear-gradient(135deg,#06b6d4,#0e7490)}
        .user-meta{display:flex;flex-direction:column;min-width:0}
        .user-name{font-weight:700;color:#0f172a;font-size:.9rem;display:flex;align-items:center;gap:.45rem;white-space:nowrap}
        .user-you{font-size:.65rem;font-weight:800;background:#fef3c7;color:#92400e;border:1px solid #fde68a;padding:.05rem .45rem;border-radius:9999px;text-transform:uppercase;letter-spacing:.04em}
        .user-email{display:flex;align-items:center;gap:.3rem;color:#64748b;font-size:.78rem;white-space:nowrap}
        .user-email svg{width:.85rem;height:.85rem;flex-shrink:0;color:#94a3b8}
        .role-badge{display:inline-flex;align-items:center;gap:.35rem;font-size:.76rem;font-weight:700;padding:.32rem .7rem;border-radius:9999px;border:1px solid;white-space:nowrap}
        .role-badge svg{width:.85rem;height:.85rem}
        .role-admin{background:#ede9fe;border-color:#c4b5fd;color:#5b21b6}
        .role-company{background:#dbeafe;border-color:#93c5fd;color:#1d4ed8}
        .role-umum{background:#f1f5f9;border-color:#e2e8f0;color:#475569}
        .company-pill{display:inline-flex;align-items:center;gap:.35rem;background:#f8fafc;border:1px solid #e2e8f0;color:#334155;font-size:.78rem;font-weight:600;padding:.32rem .65rem;border-radius:.7rem;max-width:100%}
        .company-pill svg{width:.9rem;height:.9rem;color:#64748b;flex-shrink:0}
        .company-dash{color:#cbd5e1;font-weight:700}
        .status-pill{display:inline-flex;align-items:center;gap:.45rem;font-size:.76rem;font-weight:700;padding:.32rem .75rem;border-radius:9999px;border:1px solid}
        .status-active{background:#dcfce7;border-color:#86efac;color:#166534}
        .status-inactive{background:#f1f5f9;border-color:#e2e8f0;color:#64748b}
        .pulse{width:.5rem;height:.5rem;border-radius:9999px;background:#22c55e;position:relative}
        .pulse::after{content:'';position:absolute;inset:-4px;border-radius:9999px;border:2px solid #22c55e;opacity:.4;animation:ping 1.6s cubic-bezier(0,0,.2,1) infinite}
        @keyframes ping{75%,100%{transform:scale(1.6);opacity:0}}
        .dot-static{width:.5rem;height:.5rem;border-radius:9999px;background:#94a3b8}
        .user-actions{display:flex;align-items:center;justify-content:flex-end;gap:.5rem}
        .act-btn{display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;font-weight:700;padding:.5rem .8rem;border-radius:.75rem;border:1px solid;transition:.16s;text-decoration:none;cursor:pointer;white-space:nowrap}
        .act-btn svg{width:.95rem;height:.95rem}
        .act-view{background:#fff;border-color:#e2e8f0;color:#334155}
        .act-view:hover{background:#f8fafc;border-color:#94a3b8;color:#0f172a;transform:translateY(-1px);box-shadow:0 4px 10px rgba(15,23,42,.08)}
        .act-edit{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
        .act-edit:hover{background:#1d4ed8;border-color:#1d4ed8;color:#fff;transform:translateY(-1px);box-shadow:0 6px 14px rgba(37,99,235,.3)}
        .act-delete{background:#ef4444;border-color:#ef4444;color:#fff;box-shadow:0 4px 12px rgba(239,68,68,.25)}
        .act-delete:hover{background:#dc2626;border-color:#dc2626;transform:translateY(-1px);box-shadow:0 8px 18px rgba(239,68,68,.35)}
        .user-empty{text-align:center;padding:3rem 1.5rem!important}
        .user-empty-icon{width:4rem;height:4rem;margin:0 auto 1rem;border-radius:1.25rem;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8}
        .user-empty-icon svg{width:2rem;height:2rem}
        .user-empty h4{font-weight:800;color:#0f172a;font-size:1rem}
        .user-empty p{color:#64748b;font-size:.85rem;margin:.4rem 0 1.2rem}
        .user-empty-actions{display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap}
        .user-table-foot{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem 1.4rem;border-top:1px solid #f1f5f9;background:#fbfdff;flex-wrap:wrap}
        .user-page-info{font-size:.8rem;color:#64748b}
        .user-page-info strong{color:#0f172a}
        @media(max-width:640px){.act-btn span{display:none}.act-btn{padding:.55rem}.user-table-head p{display:none}}
    </style>
    @endpush
</x-app-layout>
