<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 overflow-hidden">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white text-sm font-medium mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Berita & Artikel Karir
                    </span>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Berita Karir Terbaru</h1>
                    <p class="text-lg text-blue-100 max-w-2xl mx-auto">Informasi terbaru seputar dunia kerja, tips karir, dan pengumuman dari BKK SMK MUTU untuk membantu Anda sukses.</p>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            @if($news->count() > 0)
                <!-- Featured Article -->
                @php
                    $featured = $news->first();
                    $remaining = $news->slice(1);
                @endphp
                @if($featured)
                <div class="mb-12">
                    <article class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <div class="grid md:grid-cols-2 gap-0">
                            <div class="relative h-64 md:h-auto bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 overflow-hidden">
                                @if($featured->image)
                                <img src="{{ asset('storage/' . $featured->image) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-24 h-24 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-blue-700 text-xs font-bold uppercase tracking-wide">
                                        Featured
                                    </span>
                                </div>
                            </div>
                            <div class="p-8 flex flex-col justify-center">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        {{ \App\Support\Label::newsCategory($featured->category) }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $featured->created_at->format('d M Y') }}</span>
                                </div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('news.show', $featured) }}">{{ $featured->title }}</a>
                                </h2>
                                <p class="text-gray-600 mb-6 line-clamp-3">{{ Str::limit(strip_tags($featured->content), 180) }}</p>
                                <a href="{{ route('news.show', $featured) }}" class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
                @endif

                <!-- Remaining Articles Grid -->
                @if($remaining->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Artikel Lainnya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($remaining as $article)
                        <article class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-100">
                            <div class="relative h-48 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden">
                                @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        {{ \App\Support\Label::newsCategory($article->category) }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('news.show', $article) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <span class="text-xs text-gray-500">{{ $article->created_at->format('d M Y') }}</span>
                                    <a href="{{ route('news.show', $article) }}" class="text-sm text-blue-600 font-medium hover:text-blue-700 transition-colors">
                                        Baca
                                    </a>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Pagination -->
                <div class="mt-8">{{ $news->links() }}</div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-3xl shadow-lg p-16 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Berita</h3>
                    <p class="text-gray-600 max-w-md mx-auto">Berita karir dan artikel akan ditampilkan di sini setelah dipublikasikan oleh tim BKK SMK MUTU.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
