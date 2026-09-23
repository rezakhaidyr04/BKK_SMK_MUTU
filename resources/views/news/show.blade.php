<x-app-layout :full-bleed="true">
    @php
        $readingMinutes = max(1, ceil(str_word_count(strip_tags($news->content)) / 200));
        $excerpt = Str::limit(strip_tags($news->content), 140);
        $thumb = $news->thumbnail ?? $news->image ?? null;
    @endphp
    <div class="page-shell">
        <x-ui.page-banner
            title="{{ $news->title }}"
            subtitle="{{ $excerpt }}"
            :back-url="route('news.index')"
            back-label="Kembali ke Berita"
            eyebrow="Berita › {{ \App\Support\Label::newsCategory($news->category) }}">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    {{ \App\Support\Label::newsCategory($news->category) }}
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $news->created_at->isoFormat('D MMM YYYY') }} · {{ $readingMinutes }} menit baca
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $news->author->name ?? 'Super Admin BKK' }}
                </span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section">
            {{-- Breadcrumb subtle --}}
            <nav class="mb-6 flex items-center gap-2 text-xs text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                <span class="text-slate-400">/</span>
                <a href="{{ route('news.index') }}" class="hover:text-blue-600 transition">Berita</a>
                <span class="text-slate-400">/</span>
                <span class="text-slate-700 font-medium truncate max-w-[220px]">{{ \App\Support\Label::newsCategory($news->category) }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                {{-- Main article --}}
                <article class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                        {{-- Hero image --}}
                        @if($thumb)
                            <div class="relative overflow-hidden">
                                <img src="{{ asset('storage/' . $thumb) }}" alt="{{ $news->title }}" class="w-full h-[280px] sm:h-[360px] object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
                            </div>
                        @else
                            <div class="relative h-[220px] sm:h-[260px] bg-gradient-to-br from-blue-600 via-blue-600 to-violet-600 flex items-center justify-center overflow-hidden">
                                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, rgba(255,255,255,0.9) 1px, transparent 1px); background-size: 22px 22px;"></div>
                                <div class="relative z-10 text-center px-6">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center border border-white/20">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                    <p class="text-white/90 text-sm font-semibold tracking-wide uppercase">{{ \App\Support\Label::newsCategory($news->category) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="p-6 sm:p-8 lg:p-9">
                            {{-- Meta row --}}
                            <div class="flex flex-wrap items-center gap-3 mb-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold tracking-wide border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    {{ \App\Support\Label::newsCategory($news->category) }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $news->created_at->isoFormat('D MMMM YYYY') }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $readingMinutes }} menit baca
                                </span>
                                @if($news->author)
                                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs text-slate-500">
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        oleh {{ $news->author->name }}
                                    </span>
                                @endif
                            </div>

                            {{-- Content with improved typography --}}
                            <div class="news-article-content">
                                {!! \App\Support\HtmlSanitizer::cleanNews($news->content) !!}
                            </div>

                            {{-- Tags / footer meta --}}
                            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-slate-500">Bagikan:</span>
                                    <button onclick="navigator.clipboard.writeText(window.location.href); window.dispatchEvent(new CustomEvent('toast', {detail:{message:'Link disalin!', type:'success'}}))" class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-black transition" title="Salin link">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                    <a href="https://wa.me/?text={{ urlencode($news->title . ' - ' . url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition" title="WhatsApp">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.553 4.104 1.52 5.823L0 24l6.335-1.652A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm5.894 17.086c-.24.678-1.396 1.396-1.94 1.485-.544.09-1.067.09-1.753-.06-.485-.107-1.108-.264-1.896-.518-3.31-1.422-5.467-4.74-5.634-4.96-.168-.22-1.358-1.806-1.358-3.445 0-1.64.86-2.444 1.165-2.777.305-.334.678-.417.904-.417.227 0 .453 0 .65.01.21.01.49-.08.767.584.277.665.944 2.307 1.027 2.474.083.168.14.362.028.583-.113.22-.168.362-.335.557-.168.195-.351.417-.5.557-.15.14-.305.293-.14.574.168.28.744 1.225 1.597 1.984 1.097.976 2.022 1.278 2.31 1.422.284.14.45.117.617-.07.168-.186.718-.834.91-1.117.192-.284.384-.24.65-.14.267.095 1.69.796 1.98.94.292.14.485.21.557.329.07.117.07.678-.17 1.356z"/></svg>
                                    </a>
                                </div>
                                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Kembali ke daftar berita
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Author card mobile --}}
                    <div class="mt-6 bg-gradient-to-br from-slate-900 to-[#0a1633] rounded-2xl p-5 sm:p-6 text-white flex gap-4 items-start">
                        <div class="w-11 h-11 rounded-full bg-white/15 border border-white/20 flex items-center justify-center font-bold flex-shrink-0">
                            {{ strtoupper(substr($news->author->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold">{{ $news->author->name ?? 'Super Admin BKK' }}</p>
                            <p class="text-xs text-white/70 mt-0.5">Tim BKK SMK MUTU · Penulis berita karir & informasi dunia kerja</p>
                            <p class="text-xs text-white/60 mt-2 leading-relaxed">Membantu siswa dan alumni mendapatkan informasi karir terpercaya, tips persiapan kerja, dan peluang industri terbaru.</p>
                        </div>
                    </div>
                </article>

                {{-- Sidebar --}}
                <aside class="space-y-6 lg:sticky lg:top-24">
                    {{-- Quick info --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900">Tentang Artikel</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Kategori</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ \App\Support\Label::newsCategory($news->category) }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Waktu baca</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $readingMinutes }} menit · {{ str_word_count(strip_tags($news->content)) }} kata</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Dipublikasikan</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $news->created_at->isoFormat('dddd, D MMMM YYYY') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Related --}}
                    @if($relatedNews->count())
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900">Berita Terkait</h3>
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">{{ $relatedNews->count() }}</span>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @foreach($relatedNews as $related)
                                    @php $rThumb = $related->thumbnail ?? $related->image ?? null; @endphp
                                    <a href="{{ route('news.show', $related) }}" class="group flex gap-3 p-4 hover:bg-slate-50 transition">
                                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-100">
                                            @if($rThumb)
                                                <img src="{{ asset('storage/' . $rThumb) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-semibold text-blue-600">{{ \App\Support\Label::newsCategory($related->category) }}</p>
                                            <p class="text-sm font-semibold text-slate-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition">{{ $related->title }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $related->created_at->format('d M Y') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="p-3 bg-slate-50">
                                <a href="{{ route('news.index') }}" class="block text-center text-xs font-bold text-slate-700 hover:text-blue-600 py-2 rounded-xl bg-white border border-slate-200 hover:border-blue-200 transition">Lihat semua berita →</a>
                            </div>
                        </div>
                    @endif

                    {{-- CTA karir --}}
                    <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-blue-600 to-violet-600 p-[1px]">
                        <div class="rounded-2xl bg-gradient-to-br from-blue-600 to-violet-600 p-5 text-white">
                            <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h4 class="font-bold text-white">Siap cari kerja?</h4>
                            <p class="text-sm text-blue-100 mt-1 leading-relaxed">Temukan lowongan terbaru dari perusahaan mitra BKK SMK MUTU.</p>
                            <a href="{{ route('jobs.index') }}" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white text-blue-700 text-sm font-bold py-2.5 hover:bg-blue-50 transition">
                                Cari Lowongan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .news-article-content { color: #334155; font-size: 0.95rem; line-height: 1.75; }
        .news-article-content h2 { font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 2rem 0 0.9rem; line-height: 1.3; letter-spacing: -0.02em; }
        .news-article-content h3 { font-size: 1.08rem; font-weight: 700; color: #0f172a; margin: 1.6rem 0 0.7rem; line-height: 1.4; }
        .news-article-content p { margin: 0 0 1rem; color: #475569; }
        .news-article-content p + h2, .news-article-content p + h3 { margin-top: 2rem; }
        .news-article-content ul, .news-article-content ol { margin: 1rem 0 1.25rem 0; padding-left: 1.25rem; }
        .news-article-content ul { list-style: disc; }
        .news-article-content ol { list-style: decimal; }
        .news-article-content li { margin: 0.45rem 0; color: #475569; padding-left: 0.25rem; }
        .news-article-content li::marker { color: #2563eb; }
        .news-article-content a { color: #2563eb; font-weight: 600; text-decoration: underline; text-underline-offset: 3px; text-decoration-color: rgba(37,99,235,0.3); }
        .news-article-content a:hover { color: #1d4ed8; text-decoration-color: #1d4ed8; }
        .news-article-content strong, .news-article-content b { color: #0f172a; font-weight: 700; }
        .news-article-content blockquote { border-left: 3px solid #2563eb; background: #eff6ff; padding: 0.9rem 1rem; border-radius: 0 12px 12px 0; margin: 1.25rem 0; color: #1e40af; font-style: italic; }
        .news-article-content img { border-radius: 14px; margin: 1.25rem 0; max-width: 100%; height: auto; border: 1px solid #e2e8f0; }
        .news-article-content h2:first-child { margin-top: 0; }
        @media (max-width: 640px) {
            .news-article-content { font-size: 0.92rem; }
            .news-article-content h2 { font-size: 1.2rem; }
            .news-article-content h3 { font-size: 1rem; }
        }
    </style>
    @endpush
</x-app-layout>
