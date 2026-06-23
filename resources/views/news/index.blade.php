<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Berita Karir</h1>
            
            @if($news->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($news as $article)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600"></div>
                    <div class="p-6">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full mb-2">
                            {{ \App\Support\Label::newsCategory($article->category) }}
                        </span>
                        <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $article->title }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                        <p class="text-xs text-gray-500">{{ $article->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            {{ $news->links() }}
            @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <p class="text-gray-600">Belum ada berita tersedia</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
