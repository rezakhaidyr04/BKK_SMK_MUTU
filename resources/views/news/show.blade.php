<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner
            title="{{ $news->title }}"
            subtitle="{{ $news->created_at->format('d M Y') }}"
            :back-url="route('news.index')"
            back-label="Kembali ke Berita"
        />
        <div class="page-container page-section">
            <div class="max-w-4xl mx-auto">
                <article class="ui-panel overflow-hidden">
                    <div class="ui-panel-body">
                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ \App\Support\Label::newsCategory($news->category) }}
                        </span>
                        <h1 class="mt-4 text-3xl font-bold text-slate-900">{{ $news->title }}</h1>
                        <p class="mt-2 text-sm text-slate-500">
                            {{ $news->created_at->format('d M Y') }}
                            @if($news->author)
                                · oleh {{ $news->author->name }}
                            @endif
                        </p>

                        @if($news->thumbnail)
                            <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->title }}" class="mt-6 w-full rounded-xl object-cover max-h-96">
                        @endif

                        <div class="prose max-w-none mt-6 text-slate-700 whitespace-pre-line">{{ $news->content }}</div>
                    </div>
                </article>

                @if($relatedNews->count())
                    <section class="mt-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Berita Terkait</h2>
                        <div class="grid gap-4 sm:grid-cols-3">
                            @foreach($relatedNews as $related)
                                <a href="{{ route('news.show', $related) }}" class="ui-panel hover:shadow-md transition-shadow">
                                    <div class="ui-panel-body">
                                        <p class="text-sm font-semibold text-slate-900">{{ $related->title }}</p>
                                        <p class="mt-2 text-xs text-slate-500">{{ $related->created_at->format('d M Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
