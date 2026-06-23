<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Berita</h2>
            <a href="{{ route('admin.news.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow sm:rounded-xl p-6">
                <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Berita</label>
                        <input type="text" name="title" value="{{ old('title', $news->title) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <input type="text" name="category" value="{{ old('category', $news->category) }}"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Thumbnail</label>
                        @if($news->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $news->thumbnail) }}" class="w-32 h-20 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-400 mt-1">Thumbnail saat ini. Upload baru untuk mengganti.</p>
                        </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Isi Berita</label>
                        <textarea name="content" rows="12" required
                                  class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('content', $news->content) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-1">
                        <input type="checkbox" id="is_published" name="is_published" value="1"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               {{ $news->is_published ? 'checked' : '' }}>
                        <label for="is_published" class="text-sm font-medium text-gray-700">Publish</label>
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 transition">
                            Perbarui Berita
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="px-6 py-2.5 border border-gray-300 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
