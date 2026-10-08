<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Manajemen Berita" subtitle="Kelola artikel dan berita karir untuk platform." eyebrow="Admin › Berita">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    {{ $news->total() }} Berita · Kelola Konten
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <a href="{{ route('admin.news.create') }}" class="user-btn-primary !text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    Tulis Berita
                </a>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            <!-- Stats premium -->
            <div class="news-stats" data-reveal>
                <div class="news-stat blue">
                    <div class="news-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <div><p>Total Berita</p><h4>{{ $news->total() }}</h4></div>
                </div>
                <div class="news-stat green">
                    <div class="news-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div><p>Dipublikasikan</p><h4>{{ \App\Models\News::where('is_published', true)->count() }}</h4></div>
                </div>
                <div class="news-stat amber">
                    <div class="news-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414L9 13z"/></svg>
                    </div>
                    <div><p>Draft</p><h4>{{ \App\Models\News::where('is_published', false)->count() }}</h4></div>
                </div>
            </div>

            {{-- ===== Filter Bar Premium ===== --}}
            <div class="user-filter-card" data-reveal>
                <form method="GET" action="{{ route('admin.news.index') }}" class="user-filter-grid co-grid">
                    <div class="user-filter-field user-filter-search">
                        <label class="ui-label" for="n-search">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                            Cari Berita
                        </label>
                        <div class="user-search-wrap">
                            <svg class="user-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/></svg>
                            <input id="n-search" type="text" name="search" value="{{ request('search') }}" class="ui-input user-search-input" placeholder="Cari judul berita…" autocomplete="off" />
                            @if(request('search'))
                            <a href="{{ route('admin.news.index', array_filter(['status' => request('status')])) }}" class="user-search-clear" title="Hapus pencarian">×</a>
                            @endif
                        </div>
                    </div>
                    <div class="user-filter-field">
                        <label class="ui-label" for="n-status">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Status
                        </label>
                        <select id="n-status" name="status" class="ui-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>🟢 Dipublikasikan</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>🟡 Draft</option>
                        </select>
                    </div>
                    <div class="user-filter-actions">
                        <button type="submit" class="user-btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Saring
                        </button>
                        @if(request('search') || request('status'))
                        <a href="{{ route('admin.news.index') }}" class="user-btn-ghost" title="Atur ulang filter">
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
                    @if(request('status')==='published')<span class="user-chip user-chip-green">Dipublikasikan</span>
                    @elseif(request('status')==='draft')<span class="user-chip user-chip-amber">Draft</span>@endif
                    <span class="user-result-count">{{ $news->total() }} hasil ditemukan</span>
                </div>
                @endif
            </div>

            {{-- ===== Table Card Premium ===== --}}
            <div class="user-table-card" data-reveal>
                <div class="user-table-head">
                    <div class="user-table-head-left">
                        <div class="user-table-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <div>
                            <h3>Semua Berita <span class="user-count-badge">{{ $news->total() }}</span></h3>
                            <p>Klik baris untuk pratinjau · Kelola konten dari aksi cepat</p>
                        </div>
                    </div>
                    <div class="user-legend">
                        <span class="user-legend-item"><i class="dot dot-green"></i> Dipublikasikan</span>
                        <span class="user-legend-item"><i class="dot dot-amber"></i> Draft</span>
                    </div>
                </div>

                <div class="user-table-wrap">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Berita</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Penulis</th>
                                <th>Tanggal</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($news as $item)
                            @php
                                $gradients = ['g-blue','g-violet','g-emerald','g-amber','g-rose','g-cyan'];
                                $g = $gradients[crc32($item->title) % count($gradients)];
                            @endphp
                            <tr class="user-row" onclick="window.location='{{ route('admin.news.edit', $item) }}'">
                                <td>
                                    <div class="user-cell">
                                        @if($item->thumbnail)
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="" class="news-thumb" onclick="event.stopPropagation()">
                                        @else
                                        <div class="news-thumb-fallback {{ $g }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        </div>
                                        @endif
                                        <div class="user-meta news-meta">
                                            <span class="user-name">{{ Str::limit($item->title, 42) }}</span>
                                            <span class="user-email news-excerpt">{{ Str::limit(strip_tags($item->content), 60) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->category)
                                    <span class="cat-pill">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.414 5.414a1 1 0 01-1.414 0L5.879 10.12A1 1 0 015.586 9.414V4a1 1 0 011-1h.414z"/></svg>
                                        {{ $item->category }}
                                    </span>
                                    @else
                                    <span class="company-dash">—</span>
                                    @endif
                                </td>
                                <td onclick="event.stopPropagation()">
                                    @if($item->is_published)
                                    <span class="status-pill status-active"><span class="pulse"></span> Dipublikasikan</span>
                                    @else
                                    <span class="status-pill status-pending"><span class="pulse-amber"></span> Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="author-pill">
                                        <span class="author-ava">{{ strtoupper(mb_substr($item->author->name ?? 'A',0,1)) }}</span>
                                        {{ Str::limit($item->author->name ?? 'Admin', 16) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="date-pill">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $item->created_at->format('d M Y') }}
                                    </span>
                                </td>
                                <td onclick="event.stopPropagation()">
                                    <div class="user-actions">
                                        <a href="{{ route('news.show', $item) }}" target="_blank" rel="noopener" class="act-btn act-view" title="Lihat publik">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat</span>
                                        </a>
                                        <a href="{{ route('admin.news.edit', $item) }}" class="act-btn act-edit" title="Edit {{ Str::limit($item->title,30) }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $item) }}" class="inline" data-confirm="Hapus berita {{ Str::limit($item->title,40) }}?" data-confirm-title="Hapus Berita" data-confirm-ok="Ya, Hapus" data-confirm-variant="danger">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="act-btn act-delete" title="Hapus">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="!border-0">
                                    <div class="user-empty">
                                        <div class="user-empty-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        </div>
                                        <h4>Belum ada berita</h4>
                                        <p>Mulai tulis berita karir pertama untuk platform.</p>
                                        <div class="user-empty-actions">
                                            <a href="{{ route('admin.news.index') }}" class="user-btn-ghost">Atur Ulang Filter</a>
                                            <a href="{{ route('admin.news.create') }}" class="user-btn-primary">+ Tulis Berita</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($news->hasPages() || $news->total() > 0)
                <div class="user-table-foot">
                    <p class="user-page-info">
                        Menampilkan <strong>{{ $news->firstItem() ?? 0 }}–{{ $news->lastItem() ?? 0 }}</strong> dari <strong>{{ $news->total() }}</strong> berita
                    </p>
                    <div class="user-pagination">{{ $news->links() }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .news-stats{display:grid;gap:1rem;margin-bottom:1.25rem}
        @media(min-width:768px){.news-stats{grid-template-columns:repeat(3,1fr)}}
        .news-stat{display:flex;align-items:center;gap:.9rem;background:#fff;border:1px solid #e2e8f0;border-radius:1.1rem;padding:1rem 1.2rem;box-shadow:0 8px 24px rgba(15,23,42,.06)}
        .news-stat-icon{width:2.6rem;height:2.6rem;border-radius:.85rem;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0}
        .news-stat.blue .news-stat-icon{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}
        .news-stat.green .news-stat-icon{background:linear-gradient(135deg,#22c55e,#15803d)}
        .news-stat.amber .news-stat-icon{background:linear-gradient(135deg,#f59e0b,#b45309)}
        .news-stat-icon svg{width:1.3rem;height:1.3rem}
        .news-stat p{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b}
        .news-stat h4{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.02em}
        .user-filter-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.25rem 1.4rem;margin-bottom:1.25rem;box-shadow:0 8px 24px rgba(15,23,42,.06)}
        .user-filter-grid{display:grid;gap:1rem;grid-template-columns:1fr}
        @media(min-width:900px){.user-filter-grid.co-grid{grid-template-columns:1.6fr 1fr auto;align-items:end}}
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
        .user-btn-ghost{display:inline-flex;align-items:center;gap:.45rem;background:#fff;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:.85rem;padding:.65rem .95rem;border-radius:.8rem;transition:.18s;text-decoration:none}
        .user-btn-ghost svg{width:1rem;height:1rem}
        .user-btn-ghost:hover{background:#f8fafc;border-color:#cbd5e1}
        .user-active-filters{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-top:1rem;padding-top:.9rem;border-top:1px dashed #e2e8f0;font-size:.8rem}
        .user-active-label{color:#64748b;font-weight:600}
        .user-chip{background:#f1f5f9;border:1px solid #e2e8f0;color:#334155;font-weight:600;padding:.2rem .65rem;border-radius:9999px}
        .user-chip-green{background:#dcfce7;border-color:#bbf7d0;color:#166534}
        .user-chip-amber{background:#fef3c7;border-color:#fde68a;color:#92400e}
        .user-result-count{margin-left:auto;color:#64748b}
        .user-table-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.06)}
        .user-table-head{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1.1rem 1.4rem;background:linear-gradient(90deg,#f8fafc,#fff 60%);border-bottom:1px solid #f1f5f9;flex-wrap:wrap}
        .user-table-head-left{display:flex;align-items:center;gap:.9rem}
        .user-table-icon{width:2.75rem;height:2.75rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#2563eb,#06b6d4);color:#fff;box-shadow:0 6px 14px rgba(37,99,235,.3)}
        .user-table-icon svg{width:1.35rem;height:1.35rem}
        .user-table-head h3{font-weight:800;font-size:1rem;color:#0f172a;display:flex;align-items:center;gap:.5rem}
        .user-count-badge{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:.72rem;font-weight:800;padding:.1rem .55rem;border-radius:9999px}
        .user-table-head p{font-size:.78rem;color:#64748b;margin-top:.1rem}
        .user-legend{display:flex;gap:.8rem;font-size:.75rem;color:#64748b;font-weight:600;flex-wrap:wrap}
        .user-legend-item{display:flex;align-items:center;gap:.35rem}
        .dot{width:.55rem;height:.55rem;border-radius:9999px;display:inline-block}
        .dot-green{background:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.18)}
        .dot-amber{background:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.18)}
        .user-table-wrap{overflow-x:auto}
        .user-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.875rem;min-width:960px}
        .user-table thead th{background:#f8fafc;text-align:left;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;padding:.8rem 1.2rem;border-bottom:1px solid #e2e8f0;white-space:nowrap}
        .user-table td:last-child,.user-table thead th.text-right{text-align:right}
        .user-table tbody td{padding:.95rem 1.2rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
        .user-row{cursor:pointer;transition:background .15s}
        .user-row:hover{background:linear-gradient(90deg,#f8fbff,#fff)}
        .user-row:last-child td{border-bottom:0}
        .user-cell{display:flex;align-items:center;gap:.85rem}
        .news-thumb{width:3rem;height:3rem;border-radius:.9rem;object-fit:cover;flex-shrink:0;border:1px solid #e2e8f0;box-shadow:0 4px 10px rgba(15,23,42,.08)}
        .news-thumb-fallback{width:3rem;height:3rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;box-shadow:0 4px 10px rgba(15,23,42,.15)}
        .news-thumb-fallback svg{width:1.4rem;height:1.4rem}
        .g-blue{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}.g-violet{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
        .g-emerald{background:linear-gradient(135deg,#10b981,#047857)}.g-amber{background:linear-gradient(135deg,#f59e0b,#b45309)}
        .g-rose{background:linear-gradient(135deg,#f43f5e,#be123c)}.g-cyan{background:linear-gradient(135deg,#06b6d4,#0e7490)}
        .user-meta{display:flex;flex-direction:column;min-width:0}
        .news-meta{max-width:320px}
        .user-name{font-weight:700;color:#0f172a;font-size:.9rem}
        .news-excerpt{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:300px}
        .user-email{display:flex;align-items:center;gap:.3rem;color:#64748b;font-size:.78rem}
        .cat-pill{display:inline-flex;align-items:center;gap:.35rem;background:#f1f5f9;border:1px solid #e2e8f0;color:#334155;font-size:.76rem;font-weight:600;padding:.32rem .65rem;border-radius:.7rem;white-space:nowrap}
        .cat-pill svg{width:.85rem;height:.85rem;color:#64748b}
        .company-dash{color:#cbd5e1;font-weight:700}
        .status-pill{display:inline-flex;align-items:center;gap:.45rem;font-size:.76rem;font-weight:700;padding:.32rem .75rem;border-radius:9999px;border:1px solid;white-space:nowrap}
        .status-active{background:#dcfce7;border-color:#86efac;color:#166534}
        .status-pending{background:#fef3c7;border-color:#fde68a;color:#92400e}
        .pulse{width:.5rem;height:.5rem;border-radius:9999px;background:#22c55e;position:relative}
        .pulse::after{content:'';position:absolute;inset:-4px;border-radius:9999px;border:2px solid #22c55e;opacity:.4;animation:ping 1.6s infinite}
        .pulse-amber{width:.5rem;height:.5rem;border-radius:9999px;background:#f59e0b;position:relative}
        .pulse-amber::after{content:'';position:absolute;inset:-4px;border-radius:9999px;border:2px solid #f59e0b;opacity:.4;animation:ping 1.6s infinite}
        @keyframes ping{75%,100%{transform:scale(1.6);opacity:0}}
        .author-pill{display:inline-flex;align-items:center;gap:.45rem;font-size:.8rem;font-weight:600;color:#334155;white-space:nowrap}
        .author-ava{width:1.7rem;height:1.7rem;border-radius:.6rem;background:linear-gradient(135deg,#64748b,#0f172a);color:#fff;font-size:.68rem;font-weight:800;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0}
        .date-pill{display:inline-flex;align-items:center;gap:.35rem;font-size:.8rem;font-weight:600;color:#334155;background:#f8fafc;border:1px solid #e2e8f0;padding:.32rem .65rem;border-radius:.7rem;white-space:nowrap}
        .date-pill svg{width:.9rem;height:.9rem;color:#64748b}
        .user-actions{display:flex;align-items:center;justify-content:flex-end;gap:.5rem;flex-wrap:wrap}
        .act-btn{display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;font-weight:700;padding:.5rem .8rem;border-radius:.75rem;border:1px solid;transition:.16s;text-decoration:none;cursor:pointer;white-space:nowrap}
        .act-btn svg{width:.95rem;height:.95rem}
        .act-view{background:#fff;border-color:#e2e8f0;color:#334155}
        .act-view:hover{background:#f8fafc;border-color:#94a3b8;transform:translateY(-1px)}
        .act-edit{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
        .act-edit:hover{background:#1d4ed8;border-color:#1d4ed8;color:#fff;transform:translateY(-1px);box-shadow:0 6px 14px rgba(37,99,235,.3)}
        .act-delete{background:#ef4444;border-color:#ef4444;color:#fff;box-shadow:0 4px 12px rgba(239,68,68,.25)}
        .act-delete:hover{background:#dc2626;transform:translateY(-1px)}
        .user-empty{text-align:center;padding:3rem 1.5rem!important}
        .user-empty-icon{width:4rem;height:4rem;margin:0 auto 1rem;border-radius:1.25rem;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8}
        .user-empty-icon svg{width:2rem;height:2rem}
        .user-empty h4{font-weight:800;color:#0f172a}
        .user-empty p{color:#64748b;font-size:.85rem;margin:.4rem 0 1.2rem}
        .user-empty-actions{display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap}
        .user-table-foot{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem 1.4rem;border-top:1px solid #f1f5f9;background:#fbfdff;flex-wrap:wrap}
        .user-page-info{font-size:.8rem;color:#64748b}
        .user-page-info strong{color:#0f172a}
        @media(max-width:640px){.act-btn span{display:none}.act-btn{padding:.55rem}.news-meta{max-width:200px}.news-excerpt{max-width:190px}}
    </style>
    @endpush
</x-app-layout>
