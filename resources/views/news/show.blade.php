<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner
            title="{{ $news->title }}"
            subtitle="{{ $news->created_at->format('d M Y') }}"
            :back-url="route('news.index')"
            back-label="Kembali ke Berita" eyebrow="Beranda › Berita">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    {{ \App\Support\Label::newsCategory($news->category) }}
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $news->created_at->format('d M Y') }} · {{ $news->author->name ?? 'Admin' }}
                </span>
            </x-slot:chips>
        </x-ui.page-banner>
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

                        {{-- P0 H-10: konten sudah disanitasi server-side, aman dirender sebagai HTML. --}}
                        <div class="prose max-w-none mt-6 text-slate-700">{!! \App\Support\HtmlSanitizer::cleanNews($news->content) !!}</div>
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
