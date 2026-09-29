<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Hasil Ulasan Pengguna" subtitle="Lihat hasil ulasan pengguna — read-only, admin tidak perlu mengisi ulasan." eyebrow="Admin › Ulasan">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    {{ $stats['total'] }} Ulasan · Rata-rata {{ $stats['average'] }} · {{ $stats['satisfaction'] }}% Puas
                </span>
                <span class="page-banner__chip">Read-only</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section">
            <!-- Stats -->
            <div class="news-stats" data-reveal>
                <div class="news-stat amber">
                    <div class="news-stat-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div><p>Rating Rata-rata</p><h4>{{ number_format($stats['average'], 1) }} / 5</h4></div>
                </div>
                <div class="news-stat green">
                    <div class="news-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div><p>Kepuasan (★4-5)</p><h4>{{ $stats['satisfaction'] }}%</h4></div>
                </div>
                <div class="news-stat blue">
                    <div class="news-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div><p>Total Ulasan</p><h4>{{ $stats['total'] }}</h4></div>
                </div>
            </div>

            {{-- ===== Filter Bar ===== --}}
            <div class="user-filter-card" data-reveal>
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="user-filter-grid rv-grid">
                    <div class="user-filter-field">
                        <label class="ui-label" for="rv-rating">Filter Rating</label>
                        <select id="rv-rating" name="rating" class="ui-select" onchange="this.form.submit()">
                            <option value="">Semua Rating</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ (string)request('rating') === (string)$i ? 'selected' : '' }}>{{ $i }} ★{{ $i == 5 ? ' — Sempurna' : ($i == 1 ? ' — Buruk' : '') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="user-filter-actions">
                        <button type="submit" class="user-btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Saring
                        </button>
                        @if(request('rating'))
                        <a href="{{ route('admin.reviews.index') }}" class="user-btn-ghost">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ===== Table Card ===== --}}
            <div class="user-table-card" data-reveal>
                <div class="user-table-head">
                    <div class="user-table-head-left">
                        <div class="user-table-icon">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div>
                            <h3>Hasil Ulasan <span class="user-count-badge">{{ $reviews->total() }}</span></h3>
                            <p>Semua ulasan langsung tampil — admin hanya melihat</p>
                        </div>
                    </div>
                </div>

                <div class="user-table-wrap">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Pengulas</th>
                                <th>Rating</th>
                                <th>Ulasan</th>
                                <th>Tanggal</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            @php
                                $gradients = ['g-blue','g-violet','g-emerald','g-amber','g-rose','g-cyan'];
                                $g = $gradients[crc32($review->display_name . $review->id) % count($gradients)];
                            @endphp
                            <tr class="user-row">
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar {{ $g }}">{{ strtoupper(mb_substr($review->display_name, 0, 2)) }}</div>
                                        <div class="user-meta">
                                            <span class="user-name">{{ Str::limit($review->display_name, 22) }}
                                                @if($review->featured)<span class="feat-badge">★ Populer</span>@endif
                                            </span>
                                            <span class="user-email">{{ Str::limit($review->email ?? optional($review->user)->email ?? '-', 28) }}</span>
                                            @if($review->job_title || $review->company_name)
                                            <span class="user-email job-line">{{ Str::limit(trim(($review->job_title ?? '') . ($review->company_name ? ' @ ' . $review->company_name : '')), 32) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="stars" title="{{ $review->rating }} dari 5">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="{{ $i <= $review->rating ? 'on' : '' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        @endfor
                                    </span>
                                    <span class="rating-num">{{ $review->rating }}.0</span>
                                </td>
                                <td>
                                    <p class="review-text" title="{{ $review->comment }}">{{ Str::limit($review->comment, 90) }}</p>
                                </td>
                                <td><span class="date-pill">{{ $review->created_at->format('d M Y') }}</span></td>
                                <td>
                                    <div class="user-actions">
                                        <button type="button" class="act-btn act-view" onclick="openReviewDetail(this)"
                                            data-name="{{ e($review->display_name) }}"
                                            data-email="{{ e($review->email ?? optional($review->user)->email ?? '-') }}"
                                            data-job="{{ e(trim(($review->job_title ?? '') . ($review->company_name ? ' @ ' . $review->company_name : '')) ?: '-') }}"
                                            data-rating="{{ $review->rating }}"
                                            data-date="{{ $review->created_at->format('d M Y, H:i') }}"
                                            data-comment="{{ e($review->comment) }}"
                                            data-featured="{{ $review->featured ? 'Ya' : 'Tidak' }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="!border-0">
                                    <div class="user-empty">
                                        <div class="user-empty-icon">
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        </div>
                                        <h4>Belum ada ulasan</h4>
                                        <p>Belum ada ulasan pengguna yang masuk.</p>
                                        <div class="user-empty-actions">
                                            <a href="{{ route('admin.reviews.index') }}" class="user-btn-ghost">Atur Ulang Filter</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($reviews->hasPages() || $reviews->total() > 0)
                <div class="user-table-foot">
                    <p class="user-page-info">Menampilkan <strong>{{ $reviews->firstItem() ?? 0 }}–{{ $reviews->lastItem() ?? 0 }}</strong> dari <strong>{{ $reviews->total() }}</strong> ulasan</p>
                    <div class="user-pagination">{{ $reviews->links() }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal detail read-only --}}
    <div id="reviewModal" class="rv-modal hidden">
        <div class="rv-backdrop" onclick="closeReviewDetail()"></div>
        <div class="rv-box">
            <div class="rv-box-head">
                <h4>Detail Ulasan</h4>
                <button type="button" class="rv-close" onclick="closeReviewDetail()">✕</button>
            </div>
            <div class="rv-box-body">
                <div class="rv-stars" id="rvStars"></div>
                <p class="rv-comment" id="rvComment"></p>
                <dl class="rv-meta">
                    <div><dt>Nama</dt><dd id="rvName"></dd></div>
                    <div><dt>Email</dt><dd id="rvEmail"></dd></div>
                    <div><dt>Pekerjaan</dt><dd id="rvJob"></dd></div>
                    <div><dt>Tanggal</dt><dd id="rvDate"></dd></div>
                    <div><dt>Populer</dt><dd id="rvFeat"></dd></div>
                </dl>
                <p class="rv-note">🔒 Mode baca saja — admin tidak mengubah ulasan dari halaman ini.</p>
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
        .news-stat h4{font-size:1.3rem;font-weight:800;color:#0f172a}
        .stat-sub{font-size:.85rem;font-weight:700;color:#64748b}
        .user-filter-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.25rem 1.4rem;margin-bottom:1.25rem;box-shadow:0 8px 24px rgba(15,23,42,.06)}
        .user-filter-grid{display:grid;gap:1rem;grid-template-columns:1fr}
        @media(min-width:900px){.rv-grid{grid-template-columns:1fr auto;align-items:end}}
        .user-search-wrap{position:relative}
        .user-search-icon{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:1.05rem;height:1.05rem;color:#94a3b8;pointer-events:none}
        .user-search-input{padding-left:2.6rem!important;background:#f8fafc!important}
        .user-search-input:focus{background:#fff!important}
        .user-filter-actions{display:flex;gap:.6rem;align-items:center}
        .user-btn-primary{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;font-weight:700;font-size:.85rem;padding:.65rem 1.15rem;border-radius:.8rem;box-shadow:0 4px 14px rgba(37,99,235,.28);border:0;cursor:pointer;text-decoration:none}
        .user-btn-primary svg{width:1rem;height:1rem}
        .user-btn-primary:hover{transform:translateY(-1px)}
        .user-btn-ghost{display:inline-flex;align-items:center;background:#fff;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:.85rem;padding:.65rem .95rem;border-radius:.8rem;text-decoration:none}
        .user-btn-ghost:hover{background:#f8fafc}
        .user-table-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.06)}
        .user-table-head{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1.1rem 1.4rem;background:linear-gradient(90deg,#f8fafc,#fff 60%);border-bottom:1px solid #f1f5f9;flex-wrap:wrap}
        .user-table-head-left{display:flex;align-items:center;gap:.9rem}
        .user-table-icon{width:2.75rem;height:2.75rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 6px 14px rgba(245,158,11,.3)}
        .user-table-icon svg{width:1.3rem;height:1.3rem}
        .user-table-head h3{font-weight:800;font-size:1rem;color:#0f172a;display:flex;align-items:center;gap:.5rem}
        .user-count-badge{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-size:.72rem;font-weight:800;padding:.1rem .55rem;border-radius:9999px}
        .user-table-head p{font-size:.78rem;color:#64748b;margin-top:.1rem}
        .user-legend{display:flex;gap:.8rem;font-size:.75rem;color:#64748b;font-weight:600;flex-wrap:wrap}
        .user-legend-item{display:flex;align-items:center;gap:.35rem}
        .dot{width:.55rem;height:.55rem;border-radius:9999px;display:inline-block}
        .dot-green{background:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.18)}
        .dot-amber{background:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.18)}
        .dot-red{background:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.15)}
        .user-table-wrap{overflow-x:auto}
        .user-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.875rem;min-width:840px}
        .user-table thead th{background:#f8fafc;text-align:left;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;padding:.8rem 1.2rem;border-bottom:1px solid #e2e8f0;white-space:nowrap}
        .user-table td:last-child,.user-table thead th.text-right{text-align:right}
        .user-table tbody td{padding:.95rem 1.2rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
        .user-row:last-child td{border-bottom:0}
        .user-cell{display:flex;align-items:center;gap:.85rem}
        .user-avatar{width:2.6rem;height:2.6rem;border-radius:.9rem;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.8rem;flex-shrink:0;box-shadow:0 4px 10px rgba(15,23,42,.15)}
        .g-blue{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}.g-violet{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
        .g-emerald{background:linear-gradient(135deg,#10b981,#047857)}.g-amber{background:linear-gradient(135deg,#f59e0b,#b45309)}
        .g-rose{background:linear-gradient(135deg,#f43f5e,#be123c)}.g-cyan{background:linear-gradient(135deg,#06b6d4,#0e7490)}
        .user-meta{display:flex;flex-direction:column;min-width:0}
        .user-name{font-weight:700;color:#0f172a;font-size:.9rem;display:flex;align-items:center;gap:.4rem}
        .feat-badge{font-size:.62rem;font-weight:800;background:#fef3c7;color:#92400e;border:1px solid #fde68a;padding:.08rem .45rem;border-radius:9999px}
        .user-email{display:flex;color:#64748b;font-size:.76rem;white-space:nowrap}
        .job-line{color:#94a3b8;font-size:.72rem}
        .stars{display:inline-flex;gap:2px}
        .stars svg{width:1rem;height:1rem;fill:#e2e8f0}
        .stars svg.on{fill:#f59e0b}
        .rating-num{font-size:.75rem;font-weight:800;color:#92400e;background:#fef3c7;border:1px solid #fde68a;border-radius:9999px;padding:.1rem .5rem;margin-left:.4rem}
        .review-text{font-size:.83rem;color:#334155;max-width:320px}
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
        .date-pill{font-size:.78rem;font-weight:600;color:#334155;background:#f8fafc;border:1px solid #e2e8f0;padding:.3rem .6rem;border-radius:.65rem;white-space:nowrap}
        .user-actions{display:flex;justify-content:flex-end}
        .act-btn{display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;font-weight:700;padding:.5rem .8rem;border-radius:.75rem;border:1px solid #e2e8f0;background:#fff;color:#334155;cursor:pointer;transition:.16s}
        .act-btn svg{width:.95rem;height:.95rem}
        .act-view:hover{background:#f8fafc;border-color:#94a3b8;transform:translateY(-1px)}
        .user-empty{text-align:center;padding:3rem 1.5rem!important}
        .user-empty-icon{width:4rem;height:4rem;margin:0 auto 1rem;border-radius:1.25rem;background:#fef3c7;display:flex;align-items:center;justify-content:center;color:#d97706}
        .user-empty-icon svg{width:2rem;height:2rem;fill:currentColor}
        .user-empty h4{font-weight:800;color:#0f172a}
        .user-empty p{color:#64748b;font-size:.85rem;margin:.4rem 0 1.2rem}
        .user-empty-actions{display:flex;justify-content:center}
        .user-table-foot{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem 1.4rem;border-top:1px solid #f1f5f9;background:#fbfdff;flex-wrap:wrap}
        .user-page-info{font-size:.8rem;color:#64748b}
        .user-page-info strong{color:#0f172a}
        .rv-modal{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;padding:1rem}
        .rv-modal.hidden{display:none}
        .rv-backdrop{position:absolute;inset:0;background:rgba(15,23,42,.55);backdrop-filter:blur(2px)}
        .rv-box{position:relative;background:#fff;border-radius:1.25rem;max-width:34rem;width:100%;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.25)}
        .rv-box-head{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;background:linear-gradient(90deg,#fffbeb,#fff);border-bottom:1px solid #f1f5f9}
        .rv-box-head h4{font-weight:800;color:#0f172a}
        .rv-close{width:2rem;height:2rem;border-radius:.6rem;border:1px solid #e2e8f0;background:#fff;color:#64748b;cursor:pointer}
        .rv-close:hover{background:#fef2f2;color:#dc2626}
        .rv-box-body{padding:1.25rem}
        .rv-stars{font-size:1.2rem;color:#f59e0b;letter-spacing:.1em;margin-bottom:.6rem}
        .rv-comment{font-size:.92rem;color:#0f172a;background:#f8fafc;border:1px solid #e2e8f0;border-radius:.85rem;padding:.9rem;line-height:1.6}
        .rv-meta{margin-top:1rem;display:flex;flex-direction:column;gap:.45rem}
        .rv-meta div{display:flex;justify-content:space-between;gap:1rem;font-size:.8rem}
        .rv-meta dt{color:#64748b;font-weight:600}
        .rv-meta dd{color:#0f172a;font-weight:700;text-align:right}
        .rv-note{margin-top:1rem;font-size:.75rem;color:#64748b;background:#f1f5f9;border-radius:.7rem;padding:.6rem .8rem}
        @media(max-width:640px){.act-btn span{display:none}}
    </style>
    @endpush

    @push('scripts')
    <script>
    function openReviewDetail(btn){
        const d = btn.dataset;
        document.getElementById('rvName').textContent = d.name || '-';
        document.getElementById('rvEmail').textContent = d.email || '-';
        document.getElementById('rvJob').textContent = d.job || '-';
        document.getElementById('rvDate').textContent = d.date || '-';
        document.getElementById('rvFeat').textContent = d.featured || '-';
        document.getElementById('rvComment').textContent = d.comment || '-';
        const r = parseInt(d.rating || '0', 10);
        document.getElementById('rvStars').textContent = '★'.repeat(r) + '☆'.repeat(Math.max(0, 5 - r)) + '  (' + r + '/5)';
        document.getElementById('reviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeReviewDetail(){
        document.getElementById('reviewModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeReviewDetail(); });
    </script>
    @endpush
</x-app-layout>
