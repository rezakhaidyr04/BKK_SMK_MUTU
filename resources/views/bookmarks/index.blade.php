<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Saved Jobs</h1>
            
            @if($bookmarks->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($bookmarks as $bookmark)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900">{{ $bookmark->job->title }}</h3>
                    <p class="text-gray-600">{{ $bookmark->job->company->name ?? 'Company' }}</p>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('jobs.show', $bookmark->job->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            View Job
                        </a>
                        <form action="{{ route('bookmarks.destroy', $bookmark->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            {{ $bookmarks->links() }}
            @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <p class="text-gray-600">No saved jobs yet</p>
                <a href="{{ route('jobs.index') }}" class="mt-4 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg">Browse Jobs</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
