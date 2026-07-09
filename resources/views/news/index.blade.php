<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero title="Berita Karir" subtitle="Informasi terbaru seputar dunia kerja, tips karir, dan pengumuman dari BKK SMK MUTU." />

        <div class="page-container page-section">
            @if($news->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($news as $article)
                <article class="ui-panel group hover:shadow-lg transition-all duration-300">
                    <div class="h-44 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                    </div>
                    <div class="ui-panel-body">
                        <span class="ui-badge ui-badge-blue mb-3">
                            {{ \App\Support\Label::newsCategory($article->category) }}
                        </span>
                        <h3 class="font-bold text-lg text-slate-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('news.show', $article) }}">{{ $article->title }}</a>
                        </h3>
                        <p class="text-sm text-slate-600 mb-4 line-clamp-3">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                        <p class="text-xs text-slate-400">{{ $article->created_at->diffForHumans() }}</p>
                    </div>
                </article>
                @endforeach
            </div>
            <div class="mt-8">{{ $news->links() }}</div>
            @else
            <x-ui.panel>
                <x-ui.empty-state
                    title="Belum ada berita"
                    description="Berita karir akan ditampilkan di sini setelah dipublikasikan."
                />
            </x-ui.panel>
            @endif
        </div>
    </div>
</x-app-layout>
