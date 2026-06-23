<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="{{ route('news.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Kembali ke berita</a>

            <article class="mt-6 rounded-xl bg-white p-6 sm:p-8 shadow-lg border border-gray-100">
                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ \App\Support\Label::newsCategory($news->category) }}
                </span>
                <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $news->title }}</h1>
                <p class="mt-2 text-sm text-gray-500">
                    {{ $news->created_at->format('d M Y') }}
                    @if($news->author)
                        oleh {{ $news->author->name }}
                    @endif
                </p>

                @if($news->thumbnail)
                    <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->title }}" class="mt-6 w-full rounded-xl object-cover max-h-96">
                @endif

                <div class="prose max-w-none mt-6 text-gray-700 whitespace-pre-line">{{ $news->content }}</div>
            </article>

            @if($relatedNews->count())
                <section class="mt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Berita Terkait</h2>
                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach($relatedNews as $related)
                            <a href="{{ route('news.show', $related) }}" class="rounded-xl bg-white p-4 shadow border border-gray-100 hover:shadow-md">
                                <p class="text-sm font-semibold text-gray-900">{{ $related->title }}</p>
                                <p class="mt-2 text-xs text-gray-500">{{ $related->created_at->format('d M Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
