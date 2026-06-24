<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Edit Berita" subtitle="Perbarui artikel berita karir.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-narrow">
        <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data" class="ui-form-stack">
            @csrf
            @method('PUT')

            <div>
                <label class="ui-label">Judul Berita</label>
                <input type="text" name="title" value="{{ old('title', $news->title) }}" required class="ui-input">
            </div>
            <div>
                <label class="ui-label">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $news->category) }}" class="ui-input">
            </div>
            <div>
                <label class="ui-label">Thumbnail</label>
                @if($news->thumbnail)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="" class="w-32 h-20 object-cover rounded-lg border border-slate-200">
                    <p class="text-xs text-slate-400 mt-1">Upload baru untuk mengganti.</p>
                </div>
                @endif
                <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="ui-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium">
            </div>
            <div>
                <label class="ui-label">Isi Berita</label>
                <textarea name="content" rows="12" required class="ui-textarea">{{ old('content', $news->content) }}</textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_published" name="is_published" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ $news->is_published ? 'checked' : '' }}>
                <label for="is_published" class="text-sm text-slate-700">Publish</label>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Perbarui Berita</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
